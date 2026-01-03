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
