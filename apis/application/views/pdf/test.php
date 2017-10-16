<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie10 lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie10" lang=""> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10" lang=""> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pdf/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pdf/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pdf/css/pdf.css">
    <script src="<?php echo base_url(); ?>assets/pdf/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <title>Mpokket-transation-details</title>
</head>

<body>
    <div class="pdf_wraper">
        <div class="header_sec">
            <img src="<?php echo base_url(); ?>assets/pdf/images/logo.png" width="240px;" height="56px;">
        </div>
        <div class="contain_sec">
            <div class="contain_subhdng">
                <h1>Transaction Details</h1>
            </div>
            <div class="boro_tanr_dtl_hed">Cash details</div>
            <div class="borrow_cash_dtls">
                <div class="tran_row_custom">
                    <div class="tran_boro_state_lft">ID</div>
                    <div class="tran_boro_state_rght"><?php echo $loan_details['unique_loan_code']; ?> </div>
                </div>
                <div class="tran_row_custom">
                    <div class="tran_boro_state_lft">Principal Amount</div>
                    <div class="tran_boro_state_rght"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-blue.png"> <?php echo $loan_details['input_principle']; ?></div>
                </div>
                <div class="row tran_row_custom">
                    <div class="tran_boro_state_lft">Total Fees</div>
                    <div class="tran_boro_state_rght"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-blue.png"> <?php echo $loan_details['borrower_tfdb']; ?></div>
                </div>
                <div class="tran_row_custom">
                    <div class="tran_boro_state_lft">Approved Date</div>
                    <div class="tran_boro_state_rght_time"><?php echo $loan_details['approve_date']; ?></div>
                </div>
                <div class="row tran_row_custom">
                    <div class="tran_boro_state_lft">Accepted Date</div>
                    <div class="tran_boro_state_rght_time"><?php echo $loan_details['accepted_date']; ?></div>
                </div>
                
            </div>
            <?php
                        	$pclass='';
                        	$pStatis='';
                        	if($payment_details['payment_status']=='1-O'){
                        		$pclass='overdue';
                        		$pStatis='Overdue';
                        	}else if($payment_details['payment_status']=='2-D'){
                        		$pclass='due';
                        		$pStatis='Due';
                        	}else if($payment_details['payment_status']=='3-U'){
                        		$pclass='upcoming';
                        		$pStatis='Upcoming';
                        	}else if($payment_details['payment_status']=='4-P-'){
                        		$pclass='paid';
                        		$pStatis='paid';
                        	}
                        	
                        	?>
            <div class="boro_tanr_dtl_hed">Transaction status</div>
            <div class="borrow_cash_dtls">
                <div class="tran_row_custom">
                    <div class="tran_boro_state_lft">Status</div>
                    <div class="tran_boro_state_rght"><?php echo $pStatis; ?></div>
                </div>
                <div class="tran_row_custom">
                    <div class="tran_boro_state_lft">Payment Type</div>
                    <div class="tran_boro_state_rght"><i class="fa fa-inr" aria-hidden="true"></i> One-Time</div>
                </div>
                <div class="row tran_row_custom">
                    <div class="tran_boro_state_lft">Tenure</div>
                    <div class="tran_boro_state_rght"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $loan_details['input_npm']; ?></div>
                </div>
                <div class="tran_row_custom">
                    <div class="tran_boro_state_lft">Rate of interest</div>
                    <div class="tran_boro_state_rght_time"><?php echo $loan_details['input_air']; ?></div>
                </div>
                
            </div>
            <div class="boro_tanr_dtl_hed">Payment Details</div>




 <!-- payment details start-->
 
         <div class="table_payment">
                <div class="table-payment-row head-table-payment">
                    <div class="table-payment-cell" style="width:30%;">
                        DUE 
                    </div>
                    <div class="table-payment-cell" style="width:30%;">
                        PAID
                    </div>
                    <div class="table-payment-cell" style="width:30%;">
                        STATUS
                    </div>
                </div>
<!-- table body start -->
                
          
             <div class="table-payment-row head-table-payment">
                    <div class="table-payment-cell-body" style="width:30%;">
                        <h2 class="amount_col-payment"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-grey.png"></h2>
                            <p class="amount_down_date-payment"><?php echo $payment_details['sch_date']; ?></p>
                    </div>
                    <div class="table-payment-cell-body" style="width:30%;">
                        <h2 class="amount_col-payment"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-grey.png"> <?php echo $payment_details['borrower_emi_amount']; ?></h2>
                            <p class="amount_down_date-payment">(With fine)</p>
                    </div>
                    <div class="table-payment-cell-body" style="width:30%;">
                        <h3 class="status-payment-<?php echo $pclass; ?>"><?php echo $pStatis; ?></h3>
                    </div>
            </div>       


            </div>




        </div>
    </div>
</body>

</html>
