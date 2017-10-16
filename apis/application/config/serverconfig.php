<?php
$config['test_api_ver'] = '1_0_0';
$config['admin_email'] = 'noreply@finzo.in';
$config['admin_email_from'] = 'pAngular';
$config['site_title'] = 'angular_obr';
$config['http_response_bad_request']='400';
$config['http_response_invalid_login']='403';
$config['http_response_unauthorized']='401';
$config['http_response_not_found']='404';
$config['http_response_ok']='200';
$config['http_response_ok_no_content']='204';

$config['passcode_validity']='15';

/*$config['oauth_db_host']='mpokket-staging.cnbns2hrinms.us-west-2.rds.amazonaws.com';
$config['oauth_db_database']='stagingmpokket';
$config['oauth_db_username']='finzo';
$config['oauth_db_password']='xZ4_~F(Xeb';*/

$config['oauth_db_host']='localhost';
$config['oauth_db_database']='angular_obr';
$config['oauth_db_username']='root';
$config['oauth_db_password']='Mass4Pass';

$config['site_mode']='staging';


$config['temp_upload_file_path']='/var/www/html/angular/apis/assets/uploads/';
$config['upload_file_url'] = '/var/www/html/angular_module/assets/resources/';

$config['thump_file_url']  ='http://localhost/angular_module/assets/resources/product/thumb/';

$config['product_file_url']  ='http://localhost/angular_module/assets/resources/product/';
//$config['temp_upload_file_path']='/var/www/html/apis/assets/tmp_uploads/';
//$config['upload_file_url'] = '/var/www/html/apis/assets/resources/';

$config['bucket_url']='/var/www/html/angular_obr/assets/resources/';
$config['file_url'] = "http://".$_SERVER['SERVER_NAME']."/angular_obr/assets/resources/";

$config['protocol']    = 'smtp';
$config['smtp_host']    = 'email-smtp.us-west-2.amazonaws.com';
$config['smtp_port']    = '587';
$config['smtp_crypto']='tls';
$config['smtp_user']    = 'AKIAJ77XL2KI7GIA3KAQ';
$config['smtp_pass']    = 'Ah64mE19mUZVBxIHYFKy+Z0mjTYxVFDCphWZ0d0W45nS';
$config['charset']         = 'utf-8';
$config['wordwrap']        = TRUE;
$config['mailtype']        = 'html';
$config['newline']      = "\r\n"; 

?>
