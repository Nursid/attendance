<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * OBHS Feedback System model.
 * Tables: obhs_feedback (new), complain (existing - auto complaint records),
 *         login / user_request (existing - auth & company resolution).
 *
 * Business scope: pass the business id to limit results to one company.
 * Pass '' (empty) as $bid for a global admin (web_login type 'A') to see
 * every business's data.
 */
class Obhs_Model extends CI_Model {

	// Rating columns => report labels (single source of truth)
	public $rating_fields = array(
		'rating_toilet_cleaning'         => 'Cleaning of Toilet',
		'rating_compartment_cleaning'    => 'Cleaning of Compartment',
		'rating_toiletries_availability' => 'Availability of Toiletries',
		'rating_behaviour'               => 'Behaviour'
	);

	// Allowed rating values => labels (only these 4 values are accepted)
	public $rating_options = array(
		4 => 'Very Good',
		3 => 'Good',
		2 => 'Poor',
		1 => 'Not Attended'
	);

	// PSI denominator fixed at 12 (business rule: PSI = total score / 12 x 100)
	const PSI_MAX_SCORE = 12;

	// ------------------------------------------------------------------ auth

	public function checkMobile($mobile){
		return $this->db->where('mobile',$mobile)->where('deleted',0)->get('login')->row_array();
	}

	public function getUserCompany($id){
		return $this->db->order_by('id','DESC')->limit(1)->get_where('user_request',array('user_id'=>$id))->row_array();
	}

	// ------------------------------------------------------------------ psi

	/**
	 * Score contribution of one rating value.
	 * Very Good=4, Good=3, Poor=2, Not Attended (1) = 0.
	 */
	public function ratingScore($val){
		$val = (int)$val;
		return ($val >= 2 && $val <= 4) ? $val : 0;
	}

	/**
	 * PSI = (total score / 12) x 100 (capped at 100).
	 * "Not Attended" contributes 0 but the denominator stays 12.
	 * Example: 4 + 3 + 2 + 0 = 9 => (9/12)*100 = 75.00
	 */
	public function calculatePsi($ratings){
		$sum = 0;
		foreach($this->rating_fields as $field => $label){
			$sum += $this->ratingScore(isset($ratings[$field]) ? $ratings[$field] : 0);
		}
		return round(min(100, ($sum / self::PSI_MAX_SCORE) * 100), 2);
	}

	/** SQL expression for a rating's score (Not Attended = 0) - keeps SQL aggregates in sync with ratingScore(). */
	public function ratingScoreSql($col){
		return "(CASE WHEN $col BETWEEN 2 AND 4 THEN $col ELSE 0 END)";
	}

	// --------------------------------------------------------------- scope

	/**
	 * Business scope. Empty/null/false bid = global admin (type 'A') => no filter,
	 * so every business's feedback is included.
	 */
	private function scopeBid($bid,$alias='f'){
		if($bid !== '' && $bid !== null && $bid !== false){
			$this->db->where($alias.'.bid',$bid);
		}
	}

	// ------------------------------------------------------------------ crud

	public function addFeedback($data){
		$this->db->insert('obhs_feedback',$data);
		return $this->db->insert_id();
	}

	public function updateFeedback($id,$bid,$data){
		$this->db->where('id',$id);
		if($bid !== '' && $bid !== null && $bid !== false){ $this->db->where('bid',$bid); }
		$this->db->update('obhs_feedback',$data);
		return $this->db->affected_rows();
	}

	public function getFeedbackById($id,$bid=''){
		$this->db->select('f.*, l.name as staff_name, l.mobile as staff_mobile')
			->from('obhs_feedback f')
			->join('login l','l.id=f.uid','left')
			->where('f.id',$id);
		$this->scopeBid($bid);
		$row = $this->db->get()->row_array();
		if(empty($row)){ return $row; }
		$rows = $this->fillTrainInfo(array($row));
		return $rows[0];
	}

	public function addComplaint($data){
		$this->db->insert('complain',$data);
		return $this->db->insert_id();
	}

	public function updateComplaintStatus($complaint_id,$status){
		if(empty($complaint_id)){ return 0; }
		$this->db->where('id',$complaint_id)->update('complain',array('status'=>$status));
		return $this->db->affected_rows();
	}

	// ------------------------------------------------------- list + filters

	/**
	 * Shared filter builder for list/count/export/reports.
	 * $filters keys: start_date, end_date, train_no, coach_no, uid, feedback_type,
	 *                status, psi_min, psi_max, search
	 */
	private function applyFilters($filters){
		if(!empty($filters['start_date'])){ $this->db->where('f.journey_date >=',$filters['start_date']); }
		if(!empty($filters['end_date'])){ $this->db->where('f.journey_date <=',$filters['end_date']); }
		if(!empty($filters['train_no'])){ $this->db->where('f.train_no',$filters['train_no']); }
		if(!empty($filters['coach_no'])){ $this->db->where('f.coach_no',$filters['coach_no']); }
		if(!empty($filters['uid'])){ $this->db->where('f.uid',$filters['uid']); }
		if(!empty($filters['feedback_type'])){ $this->db->where('f.feedback_type',$filters['feedback_type']); }
		if(!empty($filters['status'])){ $this->db->where('f.status',$filters['status']); }
		if(isset($filters['psi_min']) && $filters['psi_min'] !== ''){ $this->db->where('f.psi_score >=',(float)$filters['psi_min']); }
		if(isset($filters['psi_max']) && $filters['psi_max'] !== ''){ $this->db->where('f.psi_score <=',(float)$filters['psi_max']); }
		if(!empty($filters['search'])){
			$s = $filters['search'];
			$this->db->group_start()
				->like('f.train_no',$s)->or_like('f.train_name',$s)
				->or_like('f.coach_no',$s)->or_like('f.pnr_no',$s)
				->or_like('f.passenger_name',$s)->or_like('f.passenger_mobile',$s)
				->or_like('f.boarding_station',$s)->or_like('f.destination_station',$s)
				->or_like('f.remarks',$s)->or_like('f.janitor_name',$s)
				->group_end();
		}
	}

	private $sortable = array('id','journey_date','train_no','coach_no','passenger_name','psi_score','feedback_type','status','date_time');

	/**
	 * Feedback stores one direction number while the master keeps the pair
	 * (e.g. 12155 / 12156), so both numbers key the same master row.
	 * Cached per request - the master is a small global list.
	 */
	private $train_master_map = null;

	private function trainMasterMap(){
		if($this->train_master_map === null){
			$this->train_master_map = array();
			foreach($this->getTrainMaster() as $train){
				foreach(array($train['train_no'],$train['train_no_return']) as $no){
					if($no === '' || $no === null || isset($this->train_master_map[$no])){ continue; }
					$this->train_master_map[$no] = $train;
				}
			}
		}
		return $this->train_master_map;
	}

	/** Attach master coach position, and the master name when the row has none. */
	private function fillTrainInfo($rows){
		$map = $this->trainMasterMap();
		foreach($rows as &$row){
			$no = isset($row['train_no']) ? $row['train_no'] : '';
			$master = isset($map[$no]) ? $map[$no] : array();
			if(!empty($master) && trim((string)$row['train_name']) === ''){
				$row['train_name'] = $master['train_name'];
			}
			$row['coach_position'] = empty($master) ? '' : $master['coach_position'];
		}
		unset($row);
		return $rows;
	}

	public function getFeedbackList($bid,$filters=array(),$limit=0,$offset=0,$sort_by='id',$sort_dir='DESC'){
		$this->db->select('f.*, l.name as staff_name')
			->from('obhs_feedback f')
			->join('login l','l.id=f.uid','left');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$sort_by  = in_array($sort_by,$this->sortable) ? $sort_by : 'id';
		$sort_dir = (strtoupper($sort_dir)=='ASC') ? 'ASC' : 'DESC';
		$this->db->order_by('f.'.$sort_by,$sort_dir);
		if($limit > 0){ $this->db->limit($limit,$offset); }
		return $this->fillTrainInfo($this->db->get()->result_array());
	}

	public function countFeedbackList($bid,$filters=array()){
		$this->db->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		return $this->db->count_all_results();
	}

	/** Janitor's own submissions (mobile app list). */
	public function getUserFeedbackList($uid,$limit=50,$offset=0){
		$rows = $this->db->select('f.*')
			->from('obhs_feedback f')
			->where('f.uid',$uid)
			->order_by('f.id','DESC')
			->limit($limit,$offset)
			->get()->result_array();
		return $this->fillTrainInfo($rows);
	}

	// ------------------------------------------------------------- dashboard

	public function getDashboardSummary($bid,$filters=array()){
		$this->db->select("COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			SUM(f.feedback_type='Complaint') as total_complaints,
			SUM(f.feedback_type='Complaint' AND f.status='Pending') as pending_complaints,
			SUM(f.feedback_type='Complaint' AND f.status='Done') as resolved_complaints,
			COUNT(DISTINCT f.train_no) as trains_covered,
			COUNT(DISTINCT f.uid) as active_janitors",FALSE)
			->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		return $this->db->get()->row_array();
	}

	/** Avg score per rating category (for dashboard chart). Not Attended counts as 0, unrated rows excluded. */
	public function getCategoryAverages($bid,$filters=array()){
		$select = array();
		foreach($this->rating_fields as $field => $label){
			$select[] = "ROUND(AVG(CASE WHEN f.$field=0 THEN NULL ELSE ".$this->ratingScoreSql("f.$field")." END),2) as $field";
		}
		$this->db->select(implode(',',$select),FALSE)
			->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$row = $this->db->get()->row_array();
		$out = array();
		foreach($this->rating_fields as $field => $label){
			$out[] = array('label'=>$label,'avg'=>isset($row[$field]) ? (float)$row[$field] : 0);
		}
		return $out;
	}

	/** Last N months: feedback count + avg PSI + complaints (dashboard trend + Monthly Report). */
	public function getMonthlyStats($bid,$months=12,$filters=array()){
		$this->db->select("DATE_FORMAT(f.journey_date,'%Y-%m') as month,
			COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			SUM(f.feedback_type='Complaint') as complaints,
			SUM(f.feedback_type='Complaint' AND f.status='Done') as resolved",FALSE)
			->from('obhs_feedback f');
		$this->scopeBid($bid);
		if(empty($filters['start_date']) && empty($filters['end_date'])){
			$this->db->where('f.journey_date >=',date('Y-m-01',strtotime("-".($months-1)." months")));
		}
		$this->applyFilters($filters);
		$this->db->group_by("DATE_FORMAT(f.journey_date,'%Y-%m')")->order_by('month','ASC');
		return $this->db->get()->result_array();
	}

	// --------------------------------------------------------------- reports

	public function getTrainWiseReport($bid,$filters=array(),$limit=0,$offset=0,$sort_by='total_feedback',$sort_dir='DESC'){
		$this->db->select("f.train_no, MAX(f.train_name) as train_name,
			COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			SUM(f.feedback_type='Complaint') as complaints,
			SUM(f.feedback_type='Complaint' AND f.status='Done') as resolved,
			COUNT(DISTINCT f.coach_no) as coaches",FALSE)
			->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$this->db->group_by('f.train_no');
		$allowed = array('train_no','total_feedback','avg_psi','complaints');
		$sort_by  = in_array($sort_by,$allowed) ? $sort_by : 'total_feedback';
		$sort_dir = (strtoupper($sort_dir)=='ASC') ? 'ASC' : 'DESC';
		$this->db->order_by($sort_by,$sort_dir);
		if($limit > 0){ $this->db->limit($limit,$offset); }
		$rows = $this->db->get()->result_array();

		// Master name (when feedback left it blank) + full rake size for context
		$map = $this->trainMasterMap();
		foreach($rows as &$row){
			$master = isset($map[$row['train_no']]) ? $map[$row['train_no']] : array();
			if(!empty($master) && trim((string)$row['train_name'])===''){
				$row['train_name'] = $master['train_name'];
			}
			$row['total_coaches'] = empty($master) ? '' : $master['total_coaches'];
		}
		unset($row);
		return $rows;
	}

	public function countTrainWiseReport($bid,$filters=array()){
		$this->db->select('f.train_no')->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$this->db->group_by('f.train_no');
		return $this->db->get()->num_rows();
	}

	public function getCoachWiseReport($bid,$filters=array(),$limit=0,$offset=0,$sort_by='total_feedback',$sort_dir='DESC'){
		$this->db->select("f.train_no, f.coach_no,
			COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			ROUND(AVG(CASE WHEN f.rating_toilet_cleaning=0 THEN NULL ELSE ".$this->ratingScoreSql('f.rating_toilet_cleaning')." END),2) as avg_toilet_clean,
			ROUND(AVG(CASE WHEN f.rating_compartment_cleaning=0 THEN NULL ELSE ".$this->ratingScoreSql('f.rating_compartment_cleaning')." END),2) as avg_compartment_clean,
			SUM(f.feedback_type='Complaint') as complaints",FALSE)
			->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$this->db->group_by(array('f.train_no','f.coach_no'));
		$allowed = array('train_no','coach_no','total_feedback','avg_psi','complaints');
		$sort_by  = in_array($sort_by,$allowed) ? $sort_by : 'total_feedback';
		$sort_dir = (strtoupper($sort_dir)=='ASC') ? 'ASC' : 'DESC';
		$this->db->order_by($sort_by,$sort_dir);
		if($limit > 0){ $this->db->limit($limit,$offset); }
		return $this->db->get()->result_array();
	}

	public function countCoachWiseReport($bid,$filters=array()){
		$this->db->select('f.train_no')->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$this->db->group_by(array('f.train_no','f.coach_no'));
		return $this->db->get()->num_rows();
	}

	public function getJanitorReport($bid,$filters=array(),$limit=0,$offset=0,$sort_by='avg_psi',$sort_dir='DESC'){
		$this->db->select("f.uid, COALESCE(NULLIF(MAX(f.janitor_name),''),MAX(l.name)) as janitor_name,
			MAX(l.mobile) as mobile,
			COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			ROUND(AVG(CASE WHEN f.rating_behaviour=0 THEN NULL ELSE ".$this->ratingScoreSql('f.rating_behaviour')." END),2) as avg_behaviour,
			SUM(f.feedback_type='Complaint') as complaints,
			COUNT(DISTINCT f.train_no) as trains_served",FALSE)
			->from('obhs_feedback f')
			->join('login l','l.id=f.uid','left');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$this->db->group_by('f.uid');
		$allowed = array('janitor_name','total_feedback','avg_psi','complaints');
		$sort_by  = in_array($sort_by,$allowed) ? $sort_by : 'avg_psi';
		$sort_dir = (strtoupper($sort_dir)=='ASC') ? 'ASC' : 'DESC';
		$this->db->order_by($sort_by,$sort_dir);
		if($limit > 0){ $this->db->limit($limit,$offset); }
		return $this->db->get()->result_array();
	}

	public function countJanitorReport($bid,$filters=array()){
		$this->db->select('f.uid')->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		$this->db->group_by('f.uid');
		return $this->db->get()->num_rows();
	}

	/** PSI band distribution: Excellent >=80, Good 60-79, Average 40-59, Poor <40. */
	public function getPsiDistribution($bid,$filters=array()){
		$this->db->select("SUM(f.psi_score >= 80) as excellent,
			SUM(f.psi_score >= 60 AND f.psi_score < 80) as good,
			SUM(f.psi_score >= 40 AND f.psi_score < 60) as average,
			SUM(f.psi_score > 0 AND f.psi_score < 40) as poor",FALSE)
			->from('obhs_feedback f');
		$this->scopeBid($bid);
		$this->applyFilters($filters);
		return $this->db->get()->row_array();
	}

	public function getComplaintTracking($bid,$filters=array(),$limit=0,$offset=0,$sort_by='id',$sort_dir='DESC'){
		$filters['feedback_type'] = 'Complaint';
		return $this->getFeedbackList($bid,$filters,$limit,$offset,$sort_by,$sort_dir);
	}

	public function countComplaintTracking($bid,$filters=array()){
		$filters['feedback_type'] = 'Complaint';
		return $this->countFeedbackList($bid,$filters);
	}

	// ---------------------------------------------------------- train master

	/**
	 * Active trains from obhs_train_master (global list, not business scoped).
	 * Each row carries both direction numbers, e.g. 12155 / 12156.
	 */
	public function getTrainMaster($search=''){
		$this->db->select('id, train_no, train_no_return, train_name, coach_position, total_coaches')
			->from('obhs_train_master')
			->where('status',1);
		if($search !== '' && $search !== null){
			$this->db->group_start()
				->like('train_no',$search)->or_like('train_no_return',$search)
				->or_like('train_name',$search)->or_like('coach_position',$search)
				->group_end();
		}
		return $this->db->order_by('train_no','ASC')->get()->result_array();
	}

	/** One train, matched on either the up or the return number. */
	public function getTrainByNo($train_no){
		if($train_no === '' || $train_no === null){ return array(); }
		$row = $this->db->from('obhs_train_master')
			->where('status',1)
			->group_start()->where('train_no',$train_no)->or_where('train_no_return',$train_no)->group_end()
			->get()->row_array();
		return empty($row) ? array() : $row;
	}

	/** Coach codes of a train in rake order (empty when the train is unknown). */
	public function getTrainCoaches($train_no){
		$row = $this->getTrainByNo($train_no);
		return empty($row) ? array() : $this->splitCoaches($row['coach_position']);
	}

	/** "H1,A1,A2" => array('H1','A1','A2') */
	public function splitCoaches($coach_position){
		$out = array();
		foreach(explode(',',(string)$coach_position) as $coach){
			$coach = trim($coach);
			if($coach !== ''){ $out[] = $coach; }
		}
		return $out;
	}

	/**
	 * Distinct train/coach values for filter dropdowns.
	 * Trains and coaches come from the train master first, then any extra
	 * values that only exist in recorded feedback are appended so historic
	 * rows stay filterable. `coach_map` drives the train -> coach dependency
	 * in the report filter form.
	 */
	public function getFilterOptions($bid){
		$trains = array();
		$coach_map = array();
		$coaches = array();

		foreach($this->getTrainMaster() as $t){
			$train_coaches = $this->splitCoaches($t['coach_position']);
			foreach(array($t['train_no'],$t['train_no_return']) as $no){
				if($no === '' || $no === null){ continue; }
				$trains[$no] = array('train_no'=>$no,'train_name'=>$t['train_name']);
				$coach_map[$no] = $train_coaches;
			}
			foreach($train_coaches as $c){ $coaches[$c] = true; }
		}

		$this->db->select('train_no, MAX(train_name) as train_name',FALSE)->from('obhs_feedback f');
		$this->scopeBid($bid);
		foreach($this->db->group_by('train_no')->get()->result_array() as $t){
			if(!isset($trains[$t['train_no']])){ $trains[$t['train_no']] = $t; }
		}
		ksort($trains);

		$this->db->distinct()->select('coach_no')->from('obhs_feedback f');
		$this->scopeBid($bid);
		foreach($this->db->get()->result_array() as $c){
			if($c['coach_no'] !== ''){ $coaches[$c['coach_no']] = true; }
		}
		$coach_list = array_keys($coaches);
		sort($coach_list,SORT_NATURAL);   // B1,B2..B10 rather than B1,B10,B2

		$this->db->select("f.uid, COALESCE(NULLIF(MAX(f.janitor_name),''),MAX(l.name)) as name",FALSE)
			->from('obhs_feedback f')->join('login l','l.id=f.uid','left');
		$this->scopeBid($bid);
		$janitors = $this->db->group_by('f.uid')->get()->result_array();

		$coach_options = array();
		foreach($coach_list as $c){ $coach_options[] = array('coach_no'=>$c); }

		return array(
			'trains'   => array_values($trains),
			'coaches'  => $coach_options,
			'coach_map'=> $coach_map,
			'janitors' => $janitors
		);
	}
}
