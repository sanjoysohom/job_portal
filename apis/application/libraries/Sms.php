<?php
/**
 * @author SUVRADITYA DEY
 * @date 
 * @description Send sms for any using smsgatewayhub site.
 * this class uses an XML file to get the templates and in every template there are variables along with "%" padded each side. the variables in xml will be related to thos public
    variables declared in this class accordingly. New variable in XML file should be declard in this class too.
 */

class Sms
{

    
    public $name = "";
    public $username = "";
    public $pass = "";
    public $rtono = "";
    public $datetime = "";
    public $violation = "";
    public $location = "";
    public $category = "";
    public $user = "";
    public $pwd = "";
    public $sid = "";
    public $mobile = "";
    public $msgs = "";
    public $url = "";
    public $saveSmsFlag = 0;
    public $companyid = '';
    public $simno = '';
    public $imei = '';
    public $others = '';
    public $run = '';
    public $idletime = '';
    public $vstat = '';
    public $error = '';
    public $device = '';
    public $event = '';
    public $hourmeterstatus = '';
    public $totaldur = '';
    public $ignore='';
    
    //Newly added variable
    
    public $transporter_company_name = '';
    public $auction_name = '';
    
    public $price_value = '';
    public $lead_name = '';
    
    public $transporter_name = '';
    public $customer_name = '';
    
    /**
    Created By: Suvraditya
    Creation Date: 
    Description: Function for getting the total file path along with the file name.
    */
    private function getFilePath()
    {
        //FILE NAME OF THE XML FILE
        $filename = "smstemplate.xml";
        
        //GET PHYSICAL PATH OF XML FILE
        //$path = "D:/D_Drive/Xampp/htdocs/eyesonwheel/app/Vendor/";
        //$path = "/home/malgaadi/public_html/eyesonwheel.net/app/Vendor/";
        $path ='/var/www/html/apis/assets/smstemplate/';
        
         return $path.$filename;


    }
    
    /**
    Created By: Suvraditya
    Creation Date: 
    Description: Function for getting file content of the xml file
    Parameter: $file => full file name with its absolute path
    */
    private function getFileContent($file)
    {
        //GET FILE CONTENT AND RETURN
        
        if(file_exists($file))
        {
            $fileContent = file_get_contents($file);
            return $fileContent;
            
        }
        else
        {
           
            return "FNF";//FILE NOT FOUND
        }
    }
    
    /**
    Created By: Suvraditya
    Creation Date: 
    Description: Function for getting the node values of the xml created using xpath method
    */
    private function getNodeVal($mainnode,$node,$id,$val)
    {
        //GET FILE WITH PATH
        $file = $this->getFilePath();
        
        //GET FILE CONTENT
        $fileContent = $this->getFileContent($file);
       
        if($fileContent!="FNF")
        {


            $xml = "";
            $temp = file_get_contents($file);
            $xml = simplexml_load_string($temp);
            
           
            
            if(!empty($xml))
            {
                $nodes = $xml->xpath("//".$mainnode."/".$node."[@".$id."='".$val."']");
            }
            if(!empty($nodes)){
                foreach($nodes[0] as $key=>$val)
                {
                    return $val;
                }
            }

        }
        else
        {
            
            return "CCO"; //COULDN'T CREATE OBJECT
        }
    }
    
    private function get_string_between($string, $start, $end)
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

    private function getConstantName($str)
    {
        $msgArr = explode(" ",$str);
       
        $arr = array();
        foreach($msgArr as $msgArrval)
        {
            if (preg_match ('/[a-zA-Z0-9]/', $msgArrval))
            {
                $parsed = $this->get_string_between($msgArrval, "%", "%");
                if(ord($parsed)!=0)
                {
                    $arr[] = $parsed;
                }
            }
        }
        return $arr;
    }
    
    private function getTemplateMsg($cat)
    {
        //GETTING THE TEMPLATE MESSAGE WITH VALUES
        $mainnode = "sms";
        $node = "smstemplate";
        $id = "category";
        $val = $cat;
        
        $originalMsg = $this->getNodeVal($mainnode,$node,$id,$val);
        $arr = $this->getConstantName($originalMsg);
        
        foreach($arr as $arrVal)
        {
            $originalMsg = str_replace("%".$arrVal."%", $this->$arrVal, $originalMsg);
        }
       
       
        return $originalMsg;
    }
    
    private function createUrl($urlparam = 'URLPARAM')
    {
        
        $this->msgs = $this->getTemplateMsg($this->category);
        $mainnode = "sms";
        $node = "smstemplate";
        $id = "category";
        $this->user = $this->getNodeVal($mainnode,"smsconfiguration","id","USER");
        $this->pwd = $this->getNodeVal($mainnode,"smsconfiguration","id","PASSWORD");
        $this->sid = $this->getNodeVal($mainnode,"smsconfiguration","id","SID");
        
        $this->url = $this->getTemplateMsg($urlparam);
        
        $this->url = str_replace(":","&",$this->url);
        $this->url = trim($this->url);
       
        $this->url = str_replace(" ","",$this->url);
        $this->url = str_replace(" ","",$this->url);
        $this->url = str_replace("#msg#",$this->msgs,$this->url);
        
        $this->url = str_replace(" ","%20",$this->url);
        
        return $this->url;
    }
    
    private function curlExecution($Url)
    {
        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }
        
        $Url = "http://".$Url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        
        $output =  curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }
    
    public function sendSmsFinal()
    {
        //CREATING THE URL THAT WILL BE EXECUTED IN CURL
        $url = $this->createUrl();
        
        $m = $this->curlExecution($url);
        
        $response['raw_response'] = $m;
       
        date_default_timezone_set('Asia/Calcutta');
        $response['response_time'] = time();

        $total1 = explode(",",$m);
        $response['sms_unit'] = count($total1);
        $url1 = str_replace("%20"," ",$url);;

        $smsmsg = explode("msg=",$url1);
        $smsmsg2 = explode("&fl",$smsmsg[1]);
        $smsmsg1 = $smsmsg2[0];
        $smsmsg1 = str_replace("%20"," ",$smsmsg1);
        $response['original_message'] = $smsmsg1;

        $i = 0;
        foreach($total1 as $keysms=>$valsms)
        {
            $mobraw = array();
            $mobraw = explode("-",$valsms);
            $response['mobile'][$i] = $mobraw[0];
            $response['response_code'][$i] = $mobraw[1];
            $i++;
        }
        return $response;
    }
    
    public function fetchFromUrl(){
        $url = $this->createUrl('URLUNITBALANCE');
        $m = $this->curlExecution($url);
        return $m;
    }
}
?>