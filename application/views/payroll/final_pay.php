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


        <div class="body-section">
                  <p class="sub-heading">PAY SLIP FOR THE MONTH OF:  <?=date("M Y", strtotime(!empty($_GET['selectDate']) ? $_GET['selectDate'] : date("Y-m"))); ?> </p>
      </div>
	  
        <div class="">
            <table class="" border="2">
                <thead>
                    <tr>
                        <td class="w-20">Name</td>
                        <td class="w-20"><?=$emp_details->name;?></td>
                        <td class="w-20">Pay Mode</td>
                        <td class="w-20"><?=$emp_more_details->pay_mode;?></td>
                    </tr>
                    <tr>
                        <td class="w-20">Employee Code</td>
                        <td class="w-20"><?=$emp_details->emp_code;?></td>
                        <td class="w-20">Bank Name</td>
                        <td class="w-20"><?=$emp_more_details->bank_name;?></td>
                    </tr>
                    <tr>
                        <td class="w-20">Designation</td>
                        <td class="w-20"><?=$emp_details->designation;?></td>
                        <td class="w-20">Bank A/C No</td>
                        <td class="w-20"><?=$emp_more_details->account_no;?></td>
                    </tr>
                    <tr>
                        <td class="w-20">Department</td>
                       <?php
                       // $uname = $this->web->getNameByUserId($val->user_id);
                          $dp = $this->web->getBusinessDepByUserId($emp_details->department);
                               // echo $dp[0]->name;  ?>
                        <td class="w-20"><?=$dp[0]->name;?></td>
                        <td class="w-20">P.F No</td>
                        <td class="w-20"><?=$emp_more_details->epf;?></td>
                    </tr>
                    <tr>
                        <td class="w-20">Location</td>
                        <td class="w-20"><?=$emp_details->address;?></td>
                        <td class="w-20">ESI No</td>
                        <td class="w-20"><?=$emp_more_details->esic;?></td>
                    </tr>
                    <tr>
                        <td class="w-20">Date of Joining</td>
                        <td class="w-20"><?=date("d-m-Y", $emp_details->doj);?></td>
                        <td class="w-20">UAN</td>
                        <td class="w-20"><?=$emp_more_details->uan;?></td>
                    </tr>
                    <tr>
                         <td class="w-20">Month Days</td>
                        <td class="w-20"><?=cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($_GET['selectDate'] ? $_GET['selectDate'] : date("Y-m"))),2024); ?></td>
                        <td class="w-20">PAN No</td>
                        <td class="w-20"><?=$emp_more_details->pan;?></td>
                    </tr>
                    <tr>
                        <td class="w-20">Paid Days</td>
                        <td class="w-20"><?=$paid_day;?></td>
                        <td class="w-20"></td>
                        <td class="w-20"></td>
                    </tr>
                </thead>
            </table>
            <br>
        </div>

        <div class="">
            <table class="" border="2">
                <thead>
                    <tr>
                        <td class="w-20 center"><b>EARNINGS<b>   </td>
                        <td class="w-20 center"><b>DEDUCTION  <b>    </td>
                    </tr>
                    <tr>
                        <td > 
                            <table>
                                <thead>
                                    <tr>
                                        <td>Basic</td>
                                        <td><?=$salary['basic_value']; ?></td>
                                    </tr>
                                    <?php
                                    $EARNINGS = 0;
                                    foreach($earning as $val){ 
                                    $EARNINGS = $EARNINGS+$val['amount'];
                                        ?>
                                    <tr>
                                        <td class="w-20"><?=$val['name']?></td>
                                        <td class="w-20"><?=$val['amount']?></td>

                                    </tr>
                                    <?php } ?>

                                </thead>
                            </table>
                        </td>
                        <td> 
                            <table>
                                <thead>
                                    <?php 
                                    $DEDUCTION = 0;
                                    foreach($deduction as $val){ 
                                    
                                     ?>
                                    <tr>
                                        <td class="w-20"><?=$val['name']?></td>
                                        <td class="w-20">
                                            <?php if($val['name'] == 'PF' || $val['name'] == 'ESI' || $val['name'] == 'TDS'){ ?>
                                            <?php $des = $val['header_type'] == "Manual" ? $val['amount'] : round((((($ctc_salary->basic_value)/$number_of_days)*$paid_day)*$val['header_value']))/100;
                                            $DEDUCTION = $DEDUCTION+$des;
                                                echo $des;
                                            ?>
                                            <?php }else {
                                                $DEDUCTION = $DEDUCTION+$val['amount'];
                                                echo $val['amount'];
                                            } ?>
                                        </td>

                                    </tr>
                                    <?php } ?>
                                    <tbody>
                                </tbody>
                                </thead>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>Earnigs : <?=$earning_master->amount; ?></td>  
                        <td>Deductions : <?=$deduction_master->amount; ?></td>      
                    </tr>
                    <tr>
                        <td>Total : <?=$EARNINGS+$earning_master->amount; ?></td>  
                        <td>Total : <?=$DEDUCTION+$deduction_master->amount; ?></td>      
                    </tr>
                    <tr>
                        <td>Advance : <?=$advance;?></td>
                        <td>Advance Deductions : <?=$advance_paid;?></td>
                    </tr>
                    <tr>
                        <td>Advance balance: <?=$advance_balance;?></td>
                        <td>NetPayable : <?= isset($_GET['netPayable']) ? $_GET['netPayable']-$advance_paid : 0; ?></td>
                    </tr>
                   
                    
                </thead>
            </table>
            <br>
            <table class="" border="2">
                <thead>
                    <tr>
                        <td class="w-20 center" colspan="5"><b>Leave Balances    <b>   </td>
                    </tr>
                    <tr>
                        <td>Opening Balance</td>  
                        <td>Entitlement</td>   
                        <td>Leaves Taken</td>
                        <td>End Balace</td>
                    </tr>
                    <tr>
                        <td><?=$total_leave;?></td>
                        <td><?=$open_leave->other;?></td>
                        <td><?=$usedleave;?></td>
                        <td><?=$balanceleave+$open_leave->other;?></td>
                    </tr>
                </thead>
            </table>
            <br />
            <br />
        </div>  
		
		
    </div>      

</body>
</html>