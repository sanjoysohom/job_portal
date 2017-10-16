<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//method to change the language of the website
function change_website_language($id, $name)
{
    $CI = & get_instance();

    $CI->session->unset_userdata('lang_id');
    $CI->session->unset_userdata('lang_name');

    $CI->session->set_userdata('lang_id', $id);
    $CI->session->set_userdata('lang_name', $name);
}

   function get_real_ip_addr()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }
        
   /*function getVerificationCode(){
     $CI = & get_instance();
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        ensure_length($dec_hex, 3);
       
        $guid = "";
        $guid .= $dec_hex;
        $guid .= create_guid_section(3);
       
        return $guid;
    }*/

function create_guid() {
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);
    $dec_hex = dechex($a_dec * 1000000);
    $sec_hex = dechex($a_sec);
    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);
    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);
    return $guid;
}
/*function ensure_length(&$string, $length) {
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string, $length, "0");
    } else if ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}*/

/*function create_guid_section($characters) {
    $return = "";
    for ($i = 0; $i < $characters; $i++) {
        $return .= dechex(mt_rand(0, 15));
    }
    return $return;
}*/
function rpHash($value) {
	$hash = 5381;
	$value = strtoupper($value);
	for($i = 0; $i < strlen($value); $i++) {
		$hash = (($hash << 5) + $hash) + ord(substr($value, $i));
	}
	return $hash;
}


//convert server time to gmt time
function get_gmt_time_from_offset($time_diff)
{
    $sign = substr($time_diff, 0, 1);
    $tmp = explode(':', str_replace(array('+','-'), '', $time_diff));

    $tmp_time_diff = $tmp[0]+($tmp[1]/60);

    $offset = $sign.$tmp_time_diff;
    $dateFormat = "Y-m-d G:i:s";        
    $timeNdate = gmdate($dateFormat, time()+(3600*$offset));
    return $timeNdate;
}

/*---------------------------------------------
 * Function : stripslashesFull
 * Added By : Prabhat Kumar Mandal
 * Description : Getting strip slashes from array or string or object.
 * --------------------------------------------
 * Param : array/string/object (data)
 * Return : array/string/object (result)
 */
function stripslashesFull($input)
{
    if (is_array($input)) {
        $input = array_map('stripslashesFull', $input);
        
    } elseif (is_object($input)) {
        $vars = get_object_vars($input);
        foreach ($vars as $k=>$v) {
            $input->{$k} = stripslashesFull($v);
        }
    } else {
        $input = htmlspecialchars(stripslashes($input));
                
    }
    return $input;
}

    
    
    /*---------------------------------------------
 * Function : setUrlId
 * Added By : Subhajit Singha Roy
 * Description : Setting Id to encrypted format.
 * --------------------------------------------
 * Param : Int(Id)
 * Return : String(encryption format)
 */
function setEncryption($id){
     $CI =& get_instance();
     $CI->encryption->initialize(
        array(
        'driver'=> 'OpenSSL',
        'cipher' => 'aes-128',
        'mode' => 'CBC'
        )
    );
    $encrypted_id=$CI->encryption->encrypt($id);
     return $encrypted_id;
}
/*---------------------------------------------
 * Function : getUrlId
 * Added By : Subhajit Singha Roy
 * Description : Getting Id to decrypted format.
 * --------------------------------------------
 * Param : String(decryption format)
 * Return : Int(Id)
 */
function getDrcyption($id){
    $CI =& get_instance();
     $CI->encryption->initialize(
        array(
        'driver'=> 'OpenSSL',
        'cipher' => 'aes-128',
        'mode' => 'CBC'
        )
    );
    $encrypted_id=$CI->encryption->decrypt($id);
    return $encrypted_id;
}

function get_local_date_time($user_id, $time){
    //echo $user_id.'<br>'.$time;exit;
    $CI = & get_instance();
    $CI->load->model('user_model');
    $time_zone = $CI->user_model->fetch_time_zone($user_id);    
    $dt = new DateTime($time, new DateTimeZone('UTC'));
    $dt->setTimezone(new DateTimeZone($time_zone));
    return $dt->format('Y-m-d H:i:s');
}

if (!function_exists('pre')) {
    function pre($array, $die=false) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';

        if($die)
            die();        
    }
}

if (!function_exists('generate_passcode')) {
    function generate_passcode() {
        return mt_rand(100000,999999);
    }
}


function getDatabaseDate($date){
    if($date!=''){
        $dt=explode('-',$date);
        $year=$dt['2'];
        $month=$dt['1'];
        $day=$dt['0'];
        if($month>0){
            $databasedate=$year.'-'.$month.'-'.$day;
        }else{
            $mon=date("m",strtotime($month));
            $databasedate=$year.'-'.$mon.'-'.$day;
        }
    }else{
        $databasedate='';
    }
   
    return $databasedate;
}

function getJsDate($date){
    if($date!=''){
        $dt=explode('-',$date);
        $year=$dt['0'];
        $month=$dt['1'];
        $day=$dt['2'];
        $datajs=$day.'-'.$month.'-'.$year;
    }else{
        $datajs='';
    }
   
    return $datajs;
}



/*
 * --------------------------------------------------------------------------
 * @ Function Name            : json_response()
 * @ Added Date               : 13-04-2016
 * @ Added By                 : Subhankar
 * --------------------------------------------------------------------------
 * @ Description              : json_response
 * --------------------------------------------------------------------------
 * @ return                   : string
 * --------------------------------------------------------------------------
 * @ Modified Date            : 13-04-2016
 * @ Modified By              : Subhankar
 * 
 */



if (!function_exists('json_response')) {

    function json_response($data = array(), $http_response, $error_message, $success_message){
        $CI = & get_instance();
        
        $developer = 'www.massoftind.com';
        $version = str_replace('_', '.', $CI->config->item('test_api_ver'));
        $CI->publish = array(
            'version' => $version,
            'developer' => $developer
        );

        $raws = array();   
        if($error_message != ''){
            $raws['error_message']      = $error_message;
        } else{
            $raws['success_message']    = $success_message;
        }        

        $raws['data']       = $data;
        $raws['publish']    = $CI->publish;
     

        //response in json format
        $CI->response(
            array(
                'raws' => $raws
            ), $CI->config->item($http_response)
        ); 
    }
}

if (!function_exists('getVerificationCode')) {
    function getVerificationCode(){
        $CI = & get_instance();
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        ensure_length($dec_hex, 2);
        ensure_length($sec_hex, 2);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= create_guid_section(2);
        $guid .= $sec_hex;
        $guid .= create_guid_section(2);
        return $guid;
    }
}

if (!function_exists('ensure_length')) {
    function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }
}

if (!function_exists('create_guid_section')) {
    function create_guid_section($characters) {
        $return = "";
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }
}


// get clicni id fromn office
if (!function_exists('email_config')) {

    function email_config() {
         $CI = & get_instance();
        /*  ------  finzo.com -------     */
        $config = array();
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = $CI->config->item('smtp_host');
        $config['smtp_port']    = '587';
        $config['smtp_crypto']='tls';
        $config['smtp_user']    = $CI->config->item('smtp_user');
        $config['smtp_pass']    = $CI->config->item('smtp_pass');
        $config['charset']         = 'utf-8';
        $config['wordwrap']        = TRUE;
        $config['mailtype']        = 'html';
        $config['newline']      = "\r\n";
        return $config; 
    }

    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }   
    
}

function generateRandomString($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    }

