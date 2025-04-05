<?php
if(isset($_REQUEST['datatype'])=="businesslist"){
  
  $bid = $this->input->post('id');
      $department=$this->web->getDepartByBusiness($bid);
      ?>

      <option value="" disabled selected>Select</option>  
                <?php
                  if(!empty($department)){
                    foreach($department as $department):
                      $dname = $this->web->getDepartById($department->depid);
                        foreach($dname as $dname){
                          echo "<option value=".$department->depid .">".$dname->department." (".$dname->remark.")</option>";
                        }
                    endforeach;
                  }

 }    ?>

<?php
if(isset($_REQUEST['datatypes'])=="sdepartByDepart") {
  $did = $this->input->post('id');
  $result = $this->web->getSubDepartByDepartId($did);  print_r($result);
?>
      <option value="" disabled selected>Select</option>  
                <?php
                  if(!empty($result)){
                    foreach($result as $result):
                          echo "<option value=".$result->id .">".$result->depart_name." </option>";
                    endforeach;
                  }
  }
?>
<?php
if (isset($_REQUEST['add_d_data'])=="add_depart") {
  $id = $this->input->post('data');
      $value = $this->web->getDepartById($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editdepartment">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->department; ?>" required>
        </div>
        <div class="from-group">
          <label for="pre">Prefix</label>
          <input type="text" name="prefix" id="pre" class="form-control" value="<?php echo $value['0']->Dep_code; ?>" required>
        </div>
        <div class="from-group">
          <label for="rem">Remark</label>
          <input type="text" name="remark" id="rem" class="form-control" value="<?php echo $value['0']->remark; ?>" required>
        </div>
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editdepartment",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>


<?php
if (isset($_REQUEST['add_bd_data'])=="add_bdepart") {
  $id = $this->input->post('data');
      $value = $this->web->getBusinessDepByUserId($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editbdepartment">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editbdepartment",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>










<?php
if (isset($_REQUEST['edit_b_section'])=="edit_section") {
  $id = $this->input->post('data');
      $value = $this->web->getBusinessSectionById($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/edit_bsection">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name"  class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
         <div class="from-group">
          <label for="name">Wifi Strength</label>
          <input type="text" name="strength"  class="form-control" value="<?php echo $value['0']->strength; ?>" required>
        </div>
         <div class="from-group">
          <label for="name">Location Radius</label>
          <input type="text" name="radius"  class="form-control" value="<?php echo $value['0']->radius; ?>" required>
        </div>
        <br>
        <input type="hidden" name="id"  value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/edit_bsection",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>








<?php
if (isset($_REQUEST['add_eroll'])=="add_roll") {
    $id = $this->input->post('id');
	 $bid = $this->input->post('bid');
      $value = $this->web->getDepartById($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editroll">

    <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">1)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left"> Employee List </p>
                            </div>
                     <?php $role=$this->web->checkEmpRoleCmp($id,$bid);
							$rl=$role[0]->add_emp; 
							?>
                            <div class="col-2">
                              <input id="employee_list" name="employee_list" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->employee_list=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            
                            <div class="col-1">
                              <p class="text-sm-left">2)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Add Employee</p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="add_emp" name="add_emp" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->add_emp=="1"){echo "checked";} ?>>
                            </div>
                        </div>
                        
                        
                        
                        <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">3)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Manual Attendance </p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="manual_att" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->manual_att=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            
                            <div class="col-1">
                              <p class="text-sm-left">4)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Assign</p>
                            </div>

                            <div class="col-2">
                              <input id="" name="assign" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->assign=="1"){echo "checked";} ?>> 
                            
                            </div>
                        
                            <div class="col-2" hidden>
                              <input id="" name="daily_report" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->daily_report=="1"){echo "checked";} ?>>
                            </div>
                        </div>
                        
                        
                     
                     <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">5)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Attendance Report</p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="other_report" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->other_report=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            
                            <div class="col-1">
                              <p class="text-sm-left">6)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Leave</p>
                            </div>
                            <div class="col-2" >
                              <input id="" name="leave_manage" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->leave_manage=="1"){echo "checked";} ?>>
                            </div>
                            <div class="col-2" hidden>
                              <input id="" name="att_setting" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->att_setting=="1"){echo "checked";} ?>>
                            </div>
                        </div>
                        
                        
                  <div class="row">
                            <div class="col-1">
                              <p class="text-sm-left">7)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Pending Attendance </p>
                            </div>
                         
                            
                            <div class="col-2">
                              <input id="" name="pending_att" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->pending_att=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            
                            <div class="col-1" hidden>
                              <p class="text-sm-left">8)</p>
                            </div>
                            <div class="col-3" hidden>
                              <p class="text-sm-left">Salary </p>
                            </div>
                         
                            <div class="col-2" hidden>
                              <input id="" name="salary" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->salary=="1"){echo "checked";} ?>>
                            </div>
                        </div>
                        
                        
                        
                        <div class="row" hidden>
                            <div class="col-1">
                              <p class="text-sm-left">9)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Attendance Option  </p>
                            </div>
                         
                            <div class="col-2">
                              <input id="" name="att_option" type="checkbox" value="1" data-toggle="toggle" data-onstyle="success" data-size="sm" <?php if($role[0]->att_option=="1"){echo "checked";} ?>> 
                            
                            </div>
                            
                            
                            <div class="col-1">
                              <p class="text-sm-left">10)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Leave</p>
                            </div>
                         <!--   <div class="col-2">
                              <p class="text-sm-left"></p>
                            </div>-->
                            
                        </div>
                        
                        
                     
                     <div class="row">
                            <!-- <div class="col-1">
                              <p class="text-sm-left">11)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Assign</p>
                            </div>
                          -->
                            
                            
                            
                            <!-- <div class="col-1">
                              <p class="text-sm-left">12)</p>
                            </div>
                            <div class="col-3">
                              <p class="text-sm-left">Manager Roll </p>
                            </div>
                         
                            <div class="col-2" >
                              <input id="" name="manager_role" type="checkbox" data-toggle="toggle"  value="1" data-onstyle="success" data-size="sm" 
                              <?php if($role[0]->manager_role=="1"){echo "checked";} ?>>
                            </div> -->
                        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="empType">Type</label>
              <select class="form-control" id="empType" name="empType">
                <option value="1" <?php if($role[0]->type=='1'){ echo 'selected';}?>>Admin</option>
                <option value="2" <?php if($role[0]->type=='2'){ echo 'selected';}?>>Manager</option>
                <option value="3" <?php if($role[0]->type=='3'){ echo 'selected';}?>>HR</option>
                <option value="4" <?php if($role[0]->type=='4'){ echo 'selected';}?>>Accounts</option>
                 <option value="0" <?php if($role[0]->type=='0'){ echo 'selected';}?>>Employee</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 form-group">
            <label for="department">Department</label>
            <select name="department" class="form-control">
              <option value=''>All Department</option>
            <?php
            $department = $this->web->getBusinessDepByBusinessId($bid);
            if(!empty($department)){
              foreach($department as $dep):
                $depSelected = '';
                if($dep->id==$role[0]->department){
                  $depSelected = 'selected';
                }
                echo "<option value=".$dep->id ." $depSelected>".$dep->name."</option>";
              endforeach;
            }
            ?></select>
          </div>
          <div class="col-sm-6 form-group">
            <label for="section">Section</label>
            <select name="section" class="form-control">
              <option value=''>All Section</option>
            <?php
            $section = $this->web->getBusinessSecByBId($bid);
            if(!empty($section)){
              foreach($section as $sec):
                $secSelected = '';
                if($sec->type==$role[0]->section){
                  $secSelected = 'selected';
                }
                echo "<option value=".$sec->type ." $secSelected>".$sec->name."</option>";
              endforeach;
            }
            ?></select>
          </div>
        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                         
                        
        <div class="row">
          <div class="col-7">
          <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
          <input type="hidden" name="bid" id="id" value="<?php echo $bid; ?>">
          </div>
      <div class="col-5"  align="right">
        
        <button type="button" class="btn btn-success"  id="bt_form">Save Changes</button>
 </div>
</div>
      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editroll",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>
<?php
if (isset($_REQUEST['add_bl_data'])=="add_block") {
  $id = $this->input->post('data');
      $value = $this->web->getblockbyid($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editdepartment">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
       
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editblock",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>



<?php
if (isset($_REQUEST['add_rm_data'])=="add_room_types") {
  $id = $this->input->post('data');
     $value = $this->web->getRoomtypebyid($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editroomtype">
        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
       
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editroomtype",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>






<?php
if (isset($_REQUEST['add_detail'])=="att_detail") {
  $id = $this->input->post('data');
 // echo $id;
     // $value = $this->web->getDepartById($id);
	 
	  $act="SELECT count(id) as actemp FROM user_request WHERE business_id='$id'and user_status=1 and left_date ='' ";
                      $aemp= $this->db->query($act)->result();
                    $active= $aemp[0]->actemp;  
                       echo "Total Employee:-  ".$active."<br>";
	 
	 
	 

     $att_all="SELECT count(id) as attend_all FROM attendance WHERE bussiness_id='$id'and status=1 ";
                      $atten= $this->db->query($att_all)->result();
                    $attendance_all= $atten[0]->attend_all;  
                       echo "Total Attendance:-   ".$attendance_all;
					   $cudate = date("Y-m-d");
       	    //$cudate= '2022-07-20';
				$cdate=strtotime($cudate);
				
				$start_time= strtotime(date("Y-m-d 00:00:00",$cdate));
                $end_time= strtotime(date("Y-m-d 23:59:59",$cdate));
                       
                      $att_to="SELECT count(id) as attend_to FROM attendance WHERE bussiness_id='$id'and status=1 and io_time BETWEEN $start_time and $end_time ";
                      $atten_to= $this->db->query($att_to)->result();
                    $attendance_today= $atten_to[0]->attend_to;  
                       echo "/".$attendance_today."<br>";  

 		$wifi = $this->web->getwifiByid($id);
						 
					 	   
					   echo "Wifi Detail:-  ".$wifi[0]->ssid;
					   echo "(".$wifi[0]->strength.")";
					   echo $wifi[1]->ssid;
					   echo "(".$wifi[1]->strength.")";
					   echo $wifi[2]->ssid;
					   echo "(".$wifi[2]->strength.")<br>";
					   
					   
					   
					   echo "Location Detail:-   ".$wifi[0]->location;
					    echo "(".$wifi[0]->radius.")";
						 echo $wifi[1]->location;
					    echo "(".$wifi[1]->radius.")";
						 echo $wifi[2]->location;
					    echo "(".$wifi[2]->radius.")";
		
		
		
		
		
					  
						  	 $refer=$this->web->getNameByUserId($id);
						  
						 $unames = $this->web->getNameByAssignId($refer[0]->reference);
					   // $linked_id=$unames[0]->id;
					  echo"<br> Refrence:- ".$unames[0]->name;
					  //$buid=$pre->id;
					 $licence = $this->web->getactivelicence($id);
					//echo $id;
				   $assign_id=$licence[0]->assign_id;
					 $assigned_by = $this->web->getNameByAssignId($assign_id);
					  echo "<br>Licence by:-".$assigned_by[0]->name;
					 


}
?>






<?php
if (isset($_REQUEST['user_detail'])=="use_detail") {
  $id = $this->input->post('data');
 // echo $id;
     // $value = $this->web->getDepartById($id);
	 			  $busi=$this->web->getBusinessbyUser($id);
	 			  $buid=0;
	 			  if(!empty($busi)){
		             $buid=$busi[0]->business_id; }
					 $uname = $this->web->getNameByUserId($buid);
					 echo"company Name ".$uname[0]->name;
					  echo"<br>Join Date ".date("d-m-Y",$busi[0]->doj);
			 
	 
	 
	 

     $att_all="SELECT count(id) as attend_all FROM attendance WHERE user_id='$id'and status=1 ";
                      $atten= $this->db->query($att_all)->result();
                    $attendance_all= $atten[0]->attend_all;  
                       echo "<br> Total Attendance:-   ".$attendance_all;
					   $cudate = date("Y-m-d");
       	    //$cudate= '2022-07-20';
				$cdate=strtotime($cudate);
				
				$start_time= strtotime(date("Y-m-d 00:00:00",$cdate));
                $end_time= strtotime(date("Y-m-d 23:59:59",$cdate));
                       
                      $att_to="SELECT count(id) as attend_to FROM attendance WHERE user_id='$id'and status=1 and io_time BETWEEN $start_time and $end_time ";
                      $atten_to= $this->db->query($att_to)->result();
                    $attendance_today= $atten_to[0]->attend_to;  
                       echo "/".$attendance_today."<br>";  

 		
						 
					 


}
?>


<?php
if (isset($_REQUEST['ref'])=="ref_detail") {
  $id = $this->input->post('data');
 	  $refer=$this->web->getNameByUserId($id);
	 $unames = $this->web->getIdByMb($refer[0]->reference);
					  
			 
			 
	 ?>
	 
	 
<dev style="color:red; font-size: 2rem;" id="msg"></dev>

      <form method="post" id="from" action="User/editrefer">

        <div class="form-group">
          <label for="name">Reference</label>
          
          <input type="text" name="ref" id="ref" class="form-control" value="<?php echo $unames['0']->name."(".$unames['0']->mobile.")" ;?>" required>
        </div>
        <input type="hidden" name="id" id="id" value="<?php echo $refer['0']->id; ?>">
      <button type="button" class="btn btn-success" id="bt_form">Edit Reference</button>
      
      </form>
      
    

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editreference",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>
						 
					 

<?php 
}
?>



<?php
if (isset($_REQUEST['lic'])=="lic_detail") {
  $id = $this->input->post('data');
 	
	
					  $licence = $this->web->getactivelicence($id);
					//echo $id;
				     $assign_id=$licence[0]->assign_id;
					 $assigned_by = $this->web->getNameByAssignId($assign_id);
						 
			 
	 ?>
	 
	 
<dev style="color:red; font-size: 2rem;" id="msg"></dev>

      <form method="post" id="from" action="User/editlic">

        <div class="form-group">
          <label for="name">Licence</label>
          
          <input type="text" name="lic" id="lic" class="form-control" value="<?php echo $assigned_by['0']->name."(".$assigned_by['0']->mobile.")" ;?>" required>
        </div>
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
      <button type="button" class="btn btn-success" id="bt_form">Edit Licence</button>
      
      </form>
      
    

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editlicence",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>
						 
					 

<?php 
}
?>





<?php if(isset($_REQUEST['tSDept']) == "getTokenBySdept"){
  $did = $this->input->post("did");
  $bid = $this->input->post("bid");
  $sdid = $this->input->post("sdid");
  if($sdid != "allTokens"){
    $result = $this->web->getTokenBySubDept($sdid,$bid);
    }else{
    $result = $this->web->getTokenInfo($did,$bid);
    }
    //print_r($result);
        $count=1;
        $countid = $this->session->userdata('login_id');
        //print_r($result);
?>
    <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Department</th>
                    <th>Sub-Depart</th>
                    <th>User Name</th>
                    <th>Mobile</th>
                    <th>Token No.</th>
                    <th>Query</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>

<?php
        foreach($result as $val){
        ?>
        
        <tr>
          <td><?php echo $count++?></td>
          <td>
            <?php 
                  $dname = $this->web->getDepartById($val->Dept_id);
                  echo $dname[0]->department; ?>
            </td>
          <td>
            <?php 
                  $sdname = $this->web->getSubDepartById($val->Sub_deptid);
                  echo $sdname['sdname']; ?>
            </td>
          <td>
            <?php 
                  $uname=$this->web->getBusinessById($val->userid);
                  echo $uname['name']; ?>        
            </td>
          <td><?php echo $uname['mobile']?></td>
          <td><?php echo $dname[0]->Dep_code.'_'.$val->token; ?></td>
          <td><?php echo $val->Query?></td>
          <td><?php echo $val->date?></td>
          
          <td id="stat<?php echo $val->id; ?>"  data-order="
                  <?php if($val->status == 0){echo $num='2';}elseif($val->status == '1'){echo $num='1';}else{echo $num='3';} ?>         ">
            <?php
                if ($val->status == "0") {
            ?>    
              <button class="btn btn-warning" id="stat" onclick="active('<?php echo $val->id; ?>','<?php echo $val->userid; ?>','<?php echo $countid; ?>','<?php echo $bid; ?>')">Waiting</button>
            <?php
                }elseif($val->status == "1"){
            ?>
              <button class="btn btn-success" id="stat" onclick="Close('<?php echo $val->id; ?>','<?php echo $val->userid; ?>','<?php echo $countid; ?>','<?php echo $bid; ?>')">Calling</button>
            <?php
              }elseif($val->status == "2"){
            ?>
              <button class="btn btn-primary" id="stat" >Done</button>
            <?php
              }
            ?>
          </td>
        
        </tr>
        <?php 
          }
        ?>
      </tbody>
      <tfoot>
            <tr>
              <td><button class="btn btn-success" onclick="callNext('<?php echo $bid?>')">NEXT</button></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
        </tfoot>
      </table>
<?php
  
  }

?>




<?php
if (isset($_REQUEST['add_bio_data'])=="add_device") {
  $id = $this->input->post('data');
      $value = $this->web->getdevicebyid($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editdevice">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
        <div class="from-group">
          <label for="pre">Serial No</label>
          <input type="text" name="deviceid" id="pre" class="form-control" value="<?php echo $value['0']->deviceid; ?>" required>
        </div>
        <div class="from-group">
          <label for="rem">Mode</label>
         
          <select name="mode" class="form-control">
              <?php $mode= $value[0]->mode; ?>
                                     <option value="<?php echo $mode; ?>">
                                        <?php  if($mode==0){
                                          echo "Attendance";
                                          }elseif($mode==1){
                                             echo "IN";
                                             } elseif($mode==2){
                                              echo "Out";
                                          } else{ echo "Access Control" ;
                                           }  ?>    
                                         </option>
                                    <option value="0">Attendance</option>
                                    <option value="1">In</option>
                                      <option value="2">Out</option>
                                        <option value="3">Access</option>
                                </select>
          
        </div>
        <div class="from-group">
          <label for="rem">Model</label>
        
         <select name="model" class="form-control">
                                 
                                <?php  $model=$value[0]->model; 
                                ?>
                                <option value="<?php echo $model; ?>"> 
                                 <?php  
                        if($model==0){
                           echo MidApp;
                        }elseif($model==1){
                           echo Syrotech;
                        }
                         elseif($model==2){
                           echo Secureye;
                        } 
                        else{
                           echo Mantra;
                        }
                        
                        ?>
                          </option>       
                                    <option value="0">MidApp</option>
                                    <option value="1">Syrotech</option>
                                      <option value="2">Secureye</option>
                                        <option value="3">Mantra</option>
                                </select>
          
        </div>
        
        
        
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editdevice",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>
  
 

<?php
}
?>



<?php
if (isset($_REQUEST['edit_left'])=="edit_left") {
  $id = $this->input->post('data');
  $bid=$this->web->session->userdata('login_id');
 	//  $refer=$this->web->getNameByUserId($id);
	// $unames = $this->web->getIdByMb($refer[0]->reference);
					  
			 
			 
	 ?>
	 
	 
<dev style="color:red; font-size: 2rem;" id="msg"></dev>

      <form method="post" id="from" action="User/left_emp">

        <div class="form-group">
          <label for="name">Left Date</label>
          
          <input type="date" name="dol" id="dol" class="form-control"  required>
        </div>
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
          <input type="hidden" name="bid" id="bid" value="<?php echo $bid; ?>">
      <button type="button" class="btn btn-success" id="bt_form">Left Employee</button>
      
      </form>
      
    

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/left_emp",
        data: fdata,
      success: function(res){
      $('#msg').html('Employee Left');
    }
    });
  });    
  </script>
						 
					 

<?php 
}
?>


<?php
if (isset($_REQUEST['edit_emproll'])=="edit_emproll") {
   $id = $this->input->post('data');
   if($this->session->userdata()['type']=='P'){
      // $busi=$this->web->getBusinessbyUser($this->web->session->userdata('login_id'));
      // $id=$busi[0]->business_id;
      $bid = $this->session->userdata('empCompany');
     // $role=$this->web->getRollbyid($this->web->session->userdata('login_id'),$id);
  
    } else {
      $bid=$this->web->session->userdata('login_id');
    }
 // $bid=$this->web->session->userdata('login_id');
 	//  $refer=$this->web->getNameByUserId($id);
	// $unames = $this->web->getIdByMb($refer[0]->reference);
					  
	$role=$this->web->checkEmpRoleCmp($id,$bid);		 
			 
	 ?>
	 
	 
<dev style="color:red; font-size: 2rem;" id="msg"></dev>

      <form method="post" id="from" action="User/edit_emproll">

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="empType">Type</label>
              <select class="form-control" id="empType" name="empType">
                  <option value="0" <?php if($role[0]->type=='0'){ echo 'selected';}?>>Employee</option>
                <option value="1" <?php if($role[0]->type=='1'){ echo 'selected';}?>>Admin</option>
                <option value="2" <?php if($role[0]->type=='2'){ echo 'selected';}?>>Manager</option>
                <option value="3" <?php if($role[0]->type=='3'){ echo 'selected';}?>>HR</option>
                <option value="4" <?php if($role[0]->type=='4'){ echo 'selected';}?>>Accounts</option>
              </select>
            </div>
          </div>
        </div>
        
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
          <input type="hidden" name="bid" id="bid" value="<?php echo $bid; ?>">
      <button type="button" class="btn btn-success" id="bt_form">Update</button>
      
      </form>
      
    

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/edit_emproll",
        data: fdata,
      success: function(res){
      $('#msg').html('Updated');
    }
    });
  });    
  </script>
			
<?php 
}
?>






<?php
if (isset($_REQUEST['add_class_data'])=="add_class") {
  $id = $this->input->post('data');
      $value = $this->web->getclassById($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editclass">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
        
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editclass",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>







<?php
if (isset($_REQUEST['add_subject_data'])=="add_subject") {
  $id = $this->input->post('data');
      $value = $this->web->getsubjectnamebyid($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editsubject">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
        
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editsubject",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>


<?php
if (isset($_REQUEST['add_period_data'])=="add_period") {
  $id = $this->input->post('data');
      $value = $this->web->getperiodnamebyid($id);
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editperiod">

        <div class="from-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
        
        <br>
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>

      </form>

  <script type="text/javascript">
      $('#bt_form').on('click', function(){
        var fdata = $("#from").serialize();
      $.ajax({
        type: "POST",
        url: "User/editperiod",
        data: fdata,
      success: function(res){
      $('#msg').html('New entries updated!');
    }
    });
  });    
  </script>

<?php
}
?>




<?php
if (isset($_REQUEST['add_section_data'])=="add_section") {
  $id = $this->input->post('data');
  $value = $this->web->getsectionById($id);
  $buid = $this->web->session->userdata('login_id');
  
  // Get all branches
  $branches = $this->web->getBusinessDepByBusinessId($buid);
  // Get all semesters
  $allSemesters = $this->web->getallSemesters($buid);
  
  // Get current branch-semester assignments for this section
  $currentBranchSemesters = $this->web->getSectionBranchSemesters($id);
  
  // Create arrays to store current selections
  $selectedBranches = array();
  $selectedSemesters = array();
  
  foreach($currentBranchSemesters as $bs) {
    $selectedBranches[] = $bs->branch_id;
    $selectedSemesters[] = $bs->semester_id;
  }
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="edit_section_form">
        <div class="from-group mb-3">
          <label for="name">Section Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $value['0']->name; ?>" required>
        </div>
        
        <div class="from-group mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="mb-0">Branches and Semesters</label>
            <div class="d-flex">
              <div class="form-check mr-3">
                <input class="form-check-input" type="checkbox" id="edit-select-all-branches">
                <label class="form-check-label" for="edit-select-all-branches">
                  <strong>All Branches</strong>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="edit-select-all-semesters">
                <label class="form-check-label" for="edit-select-all-semesters">
                  <strong>All Semesters</strong>
                </label>
              </div>
            </div>
          </div>
          
          <div id="edit_branches_semesters_container" class="border p-3 rounded">
            <?php
              if(!empty($branches)) {
                // First create an array to store semesters by branch
                $semestersByBranch = array();
                
                // Organize semesters by branch
                foreach($branches as $branch) {
                  $semestersByBranch[$branch->id] = array(
                    'name' => $branch->name,
                    'semesters' => array()
                  );
                }
                
                // Categorize semesters by branch
                foreach($allSemesters as $semester) {
                  $semesterDeps = explode(',', $semester->dep_id);
                  foreach($semesterDeps as $depId) {
                    if(isset($semestersByBranch[$depId])) {
                      $semestersByBranch[$depId]['semesters'][] = $semester;
                    }
                  }
                }
                
                // Display branches and their semesters
                foreach($semestersByBranch as $branchId => $branchData) {
                  if(empty($branchData['semesters'])) {
                    continue; // Skip branches with no semesters
                  }
                  
                  $isBranchSelected = in_array($branchId, $selectedBranches);
                  
                  echo '<div class="branch-section mb-4">';
                  echo '<div class="branch-header d-flex align-items-center bg-primary text-white p-2 rounded">';
                  echo '<div class="form-check mb-0 mr-2">';
                  echo '<input class="form-check-input edit-branch-checkbox" type="checkbox" value="' . $branchId . '" id="edit_branch_' . $branchId . '" data-branch-name="' . $branchData['name'] . '"' . ($isBranchSelected ? ' checked' : '') . '>';
                  echo '</div>';
                  echo '<h5 class="mb-0 branch-title">' . $branchData['name'] . '</h5>';
                  echo '</div>';
                  
                  echo '<div class="semester-list pl-4" id="edit_semesters_' . $branchId . '" style="display:' . ($isBranchSelected ? 'block' : 'none') . ';">';
                  foreach($branchData['semesters'] as $semester) {
                    $isSemesterSelected = in_array($semester->id, $selectedSemesters);
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input edit-semester-checkbox" data-branch="' . $branchId . '" type="checkbox" value="' . $semester->id . '" id="edit_semester_' . $branchId . '_' . $semester->id . '" data-semester-name="' . $semester->semestar_name . '"' . ($isSemesterSelected ? ' checked' : '') . ($isBranchSelected ? '' : ' disabled') . '>';
                    echo '<label class="form-check-label" for="edit_semester_' . $branchId . '_' . $semester->id . '">' . $semester->semestar_name . ' (' . $semester->year . ' Year)</label>';
                    echo '</div>';
                  }
                  echo '</div>'; // End semester-list
                  echo '</div>'; // End branch-section
                }
              } else {
                echo '<div class="alert alert-info">No branches found</div>';
              }
            ?>
          </div>
        </div>
        
        <!-- Hidden input to store structured branch/semester data -->
        <input type="hidden" name="structured_data" id="edit_structured_data" value="">
        <input type="hidden" name="id" id="id" value="<?php echo $value['0']->id; ?>">
        <button type="button" class="btn btn-success" id="bt_form">Save Changes</button>
      </form>

  <script type="text/javascript">
    $(function () {
      // Branch checkbox functionality
      $('.edit-branch-checkbox').on('change', function() {
        var branchId = $(this).val();
        var isChecked = $(this).is(':checked');
        
        // Show/hide semester list for this branch
        $('#edit_semesters_' + branchId).toggle(isChecked);
        
        // Enable/disable semester checkboxes for this branch
        $('.edit-semester-checkbox[data-branch="' + branchId + '"]').prop('disabled', !isChecked);
        
        // If branch is unchecked, uncheck all its semesters
        if (!isChecked) {
          $('.edit-semester-checkbox[data-branch="' + branchId + '"]').prop('checked', false);
        }
        
        // If branch is checked and "select all semesters" is checked, check all its semesters
        if (isChecked && $('#edit-select-all-semesters').is(':checked')) {
          $('.edit-semester-checkbox[data-branch="' + branchId + '"]').prop('checked', true);
        }
        
        // Update "Select All Branches" checkbox
        updateSelectAllBranches();
      });
      
      // "Select All Branches" checkbox functionality
      $('#edit-select-all-branches').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.edit-branch-checkbox').prop('checked', isChecked);
        
        // Show/hide all semester lists
        if (isChecked) {
          $('.semester-list').show();
        } else {
          $('.semester-list').hide();
        }
        
        // Enable/disable all semester checkboxes
        $('.edit-semester-checkbox').prop('disabled', !isChecked);
        
        // If unchecking all branches, uncheck all semesters
        if (!isChecked) {
          $('.edit-semester-checkbox').prop('checked', false);
        }
        
        // If checking all branches and "select all semesters" is checked, check all semesters
        if (isChecked && $('#edit-select-all-semesters').is(':checked')) {
          $('.edit-semester-checkbox').prop('checked', true);
        }
      });
      
      // "Select All Semesters" checkbox functionality
      $('#edit-select-all-semesters').on('change', function() {
        var isChecked = $(this).is(':checked');
        
        // Only check semesters for selected branches
        $('.edit-branch-checkbox:checked').each(function() {
          var branchId = $(this).val();
          $('.edit-semester-checkbox[data-branch="' + branchId + '"]').prop('checked', isChecked);
        });
        
        // Update "All Semesters" checkbox status
        updateSelectAllSemesters();
      });
      
      // When any individual semester checkbox changes
      $('.edit-semester-checkbox').on('change', function() {
        updateSelectAllSemesters();
      });
      
      // Initialize "Select All" checkboxes
      updateSelectAllBranches();
      updateSelectAllSemesters();
      
      // Helper function to update "Select All Branches" checkbox
      function updateSelectAllBranches() {
        var allBranches = $('.edit-branch-checkbox').length;
        var selectedBranches = $('.edit-branch-checkbox:checked').length;
        $('#edit-select-all-branches').prop('checked', allBranches === selectedBranches && allBranches > 0);
      }
      
      // Helper function to update "Select All Semesters" checkbox
      function updateSelectAllSemesters() {
        var enabledSemesters = $('.edit-semester-checkbox:not(:disabled)').length;
        var selectedSemesters = $('.edit-semester-checkbox:checked').length;
        $('#edit-select-all-semesters').prop('checked', enabledSemesters === selectedSemesters && enabledSemesters > 0);
      }
    });
  
    $('#bt_form').on('click', function(){
      // Create a structured data object
      var structuredData = {};
      
      // Loop through all checked branches
      $('.edit-branch-checkbox:checked').each(function() {
        var branchId = $(this).val();
        
        // Find all checked semesters for this branch
        var branchSemesters = [];
        $('.edit-semester-checkbox[data-branch="' + branchId + '"]:checked').each(function() {
          var semesterId = $(this).val();
          branchSemesters.push(semesterId);
        });
        
        // Add branch and its semesters to the structured data
        structuredData[branchId] = branchSemesters;
      });
      
      // Store structured data in hidden input
      $('#edit_structured_data').val(JSON.stringify(structuredData));
      
      // Create form data including the structured data
      var fdata = {
        id: $('#id').val(),
        name: $('#name').val(),
        structured_data: JSON.stringify(structuredData)
      };
      
      $.ajax({
        type: "POST",
        url: "User/edit_S_Section",
        data: fdata,
        success: function(res){
          $('#msg').html('Section updated successfully!');
          // Reload the section list table after a short delay
          setTimeout(function() {
            location.reload();
          }, 1500);
        }
      });
    });
  </script>

<?php
}
?>






<?php
if(isset($_REQUEST['datatypes_session'])=="sessionlist") {
  $did = $this->input->post('id');
            $bid = $this->web->session->userdata('login_id');

  $result = $this->web->getSessionByDeptId($did,$bid);  print_r($result);
?>     
      <option value="" disabled selected>selected</option>  
                <?php
                echo "<option value=0> Select All </option>";
                  if(!empty($result)){
                    foreach($result as $result):
                          
                          echo "<option value=".$result->id .">".$result->session_name." </option>";
                    endforeach;
                  }
  }
?>



<?php
if(isset($_REQUEST['datatypes_session'])=="sessionlist_multiple") {
  $ids = $this->input->post('ids');
  $bid = $this->web->session->userdata('login_id');
  
  echo "<option value='' disabled selected>Select Semesters</option>";
  
  // For each branch ID, get related semesters
  if(is_array($ids)) {
    $processed_semesters = array(); // To track already added semesters
    
    foreach($ids as $did) {
      $result = $this->web->getallSemesters($bid);
      
      if(!empty($result)) {
        foreach($result as $semester) {
          // Check if the semester is related to this branch
          $dep_ids = explode(',', $semester->dep_id);
          if(in_array($did, $dep_ids) && !in_array($semester->id, $processed_semesters)) {
            echo "<option value='" . $semester->id . "'>" . $semester->semestar_name . " (" . $semester->year . " Year)</option>";
            $processed_semesters[] = $semester->id; // Track this semester as processed
          }
        }
      }
    }
  }
}
?>



<?php
if(isset($_REQUEST['datatypes_section'])=="sectionlist") {
  $did = $this->input->post('id');
  $result = $this->web->getSectionBySessionId($did);  print_r($result);
?>
      <option value="" disabled selected>Select</option>  
                <?php
                echo "<option value=0> Select All </option>";
                  if(!empty($result)){
                      
                    foreach($result as $result):
                        
                          echo "<option value=".$result->id .">".$result->name." </option>";
                    endforeach;
                  }
  }
?>







