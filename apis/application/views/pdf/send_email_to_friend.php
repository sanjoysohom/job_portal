<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/pdf/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/pdf/css/pdf.css">
 <script src="<?php echo base_url(); ?>/assets/pdf/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<title>surgilogix</title>
</head>

<body>
	<div class="pdf_wraper">
		<div class="contain_sec">			
			<div class="patient_info">
				<div class="main_contain_sec">					
					<div class="main_cont_part">
						<div class="label_sc">Hospital Name:</div>
						<div class="label_txt"><?php echo $detail['hospital_name']; ?></div>
					</div>					
					<div class="main_cont_part">
						<div class="label_sc">Address:</div>
						<div class="addrs_sec">							
							<?php if(!empty($hospital_dtl['street1'])){?>
							<span><?php echo $hospital_dtl['street1']; ?></span>
							<?php } ?>
							<?php if(!empty($hospital_dtl['street2']) && $hospital_dtl['street2']!='null'){?>
							<span><?php echo $hospital_dtl['street2']; ?></span>
							<?php } ?>					
							<?php if(!empty($hospital_dtl['street3']) && $hospital_dtl['street3']!='null'){?>
							<span><?php echo $hospital_dtl['street3']; ?></span>
							<?php } ?>
							<?php if(!empty($hospital_dtl['city']) && $hospital_dtl['city']!='null'){?>
							<span><?php echo $hospital_dtl['city']; ?></span>
							<?php }?>							
							<?php if(!empty($hospital_dtl['state'])){?>
							<span><?php echo $hospital_dtl['state']; ?></span>
							<?php }?>							
							<?php if(!empty($hospital_dtl['zipcode'])){?>
							<span><?php echo $hospital_dtl['zipcode']; ?></span>
							<?php }?>
						</div>
					</div>					
					<div class="main_cont_part">
						<div class="label_sc">Doctor Name:</div>
						<div class="label_txt"><?php echo $detail['doctor_f_name']; ?> <?php echo $detail['doctor_l_name']; ?></div>
					</div>
					<div class="main_cont_part">
						<div class="label_sc">Patient Name:</div>
						<div class="label_txt"><?php echo $detail['patient_name']; ?></div>
					</div>
					<div class="main_cont_part">
						<div class="label_sc">Surgery Date:</div>
						<div class="label_txt"><?php echo date("d-M-Y", strtotime($detail['surgery_timestamp'])); ?></div>
					</div>					
				</div>
				<div class="main_contain_sec">
					<div class="main_cont_part">
						<div class="label_sc">Patient Sticker:</div>
						<div class="label_txt"><img width="150" heigth="100" src="<?php echo $chargesheet_file_url; ?>/<?php echo $detail['id']; ?>/template/patient_sticker/<?php echo $patient_sticker[0]['id'].'.'.$patient_sticker[0]['file_extension']; ?>"/></div>
					</div>
					<div class="main_cont_part">
						<div class="label_sc">Salesrep Name:</div>
						<div class="label_txt"><?php echo $detail['f_name']; ?> <?php echo $detail['l_name']; ?></div>
					</div>
					<div class="main_cont_part">
						<div class="label_sc">Distributor Name:</div>
						<div class="label_txt"><?php echo $detail['distributor_name']; ?></div>
					</div>					
					<div class="main_cont_part">
						<div class="label_sc">Contact #:</div>
						<div class="label_txt"><?php echo "(".substr($chargesheet_by['phone'], 0, 3).") ".substr($chargesheet_by['phone'], 3, 3)."-".substr($chargesheet_by['phone'],6); ?></div>
					</div>
					<?php /*
					<div class="main_cont_part">
						<div class="label_sc">Company Name:</div>
						<div class="label_txt"><?php echo $chargesheet_by['company_name']; ?></div>
					</div>
					<div class="main_cont_part">
						<div class="label_sc">Email Id:</div>
						<div class="label_txt"><?php echo $chargesheet_by['email']; ?></div>
					</div> */?>
					<?php if(!empty($chargesheet_by['po_number'])){ ?>
					<div class="main_cont_part">
						<div class="label_sc">PO Number: </div>
						<div class="label_txt">#<?php echo $chargesheet_by['po_number']; ?></div>
					</div>
					<?php } ?>
					<?php if(!empty($chargesheet_by['po_number'])){ ?>
					<div class="main_cont_part">
						<div class="label_sc">PO Date:</div>
						<div class="label_txt">
						<?php echo date("d-M-Y", strtotime($detail['po_addition_timestamp'])) ; ?>
						</div>
					</div>
					<?php } ?>
					<?php if($is_active>0){ 
						if(!empty($chargesheet_by['invoice_number'])){
					?>
					<div class="main_cont_part">
						<div class="label_sc">Invoice No:</div>
						<div class="label_txt">#<?php echo $chargesheet_by['invoice_number']; ?></div>
					</div>
					<?php } ?>
					<?php if(!empty($chargesheet_by['invoice_timestamp'])){ ?>
					<div class="main_cont_part">
						<div class="label_sc">Invoice Date:</div>
						<div class="label_txt">
						<?php echo date("d-M-Y", strtotime($detail['invoice_timestamp'])) ; ?>
						
						</div>
					</div>					
					<?php
						}
					 } ?>
				</div>
			</div>
			<div class="product_list">
				<div class="prd_list_hdng">
					<div class="list1">Name</div>
					<div class="list2">Code</div>
					<div class="list3">Description</div>
					<div class="list4">Qty.</div>
					<div class="list5">Unit Price</div>
				</div>
				<?php if(is_array($prouctdtl) && count($prouctdtl)>0){
					foreach($prouctdtl as $pdt){
				?>
				<div class="prd_list_contain">
					<div class="list1"><?php echo $pdt['product_name']; ?>&trade;</div>
					<div class="list2"><?php echo $pdt['product_code']; ?></div>
					<div class="list3"><?php echo $pdt['description']; ?></div>
					<div class="list4"><?php echo $pdt['quantity']; ?></div>
					<div class="list5">$<?php echo $pdt['total_price']; ?></div>
				</div>
				<?php
					}
				}
				?>
			</div>
			<div class="patient_sticker_sec">
				<h3>Place product stickers below:</h3>
				<div class="patient_sticker_part">
				<?php if(is_array($product_sticker) && count($product_sticker)>0){
					foreach($product_sticker as $pdt){
				?>
				<img width="150" heigth="80" src="<?php echo $chargesheet_file_url; ?>/<?php echo $detail['id']; ?>/template/product_sticker/150X80_<?php echo $pdt['id'].'.'.$pdt['file_extension']; ?>" >
				<?php
					}
				}
				?>
				</div>
			</div>
			<div class="patient_info">
				<div class="patient_info_prt">
					<strong>Signature: </strong>
					<img width="150" heigth="100" src="<?php echo $chargesheet_file_url; ?>/<?php echo $detail['id']; ?>/template/signature/<?php echo $hospital_sticker[0]['id'].'.'.$hospital_sticker[0]['file_extension']; ?>"/>
				</div>
				<div class="patient_info_prt">
					<strong>Name:</strong>
					<span><?php echo $hospital_sticker[0]['name_of_signatory']; ?></span>
				</div>
				<div class="patient_info_prt">
					<strong>Designation:</strong>
					<span><?php echo $hospital_sticker[0]['designation_of_signatory']; ?></span>
				</div>

			</div>
		</div>
	</div>	
</body>
</html>
