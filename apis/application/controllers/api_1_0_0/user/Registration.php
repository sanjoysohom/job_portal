<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
//error_reporting(E_ALL);
require_once('src/OAuth2/Autoloader.php');
require APPPATH . 'libraries/api/REST_Controller.php';


class Registration extends REST_Controller
{
    function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->config('rest');
        $this->load->config('serverconfig');
        $developer         = 'www.massoftind.com';
        $this->app_path    = "api_" . $this->config->item('test_api_ver');
        //publish app version
        $version           = str_replace('_', '.', $this->config->item('test_api_ver'));
        $this->publish     = array(
            'version' => $version,
            'developer' => $developer
        );
        //echo $_SERVER['SERVER_ADDR']; exit;
        $dsn               = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
        $dbusername        = $this->config->item('oauth_db_username');
        $dbpassword        = $this->config->item('oauth_db_password');
        $sitemode          = $this->config->item('site_mode');
        $this->path_detail = $this->config->item($sitemode);
        $this->load->model('api_' . $this->config->item('test_api_ver') . '/user_model', 'user_model');
        //$this->load->model('api_' . $this->config->item('test_api_ver') . '/profile_model', 'profile_model');
        $this->load->model('api_' . $this->config->item('test_api_ver') . '/lender/registration_model', 'registration');

        $this->load->model('api_' . $this->config->item('test_api_ver') . '/lender/lender_model', 'lender_model');
        
        $this->load->library('email');
          $this->load->library('encrypt');
        $this->push_type = 'P';
        //$this->load->library('mpdf');
        OAuth2\Autoloader::register();
        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage            = new OAuth2\Storage\Pdo(array(
            'dsn' => $dsn,
            'username' => $dbusername,
            'password' => $dbpassword
        ));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $this->oauth_server = new OAuth2\Server($storage);
        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    }

    public function userRegistration_post()
    {
       
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();

        if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) 
        {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        }
        else
        {
            $flag = true;
            $req_arr = array();

            if(empty($this->post('u_name',true))){
                $flag =false;
                $error_message = 'Name is required';

            }else{
                $req_arr['name'] = $this->post('u_name',true);
            }

            if(empty($this->post('u_add',true))){
                $flag = false;
                $error_message = 'Address is required';

            }else{
                $req_arr['address'] = $this->post('u_add',true);

            }

            if(empty($this->post('u_pass',true))){
                $flag = false;
                $error_message = 'Password is required';
            }else{
                $req_arr['password'] = password_hash($this->post('u_pass',true),PASSWORD_BCRYPT);
            }

            if(empty($this->post('u_email',true))){

                $falg = false;
                $error_message = 'Email is required';

            }else{
                $req_arr['email'] = $this->post('u_email',true);
            }

            if(empty($this->post('u_mob',true))){

                $req_arr['phone']="";

            }
            else
            {
                $req_arr['phone']=$this->post('u_mob',true);
            }

           if($flag)
           {

                $insertArr                  = array();
                $insertArr['name']          = $req_arr['name'];
                $insertArr['address']       = $req_arr['address'];
                $insertArr['email']         = $req_arr['email'];
                $insertArr['mobile']        = $req_arr['phone'];
                $insertArr['password']     = $req_arr['password'];
                $user_id = $this->registration->addUser($insertArr);
               
            }
            else
            {
                $http_response = 'http_response_unauthorized';

            }

        }

      json_response($req_arr,$http_response,$error_message,$success_message);
    }

    function token_post() {
         // Handle a request for an OAuth2.0 Access Token and send the response to the client
        $this->oauth_server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }

}



