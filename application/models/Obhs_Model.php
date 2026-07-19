<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * OBHS Feedback System model.
 * Tables: obhs_feedback (new), complain (existing - auto complaint records),
 *         login / user_request (existing - auth & company resolution).
 */
class Obhs_Model extends CI_Model {

	// Rating columns => report labels (single source of truth)
	public $rating_fields = array(
		'rating_coach_cleanliness'  => 'Coach Cleanliness',
		'rating_toilet_cleanliness' => 'Toilet Cleanliness',
		'rating_doorway_cleanliness'=> 'Doorway Cleanliness',
		'rating_bedroll'            => 'Bedroll Quality',
		'rating_staff_behaviour'    => 'Staff Behaviour',
		'rating_pest_control'       => 'Pest Control'
	);

	// ------------------------------------------------------------------ auth

	public function checkMobile($mobile){
		return $this->db->where('mobile',$mobile)->where('deleted',0)->get('login')->row_array();
	}

	public function getUserCompany($id){
		return $this->db->order_by('id','DESC')->limit(1)->get_where('user_request',array('user_id'=>$id))->row_array();
	}

	// ------------------------------------------------------------------- psi

	/**
	 * PSI = average of rated (non-zero) categories x 20  => 0-100.
	 */
	public function calculatePsi($ratings){
		$sum = 0; $count = 0;
		foreach($this->rating_fields as $field => $label){
			$val = isset($ratings[$field]) ? (int)$ratings[$field] : 0;
			if($val > 0){
				$sum += min($val,5);
				$count++;
			}
		}
		if($count == 0){ return 0.00; }
		return round(($sum / $count) * 20, 2);
	}

	// ------------------------------------------------------------------ crud

	public function addFeedback($data){
		$this->db->insert('obhs_feedback',$data);
		return $this->db->insert_id();
	}

	public function updateFeedback($id,$bid,$data){
		$this->db->where('id',$id)->where('bid',$bid)->update('obhs_feedback',$data);
		return $this->db->affected_rows();
	}

	public function getFeedbackById($id,$bid=''){
		$this->db->select('f.*, l.name as staff_name, l.mobile as staff_mobile')
			->from('obhs_feedback f')
			->join('login l','l.id=f.uid','left')
			->where('f.id',$id);
		if($bid !== ''){ $this->db->where('f.bid',$bid); }
		return $this->db->get()->row_array();
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

	public function getFeedbackList($bid,$filters=array(),$limit=0,$offset=0,$sort_by='id',$sort_dir='DESC'){
		$this->db->select('f.*, l.name as staff_name')
			->from('obhs_feedback f')
			->join('login l','l.id=f.uid','left')
			->where('f.bid',$bid);
		$this->applyFilters($filters);
		$sort_by  = in_array($sort_by,$this->sortable) ? $sort_by : 'id';
		$sort_dir = (strtoupper($sort_dir)=='ASC') ? 'ASC' : 'DESC';
		$this->db->order_by('f.'.$sort_by,$sort_dir);
		if($limit > 0){ $this->db->limit($limit,$offset); }
		return $this->db->get()->result_array();
	}

	public function countFeedbackList($bid,$filters=array()){
		$this->db->from('obhs_feedback f')->where('f.bid',$bid);
		$this->applyFilters($filters);
		return $this->db->count_all_results();
	}

	/** Janitor's own submissions (mobile app list). */
	public function getUserFeedbackList($uid,$limit=50,$offset=0){
		return $this->db->select('f.*')
			->from('obhs_feedback f')
			->where('f.uid',$uid)
			->order_by('f.id','DESC')
			->limit($limit,$offset)
			->get()->result_array();
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
			->from('obhs_feedback f')->where('f.bid',$bid);
		$this->applyFilters($filters);
		return $this->db->get()->row_array();
	}

	/** Avg score per rating category (for dashboard chart). */
	public function getCategoryAverages($bid,$filters=array()){
		$select = array();
		foreach($this->rating_fields as $field => $label){
			$select[] = "ROUND(AVG(NULLIF(f.$field,0)),2) as $field";
		}
		$this->db->select(implode(',',$select),FALSE)
			->from('obhs_feedback f')->where('f.bid',$bid);
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
			->from('obhs_feedback f')
			->where('f.bid',$bid);
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
			->from('obhs_feedback f')->where('f.bid',$bid);
		$this->applyFilters($filters);
		$this->db->group_by('f.train_no');
		$allowed = array('train_no','total_feedback','avg_psi','complaints');
		$sort_by  = in_array($sort_by,$allowed) ? $sort_by : 'total_feedback';
		$sort_dir = (strtoupper($sort_dir)=='ASC') ? 'ASC' : 'DESC';
		$this->db->order_by($sort_by,$sort_dir);
		if($limit > 0){ $this->db->limit($limit,$offset); }
		return $this->db->get()->result_array();
	}

	public function countTrainWiseReport($bid,$filters=array()){
		$this->db->select('f.train_no')->from('obhs_feedback f')->where('f.bid',$bid);
		$this->applyFilters($filters);
		$this->db->group_by('f.train_no');
		return $this->db->get()->num_rows();
	}

	public function getCoachWiseReport($bid,$filters=array(),$limit=0,$offset=0,$sort_by='total_feedback',$sort_dir='DESC'){
		$this->db->select("f.train_no, f.coach_no,
			COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			ROUND(AVG(NULLIF(f.rating_coach_cleanliness,0)),2) as avg_coach_clean,
			ROUND(AVG(NULLIF(f.rating_toilet_cleanliness,0)),2) as avg_toilet_clean,
			SUM(f.feedback_type='Complaint') as complaints",FALSE)
			->from('obhs_feedback f')->where('f.bid',$bid);
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
		$this->db->select('f.train_no')->from('obhs_feedback f')->where('f.bid',$bid);
		$this->applyFilters($filters);
		$this->db->group_by(array('f.train_no','f.coach_no'));
		return $this->db->get()->num_rows();
	}

	public function getJanitorReport($bid,$filters=array(),$limit=0,$offset=0,$sort_by='avg_psi',$sort_dir='DESC'){
		$this->db->select("f.uid, COALESCE(NULLIF(MAX(f.janitor_name),''),MAX(l.name)) as janitor_name,
			MAX(l.mobile) as mobile,
			COUNT(*) as total_feedback,
			ROUND(AVG(NULLIF(f.psi_score,0)),2) as avg_psi,
			ROUND(AVG(NULLIF(f.rating_staff_behaviour,0)),2) as avg_behaviour,
			SUM(f.feedback_type='Complaint') as complaints,
			COUNT(DISTINCT f.train_no) as trains_served",FALSE)
			->from('obhs_feedback f')
			->join('login l','l.id=f.uid','left')
			->where('f.bid',$bid);
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
		$this->db->select('f.uid')->from('obhs_feedback f')->where('f.bid',$bid);
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
			->from('obhs_feedback f')->where('f.bid',$bid);
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

	/** Distinct train/coach values for filter dropdowns. */
	public function getFilterOptions($bid){
		$trains = $this->db->select('train_no, MAX(train_name) as train_name',FALSE)
			->from('obhs_feedback')->where('bid',$bid)
			->group_by('train_no')->order_by('train_no','ASC')->get()->result_array();
		$coaches = $this->db->distinct()->select('coach_no')
			->from('obhs_feedback')->where('bid',$bid)
			->order_by('coach_no','ASC')->get()->result_array();
		$janitors = $this->db->select("f.uid, COALESCE(NULLIF(MAX(f.janitor_name),''),MAX(l.name)) as name",FALSE)
			->from('obhs_feedback f')->join('login l','l.id=f.uid','left')
			->where('f.bid',$bid)->group_by('f.uid')->get()->result_array();
		return array('trains'=>$trains,'coaches'=>$coaches,'janitors'=>$janitors);
	}
}
