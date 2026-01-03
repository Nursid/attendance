<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mid | App</title>
    <style>
	
	@import "https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i";
	
	
        body{
            background-color: #F6F6F6; 
            margin: 0;
            padding: 0;
			font-size:14px;
			font-family:Montserrat;
        }
		.center-align{
		text-align:center;
	}
	
		.center-align-margin{
		    margin: 0 auto;
			text-align:center;
		}
		
		.bg{
		background: #e1f0f8;
		}
		
		.logo-center{
		text-align:center;
		}
		
        h1,h2,h3,h4,h5,h6{
            margin: 0;
            padding: 0;
        }
        p{
            margin: 0;
            padding: 0;
        }
        .container{
            width: 600px;
			background-color: white;
            margin-right: auto;
            margin-left: auto;
        }
        .brand-section{
           background-color: #fff;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
        }

        .row{
            display: flex;
            flex-wrap: wrap;
        }
        .col-6{
            width: 50%;
            flex: 0 0 auto;
        }
        .col-8{
            width: 70%;
            flex: 0 0 auto;
        }
        
        .text-white{
            color: #fff;
        }
        .company-details{
            float: right;
            text-align: right;
        }
        .body-section{
            padding: 16px;
            border: 1px solid #e7e4e4;
        }
        .heading{
            font-size: 13px;
            margin-bottom: 08px;
        }
        .sub-heading{
            color: #262626;
            margin-bottom: 05px;
        }
        table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            vertical-align: middle !important;
/*            text-align: center;*/
        }
        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #f2f2f2;
        }
        .text-right{
            text-align: end;
        }
        .w-20{
            width: 20%;
        }
		.w-40{
            width: 40%;
        }
        .float-right{
            float: right;
        }
        .center{
          text-align: center;
        }
		
		.bold{
		font-weight:bold;
		}
		
		
		.col-12{
		width:100%;
		}
		.col-4{
            width: 30%;
            flex: 0 0 auto;
        }
		
    </style>
</head>
<body>

    <div class="container">
        <div class="brand-section">
            <div class="row">
                <div class="col-4 logo-center">
                    <h1 class="text-white"><img src="<?=base_url("/").$cmp_details['image']?>" width="100"/></h1>
                </div>
                <div class="col-8 center">
                    <h2 class="heading"><?=$cmp_details['name']?></h2>
                   
                    <p class="sub-heading"><?=$cmp_details['address']?> </p>
                    <p class="sub-heading"><b>TCIN No.</b> : <?=$cmp_details['gstn']?></p>
                </div>
            </div>
        </div>
        <?php ?>

        <div class="body-section">
                  <p class="sub-heading">Payment Detail for the final month of :  <?=date("M Y", strtotime($date) ); ?> </p>
      </div>
	  
        <div class="">
            <table class="" border="1">
                <thead>
                    <tr>
                        <td class="w-20">Name</td>
                        <td class="w-20"><?=$emp_details->name;?></td>
                        <td class="w-20">Employee Code</td>
                        <td class="w-20"><?=$emp_details->emp_code;?></td>
                    </tr>
                    
                    <tr>
                        <td class="w-20">Designation</td>
                        <td class="w-20"><?=$emp_details->designation;?></td>
                        <td class="w-20">Department</td>
                       <?php
                       // $uname = $this->web->getNameByUserId($val->user_id);
                          $dp = $this->web->getBusinessDepByUserId($emp_details->department);
                               // echo $dp[0]->name;  ?>
                        <td class="w-20"><?=$dp[0]->name;?></td>
                    </tr>
                   
                    <tr>
                        <td class="w-20">Address</td>
                        <td class="w-20"><?=$emp_details->address;?></td>
                         <td class="w-20">PayMode
                         <br>
                        <?=$pay_mode;?>
                        
                        </td>
                        <td class="w-20">Bank Detail
                         <br> <?=$emp_more_details->bank_name;?>
                         <br><?=$emp_more_details->ifsc_code;?> 
                         <br><?=$emp_more_details->account_no;?> 
                         
                         </td>
                        
                        
                    </tr>
                    <tr>
                        <td class="w-20">Date of Joining</td>
                        <td class="w-20"><?=date("d-m-Y", $emp_details->doj);?></td>
                       <td class="w-20">Left Date</td>
                        <td class="w-20"><?=date("d-m-Y", $emp_name->left_date);?></td>
                        
                    </tr>
                    
                  <!--  <tr>
                        <td class="w-20">Paid Days</td>
                        <td class="w-20"><?=$paid_day;?></td>
                        <td class="w-20"> Salary Amount</td>
                        <td class="w-20"></td>
                    </tr>-->
                </thead>
            </table>
            <br>
        </div>

        <div class="">
            <table class="" border="1">
                <thead>
                    <tr>
                        <td >CTC Amount  </td>
                        <td class="w-40 center" ><?=$ctc_amount;?>  </td>
                        
                    </tr>
                <tr>
                 <tr>
                        <td >Final Paid Days  </td>
                        <td class="w-40 center" ><?=$paid_day;?>  </td>
                        
                    </tr>
                <tr>
                        <td >Final Salary Amount  </td>
                        <td class="w-40 center" ><?=$FinalSalary;?>  </td>
                        
                    </tr>
                    <tr>
                        <td >Final EARNINGS  </td>
                         <td class="w-40 center" ><?=$earning_master->amount; ?>  </td>
                        
                    </tr>
                    <tr>
                    
                    <td >Final DEDUCTION     </td> 
                     <td class="w-40 center" ><?=$deduction_master->amount; ?></td> 
                  
                    </tr>
                    <tr>
                   <td > Advance Balance   </td> 
                     <td class="w-40 center" > <?=$advance; ?> </td> 
                   </tr>
                   
                   <tr>
                    
                    <td >Balance Leave    </td> 
                     <td class="w-40 center" ><?=$balanceleave ?> </td> 
                  
                    </tr>
                    <tr>
                    
                    <td >Leave Amount Adjusted     </td> 
                    <td class="w-40 center" ><?=$oneDaySalary*$balanceleave ?> </td> 
                  
                    </tr>
                    <tr>
                    
                    <td ><b>NetPayable Amount  <b>   </td> 
                     <td class="w-40 center" ><b><?=$FinalSalary+$earning_master->amount-$deduction_master->amount-$advance_balance+($oneDaySalary*$balanceleave)?>  <b> </td> 
                  
                    </tr>
                    
                   
                    
                </thead>
            </table>
           
            <br />
            <br />
        </div>  
		
		
    </div>      

</body>
</html>