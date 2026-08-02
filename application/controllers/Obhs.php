<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

/**
 * OBHS Feedback System - Admin dashboard & reports.
 * Follows the existing portal pattern (session auth like User.php,
 * dedicated feature controller like Payroll.php).
 */
class Obhs extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('url','text'));
		$this->load->library('session');
		$this->load->model('Web_Model','web');
		$this->load->model('Obhs_Model','obhs');
	}

	/**
	 * Session guard + business scope (same convention as User/Payroll).
	 * Super admin (web_login type 'A') gets '' => sees every business.
	 */
	private function bid(){
		if(empty($this->session->userdata('id'))){
			redirect('user-login');
		}
		if($this->session->userdata('type')=='A'){
			return '';
		}
		if($this->session->userdata('type')=='P'){
			return $this->session->userdata('empCompany');
		}
		return $this->session->userdata('login_id');
	}

	/** Collect report filters from GET (shared across all reports/exports). */
	private function filters(){
		$get = $this->input->get();
		$keys = array('start_date','end_date','train_no','coach_no','uid','feedback_type','status','psi_min','psi_max','search');
		$filters = array();
		foreach($keys as $k){
			if(isset($get[$k]) && $get[$k] !== ''){ $filters[$k] = trim($get[$k]); }
		}
		return $filters;
	}

	private function sortParams($default_by,$default_dir='DESC'){
		return array(
			'sort_by'  => $this->input->get('sort_by') ? $this->input->get('sort_by') : $default_by,
			'sort_dir' => (strtoupper((string)$this->input->get('sort_dir'))=='ASC') ? 'ASC' : $default_dir
		);
	}

	private function pageParams(){
		$page = (int)$this->input->get('pg');
		return ($page > 0) ? $page : 1;
	}

	// ------------------------------------------------------------- dashboard

	public function dashboard(){
		$bid = $this->bid();
		$filters = $this->filters();

		$data['title']    = 'OBHS Dashboard';
		$data['summary']  = $this->obhs->getDashboardSummary($bid,$filters);
		$data['category'] = $this->obhs->getCategoryAverages($bid,$filters);
		$data['monthly']  = $this->obhs->getMonthlyStats($bid,6,$filters);
		$data['psi_dist'] = $this->obhs->getPsiDistribution($bid,$filters);
		$data['recent']   = $this->obhs->getFeedbackList($bid,array(),10,0,'id','DESC');
		$data['filters']  = $filters;
		$this->load->view('obhs/dashboard',$data);
	}

	// ----------------------------------------------------- report definitions

	/**
	 * Central report registry: columns + data callbacks.
	 * Every report gets filter/search/pagination/sort/export/print via
	 * the shared obhs/report view.
	 */
	private function reportConfig($report){
		$reports = array(
			'master' => array(
				'title'   => 'Master Search',
				'columns' => array(
					'id'=>'ID','journey_date'=>'Journey Date','train_no'=>'Train No','train_name'=>'Train Name',
					'coach_no'=>'Coach','pnr_no'=>'PNR','passenger_name'=>'Passenger','passenger_mobile'=>'Mobile',
					'psi_score'=>'PSI','feedback_type'=>'Type','status'=>'Status','janitor_name'=>'Janitor'
				),
				'sortable'=> array('id','journey_date','train_no','coach_no','passenger_name','psi_score','feedback_type','status'),
				'default_sort'=>'id',
				'detail_link'=>true
			),
			'train' => array(
				'title'   => 'Train Wise Report',
				'columns' => array(
					'train_no'=>'Train No','train_name'=>'Train Name','coaches'=>'Coaches Covered','total_coaches'=>'Rake Coaches',
					'total_feedback'=>'Feedbacks','avg_psi'=>'Avg PSI','complaints'=>'Complaints','resolved'=>'Resolved'
				),
				'sortable'=> array('train_no','total_feedback','avg_psi','complaints'),
				'default_sort'=>'total_feedback',
				'detail_link'=>false
			),
			'coach' => array(
				'title'   => 'Coach Wise Report',
				'columns' => array(
					'train_no'=>'Train No','coach_no'=>'Coach','total_feedback'=>'Feedbacks','avg_psi'=>'Avg PSI',
					'avg_coach_clean'=>'Avg Coach Cleanliness','avg_toilet_clean'=>'Avg Toilet Cleanliness','complaints'=>'Complaints'
				),
				'sortable'=> array('train_no','coach_no','total_feedback','avg_psi','complaints'),
				'default_sort'=>'total_feedback',
				'detail_link'=>false
			),
			'janitor' => array(
				'title'   => 'Janitor Performance',
				'columns' => array(
					'janitor_name'=>'Janitor','mobile'=>'Mobile','total_feedback'=>'Feedbacks','avg_psi'=>'Avg PSI',
					'avg_behaviour'=>'Avg Behaviour Rating','complaints'=>'Complaints','trains_served'=>'Trains Served'
				),
				'sortable'=> array('janitor_name','total_feedback','avg_psi','complaints'),
				'default_sort'=>'avg_psi',
				'detail_link'=>false
			),
			'psi' => array(
				'title'   => 'PSI Report',
				'columns' => array(
					'id'=>'ID','journey_date'=>'Journey Date','train_no'=>'Train No','coach_no'=>'Coach',
					'passenger_name'=>'Passenger','rating_coach_cleanliness'=>'Coach Clean','rating_toilet_cleanliness'=>'Toilet Clean',
					'rating_doorway_cleanliness'=>'Doorway','rating_bedroll'=>'Bedroll','rating_staff_behaviour'=>'Behaviour',
					'rating_pest_control'=>'Pest Control','psi_score'=>'PSI'
				),
				'sortable'=> array('id','journey_date','train_no','coach_no','psi_score'),
				'default_sort'=>'psi_score',
				'detail_link'=>true
			),
			'monthly' => array(
				'title'   => 'Monthly Report',
				'columns' => array(
					'month'=>'Month','total_feedback'=>'Feedbacks','avg_psi'=>'Avg PSI',
					'complaints'=>'Complaints','resolved'=>'Resolved'
				),
				'sortable'=> array(),
				'default_sort'=>'month',
				'detail_link'=>false
			),
			'complaints' => array(
				'title'   => 'Complaint Tracking',
				'columns' => array(
					'id'=>'ID','journey_date'=>'Journey Date','train_no'=>'Train No','coach_no'=>'Coach',
					'passenger_name'=>'Passenger','passenger_mobile'=>'Mobile','remarks'=>'Complaint',
					'status'=>'Status','janitor_name'=>'Janitor','complaint_id'=>'Complaint Ref'
				),
				'sortable'=> array('id','journey_date','train_no','coach_no','status'),
				'default_sort'=>'id',
				'detail_link'=>true
			)
		);
		return isset($reports[$report]) ? $reports[$report] : false;
	}

	/** Fetch rows + total for a report (paged when $limit>0). */
	private function reportData($report,$bid,$filters,$limit=0,$offset=0,$sort_by='',$sort_dir='DESC'){
		switch($report){
			case 'master':
				return array(
					'rows' =>$this->obhs->getFeedbackList($bid,$filters,$limit,$offset,$sort_by,$sort_dir),
					'total'=>$this->obhs->countFeedbackList($bid,$filters)
				);
			case 'train':
				return array(
					'rows' =>$this->obhs->getTrainWiseReport($bid,$filters,$limit,$offset,$sort_by,$sort_dir),
					'total'=>$this->obhs->countTrainWiseReport($bid,$filters)
				);
			case 'coach':
				return array(
					'rows' =>$this->obhs->getCoachWiseReport($bid,$filters,$limit,$offset,$sort_by,$sort_dir),
					'total'=>$this->obhs->countCoachWiseReport($bid,$filters)
				);
			case 'janitor':
				return array(
					'rows' =>$this->obhs->getJanitorReport($bid,$filters,$limit,$offset,$sort_by,$sort_dir),
					'total'=>$this->obhs->countJanitorReport($bid,$filters)
				);
			case 'psi':
				return array(
					'rows' =>$this->obhs->getFeedbackList($bid,$filters,$limit,$offset,($sort_by=='psi_score'||in_array($sort_by,array('id','journey_date','train_no','coach_no'))) ? $sort_by : 'psi_score',$sort_dir),
					'total'=>$this->obhs->countFeedbackList($bid,$filters)
				);
			case 'monthly':
				$rows = $this->obhs->getMonthlyStats($bid,12,$filters);
				return array('rows'=>$rows,'total'=>count($rows));
			case 'complaints':
				return array(
					'rows' =>$this->obhs->getComplaintTracking($bid,$filters,$limit,$offset,$sort_by,$sort_dir),
					'total'=>$this->obhs->countComplaintTracking($bid,$filters)
				);
		}
		return array('rows'=>array(),'total'=>0);
	}

	/** Shared renderer used by all report pages. */
	private function renderReport($report){
		$bid = $this->bid();
		$config = $this->reportConfig($report);
		if($config===false){ show_404(); }

		$filters  = $this->filters();
		$sort     = $this->sortParams($config['default_sort']);
		$page     = $this->pageParams();
		$per_page = 50;
		$offset   = ($page-1)*$per_page;

		// Monthly report is a small aggregate - no server paging needed
		$limit = ($report=='monthly') ? 0 : $per_page;
		$result = $this->reportData($report,$bid,$filters,$limit,$offset,$sort['sort_by'],$sort['sort_dir']);

		$data['title']       = $config['title'];
		$data['report']      = $report;
		$data['columns']     = $config['columns'];
		$data['sortable']    = $config['sortable'];
		$data['detail_link'] = $config['detail_link'];
		$data['rows']        = $result['rows'];
		$data['total']       = $result['total'];
		$data['page']        = $page;
		$data['per_page']    = $per_page;
		$data['total_pages'] = ($limit>0) ? max(1,(int)ceil($result['total']/$per_page)) : 1;
		$data['filters']     = $filters;
		$data['sort']        = $sort;
		$data['options']     = $this->obhs->getFilterOptions($bid);
		$data['print_mode']  = ($this->input->get('print')=='1');
		$this->load->view('obhs/report',$data);
	}

	// -------------------------------------------------------- report actions

	public function master_search(){ $this->renderReport('master'); }
	public function train_report(){ $this->renderReport('train'); }
	public function coach_report(){ $this->renderReport('coach'); }
	public function janitor_report(){ $this->renderReport('janitor'); }
	public function psi_report(){ $this->renderReport('psi'); }
	public function monthly_report(){ $this->renderReport('monthly'); }
	public function complaint_report(){ $this->renderReport('complaints'); }

	// ---------------------------------------------------------------- detail

	public function feedback_detail($id=0){
		$bid = $this->bid();
		$row = $this->obhs->getFeedbackById((int)$id,$bid);
		if(empty($row)){ show_404(); }
		$data['title']    = 'Feedback #'.$row['id'];
		$data['feedback'] = $row;
		$data['rating_fields'] = $this->obhs->rating_fields;
		$this->load->view('obhs/feedback_detail',$data);
	}

	/** POST: update status/remarks from detail page; syncs linked complaint. */
	public function update_feedback(){
		$bid = $this->bid();
		$id = (int)$this->input->post('id');
		$status = $this->input->post('status');
		$remarks = $this->input->post('admin_remarks');
		$row = $this->obhs->getFeedbackById($id,$bid);
		if(!empty($row) && in_array($status,array('Pending','Working','Done'))){
			$update = array('status'=>$status);
			if($remarks !== null && $remarks !== ''){
				$update['remarks'] = $row['remarks']."\n[Admin ".date('d-m-Y H:i')."] ".$remarks;
			}
			$this->obhs->updateFeedback($id,$bid,$update);
			if(!empty($row['complaint_id'])){
				$this->obhs->updateComplaintStatus($row['complaint_id'],$status);
			}
			$this->session->set_flashdata('obhs_msg','Feedback #'.$id.' updated successfully');
		}
		redirect('obhs-feedback/'.$id);
	}

	// ---------------------------------------------------------------- export

	/** Excel export (full filtered dataset, TSV pattern like export_access_report). */
	public function export($report=''){
		$bid = $this->bid();
		$config = $this->reportConfig($report);
		if($config===false){ show_404(); }

		$filters = $this->filters();
		$sort    = $this->sortParams($config['default_sort']);
		$result  = $this->reportData($report,$bid,$filters,0,0,$sort['sort_by'],$sort['sort_dir']);

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=OBHS_".ucfirst($report)."_Report_".date('Ymd').".xls");

		echo "S.No\t".implode("\t",array_values($config['columns']))."\n";
		$i=1;
		foreach($result['rows'] as $row){
			$line = array($i++);
			foreach(array_keys($config['columns']) as $key){
				$val = isset($row[$key]) ? $row[$key] : '';
				$line[] = str_replace(array("\t","\n","\r"),' ',(string)$val);
			}
			echo implode("\t",$line)."\n";
		}
	}
}
