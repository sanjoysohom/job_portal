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
              <img src="<?php echo base_url(); ?>assets/pdf/images/logo.png">
        </div>
        <div class="contain_sec">
            <div class="contain_subhdng" style="margin-bottom: 30px; ">
                <h1>Transactions</h1>
            </div>


            <div class="table_payment">
                <div class="table-payment-row head-table-payment">
                    <div class="table-payment-cell" style="width:30.5%;">
                        Start date
                    </div>
                    <div class="table-payment-cell" style="width:30.5%;">
                        Amount
                    </div>
                    <div class="table-payment-cell" style="width:30.5%;">
                        STATUS
                    </div>
                </div>
<!-- table body start -->
                <?php if(is_array($all_cash_token) && count($all_cash_token)){
                        foreach($all_cash_token as $taken){
                    ?>
                <div class="table-payment-row head-table-payment">
                    <div class="table-payment-cell-body" style="width:30%;">
                        <h2 class="amount_col-payment"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-grey.png"></h2>
                            <p class="amount_down_date-payment"><?php echo $taken['sch_date']; ?></p>
                    </div>
                    <div class="table-payment-cell-body" style="width:30%;">
                        <h2 class="amount_col-payment"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-grey.png"> <?php echo $taken['borrower_emi_amount']; ?></h2>
                            <p class="amount_down_date-payment">(With fine)</p>
                    </div>
                    <?php
                            $pclass='';
                            $pStatis='';
                            if($taken['payment_status']=='1-O'){
                                $pclass='overdue';
                                $pStatis='Overdue';
                            }else if($taken['payment_status']=='2-D'){
                                $pclass='due';
                                $pStatis='Due';
                            }else if($taken['payment_status']=='3-U'){
                                $pclass='upcoming';
                                $pStatis='Upcoming';
                            }else if($taken['payment_status']=='4-P-'){
                                $pclass='paid';
                                $pStatis='paid';
                            }else{
                                $pclass='upcoming';
                                $pStatis='Upcoming';
                            }
                            
                            ?>
                    <div class="table-payment-cell-body" style="width:30%;">
                        <h3 class="status-payment-<?php echo $pclass; ?>"><?php echo $pStatis; ?></h3>
                    </div>
                </div>  

                 <?php
                        }
                    }
                    ?>    
          
            <!-- <div class="table-payment-row head-table-payment">
                    <div class="table-payment-cell-body" style="width:30.5%;">
                        <p class="amount_down_date-payment">May 15, 2016</p>
                    </div>
                    <div class="table-payment-cell-body" style="width:30.5%;">
                        <h2 class="amount_col-payment"><img src="<?php echo base_url(); ?>assets/pdf/images/rupee-grey.png"> 631</h2>
                    </div>
                    <div class="table-payment-cell-body" style="width:30.5%;">
                        <h3 class="status-payment-overdue">overdue</h3>
                    </div>
            </div> -->      


            </div>

        </div>
    </div>
</body>

</html>
