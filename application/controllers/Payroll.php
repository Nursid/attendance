<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class Payroll extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library(array('session','Ciqrcode','zip'));
		$this->load->model('Web_Model','web');
		$this->load->model('Api_Model_v11','app');
		$this->load->helper('cookie');

	}


	public function header(){

		$data['title'] 	= 'Headers';
		$data['lMenu']  = 'Sallery';
		if($this->session->userdata()['type']=='P'){
      
      $bid = $this->session->userdata('empCompany');
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
		$data['query']	= $this->db->get_where('ctc_head',['bid'=>$bid])->result_array();
		$this->load->view('payroll/salary_head',$data);

	}

	public function student(){

		$data['title'] 	= 'Headers';
		$data['lMenu']  = 'Student';
		$data['query']	= $this->db->get_where('ctc_head',['bid'=>$this->session->userdata('login_id')])->result_array();
		$this->load->view('payroll/student',$data);

	}

public function add_salary_head(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			
			$postdata=array(
			             'bid'=>$postdata['bid'],
						'name'=>$postdata['name'],
						'type'=>$postdata['type'],
						 'date_time'=>time(),
						 'active'=> 1
						 
					);
			$data=$this->db->insert('ctc_head',$postdata);
			$actdata=array(
			                            'bid'=>$postdata['bid'],
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Salary head Added ",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			if($data > 0){
			    
				$this->session->set_flashdata('msg','New Header Added!');
				redirect('Payroll/header');
			}
		}
		else{
			redirect('user-login');
		}
	}




	public function update_salary_head(){
		if(!empty($this->session->userdata('login_id'))){
			$postdata=$this->input->post();
			 if ($this->session->userdata()['type'] == 'P') {
          
          $loginId = $this->session->userdata('empCompany');
        } else {
          $loginId = $this->web->session->userdata('login_id');
        }
			$insert['bid'] = $loginId;
			if($postdata['active'] == "true"){
				$insert['active'] = 1;
			}else{
				$insert['active'] = false;
			}
			$insert['type'] = $postdata['type'];
			$insert['name'] = $postdata['name'];
			$insert['date_time'] = time();
			$query = $this->db->get_where('ctc_head',['name'=>$postdata['name'],'bid'=>$loginId]);
			if($query->num_rows() > 0 )
			{
				$this->db->where('name',$query->row()->name);
				$this->db->where('bid',$loginId);
				$data=$this->db->update('ctc_head',$insert);
				$actdata=array(
			                   'bid'=>$loginId ,
				             'uid'=>$this->web->session->userdata('login_id'),
				            'activity'=>"CTC Head Activated",
				            'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			}else{
				$data=$this->db->insert('ctc_head',$insert);
				$actdata=array(
			                   'bid'=>$loginId ,
				             'uid'=>$this->web->session->userdata('login_id'),
				            'activity'=>"CTC Head Activated",
				            'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
			}
				
			
			if($data > 0){
			    
				redirect('Payroll/header');
			}else{
				$this->session->set_flashdata('msg','Select One Row');
				redirect('Payroll/header');
			}
		}
		else{
			redirect('user-login');
		}
	}


public function edit_head(){
		if(!empty($this->session->userdata('id'))){
			$this->load->view('payroll/edit_head');
		}
		else{
			redirect('user-login');
		}
	}
	
	
	public function header_save(){
		if(!empty($this->session->userdata('login_id'))){
			$postdata=$this->input->post();
			$data = 0;
			if(isset($postdata['header_name'])){

				foreach($postdata['header_name'] as $key => $val){					
					$insert['bid'] = $this->session->userdata('login_id');
					$insert['header_name'] = $postdata['header_name'][$key];
					$insert['fixed'] = $postdata['fixed'][$key];
					$insert['percentage_of'] = $postdata['percentage_of'][$key];
					$header_name = $postdata['header_name'][$key];
					if($header_name == "TA" || $header_name == "DA" || $header_name == "HRA" || $header_name == "Conveyance" || $header_name == "Medical" || $header_name == "Special"){
						$insert['header_type'] = "Allowance";
					}else{
						$insert['header_type'] = "Dedction";
					}

					$query = $this->db->select("*")->from('header')
									  ->where('header_name',$postdata['header_name'][$key])
									  ->where('bid',$this->session->userdata('login_id'))->get();
					if($query->num_rows() > 0 )
    				{
    					$this->db->where('header_name',$postdata['header_name'][$key]);
    					$this->db->where('bid',$this->session->userdata('login_id'));
    					$data=$this->db->update('header',$insert);
    				}else{
						$data=$this->db->insert('header',$insert);
					}
				}
			}
			
			if($data > 0){
				$this->session->set_flashdata('msg','New Header Added!');
				redirect('Payroll/header');
			}else{
				$this->session->set_flashdata('msg','Select One Row');
				redirect('Payroll/header');
			}
		}
		else{
			redirect('user-login');
		}
	}

	public function header_save_new(){
		if(!empty($this->session->userdata('login_id'))){
			$postdata=$this->input->post();
			$data = 0;			
			$insert['bid'] = $this->session->userdata('login_id');
			$insert['header_name'] = $postdata['header_name'];
			$insert['header_type'] = $postdata['header_type'];
			$insert['fixed'] = $postdata['fixed'];
			$insert['percentage_of'] = $postdata['percentage_of'];
			$data=$this->db->insert('header',$insert);
			if($data > 0){
				$this->session->set_flashdata('msg','New Header Added!');
				redirect('Payroll/header');
			}else{
				$this->session->set_flashdata('msg','Select One Row');
				redirect('Payroll/header');
			}
		}
		else{
			redirect('user-login');
		}
	}

	public function salary(){

		$data['title'] 	= 'Salary';
		if($this->session->userdata()['type']=='P'){
      $id = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $id=$this->web->session->userdata('login_id');
    }
    
	/*	$data['employee']	= $this->db->select("*")
									->from('login')
									->join('user_request','user_request.user_id=login.id','left')
									->where(['user_request.business_id'=>$this->session->userdata('login_id')])
									->get()
									->result_array();;*/
		$data['header']	= $this->db->get_where('header',['bid'=>$id])->result_array();
	/*	$data['query']	= $this->db->select("*")
									->from('salary')
									->join('header','header.id=salary.header_id','left')
									->join('login','login.id=salary.uid','left')
									->where(['salary.bid'=>$this->session->userdata('login_id')])
									->get()
									->result_array();*/
		$this->load->view('payroll/salary',$data);

	}

	public function salary_save_new(){
		if(!empty($this->session->userdata('login_id'))){
			$postdata=$this->input->post();
			$data = 0;			
			$insert['bid'] = $this->session->userdata('login_id');
			$insert['uid'] = $postdata['uid'];
			$insert['header_id'] = $postdata['header_id'];
			$insert['amount'] = $postdata['amount'];
			$data=$this->db->insert('salary',$insert);
			if($data > 0){
				$this->session->set_flashdata('msg','New Salary Added!');
				redirect('Payroll/salary');
			}else{
				$this->session->set_flashdata('msg','Select One Row');
				redirect('Payroll/salary');
			}
		}
		else{
			redirect('user-login');
		}
	}

	public function employeesSalary()
	{
		$this->load->library('pagination');

		$data['page']  = 'payroll/salary';
		$data['title'] = 'Manage - Salary';
		$data['lMenu'] = 'Sallery';
		$search = $this->input->get('search');

		$cmpName = $this->web->getBusinessById($this->session->userdata('login_id'));

		$limit  = 25; // records per page
		$page   = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		// Get total rows count
		$total_rows = $this->web->countSalaryEmployees($search); // create this function



		if ($this->session->userdata()['type'] == 'P') {
			$loginID = $this->session->userdata('empCompany');
			$role = $this->web->getRollbyid($this->session->userdata('login_id'), $loginID);
		} else {
			$loginID = $this->session->userdata('login_id');
		}
		$config['base_url'] = base_url('Payroll/employeesSalary');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $limit;
		$config['uri_segment'] = 3;
		$data['offset'] = $page;

		/* ===== Bootstrap / DataTable Style ===== */

		$config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
		$config['full_tag_close']   = '</ul>';

		$config['first_link']       = '«';
		$config['first_tag_open']   = '<li class="page-item">';
		$config['first_tag_close']  = '</li>';

		$config['last_link']        = '»';
		$config['last_tag_open']    = '<li class="page-item">';
		$config['last_tag_close']   = '</li>';

		$config['next_link']        = 'Next';
		$config['next_tag_open']    = '<li class="page-item">';
		$config['next_tag_close']   = '</li>';

		$config['prev_link']        = 'Prev';
		$config['prev_tag_open']    = '<li class="page-item">';
		$config['prev_tag_close']   = '</li>';

		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '</span></li>';

		$config['num_tag_open']     = '<li class="page-item">';
		$config['num_tag_close']    = '</li>';

		$config['attributes']       = array('class' => 'page-link');

		$this->pagination->initialize($config);

		// Fetch limited data
		$data['salEmpList'] = $this->web->getSallaryReport($this->input->post(), $limit, $page, $search);
		
			// echo '<pre>';
			// print_r($data);
			// die();

		$data['pagination'] = $this->pagination->create_links();
		$data['cmp_name'] = $cmpName['name'];
		$data['payrollList'] = $this->web->getData('payroll_master', ['status'=>1], '', 'ASC');

		$this->load->view('salary/include/page', $data);
	}

	public function salaryReport()
	{
		$data['page']  		= 'payroll/employeesNetSalarylist';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
     if($this->session->userdata()['type']=='P'){
     
       $business_id = $this->session->userdata('empCompany');
        } else {
        $business_id=$this->web->session->userdata('login_id');
        }
        $cmpName = $this->web->getBusinessById($business_id);

		$month = $this->input->post('date_from'); // example: 2026-02

		if (!empty($month)) {

			$selectedYear  = date('Y', strtotime($month));
			$selectedMonth = date('m', strtotime($month));

			$currentYear   = date('Y');
			$currentMonth  = date('m');

			// Start date always 1st of selected month
			$start_date = $selectedYear . '-' . $selectedMonth . '-01';

			// If selected month is current month
			if ($selectedYear == $currentYear && $selectedMonth == $currentMonth) {
				$end_date = date('Y-m-d'); // today's date
			} else {
				// Last day of selected month
				$end_date = date('Y-m-t', strtotime($month));
			}
		}


		$apiUrl = "http://31.97.230.189:3000/api/attendance/monthly";

		$postData = [
			"companyId" =>  $business_id,
			"start_date" => $start_date,
			"end_date" => $end_date,
			"department" => "all",
			"section" => "all",
			"action" => "1"
		];

		$ch = curl_init($apiUrl);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response, true);

		if (!empty($response['success']) && $response['success'] == true) {

            $report = $response['data']['report'];
            $days   = count($response['data']['days']);

		

            $data = [];

            foreach ($report as $emp) {

                $data[] = [
                    'bid'         => $business_id,
                    'uid'         => $emp['user_id'],
                    'start_date'  => strtotime($start_date),
  				    'end_date'    => strtotime($end_date),
                    'days'        => $days,
                    'present'     => $emp['totalPresent'],
                    'absent'      => $emp['totalAbsent'],
                    'half_day'    => $emp['totalP2'],
                    'week_off'    => $emp['totalWeekOff'],
                    'holiday'     => $emp['totalHoliday'],
                    'leaves'      => $emp['totalLeaves'],
                    'ed'          => $emp['totalOD'],
                    'short_leave' => $emp['totalShortLeave'],
                    'status'      => 1,
                    'date_time'   => date('Y-m-d H:i:s')
                ];
            }


            // ===== TRANSACTION START =====
            $this->db->trans_begin();
            // Delete old month data
            $this->db->where('bid', $business_id);
            $this->db->where('start_date', $start_date);
            $this->db->delete('salary_report');

            // Bulk insert new data
            if (!empty($data)) {
                $this->db->insert_batch('salary_report', $data);
            }

            // ===== TRANSACTION CHECK =====
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo "Transaction Failed";
            } else {
                $this->db->trans_commit();
                echo "Salary Report Calculated Successfully";
            }

        } else {
            echo "API Failed";
        }

		// echo '<pre>';
		// print_r($this->input->post());
		// die();

		// if($this->input->post()){
        //     $data['salEmpList'] = $this->web->insertDaysReport($this->input->post());
		// 	$data['salEmpList'] = $this->web->insertSalleryReport($this->input->post());
		// 	$data['salEmpList'] = $this->web->getSallaryReport($this->input->post());
		// 	$data['date_from']  = $this->input->post()['date_from'];
		// 	$date  = $this->input->post()['date_from'];
		// }
		// else
		// {   $data['salEmpList'] = $this->web->insertDaysReport($this->input->post());
		// 	$data['salEmpList'] 	= $this->web->insertSalleryReport();
		// 	$data['salEmpList'] = $this->web->getSallaryReport();
		// 	$data['date_from'] = date("Y-m");
		// 	$date = date("Y-m");
		// }
		$data['cmp_name']=$cmpName['name'];

		// redirect('salary-employees');
		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		redirect('/Payroll/employeesNetSalary?getDate='.$date);
	}

	public function getCurrentCtcDetails()
	{


		$selectedUserID = $this->input->post('userID');
		if($this->session->userdata()['type']=='P'){
     
     $business_id = $this->session->userdata('empCompany');
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
		// $business_id  = $this->web->session->userdata('login_id');
		$date = $this->input->post('date_from');
		$salary_month = $this->db->select('DATE_FORMAT(date, "%Y-%m") as added_date')->from('salary')->where(['uid'=>$selectedUserID])->where("DATE_FORMAT(salary.date,'%Y-%m') <=",$date)->order_by("date", "asc")->get()->result_array();
                          if($salary_month){
                              if(!in_array($date,array_column($salary_month, 'added_date'))){
                                if(current(array_column($salary_month, 'added_date')) < $date){
                                  unset($date);
                                  $addedDates = array_column($salary_month, 'added_date');
                                  $date = "";
                                  if(!empty($addedDates)){
                                     $date = $addedDates[count($addedDates)-1];
                                  }
                                }
                              }
                          }
		
//$salary = $this->db->get_where('salary',['uid'=>$empData->emp_id,"DATE_FORMAT(salary.date,'%Y-%m')"=>$date])->row();
		$checkExist 	= $this->db->query("SELECT * FROM salary WHERE bid = '".$business_id."' AND  uid = '".$selectedUserID."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();

	//	if(empty($checkExist))
	//	{
		//	$checkExist = $this->db->query("SELECT * FROM salary WHERE bid = '".$business_id."' AND  uid = '".$selectedUserID."' ORDER BY date ASC ")->row_array();
	//	}


		if(!empty($checkExist))
		{

			$allowance = '';
			$deduction = '';
			 $allowanceForm_val = $this->db->get_where('ctc_head',['active'=>1,'bid'=>$business_id])->result_array();
			 $deductionForm_val = $this->db->get_where('ctc_head',['type'=>'Deduction','active'=>1,'bid'=>$business_id])->result_array();
			 $deductionForm  = [];
			 foreach ($deductionForm_val as $key => $dFormData) {
			 	array_push($deductionForm , $dFormData['name']);
			 }
			// $allColumnArray = array('DA','HRA','MEAL', 'CONVEYANCE','MEDICAL','SPECIAL','TA', 'PF','ESI','Other');
			// $deductionForm  = array('TDS','ESI','Other');


			foreach ($allowanceForm_val as $key => $FormData) {
				$checkHeader = $this->db->select('salary_basic.*')
                                          ->from('salary_basic')
                                          ->join('salary','salary.id=salary_basic.sid','left')
                                          ->where(['salary_basic.header_id'=>$FormData['id'],'salary.uid'=>$selectedUserID])
                                          ->get()
                                          ->row();
				$form_data  = strtolower($FormData['id']);
				$html = '';
				$html .= '<div class="row">';
				$html .= '<div class="col-md-5">';
				$html .= '<div class="form-group">';
				$html .= '<div class="input-group">';
				$html .= '<input type="hidden" class="inp_allowance" value="'.$FormData['id'].'" name="header_id[]">
				<input type="text" class="form-control" readonly="" value="'.$FormData['name'].'" name="allowance[]">';
				$html .= '<div class="input-group-append">';
				$html .= '<select name="type[]"  id="'.$form_data.'_type" class="bg-light" onchange="setBasicCTC();">';
				$html .= '<option value="Manual" '.(($checkHeader->header_type=='Manual')?'selected': '').' >Manual</option>';
				$html .= '<option value="%" '.(($checkHeader->header_type=='%')?'selected': '').' >%</option>';
				$html .= '</select>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div class="col-md-3">';
				$html .= '<div class="form-group">';
				$html .= '<div class="input-group">';
				$html .= '<div class="input-group-append  '.$form_data.'_manual '.(($checkHeader->header_type=='Manual')?'': 'd-none').' ">';
				$html .= '<span class="input-group-text">'.INDIAN_SYMBOL.'</span>';
				$html .= '</div>';
				$html .= '<input type="number" name="value[]" value="'.$checkHeader->header_value.'" oninput="setBasicCTC();" min="0" step="0.01" class="form-control" id="'.$form_data.'_value" placeholder="0">';
				$html .= '<div class="input-group-append '.$form_data.'_percent  '.(($checkHeader->header_type=='Manual')?'d-none': '').' ">';
				$html .= '<span class="input-group-text">%</span>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div class="col-md-4">';
				$html .= ' <div class="form-group">';
				$html .= ' <div class="input-group">';
				$html .= ' <div class="input-group-append">';
				$html .= ' <span class="input-group-text">'.INDIAN_SYMBOL.'</span>';
				$html .= ' </div>';
				$html .= ' <input type="number"  id="'.$form_data.'_amount" name="amount[]" value="'.$checkHeader->amount.'" readonly="" min="0" class="form-control amount_type" data-type="'.$FormData['type'].'" id="allowance_value" placeholder="0">';
				$html .= ' </div>';
				$html .= ' </div>';
				$html .= ' </div>';
				$html .= ' </div>';


				if(in_array($FormData['name'], $deductionForm))
				{
					$deduction .= $html;
				}
				else
				{
					$allowance .= $html;
				}

			}

			$response = array('status'    => 1,
			'details'   => $checkExist,
			'deduction' => $deduction,
			'allowance' => $allowance,
		);
		}
		else
		{
			$allowance = '';
			$deduction = '';
			 $allowanceForm_val = $this->db->get_where('ctc_head',['active'=>1,'bid'=>$business_id])->result_array();
			 $deductionForm_val = $this->db->get_where('ctc_head',['type'=>'Deduction','active'=>1,'bid'=>$business_id])->result_array();
			 $deductionForm  = [];
			 foreach ($deductionForm_val as $key => $dFormData) {
			 	array_push($deductionForm , $dFormData['name']);
			 }
			// $allColumnArray = array('DA','HRA','MEAL', 'CONVEYANCE','MEDICAL','SPECIAL','TA', 'PF','ESI','Other');
			// $deductionForm  = array('TDS','ESI','Other');


			foreach ($allowanceForm_val as $key => $FormData) {
				$checkHeader = "Manual";
				$form_data  = strtolower($FormData['id']);
				$html = '';
				$html .= '<div class="row">';
				$html .= '<div class="col-md-5">';
				$html .= '<div class="form-group">';
				$html .= '<div class="input-group">';
				$html .= '<input type="hidden" class="inp_allowance" value="'.$FormData['id'].'" name="header_id[]">
				<input type="text" class="form-control" readonly="" value="'.$FormData['name'].'" name="allowance[]">';
				$html .= '<div class="input-group-append">';
				$html .= '<select name="type[]"  id="'.$form_data.'_type" class="bg-light" onchange="setBasicCTC();">';
				$html .= '<option value="Manual" '.(($checkHeader=='Manual')?'selected': '').' >Manual</option>';
				$html .= '<option value="%" '.(($checkHeader=='%')?'selected': '').' >%</option>';
				$html .= '</select>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div class="col-md-3">';
				$html .= '<div class="form-group">';
				$html .= '<div class="input-group">';
				$html .= '<div class="input-group-append  '.$form_data.'_manual '.(($checkHeader=='Manual')?'': 'd-none').' ">';
				$html .= '<span class="input-group-text">'.INDIAN_SYMBOL.'</span>';
				$html .= '</div>';
				$html .= '<input type="number" name="value[]" value="'.$checkHeader.'" oninput="setBasicCTC();" min="0" step="0.01" class="form-control" id="'.$form_data.'_value" placeholder="0">';
				$html .= '<div class="input-group-append '.$form_data.'_percent  '.(($checkHeader=='Manual')?'d-none': '').' ">';
				$html .= '<span class="input-group-text">%</span>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '<div class="col-md-4">';
				$html .= ' <div class="form-group">';
				$html .= ' <div class="input-group">';
				$html .= ' <div class="input-group-append">';
				$html .= ' <span class="input-group-text">'.INDIAN_SYMBOL.'</span>';
				$html .= ' </div>';
				$html .= ' <input type="number"  id="'.$form_data.'_amount" name="amount[]" value="" readonly="" min="0" class="form-control amount_type" data-type="'.$FormData['type'].'" id="allowance_value" placeholder="0">';
				$html .= ' </div>';
				$html .= ' </div>';
				$html .= ' </div>';
				$html .= ' </div>';


				if(in_array($FormData['name'], $deductionForm))
				{
					$deduction .= $html;
				}
				else
				{
					$allowance .= $html;
				}

			}

				$response = array('status'    => 1,
				'details'   => ["basic"=>"02"],
				'deduction' => $deduction,
				'allowance' => $allowance,
			);

		}

		echo json_encode($response);

	}

	public function saveCtc()
	{
		$in_data = $this->input->post();
		$date = $this->input->post('date_from');
		if($this->session->userdata()['type']=='P'){
      
      $business_id = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
	//	$business_id  = $this->web->session->userdata('login_id');
	$uname = $this->web->getNameByUserId($in_data['select_user_id']);
		$saveCtcArray = array(
			'bid' 	=> $business_id,
			'uid' 		=> $in_data['select_user_id'],
			'basic' 			=> $in_data['basic'],
			'basic_value' 	=> $in_data['basic_value'],
			'total_ctc_amount' 	=> $in_data['input_total_ctc_amount'],
			'date'=> date("Y-m-d H:i:s",strtotime($date))
		);

		$checkExist = $this->db->query("SELECT id FROM salary WHERE  bid = '".$business_id."' AND  uid = '".$in_data['select_user_id']."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
		if(!empty($checkExist))
		{
			$save = $this->web->UpdateData('salary' ,$saveCtcArray, array('id' => $checkExist['id']));
			if(!empty($in_data['header_id']))
			{
				
				foreach ($in_data['header_id'] as $key => $allData) {
					$salaryBasic = $this->db->get_where('salary_basic',['sid'=>$checkExist['id'],'id','header_id'=>$in_data['header_id'][$key]])->row();
					if($salaryBasic){
						$insert['sid']   = $checkExist['id'];
						$insert['header_id']   = $in_data['header_id'][$key];
						$insert['header_type']   = $in_data['type'][$key];
						$insert['header_value']   = $in_data['value'][$key];
						$insert['amount']   = $in_data['amount'][$key];
						$this->web->UpdateData('salary_basic' ,$insert, ['sid'=>$checkExist['id'],'id','header_id'=>$in_data['header_id'][$key]]);
						
						
							
					}else{
						$insert['sid']   = $checkExist['id'];
						$insert['header_id']   = $in_data['header_id'][$key];
						$insert['header_type']   = $in_data['type'][$key];
						$insert['header_value']   = $in_data['value'][$key];
						$insert['amount']   = $in_data['amount'][$key];
						$this->web->saveData('salary_basic' ,$insert);
						
					}
				}
				
				
				$actdata=array(
			                   'bid'=>$business_id ,
				             'uid'=>$this->web->session->userdata('login_id'),
				            'activity'=>"Salary of employee ".$uname[0]->name. " updated for the month of ".date('M',strtotime($date)). " ",
				            'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
				
			}
			$actdata=array(
			                   'bid'=>$business_id ,
				             'uid'=>$this->web->session->userdata('login_id'),
				            'activity'=>"Salary of employee ".$uname[0]->name. " Added for the month of ".date('M',strtotime($date)). "",
				            'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
		}
		else
		{
			$this->web->saveData('salary' ,$saveCtcArray);
			$insert_id = $this->db->insert_id();
			if(!empty($in_data['header_id']))
			{
				foreach ($in_data['header_id'] as $key => $allData) {
					$insert['sid']   = $insert_id;
					$insert['header_id']   = $in_data['header_id'][$key];
					$insert['header_type']   = $in_data['type'][$key];
					$insert['header_value']   = $in_data['value'][$key];
					$insert['amount']   = $in_data['amount'][$key];
					$this->web->saveData('salary_basic' ,$insert);
					
				
				}
			}
			
			
			$actdata=array(
			                   'bid'=>$business_id ,
				             'uid'=>$this->web->session->userdata('login_id'),
				            'activity'=>"Salary of employee ".$uname[0]->name. " Added for the month of ".date('M',strtotime($date)). "",
				            'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);
	
			
			
			
		}


		$response = array('message' 	=> 'CTC have successfully saved.','status'  => '1');
		echo json_encode($response);

	}


	//salaryEmployees
	public function employeesNetSalary()
	{
		$this->load->library('pagination');
	    if($this->session->userdata()['type']=='P'){
          $business_id = $this->session->userdata('empCompany');
        } else {
          $business_id=$this->web->session->userdata('login_id');
        }
		$data['page']  		= 'payroll/employeesNetSalarylist';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
        $cmpName = $this->web->getBusinessById($business_id);
		$search = $this->input->get('search');
		$limit  = 15; // records per page
		$page   = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$total_rows = $this->web->countSalaryEmployees($search); // create this function

		$config['base_url'] = base_url('Payroll/employeesNetSalary');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $limit;
		$config['uri_segment'] = 3;
		$data['offset'] = $page;


		/* ===== Bootstrap / DataTable Style ===== */

		$config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
		$config['full_tag_close']   = '</ul>';

		$config['first_link']       = '«';
		$config['first_tag_open']   = '<li class="page-item">';
		$config['first_tag_close']  = '</li>';

		$config['last_link']        = '»';
		$config['last_tag_open']    = '<li class="page-item">';
		$config['last_tag_close']   = '</li>';

		$config['next_link']        = 'Next';
		$config['next_tag_open']    = '<li class="page-item">';
		$config['next_tag_close']   = '</li>';

		$config['prev_link']        = 'Prev';
		$config['prev_tag_open']    = '<li class="page-item">';
		$config['prev_tag_close']   = '</li>';

		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '</span></li>';

		$config['num_tag_open']     = '<li class="page-item">';
		$config['num_tag_close']    = '</li>';

		$config['attributes']       = array('class' => 'page-link');

		$this->pagination->initialize($config);


		if(isset($_GET['getDate'])){

			$data['salEmpList'] 	= $this->web->getSallaryReport(['date_from'=>$_GET['getDate']],$limit, $page);
			$data['date_from']		= $_GET['getDate'];
		}
		else
		{
			$data['salEmpList'] 	= $this->web->getSallaryReport(null, $limit, $page, null);
			$data['date_from'] 		= date("Y-m");
		}

		$data['pagination'] = $this->pagination->create_links();

		// echo '<pre>';
		// print_r($data);
		// die();
    	$data['cmp_name']=$cmpName['name'];
		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		$this->load->view('salary/include/page',$data);
	}


	//salaryEmployees
	public function employeesPayslip()
	{
		$id = $_GET['id'];
		$date = $_GET['date'];
		$selectDate = $_GET['selectDate'];
		
		$data['date']= $date;
	
		$salary_month = $this->db->select('DATE_FORMAT(date, "%Y-%m") as added_date')->from('salary')->where(['uid'=>$id])->where("DATE_FORMAT(salary.date,'%Y-%m') <=",$date)->order_by("date", "asc")->get()->result_array();
	      if($salary_month){
	          if(!in_array($date,array_column($salary_month, 'added_date'))){
	            if(current(array_column($salary_month, 'added_date')) < $date){
	              unset($date);
	              $date = end(array_column($salary_month, 'added_date'));
	            }
	          }
	      }
		$data['page']  		= 'payroll/payslip';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
			if($this->session->userdata()['type']=='P'){
      
      $bid = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
		//$bid= $this->web->session->userdata('login_id');
		// $date = date("Y-m-d");

		$yearName  = date('Y', strtotime($selectDate));
		$monthName = date('m', strtotime($selectDate));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($date)),date('Y',strtotime($date))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days");

		$salaryData = $this->db->query("SELECT * FROM salary_report where uid='$id' and bid='$bid' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
		
	//	$data['paid_day'] = $salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+$salaryData['leaves']+$salaryData['ed'];
		$data['paid_day'] = $salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+$salaryData['leaves'];
		$data['emp_details']  = $this->db->get_where('login',['id'=>$id])->row();
		$data['pay_mode']=$salaryData['pay_mode'];
		
		//new line
		$data['emp_more_details']  = $this->db->get_where('staff_detail',['uid'=>$id])->row();
	//	$data['leave3'] = $this->db->query("SELECT * FROM leaves where uid='$id' and bid='$bid' AND YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName'")->row_array();
	  
		$data['open_leave']  = $this->db->get_where('open_leave',['uid'=>$id],['bid'=>$bid])->row();

	
		$data['report']  = $this->db->get_where('salary_report',['uid'=>$id])->row();
    	$data['cmp_details'] =	$this->web->getBusinessById($bid);
    	$data['earning'] =	$this->db->select("salary_basic.*,ctc_head.name")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Allowance')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
    	$data['deduction'] =	$this->db->select("salary_basic.*,ctc_head.name,salary_basic.header_value,salary_basic.header_type")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Deduction')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
    								
    	$data['salary_report'] =	$this->db->select("*")->from('salary_report')
    								->where('uid',$id)
    								->where("DATE_FORMAT(date_time,'%Y-%m')", $date)
    								->get()->row();

    	$data['ctc_salary'] 		=	$this->db->select("*")->from('salary')
    								->where('uid',$id)
    								// ->where("DATE_FORMAT(date,'%Y-%m')", $date)
    								->get()->row();
    //	$data['working_days']	= $data['salary_report'] ? $data['salary_report']->week_off : 0;

		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		$data['salary']  = $this->db->query("SELECT * FROM salary WHERE bid = '".$bid."' AND  uid = '".$id."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
		
		

		$data['allowance_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Allowance'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();

      	$data['deduction_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Deduction'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();
        $data['earning_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $data['deduction_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
         
         $data['leaven'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >",$start_time )
                                              ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
        $data['leaveold'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where('leaves.type!=',"other")
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date <",$start_time )
                                             // ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
          $data['leaveoldothern'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             ->where('leaves.type',"other")
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             
                                              ->where("from_date <",$start_time )
                                             
                                              ->get()
                                              ->row();                                    
                                                                                    
         
       // $data['usedleave']=		$end_time;
        
        $data['usedoldleave']=$data['leaveold'] ? $data['leaveold']->half_day :0; 
        $data['leaveoldother']=$data['leaveoldothern'] ? $data['leaveoldothern']->half_day :0; 
        
       // $data['total_leave'] =	$data['open_leave'] ? $data['open_leave']->cl+$data['open_leave']->el+$data['open_leave']->pl+$data['open_leave']->sl+$data['open_leave']->hl+$data['open_leave']->rh-$data['usedoldleave']:0;
       // $data['balanceleave']=$data['total_leave']- $data['usedleave'] ;
       
        
        $openleavedate=$data['open_leave'] ? $data['open_leave']->open_date:0;
        $openleavemonth=date('m', $openleavedate);
        $monthdiff=$monthName-$openleavemonth;
        $data['usedleave']=$data['leaven'] ? $data['leaven']->half_day :0;
        $data['entitleleave']=$data['open_leave'] ? $data['open_leave']->fixed_limit :0;
         $balanceleave=$data['entitleleave']?$data['entitleleave']:0;
         $data['balanced_leave']= $balanceleave* $monthdiff;
        $data['openleave']=$data['open_leave'] ? $data['open_leave']->other-$data['leaveoldother']- $data['usedoldleave']+ $data['balanced_leave'] :0 ;
        
        
        $advance = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $imi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
          $oldimi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')<" ,$selectDate)
                          ->get()
                          ->row();                 
        
        $loan = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[4])
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          //->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
        $advance_total = isset($advance->amount) ? $advance->amount : 0;
        $loan_amount = isset($loan->amount) ? $loan->amount : 0;
        $imi_amount = isset($imi->amount) ? $imi->amount : 0;
        $oldimi_amount = isset($oldimi->amount) ? $oldimi->amount : 0;
        
       // $data['advance'] = $advance_total+($loan_amount-$imi_amount);
       $data['advance'] = $advance_total+$loan_amount-$oldimi_amount;
        $data['advance_paid'] = $advance_total+ $imi_amount;
         $data['advance_balance'] = $loan_amount- $imi_amount-$oldimi_amount;
        $data['imi'] = $imi_amount;
        $data['loan'] = $loan_amount;
        $data['number_of_days'] = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($date)),date('Y',strtotime($date)));
            // print_r(round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$number_of_days)*$data['working_days'])); exit;
        
        // $data['oneDaySalary'] = $data['ctc_salary'] ? round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])*$data['working_days']) : 0;
        $data['oneDaySalary'] = $data['ctc_salary'] ? round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])) : 0;
		$this->load->view('payroll/payslip',$data);
	}

	public function earnings()
	{
		$data['page']  		= 'payroll/earnings';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
   	if($this->session->userdata()['type']=='P'){
      
      $business_id = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
        $cmpName = $this->web->getBusinessById($business_id);
		$data['salEmpList'] 	= $this->web->getSallaryReport();
		$data['date_from'] = date("Y-m");
    	$data['cmp_name']=$cmpName['name'];
		$data['payrollList'] 	= $this->db->get_where('payroll_master', ['status' => 1], '', 'ASC')->result_array();
		// print_r($data['payrollList']); exit;
		$this->load->view('salary/include/page',$data);
	}

public function addDeductAmount()
{
    if($this->session->userdata()['type']=='P'){
      
      $bid = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
    
	$in_data = $this->input->post();
	if($in_data){
		$settle = 1;
		$paid = 1;
		$payrollId = 0;
		$masterId = $in_data['payroll_master_id'];
		// if($in_data['payroll_master_id']==0){
		// 	$masterId = 2;
		// 	$paid = 1;
		// 	$settle = 1;
		// }
		// if($in_data['payroll_master_id']==2){
		// 	$masterId = 2;
		// 	$paid = 0;
		// 	$settle = 0;
		// }
		$saveArray = array(
			'business_id' 		=> $bid,
			'payroll_master_id'	=> $masterId,
			'user_id	' 		=> $in_data['add_deduct_user_id'],
			'pay_date' 			=> $in_data['date'],
			'amount	' 			=> $in_data['amount'],
			'remarks' 			=> $in_data['note'],
			'status' 			=> 1,
			'settled'			=>$settle,
			'paid'				=>$paid,
			'payroll_id'		=>$payrollId,
			'date' 			=> date("Y-m-d H:i:s",strtotime($in_data['selectDate']))
		);

		$save = $this->web->saveData('payroll_history' ,$saveArray);
		if($save > 0)
		{
			$response = array('message' 	=> 'Payrol have successfully saved',
			'status'  => '1'
		);
		$uname = $this->web->getNameByUserId($in_data['add_deduct_user_id']);
		$actdata=array(
			                            'bid'=>$bid,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New Earning of Employee ".$uname[0]->name. " Added",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
				
						
	}
	else
	{
		$response = array('message' 	=> 'Sorry! somthings wents wrong.',
		'status'  => '0'
	);
}
echo json_encode($response);
}
}


/*  GET PAYROLL HISTORY  */

public function payrolHidtory(){
	$in_data = $this->input->post();
	$response = array('list' 		=> '<tr><th colspan="4"><p class="text-center text-danger">Data notfound.</p></th></tr>',
	'status'  	=> '1',
	'totalAmount' => '0',
	);
	if($this->session->userdata()['type']=='P'){
      
      $business_id = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
	//$business_id = $this->web->session->userdata('login_id');
	if($in_data['payrolID']){
		$payrolID 		= $in_data['payrolID'];
		$user_id 		= $in_data['user_id'];
		$paid = 1;
		$payrollHist = $this->db->select("payroll_history.*,payroll_master.name")
								->from('payroll_history')
								->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
								->where(['business_id' => $business_id, 'user_id' => $user_id,'payroll_history.status'=>1,'paid'=>$paid,'ORDER BY pay_date DESC'])
								->get()
								->result_array();

		$html = '';
		if(!empty($payrollHist)){
			$sr =1;
			foreach ($payrollHist as $key => $value) {
				$payRollAmount = $value['amount'];
				$payRollId = $value['id'];
				$html .='<tr>';
				$html .=' <td>'.$sr.'</td>';
				$html .=' <td>'.$value['name'].'</td>';
				$html .=' <td>'.$value['amount'].'</td>';
				$html .=' <td>'.$value['pay_date'].'</td>';
				$html .=' <td><p> '.$value['remarks'].'</p></td>';
				if($value['payroll_master_id']==4){
					$html .=' <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#settleAmountModal" onclick="editSettleModalAmount('."$user_id".','."$payRollAmount".','."$payRollId".')">Deduct</button></td>';
				}else{
					$html .=' <td></td>';
				}
				$html .=' </tr>';
				$sr++;
			}
			$response = array('list' 		=> $html ,
			'status'  		=> '1' ,
			'totalAmount' 	=> array_sum(array_column($payrollHist,'amount') ) ,
			);
		}
	}
	echo json_encode($response);
	}

	public function earningDelete(){
		if (!empty($this->session->userdata('id'))) {
		    if($this->session->userdata()['type']=='P'){
      
      $business_id = $this->session->userdata('empCompany');
      
  
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
			$id = $_GET['id'];
			$uid = $_GET['uid'];
			$update['status']   = 2;
			$this->web->UpdateData('payroll_history' ,$update, ['id'=>$id]);
				$uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$business_id,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Earning of Employee ".$uname[0]->name. " Deleted",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
			redirect('Payroll/earningHistory?id='.$uid);
			
			
				
						
		} else {
			redirect('user-login');
		}
		
	}

	public function earningEdit($id){
		$data['page']  		= 'payroll/earningEdit';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';

		$data['payrollList'] 	= $this->db->get_where('payroll_master', ['status' => 1], '', 'ASC')->result_array();
		$data['earning'] 	= $this->db->get_where('payroll_history', ['id' => $id])->row();
		$this->load->view('salary/include/page',$data);
		
	}

	public function earningUpdate(){
		if (!empty($this->session->userdata('id'))) {
		    if($this->session->userdata()['type']=='P'){
      
      $business_id = $this->session->userdata('empCompany');
      //$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
    
			$id = $this->input->post('id');
			$uid = $this->input->post('user_id');
			$update['payroll_master_id']   = $this->input->post('payroll_master_id');
			$update['pay_date']   = $this->input->post('pay_date');
			$update['amount']   = $this->input->post('amount');
			$update['remarks']   = $this->input->post('remarks');
			$this->web->UpdateData('payroll_history' ,$update, ['id'=>$id]);
			
			$uname = $this->web->getNameByUserId($uid);
			$actdata=array(
			                            'bid'=>$business_id,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"Earning of Employee ".$uname[0]->name. " edited",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
				
						
						
			redirect('Payroll/earningHistory?id='.$uid);
		} else {
			redirect('user-login');
		}
		
	}

/*  GET PAYROLL HISTORY  */

public function earningHistory()
{
	$data['page']  		= 'payroll/earningHistory';
	$data['title'] 		= 'Manage - Earning History';
	$data['lMenu']  	= 'Earning History';
	if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $business_id = $this->session->userdata('empCompany');
      //$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $business_id=$this->web->session->userdata('login_id');
    }
    
//	$business_id = $this->web->session->userdata('login_id');
	$id = $_GET['id'];
	$data['list'] = $this->db->select("payroll_history.*,payroll_master.name")
							->from('payroll_history')
							->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
							->where(['business_id' => $business_id, 'user_id' => $id,'payroll_history.status'=>1,'ORDER BY pay_date DESC' ])
							->get()
							->result();	
	$this->load->view('salary/include/page',$data);
}

	function getCompanyUsersAtttest(){
  	// $data=json_decode(file_get_contents("php://input"));
   	//if(key($data)=="checkon"){
    $postdata=$this->input->post();
	$start_date = "2010-01-01";
	$end_date = "2024-01-31";
	$mobile = 7309990666;
	$check=$this->app->checkMobile($mobile);
	 // $checkkey=$data->checkon->key;

     if(!empty($check['id']) && $check['user_group']==1){
       $users_data = $this->app->getCompanyUsers($check['id']);
       $start_time = strtotime(date("Y-m-d 00:00:00",strtotime($start_date)));
       $end_time = strtotime(date("Y-m-d 23:59:59",strtotime($end_date)));

      
       if(!empty($users_data)){
         $new_array = array();
         foreach($users_data as $user){
             if(($user->doj!="" || $start_time>=$user->doj) && ($user->left_date=="" || $start_time<$user->left_date)){
                 $user_at = $this->app->getUserAttendanceByDate($start_time,$end_time,$user->user_id,$check['id'],1);
               
               $data = array();

             
               if(!empty($user_at)){
                 foreach($user_at as $at){

                   $data[] = array(
                    'id'=>$at->id,
                     'mode'=>$at->mode,
                     'time'=>date('h:i A', $at->io_time),
                     'latitude'=>$at->latitude,
                     'longitude'=>$at->longitude,
                     'verified'=>$at->verified,
                     'manual'=>$at->manual,
                     'location'=>$at->location,
                     'night'=>$at->night
                   );
                 }
               }else{
                 $data = array();
               }
            
               $new_array[] =array(
                 'user_id'=>$user->user_id,
				 'emp_code'=>$user->emp_code,
                 'name'=>$user->name,
                 'startdate'=>$start_time,
                 'enddate'=> $end_time,
                 'logdata'=> $data,
               );


               	$insert['uid'] = $user->user_id;
				$insert['bid'] = 5;
				$insert['day_start_time'] = $user->user_id;
				$insert['day_end_time'] = $user->user_id;
				$insert['data'] = json_encode($data);
				// $this->db->insert('daily_report',$insert);

             }
         }
         echo $response= json_encode(array('checkon'=>$new_array));
       }else{
         $res=array('msg'=>'No Data Foud');
         echo $response= json_encode(array('checkon'=>$res));
       }
     }else{
         $res=array('msg'=>'Currently Inactive','prime'=>'0');
         echo $response= json_encode(array('checkon'=>$res));
     }
   }

   public function update_working_days(){
		if(!empty($this->session->userdata('id'))){
			$postdata=$this->input->post();
			if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $bid = $this->session->userdata('empCompany');
      //$role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
			if(isset($postdata['user_id'])){
				$present = 0;
				$halfDay = 0;
				$weekOff = 0;
				$holiday = 0;
				$leaves = 0;
				$shortLeave = 0;
				$ed = 0;


				if(isset($postdata['wdPresent'])){
					$present = $postdata['wdPresent'];
				}
				if(isset($postdata['wdHalfDay'])){
					$halfDay = $postdata['wdHalfDay'];
				}
				if(isset($postdata['wdWeekOff'])){
					$weekOff = $postdata['wdWeekOff'];
				}
				if(isset($postdata['wdHoliday'])){
					$holiday = $postdata['wdHoliday'];
				}
				if(isset($postdata['wdLeaves'])){
					$leaves = $postdata['wdLeaves'];
				}
				if(isset($postdata['wdShortLeave'])){
					$shortLeave = $postdata['wdShortLeave'];
				}
				if(isset($postdata['wdED'])){
					$ed = $postdata['wdED'];
				}

				if(isset($postdata['date_from']) && !empty($postdata['date_from'])){
					 $year = date('Y', strtotime($postdata['date_from']));
					$month = date('m', strtotime($postdata['date_from']));
					$this->web->updateWorkingDays($bid,$postdata['user_id'],$year,$month,$present,$halfDay,$weekOff,$holiday,$leaves,$shortLeave,$ed);
					
					$uname = $this->web->getNameByUserId($postdata['user_id']);
                                     //echo $uname[0]->name;	
							$actdata=array(
			                            'bid'=>$bid,
				                        'uid'=>$this->web->session->userdata('login_id'),
				                        'activity'=>"New working day updated for the employee of ".$uname[0]->name. " ",
				                        'date_time'=>time()
				
			                             );
			                  $data=$this->db->insert('activity',$actdata);	
				
						
					$data = array();
					$data['page']  		= 'salary/emplist';
            		$data['title'] 		= 'Manage - Salary';
            		$data['lMenu']  	= 'Sallery';

            		$data['salEmpList'] = $this->web->getSallaryReport($this->input->post());
            		$data['date_from'] = $this->input->post()['date_from'];
            		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
            		
            		
            		
            		redirect('Payroll/employeesNetSalary?getDate='.$postdata['date_from']);   
				}else{
				redirect('Payroll/employeesNetSalary?getDate='.$postdata['date_from']);  
				}
				
				
			}
		}else{
			redirect('user-login');
		}

	}


public function staffPayslip_o(){
	if(!empty($this->session->userdata('id'))){
		$id = $_POST['uid'];
		$date = $_POST['month'];
		$selectDate = $_POST['month'];
		$data['date']= $date;
	
		$salary_month = $this->db->select('DATE_FORMAT(date, "%Y-%m") as added_date')->from('salary')->where(['uid'=>$id])->where("DATE_FORMAT(salary.date,'%Y-%m') <=",$date)->order_by("date", "asc")->get()->result_array();
	      if($salary_month){
	          if(!in_array($date,array_column($salary_month, 'added_date'))){
	            if(current(array_column($salary_month, 'added_date')) < $date){
	              unset($date);
	              $date = end(array_column($salary_month, 'added_date'));
	            }
	          }
	      }
	
                       $salary = $this->db->get_where('salary',['uid'=>$id,"DATE_FORMAT(salary.date,'%Y-%m')"=>$date])->row();	  
		$data['page']  		= 'payroll/payslip';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
		$bcid= $this->web->getBusinessbyUser($id);
	 $bid =$bcid[0]->business_id;
//$bid = $this->session->userdata('empCompany');
	//	$data['buid'] =$bcid[0]->business_id;
		
	    // 
	    // $this->web->getUserCompanies($getLogin['login_id']);
	
		// $date = date("Y-m-d");

		$yearName  = date('Y', strtotime($selectDate));
		$monthName = date('m', strtotime($selectDate));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($selectDate)),date('Y',strtotime($selectDate))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days");

		$salaryData = $this->db->query("SELECT * FROM salary_report where uid='$id' and bid='$bid' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
		
		$data['paid_day'] = $salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+$salaryData['leaves']+$salaryData['ed'];
		$data['emp_details']  = $this->db->get_where('login',['id'=>$id])->row();
		
	   $data['pay_mode']=$salaryData['pay_mode'];
		//new line
		$data['emp_more_details']  = $this->db->get_where('staff_detail',['uid'=>$id])->row();
	//	$data['leave3'] = $this->db->query("SELECT * FROM leaves where uid='$id' and bid='$bid' AND YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName'")->row_array();
	  
		$data['open_leave']  = $this->db->get_where('open_leave',['uid'=>$id],['bid'=>$bid])->row();

	
		$data['report']  = $this->db->get_where('salary_report',['uid'=>$id])->row();
    	$data['cmp_details'] =	$this->web->getBusinessById($bid);
    	$data['earning'] =	$this->db->select("salary_basic.*,ctc_head.name")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Allowance')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
									
    	$data['deduction'] =	$this->db->select("salary_basic.*,ctc_head.name,salary_basic.header_value,salary_basic.header_type")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Deduction')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
    								
    	$data['salary_report'] =	$this->db->select("*")->from('salary_report')
    								->where('uid',$id)
    								->where("DATE_FORMAT(date_time,'%Y-%m')", $date)
    								->get()->row();

    	$data['ctc_salary'] 		=	$this->db->select("*")->from('salary')
    								->where('uid',$id)
    								->where("DATE_FORMAT(date,'%Y-%m')", $date)
    								->get()->row();
    //	$data['working_days']	= $data['salary_report'] ? $data['salary_report']->week_off : 0;

		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		
		$data['salary']  = $this->db->query("SELECT * FROM salary WHERE bid = '".$bid."' AND  uid = '".$id."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
		
		

		$data['allowance_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Allowance'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();

      	$data['deduction_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Deduction'])
						->where(['ctc_head.name !='=>'TDS'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();
        $data['earning_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $data['deduction_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
											  
											  
											  
											  
											  
       $data['leaven'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >",$start_time )
                                              ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
        $data['leaveold'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where('leaves.type!=',"other")
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date <",$start_time )
                                             // ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
          $data['leaveoldothern'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             ->where('leaves.type',"other")
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             
                                              ->where("from_date <",$start_time )
                                             
                                              ->get()
                                              ->row();                                    
                                                                                    
         
       // $data['usedleave']=		$end_time;
        
        $data['usedoldleave']=$data['leaveold'] ? $data['leaveold']->half_day :0; 
        $data['leaveoldother']=$data['leaveoldothern'] ? $data['leaveoldothern']->half_day :0; 
        
       // $data['total_leave'] =	$data['open_leave'] ? $data['open_leave']->cl+$data['open_leave']->el+$data['open_leave']->pl+$data['open_leave']->sl+$data['open_leave']->hl+$data['open_leave']->rh-$data['usedoldleave']:0;
       // $data['balanceleave']=$data['total_leave']- $data['usedleave'] ;
       
        
        $openleavedate=$data['open_leave'] ? $data['open_leave']->open_date:0;
        $openleavemonth=date('m', $openleavedate);
        $monthdiff=$monthName-$openleavemonth;
        $data['usedleave']=$data['leaven'] ? $data['leaven']->half_day :0;
        $data['entitleleave']=$data['open_leave'] ? $data['open_leave']->fixed_limit :0;
         $balanceleave=$data['entitleleave']?$data['entitleleave']:0;
         $data['balanced_leave']= $balanceleave* $monthdiff;
        $data['openleave']=$data['open_leave'] ? $data['open_leave']->other-$data['leaveoldother']- $data['usedoldleave']+ $data['balanced_leave'] :0 ;
        $advance = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $imi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
          $oldimi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')<" ,$selectDate)
                          ->get()
                          ->row();                 
        
        $loan = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[4])
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          //->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
        $advance_total = isset($advance->amount) ? $advance->amount : 0;
        $loan_amount = isset($loan->amount) ? $loan->amount : 0;
        $imi_amount = isset($imi->amount) ? $imi->amount : 0;
        $oldimi_amount = isset($oldimi->amount) ? $oldimi->amount : 0;
        
       // $data['advance'] = $advance_total+($loan_amount-$imi_amount);
       $data['advance'] = $advance_total+$loan_amount-$oldimi_amount;
        $data['advance_paid'] = $advance_total+ $imi_amount;
         $data['advance_balance'] = $loan_amount- $imi_amount-$oldimi_amount;
        $data['imi'] = $imi_amount;
        $data['loan'] = $loan_amount;
        $data['number_of_days'] = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($selectDate)),date('Y',strtotime($selectDate)));
            // print_r(round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$number_of_days)*$data['working_days'])); exit;
        
       // $data['oneDaySalary'] = $data['ctc_salary'] ? round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])*$data['paid_day']) : 0;
       
          $data['oneDaySalary'] = $data['salary'] ? round((($data['salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])*$data['paid_day']) : 0;
		$this->load->view('employee/payslip_report',$data);
	}
		else{
			redirect('user-login');
		}
	}






public function staffPayslip(){
	if(!empty($this->session->userdata('id'))){
		$id = $_POST['uid'];
		$date = $_POST['month'];
		$selectDate = $_POST['month'];
		$data['date']= $date;
	
		$salary_month = $this->db->select('DATE_FORMAT(date, "%Y-%m") as added_date')->from('salary')->where(['uid'=>$id])->where("DATE_FORMAT(salary.date,'%Y-%m') <=",$date)->order_by("date", "asc")->get()->result_array();
	      if($salary_month){
	          if(!in_array($date,array_column($salary_month, 'added_date'))){
	            if(current(array_column($salary_month, 'added_date')) < $date){
	              unset($date);
	              $date = end(array_column($salary_month, 'added_date'));
	            }
	          }
	      }
	
                       $salary = $this->db->get_where('salary',['uid'=>$id,"DATE_FORMAT(salary.date,'%Y-%m')"=>$date])->row();	  
		$data['page']  		= 'payroll/payslip';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
		$bcid= $this->web->getBusinessbyUser($id);
	 $bid =$bcid[0]->business_id;
//$bid = $this->session->userdata('empCompany');
	//	$data['buid'] =$bcid[0]->business_id;
		
	    // 
	    // $this->web->getUserCompanies($getLogin['login_id']);
	
		// $date = date("Y-m-d");

		$yearName  = date('Y', strtotime($selectDate));
		$monthName = date('m', strtotime($selectDate));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($selectDate)),date('Y',strtotime($selectDate))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days");

		$salaryData = $this->db->query("SELECT * FROM salary_report where uid='$id' and bid='$bid' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
		
		$data['paid_day'] = $salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+$salaryData['leaves']+$salaryData['ed'];
		$data['emp_details']  = $this->db->get_where('login',['id'=>$id])->row();
		
	   $data['pay_mode']=$salaryData['pay_mode'];
		//new line
		$data['emp_more_details']  = $this->db->get_where('staff_detail',['uid'=>$id])->row();
	//	$data['leave3'] = $this->db->query("SELECT * FROM leaves where uid='$id' and bid='$bid' AND YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName'")->row_array();
	  
		$data['open_leave']  = $this->db->get_where('open_leave',['uid'=>$id],['bid'=>$bid])->row();

	
		$data['report']  = $this->db->get_where('salary_report',['uid'=>$id])->row();
    	$data['cmp_details'] =	$this->web->getBusinessById($bid);
    	$data['earning'] =	$this->db->select("salary_basic.*,ctc_head.name")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Allowance')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
									
    	$data['deduction'] =	$this->db->select("salary_basic.*,ctc_head.name,salary_basic.header_value,salary_basic.header_type")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Deduction')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
    								
    	$data['salary_report'] =	$this->db->select("*")->from('salary_report')
    								->where('uid',$id)
    								->where("DATE_FORMAT(date_time,'%Y-%m')", $date)
    								->get()->row();

    	$data['ctc_salary'] 		=	$this->db->select("*")->from('salary')
    								->where('uid',$id)
    								->where("DATE_FORMAT(date,'%Y-%m')", $date)
    								->get()->row();
    //	$data['working_days']	= $data['salary_report'] ? $data['salary_report']->week_off : 0;

		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		
		$data['salary']  = $this->db->query("SELECT * FROM salary WHERE bid = '".$bid."' AND  uid = '".$id."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
		
		

		$data['allowance_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Allowance'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();

      	$data['deduction_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Deduction'])
						->where(['ctc_head.name !='=>'TDS'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();
        $data['earning_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $data['deduction_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
		$pf = $this->db->select('salary_basic.amount,salary_basic.header_type,salary_basic.header_value')
											  ->from('salary_basic')
											  ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
											  ->join('salary','salary.id=salary_basic.sid','left')
											  ->where(['salary.uid'=>$id])
											  ->where(['ctc_head.type'=>'Deduction'])
											  ->where(['ctc_head.name'=>'PF'])
											  ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
											  ->get()
											  ->row();
		$esi = $this->db->select('salary_basic.amount,salary_basic.header_type,salary_basic.header_value')
											  ->from('salary_basic')
											  ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
											  ->join('salary','salary.id=salary_basic.sid','left')
											  ->where(['salary.uid'=>$id])
											  ->where(['ctc_head.type'=>'Deduction'])
											  ->where(['ctc_head.name'=>'ESI'])
											  ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
											  ->get()
											  ->row();
		$tds = $this->db->select('salary_basic.amount,salary_basic.header_type,salary_basic.header_value')
											  ->from('salary_basic')
											  ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
											  ->join('salary','salary.id=salary_basic.sid','left')
											  ->where(['salary.uid'=>$id])
											  ->where(['ctc_head.type'=>'Deduction'])
											  ->where(['ctc_head.name'=>'TDS'])
											  ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
											  ->get()
											  ->row();
											  									  
											  
											  
											  
											  
       $data['leaven'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >",$start_time )
                                              ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
        $data['leaveold'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where('leaves.type!=',"other")
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date <",$start_time )
                                             // ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
          $data['leaveoldothern'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             ->where('leaves.type',"other")
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             
                                              ->where("from_date <",$start_time )
                                             
                                              ->get()
                                              ->row();                                    
                                                                                    
         
       // $data['usedleave']=		$end_time;
        
        $data['usedoldleave']=$data['leaveold'] ? $data['leaveold']->half_day :0; 
        $data['leaveoldother']=$data['leaveoldothern'] ? $data['leaveoldothern']->half_day :0; 
        
       // $data['total_leave'] =	$data['open_leave'] ? $data['open_leave']->cl+$data['open_leave']->el+$data['open_leave']->pl+$data['open_leave']->sl+$data['open_leave']->hl+$data['open_leave']->rh-$data['usedoldleave']:0;
       // $data['balanceleave']=$data['total_leave']- $data['usedleave'] ;
       
        
        $openleavedate=$data['open_leave'] ? $data['open_leave']->open_date:0;
        $openleavemonth=date('m', $openleavedate);
        $monthdiff=$monthName-$openleavemonth;
        $data['usedleave']=$data['leaven'] ? $data['leaven']->half_day :0;
        $data['entitleleave']=$data['open_leave'] ? $data['open_leave']->fixed_limit :0;
         $balanceleave=$data['entitleleave']?$data['entitleleave']:0;
         $data['balanced_leave']= $balanceleave* $monthdiff;
        $data['openleave']=$data['open_leave'] ? $data['open_leave']->other-$data['leaveoldother']- $data['usedoldleave']+ $data['balanced_leave'] :0 ;
        $advance = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $imi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
          $oldimi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')<" ,$selectDate)
                          ->get()
                          ->row();                 
        
        $loan = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[4])
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          //->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
        $advance_total = isset($advance->amount) ? $advance->amount : 0;
        $loan_amount = isset($loan->amount) ? $loan->amount : 0;
        $imi_amount = isset($imi->amount) ? $imi->amount : 0;
        $oldimi_amount = isset($oldimi->amount) ? $oldimi->amount : 0;
        
       // $data['advance'] = $advance_total+($loan_amount-$imi_amount);
       $data['advance'] = $advance_total+$loan_amount-$oldimi_amount;
        $data['advance_paid'] = $advance_total+ $imi_amount;
         $data['advance_balance'] = $loan_amount- $imi_amount-$oldimi_amount;
        $data['imi'] = $imi_amount;
        $data['loan'] = $loan_amount;
        $data['number_of_days'] = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($selectDate)),date('Y',strtotime($selectDate)));
           
           
         $number_of_days= cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($selectDate)),date('Y',strtotime($selectDate)));
						  $deduction_t=$data['deduction_total'] ? $data['deduction_total']->total :0;
						  $allowance_t=$data['allowance_total'] ? $data['allowance_total']->total :0;
						  $earning_total=$data['earning_master'] ? $data['earning_master']->amount :0;
						  $deduction_total=$data['deduction_master'] ? $data['deduction_master']->amount :0;
						  $nwd=$data['paid_day'] ? $data['paid_day']:0;
						  $total_salary = $salary ? round((($salary->basic_value+$deduction_t+$allowance_t)/$number_of_days)*$nwd) : 0;

						  $total_pf = isset($pf->amount) ?  ($pf->header_type=="Manual" ? $pf->amount : round((((($salary->basic_value)/$number_of_days)*$nwd)*$pf->header_value))/100) : 0;
						  $total_esi = isset($esi->amount) ?  ($esi->header_type=="Manual" ? $esi->amount : round((((($salary->basic_value)/$number_of_days)*$nwd)*$esi->header_value))/100) : 0;
						  $total_tds = isset($tds->amount) ?  ($tds->header_type=="Manual" ? $tds->amount : round((((($salary->basic_value)/$number_of_days)*$nwd)*$tds->header_value))/100) : 0;
						// $data['netPayables'] = ($total_salary+$earning_total)-($total_pf+$total_esi+$total_tds+$deduction_total);
                        $data['net_paid_day'] =($total_salary+$earning_total)-($total_pf+$total_esi+$total_tds+$deduction_total);
              $data['oneDaySalary'] = $data['salary'] ? round((($data['salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])*$data['paid_day']) : 0;
		$this->load->view('employee/payslip_report',$data);
	}
		else{
			redirect('user-login');
		}
	}





public function finalPay()
	{
		$id = $_GET['id'];
		if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $bid = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
	//	$bid= $this->web->session->userdata('login_id');
		$data['emp_name'] = $this->db->get_where('user_request',['user_id'=>$id],['business_id'=>$bid])->row();
		$selectDate=date("Y-m",$data['emp_name']->left_date);
		$date = $selectDate;
		//$selectDate = $_GET['selectDate'];
		
		$data['date']= $date;
	
		$salary_month = $this->db->select('DATE_FORMAT(date, "%Y-%m") as added_date')->from('salary')->where(['uid'=>$id])->where("DATE_FORMAT(salary.date,'%Y-%m') <=",$date)->order_by("date", "asc")->get()->result_array();
	      if($salary_month){
	          if(!in_array($date,array_column($salary_month, 'added_date'))){
	            if(current(array_column($salary_month, 'added_date')) < $date){
	              unset($date);
	              $date = end(array_column($salary_month, 'added_date'));
	            }
	          }
	      }
		$data['page']  		= 'payroll/payslip';
		$data['title'] 		= 'Manage - Salary';
		$data['lMenu']  	= 'Sallery';
		
		// $date = date("Y-m-d");

		$yearName  = date('Y', strtotime($selectDate));
		$monthName = date('m', strtotime($selectDate));
		 $d = (cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($date)),date('Y',strtotime($date))))-1;
		$start_time = strtotime(date("d-m-Y 00:00:00",strtotime($yearName."-".$monthName."-01")));
		$end_time = strtotime(date("d-m-Y 23:59:59",strtotime($yearName."-".$monthName."-01"))." +".$d." days");

		$salaryData = $this->db->query("SELECT * FROM salary_report where uid='$id' and bid='$bid' AND  YEAR(date_time)='$yearName' AND MONTH(date_time)='$monthName'")->row_array();
		
		$data['paid_day'] = $salaryData['present']+($salaryData['half_day']/2)+$salaryData['week_off']+$salaryData['holiday']+$salaryData['leaves']+$salaryData['ed'];
		$data['pay_mode']=$salaryData['pay_mode'];
		$data['emp_details']  = $this->db->get_where('login',['id'=>$id])->row();
		
		//new line
		$data['emp_more_details']  = $this->db->get_where('staff_detail',['uid'=>$id])->row();
	//	$data['leave3'] = $this->db->query("SELECT * FROM leaves where uid='$id' and bid='$bid' AND YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName'")->row_array();
	  
		$data['open_leave']  = $this->db->get_where('open_leave',['uid'=>$id],['bid'=>$bid])->row();
$data['total_leave'] =	$data['open_leave'] ? $data['open_leave']->cl+$data['open_leave']->el+$data['open_leave']->pl+$data['open_leave']->sl+$data['open_leave']->hl+$data['open_leave']->rh :0;

$open_date=$data['open_leave'] ? $data['open_leave']->open_date:0;
$openleavemonth=date('m', $open_date);
$monthdiff=$monthName-$openleavemonth+1;

$data['fixed_limit']=$data['open_leave'] ? $data['open_leave']->fixed_limit:0;
$opening_leave= ($data['fixed_limit']* $monthdiff);
	
		$data['report']  = $this->db->get_where('salary_report',['uid'=>$id])->row();
    	$data['cmp_details'] =	$this->web->getBusinessById($bid);
    	$data['earning'] =	$this->db->select("salary_basic.*,ctc_head.name")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Allowance')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
    	$data['deduction'] =	$this->db->select("salary_basic.*,ctc_head.name,salary_basic.header_value,salary_basic.header_type")->from('salary')
    								->join('salary_basic','salary_basic.sid=salary.id')
    								->join('ctc_head','ctc_head.id=salary_basic.header_id')
    								->where('uid',$id)
    								->where('type','Deduction')
    								->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
    								->get()->result_array();
    								
    	$data['salary_report'] =	$this->db->select("*")->from('salary_report')
    								->where('uid',$id)
    								->where("DATE_FORMAT(date_time,'%Y-%m')", $date)
    								->get()->row();

    	$data['ctc_salary'] 		=	$this->db->select("*")->from('salary')
    								->where('uid',$id)
    								// ->where("DATE_FORMAT(date,'%Y-%m')", $date)
    								->get()->row();
    	$data['working_days']	= $data['salary_report'] ? $data['salary_report']->week_off : 0;

		$data['payrollList'] 	= $this->web->getData('payroll_master', array('status' => 1), '', 'ASC');
		$data['salary']  = $this->db->query("SELECT * FROM salary WHERE bid = '".$bid."' AND  uid = '".$id."' AND  YEAR(date) = '".date('Y',strtotime($date))."' AND MONTH(date) = '".date('m',strtotime($date))."' ")->row_array();
		
		

		$data['allowance_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Allowance'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();

      	$data['deduction_total'] = $this->db->select('SUM(salary_basic.amount) as total')
                        ->from('salary_basic')
                        ->join('ctc_head','ctc_head.id=salary_basic.header_id','left')
                        ->join('salary','salary.id=salary_basic.sid','left')
                        ->where(['salary.uid'=>$id])
                        ->where(['ctc_head.type'=>'Deduction'])
						->where(['ctc_head.name !='=>'TDS'])
                        ->where("DATE_FORMAT(salary.date,'%Y-%m')", $date)
                        ->get()
                        ->row();
        $data['earning_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[1,3,5,6])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(payroll_history.pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $data['deduction_master'] = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[7,8])
                                              ->where("user_id",$id)
                                              ->where('payroll_history.status',1)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
         
         $data['leaven'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date >",$start_time )
                                              ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
        $data['leaveold'] = $this->db->select_sum('leaves.half_day')
                                              ->from('leaves')
                                             // ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                             // ->where_in('payroll_master_id',[2])
                                              //->where("payroll_id",0)
                                              ->where('leaves.status',1)
                                              ->where("uid",$id)
                                              ->where("bid",$bid)
                                             // ->where("DATE_FORMAT(from_date,%Y-%m)",$monthName)
                                              ->where("from_date <",$start_time )
                                             // ->where("from_date <",$end_time)
                                             // ->where("2024",$yearName)
                                             // ->where("date('m', strtotime(from_date))",$monthName)
                                             //->where(YEAR(from_date)='$yearName' AND MONTH(from_date)='$monthName')
                                              ->get()
                                              ->row();
                                                                                    
         
       // $data['usedleave']=		$end_time;
        $data['usedleave']=$data['leaven'] ? $data['leaven']->half_day :0; 
        $data['usedoldleave']=$data['leaveold'] ? $data['leaveold']->half_day :0; 
        $data['balanceleave']=$opening_leave- $data['usedleave']-$data['usedoldleave'] ;
        $advance = $this->db->select_sum('payroll_history.amount')
                                              ->from('payroll_history')
                                              ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                                              ->where_in('payroll_master_id',[2])
                                              ->where("payroll_id",0)
                                              ->where('payroll_history.status',1)
                                              ->where("user_id",$id)
                                              ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                                              ->get()
                                              ->row();
        $imi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
          $oldimi = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[2])
                          ->where("payroll_id !=",0)
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          ->where("DATE_FORMAT(pay_date,'%Y-%m')<" ,$selectDate)
                          ->get()
                          ->row();                 
        
        $loan = $this->db->select_sum('payroll_history.amount')
                          ->from('payroll_history')
                          ->join('payroll_master','payroll_master.id=payroll_history.payroll_master_id')
                          ->where_in('payroll_master_id',[4])
                          ->where('payroll_history.status',1)
                          ->where("user_id",$id)
                          //->where("DATE_FORMAT(pay_date,'%Y-%m')", $selectDate)
                          ->get()
                          ->row();
        $advance_total = isset($advance->amount) ? $advance->amount : 0;
        $loan_amount = isset($loan->amount) ? $loan->amount : 0;
        $imi_amount = isset($imi->amount) ? $imi->amount : 0;
        $oldimi_amount = isset($oldimi->amount) ? $oldimi->amount : 0;
        
       // $data['advance'] = $advance_total+($loan_amount-$imi_amount);
       $data['advance'] = $advance_total+$loan_amount-$oldimi_amount;
        $data['advance_paid'] = $advance_total+ $imi_amount;
         $data['advance_balance'] = $loan_amount- $imi_amount-$oldimi_amount;
        $data['imi'] = $imi_amount;
        $data['loan'] = $loan_amount;
        $data['number_of_days'] = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($date)),date('Y',strtotime($date)));
            // print_r(round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$number_of_days)*$data['working_days'])); exit;
        $data['ctc_amount'] = $data['ctc_salary'] ? $data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total: 0;
        $data['oneDaySalary'] = $data['ctc_salary'] ? round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])) : 0;
		 $data['FinalSalary'] = $data['ctc_salary'] ? round((($data['ctc_salary']->basic_value+$data['deduction_total']->total+$data['allowance_total']->total)/$data['number_of_days'])*$data['paid_day']) : 0;
		
		$this->load->view('attendance/finalPayment',$data);
	}

public function changeLeaveFmDate2(){
		if (!empty($this->session->userdata('id'))) {
			$id = $this->input->post("id");
		//	$id=3765;
			$from_date = $this->input->post("from_date");
			$info = $this->web->updateFromLDate2($id,$from_date);
		}else{
			redirect('user-login');
		}
	}	







	
}


?>