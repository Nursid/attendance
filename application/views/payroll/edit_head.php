<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>




<?php
if (isset($_REQUEST['edit_data'])=="edit_data") {
  $id = $this->input->post('data');
       $value = $this->web->getHeadbyId($id);
	
?>
      <dev style="color:red; font-size: 2rem;" id="msg"></dev>
      <form method="post" id="from" action="User/editctchead">

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
        url: "User/editctchead",
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


</body>
</html>