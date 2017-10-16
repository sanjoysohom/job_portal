<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(0);
error_reporting(E_ALL);
require_once('src/OAuth2/Autoloader.php');
require APPPATH . 'libraries/api/REST_Controller.php';
class Profile extends REST_Controller
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
        $this->load->model('api_' . $this->config->item('test_api_ver') . '/lender/lender_model', 'lender_model');
        $this->load->model('api_' . $this->config->item('test_api_ver') . '/lender/Profile_model', 'profile');
        $this->load->model('api_' . $this->config->item('test_api_ver') . '/lender/lender_model', 'lender');
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
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getProfileDetails()
     * @ Added Date               : 10-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get Users Profile Details
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     
     */
    public function getProfileDetails_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag = true;
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                //pre($check_session,1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $req_arr            = array();
                    $req_arr['user_id'] = $plaintext_user_id;
                    $user_details       = $this->profile->fetchProfileDeatilsAll($req_arr['user_id']);
                    //pre($user_details,1);
                    $user_img           = array();
                    if (!empty($user_details) && count($user_details) > 0 && $user_details['is_active'] == 1) {
                        $tmpUserDtl = $this->profile->fetchTempProfileBasic($req_arr);
                        //pre($tmpUserDtl,1);

                                $extra['profession_type']                  = $this->user_model->getProfessionType();
                                $extra['gender']                  = $this->user_model->getGender();
                                $extra['marital_status']          = $this->profile->fetchMatrialStatus();
                                $extra['residence_status']        = $this->profile->fetchResidenceStatus();

                                $result_arr['extraDetails']       = $extra;



                        if (is_array($tmpUserDtl) && count($tmpUserDtl) > 0) {
                            $user_img['display_name'] = $tmpUserDtl['display_name'];
                            if ($tmpUserDtl['s3_media_version'] != '') {
                                $user_img['profile_image_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/profile/' . $req_arr['user_id'] . '.' . $tmpUserDtl['profile_picture_file_extension'] . '?versionId=' . $tmpUserDtl['s3_media_version'];
                            } else {
                                $user_img['profile_image_url'] = '';
                            }
                            $tmpUserDtl['profile_image_url'] = $user_img['profile_image_url'];
                            $extra['p_type']                 = $this->user_model->getProfessionType();
                            $extra['gender']                 = $this->user_model->getGender();
                            $extra['marital_status']         = $this->profile->fetchMatrialStatus();
                            $extra['residence_status']       = $this->profile->fetchResidenceStatus();
                            $result_arr['main_data']         = $tmpUserDtl;
                            $result_arr['extraDetails']      = $extra;
                            $http_response                   = 'http_response_ok';
                            $success_message                 = 'Profile fetched succefully';
                        } else {
                            $mainUserDtl = $this->profile->getMainUserBasicDetails($req_arr);
                            if (is_array($mainUserDtl) && count($mainUserDtl) > 0) {
                                $user_img['display_name'] = $mainUserDtl['display_name'];
                                if ($mainUserDtl['s3_media_version'] != '') {
                                    $user_img['profile_image_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/profile/' . $req_arr['user_id'] . '.' . $mainUserDtl['profile_picture_file_extension'] . '?versionId=' . $mainUserDtl['s3_media_version'];
                                } else {
                                    $user_img['profile_image_url'] = '';
                                }
                                $mainUserDtl['profile_image_url'] = $user_img['profile_image_url'];
                                $result_arr['main_data']          = $mainUserDtl;                
                                /*$extra['p_type']                  = $this->user_model->getProfessionType();
                                $extra['gender']                  = $this->user_model->getGender();
                                $extra['marital_status']          = $this->profile->fetchMatrialStatus();
                                $extra['residence_status']        = $this->profile->fetchResidenceStatus();

                                $result_arr['extraDetails']       = $extra;*/
                                //PRE( $result_arr,1);
                                
                                $http_response   = 'http_response_ok';
                                $success_message = 'Profile fetched succefully';
                            }
                        }
                    } else {
                        $http_response = 'http_response_bad_request';
                        $error_message = 'Invalid user or User Inactive ,Please verify your Email id and Mobile number';
                    }
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'You are not logged in. Please log in and try again';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : editProfileDetails()
     * @ Added Date               : 10-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Update Profile Deails
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     
     */
    public function editProfileDetails_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            //pre($this->post(),1);
            $flag                = true;
            $has_profile_picture = false;
            $req_arr             = $data = array();
            if (!$this->post('display_name') && $flag) {
                $flag          = false;
                $error_message = 'Display name can not be null';
            } else {
                $data['display_name'] = $this->post('display_name', TRUE);
            }
            if (!$this->post('f_name') && $flag) {
                $flag          = false;
                $error_message = 'First name can not be null';
            } else {
                if (!ctype_alpha($this->post('f_name'))) {
                    $flag          = false;
                    $error_message = 'First name should contain only alphabets';
                } else {
                    $data['f_name'] = $this->post('f_name', TRUE);
                }
            }
            $data['m_name'] = $this->post('m_name', TRUE);
            if ($this->post('m_name', TRUE) != '') {
                if (!ctype_alpha($this->post('m_name', TRUE))) {
                    $flag          = false;
                    $error_message = 'Middle name should be alphabets only';
                }
            }
            if (!$this->post('l_name') && $flag) {
                $flag          = false;
                $error_message = 'Last name can not be null';
            } else {
                if (!ctype_alpha($this->post('l_name'))) {
                    $flag          = false;
                    $error_message = 'Last name should contain only alphabets';
                } else {
                    $data['l_name'] = $this->post('l_name', TRUE);
                }
            }
            if (!$this->post('residence_street1') && $flag) {
                $flag          = false;
                $error_message = 'Residence street1 can not be null';
            } else {
                $data['residence_street1'] = $this->post('residence_street1', TRUE);
            }
            if ($this->post('residence_street2')) {
                $data['residence_street2'] = $this->post('residence_street2', TRUE);
            } else {
                $data['residence_street2'] = '';
            }
            if ($this->post('residence_street3')) {
                $data['residence_street3'] = $this->post('residence_street3', TRUE);
            } else {
                $data['residence_street3'] = '';
            }
            if (!$this->post('residence_zipcode') && $flag) {
                $flag          = false;
                $error_message = 'Residence pincode can not be null';
            } else {
                $data['residence_zipcode'] = $this->post('residence_zipcode', TRUE);
            }
            if (!$this->post('residence_city') && $flag) {
                $flag          = false;
                $error_message = 'Residence city can not be null';
            } else {
                $data['residence_city'] = $this->post('residence_city', TRUE);
            }
            if (!$this->post('residence_state') && $flag) {
                $flag          = false;
                $error_message = 'Residence state can not be null';
            } else {
                $data['residence_state'] = $this->post('residence_state', TRUE);
            }
            if (!$this->post('permanent_street1') && $flag) {
                $flag          = false;
                $error_message = 'Permanent street1 can not be null';
            } else {
                $data['permanent_street1'] = $this->post('permanent_street1', TRUE);
            }
            if ($this->post('permanent_street2')) {
                $data['permanent_street2'] = $this->post('permanent_street2', TRUE);
            } else {
                $data['permanent_street2'] = '';
            }
            if ($this->post('permanent_street3')) {
                $data['permanent_street3'] = $this->post('permanent_street3', TRUE);
            } else {
                $data['permanent_street3'] = '';
            }
            if (!$this->post('permanent_zipcode') && $flag) {
                $flag          = false;
                $error_message = 'Permanent pincode can not be null';
            } else {
                if (!intval($this->post('permanent_zipcode'))) {
                    $flag          = false;
                    $error_message = 'Permanent pincode should contain only numbers';
                } else {
                    $data['permanent_zipcode'] = $this->post('permanent_zipcode', TRUE);
                }
               /* if (!$this->post('permanent_post_office') && $flag) {
                    $flag          = false;
                    $error_message = 'Permanent pincode not be null';
                } else {
                    $data['permanent_post_office'] = $this->post('permanent_post_office', TRUE);
                }*/
            }
            if (!$this->post('permanent_city') && $flag) {
                $flag          = false;
                $error_message = 'Permanent city can not be null';
            } else {
                $data['permanent_city'] = $this->post('permanent_city', TRUE);
            }
            /*if (!$this->post('permanent_district') && $flag) {
                $flag          = false;
                $error_message = 'Permanent district can not be null';
            } else {
                $data['permanent_district'] = $this->post('permanent_district', TRUE);
            }*/
            if (!$this->post('permanent_state') && $flag) {
                $flag          = false;
                $error_message = 'Permanent state can not be null';
            } else {
                $data['permanent_state'] = $this->post('permanent_state', TRUE);
            }
            if (!$this->post('fk_profession_type_id') && $flag) {
                $flag          = false;
                $error_message = 'Profession type can not be null';
            } else {
                $data['fk_profession_type_id'] = $this->post('fk_profession_type_id', TRUE);
            }
            if (!$this->post('date_of_birth') && $flag) {
                $flag          = false;
                $error_message = 'DOB can not be null';
            } else {
                //$dob                   = $this->post('date_of_birth', TRUE);
                //$data['date_of_birth'] = getDatabaseDate($dob);
                $data['date_of_birth'] = $this->post('date_of_birth', TRUE);
            }
            if (!$this->post('fathers_name') && $flag) {
                $flag          = false;
                $error_message = 'father name can not be null';
            } else {
                $data['fathers_name'] = $this->post('fathers_name', TRUE);
            }
            if (!$this->post('fk_gender_id') && $flag) {
                $flag          = false;
                $error_message = 'Gender can not be null';
            } else {
                $data['fk_gender_id'] = $this->post('fk_gender_id', TRUE);
            }
            if (!$this->post('fk_marital_status_id') && $flag) {
                $flag          = false;
                $error_message = 'Marital status can not be null';
            } else {
                $data['fk_marital_status_id'] = $this->post('fk_marital_status_id', TRUE);
            }
            if (!$this->post('fk_residence_status_id') && $flag) {
                $error_message = 'Resedence status can not be null';
                $flag          = false;
            } else {
                $data['fk_residence_status_id'] = $this->post('fk_residence_status_id', TRUE);
            }
            //pre($data,1);
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                //pre($check_session,1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $data['fk_user_id']           = $req_arr1['user_id'];
                    $req_arr['user_id']           = $req_arr1['user_id'];
                    $addTempProfileBasic_id       = 0;
                    $profile_picture_file_url     = '';
                    //$action_status            = true;
                    //  GET DATA FROM TEMP PROFILE BASIC TABLE               
                    $tempProfileBasic_details     = $this->profile->fetchTempProfileBasic($req_arr);
                    //$profile_picture_file_url     = '';
                    //pre( $tempProfileBasic_details,1);       
                    $user_BasicProfileDetails_arr = $this->profile->getBasicProfileDetailsFromMain($data);
                    //pre($user_BasicProfileDetails_arr,1);
                    //  COMPARE WITH PREVIOUS VALUE
                    if (!empty($user_BasicProfileDetails_arr) && count($user_BasicProfileDetails_arr) > 0) {
                        $profile_name_arr          = array(
                            'display_name',
                            'f_name',
                            'm_name',
                            'l_name'
                        );
                        $residence_address_arr     = array(
                            'residence_street1',
                            'residence_street2',
                            'residence_street3',
                            'residence_zipcode',
                            'residence_city',
                            'residence_state'
                        );
                        $permanent_address_arr     = array(
                            'permanent_street1',
                            'permanent_street2',
                            'permanent_street3',
                            'permanent_zipcode',
                            'permanent_city',
                            'permanent_state'
                        );
                        $other_info_arr            = array(
                            'fk_profession_type_id',
                            'date_of_birth',
                            'fathers_name',
                            'fk_gender_id',
                            'fk_marital_status_id',
                            'fk_residence_status_id'
                        );
                        $admin_status_profile_name = $admin_status_residence_address = $admin_status_permanent_address = $admin_status_other_info = true;
                        foreach ($data as $key => $value) {
                            if (in_array($key, $profile_name_arr) && $admin_status_profile_name) {
                                if ($data[$key] != $user_BasicProfileDetails_arr[$key]) {
                                    $admin_status_profile_name = false;
                                }
                            } else if (in_array($key, $residence_address_arr) && $admin_status_residence_address) {
                                if ($data[$key] != $user_BasicProfileDetails_arr[$key]) {
                                    $admin_status_residence_address = false;
                                }
                            } else if (in_array($key, $permanent_address_arr) && $admin_status_permanent_address) {
                                if ($data[$key] != $user_BasicProfileDetails_arr[$key]) {
                                    $admin_status_permanent_address = false;
                                }
                            } else if (in_array($key, $other_info_arr) && $admin_status_other_info) {
                                if ($data[$key] != $user_BasicProfileDetails_arr[$key]) {
                                    $admin_status_other_info = false;
                                }
                            }
                        }
                        if (!$admin_status_profile_name) {
                            $data['admin_status_profile_name'] = 'P';
                        }
                        if (!$admin_status_residence_address) {
                            $data['admin_status_residence_address'] = 'P';
                        }
                        if (!$admin_status_permanent_address) {
                            $data['admin_status_permanent_address'] = 'P';
                        }
                        if (!$admin_status_other_info) {
                            $data['admin_status_other_info'] = 'P';
                        }
                        // pre($data,1);
                    } else { // IF NOT EXIST IN BASIC PROFILE DETAILS MAIN 
                        $data['admin_status_profile_name']      = 'P';
                        $data['admin_status_residence_address'] = 'P';
                        $data['admin_status_permanent_address'] = 'P';
                        $data['admin_status_other_info']        = 'P';
                    }
                    //  AVAILABLE DATA FOR ADMIN APPROVAL
                    if (!empty($tempProfileBasic_details) && count($tempProfileBasic_details) > 0) {
                        $data['id'] = $tempProfileBasic_details['id'];
                        // pre($data, 1);
                        //profile_model out
                        $this->profile->updateTempProfileBasic($data);
                        $addTempProfileBasic_id = $tempProfileBasic_details['id'];
                    } else { // NOT AVAILABLE ANY DATA FOR APPROVAL
                        if (is_array($user_BasicProfileDetails_arr) && count($user_BasicProfileDetails_arr) > 0) {
                            $data['fk_profile_basic_id'] = $user_BasicProfileDetails_arr['id'];
                            //$data['id']                  = $user_BasicProfileDetails_arr['id'];
                            if ($user_BasicProfileDetails_arr['profile_picture_file_extension'] != '') {
                                $data['profile_picture_file_extension'] = $user_BasicProfileDetails_arr['profile_picture_file_extension'];
                                $data['s3_media_version']               = $user_BasicProfileDetails_arr['s3_media_version'];
                                $data['has_profile_picture']            = '1';
                            }
                            $addTempProfileBasic_id = $this->profile->addTempProfileBasic($data);
                        } else {
                            $addTempProfileBasic_id = $this->profile->addTempProfileBasic($data);
                        }
                    }

                    if (!empty($_FILES['file']['name'])) {
                        
                        list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
                        $img_name_path           = strtolower($_FILES['file']["name"]);
                        $imgerr                  = pathinfo($img_name_path, PATHINFO_EXTENSION);
                        $imgerr                  = 'jpg';
                        $config['upload_path']   = $this->config->item('temp_upload_file_path');
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $strtotm                 = strtotime(date('Y-m-d H:i:s'));
                        $filename                = $data['fk_user_id'] . '_' . $strtotm;
                        $config['file_name']     = $filename . '.' . $imgerr;
                        $this->load->library('upload');
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('file')) {
                            $dataIMG                 = array();
                            $dataIMG                 = $this->upload->data();
                            $aws_target_file_path    = 'resources/' . $data['fk_user_id'] . '/profile/' . $data['fk_user_id'] . '.' . $imgerr;
                            $aws_temp_name           = $this->config->item('temp_upload_file_path') . $dataIMG['file_name'];
                            $response                = $this->aws->uploadfile($this->aws->bucket, $aws_target_file_path, $aws_temp_name, 'public-read');
                            $res['s3_media_version'] = $response['VersionId'];
                            if ($res['s3_media_version'] != '') {
                                $profile_picture_file_url              = $this->config->item('bucket_url') . $data['fk_user_id'] . '/profile/' . $data['fk_user_id'] . '.' . $imgerr . '?versionId=' . $res['s3_media_version'];
                                $res['has_profile_picture ']           = '1';
                                $res['profile_picture_file_extension'] = $imgerr;
                                $res['s3_media_version']               = $res['s3_media_version'];
                                $this->profile->updateProfileBasic($res, $data['fk_user_id']);

                                unlink($aws_temp_name);
                            }
                        } else {
                            $http_response = 'http_response_ok';
                            $error_message = $this->upload->display_errors();
                            //$error_message = 'File Type is not supported! Only GIF,JPG,PNG file can upoload';
                        }
                    }
                    if ($addTempProfileBasic_id > 0) {
                        $result_arr['dataset'] = array(
                            'id' => $addTempProfileBasic_id,
                            'user_id' => $req_arr1['user_id']
                        );
                        if ($error_message == 'File Type is not supported! Only GIF,JPG,PNG file can upoload') {
                            $http_response = 'http_response_bad_request';
                        } else {
                            $http_response = 'http_response_ok';
                        }
                        $success_message = 'Successfully Updated';
                    } else {
                        $http_response = 'http_response_bad_request';
                        $error_message = 'Something went wrong';
                    }
                    /*} else {
                    $http_response = 'http_response_bad_request';
                    }*/
                } else {
                    $http_response = 'http_response_bad_request';
                    $error_message = 'Invalid Login';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchAllBank()
     * @ Added Date               : 15-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get all User Bank details
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     
     */
    public function getAllBank_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag = true;
            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                //pre($check_session,1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $req_arr            = array();
                    $req_arr['user_id'] = $plaintext_user_id;
                    $data               = array();
                    $bank_list_tmp      = $this->profile->getAllBankTmp($req_arr);
                    $i                  = 0;
                    if (is_array($bank_list_tmp) && count($bank_list_tmp) > 0) {
                        foreach ($bank_list_tmp as $bank_tmp) {
                            $data[$i]['id']             = $bank_tmp['id'];
                            $data[$i]['fk_user_id']     = $bank_tmp['fk_user_id'];
                            $data[$i]['fk_bank_id']     = $bank_tmp['fk_bank_id'];
                            $ifsc_bank_details          = $this->profile->fetchIfscBankDetails($bank_tmp['fk_bank_id']);
                            $data[$i]['bank_name']      = $ifsc_bank_details['bank_name'];
                            $data[$i]['ifsc_code']      = $ifsc_bank_details['ifsc_code'];
                            $data[$i]['bank_branch']    = $ifsc_bank_details['bank_branch'];
                            $data[$i]['bank_city']      = $ifsc_bank_details['bank_city'];
                            $data[$i]['bank_state']     = $ifsc_bank_details['bank_state'];
                            $data[$i]['account_number'] = $bank_tmp['account_number'];
                            $data[$i]['bank_status']    = $bank_tmp['bank_status'];
                            $data[$i]['is_primary']     = $bank_tmp['is_primary'];
                            $data[$i]['admin_message_bank'] = $bank_tmp['admin_message_bank'];
                            $i++;
                        }
                    }
                    $bank_list = $this->profile->getAllBank($req_arr);
                    //pre($bank_list,1);                   
                    if (is_array($bank_list) && count($bank_list) > 0) {
                        foreach ($bank_list as $banks) {
                            $is_in_tmp = $this->profile->iSInTmpTableBank($banks);
                            //PRE($is_in_tmp,1);
                            if ($is_in_tmp == 0) {
                                $data[$i]['id']             = $banks['id'];
                                $data[$i]['fk_user_id']     = $banks['fk_user_id'];
                                $data[$i]['fk_bank_id']     = $banks['fk_bank_id'];
                                $ifsc_bank_details          = $this->profile->fetchIfscBankDetails($banks['fk_bank_id']);
                                $data[$i]['ifsc_code']      = $ifsc_bank_details['ifsc_code'];
                                $data[$i]['bank_name']      = $ifsc_bank_details['bank_name'];
                                $data[$i]['bank_branch']    = $ifsc_bank_details['bank_branch'];
                                $data[$i]['bank_city']      = $ifsc_bank_details['bank_city'];
                                $data[$i]['bank_state']     = $ifsc_bank_details['bank_state'];
                                $data[$i]['account_number'] = $banks['account_number'];
                                $data[$i]['bank_status']    = 'A';
                                $data[$i]['is_primary']     = $banks['is_primary'];
                                                                
                                $data[$i]['admin_message_bank'] = '';
                                $i++;
                            }
                        }
                    }
                    //pre($data,1);
                    $raws['bank_details']  = $data;
                    $raws['bank_count']    = count($data);
                    $result_arr['dataset'] = $raws;
                    $http_response         = 'http_response_ok';
                    $success_message       = '';
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'You are not logged in. Please log in and try again';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addEditBank()
     * @ Added Date               : 15-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Add bank in User Profile
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    public function addEditBank_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            if (!$this->post('fk_bank_id')) {
                $flag = false;
            } else {
                
                $data['fk_bank_id'] = $req_arr['fk_bank_id'] = $this->post('fk_bank_id', TRUE);
            }
            if (!$this->post('account_number')) {
                $flag = false;
            } else {
                $req_arr['account_number'] = $this->post('account_number', TRUE);
                $data['account_number']    = $req_arr['account_number'];
            }
            /*$req_arr['is_primary'] = $this->post('is_primary', TRUE);
            if ($req_arr['is_primary'] == 'Y') {
            $data['is_primary'] = $req_arr['is_primary'];
            } else {
            $data['is_primary'] = 'N';
            }*/
            $req_arr['bank_id'] = $this->post('id', TRUE);
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                //PRE($req_arr1,1);
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $data['fk_user_id']        = $req_arr1['user_id'];
                    $data['addition_datetime'] = date("Y-m-d H:i:s");
                    $data['bank_status'] =  'P';
                    if ($req_arr['bank_id'] > 0) {
                        //update
                        // pre($req_arr,1);
                        $bank_details_tmp = $this->profile->getBankTmp($req_arr['bank_id']);
                        if (is_array($bank_details_tmp) && count($bank_details_tmp) > 0) {
                            $data['id'] = $req_arr['bank_id'];
                            $this->profile->updateBank($data);
                            $http_response   = 'http_response_ok';
                            $success_message = '';
                        } else {
                            $bank_details = $this->profile->getBank($req_arr['bank_id']);
                            if (is_array($bank_details) && count($bank_details) > 0) {
                                $data['fk_user_id']         = $req_arr1['user_id'];
                                //$data['id']                 = $req_arr['bank_id'];
                                $data['fk_profile_bank_id'] = $req_arr['bank_id'];
                                $this->profile->addBankTmp($data);
                                $http_response   = 'http_response_ok';
                                $success_message = '';
                            } else {
                                $http_response = 'http_response_bad_request';
                                $error_message = 'Wrong id';
                            }
                        }
                    } else {
                        //pre($data,1);
                        if ($req_arr['bank_id'] < 1) {
                            
                            
                            $bank_tmp  = $this->profile->checkifBankAddedTmp($data['fk_user_id']);
                            $bank_main = $this->profile->checkifBankAddedMain($data['fk_user_id']);
                            if ((empty($bank_main)) && (empty($bank_tmp))) {
                                $data['is_primary'] = "Y";
                            } else {
                                $data['is_primary'] = "N";
                            }
                            // pre($data, 1);
                            //pre($bank_details,1);
                            $bank_id            = $this->profile->addBankTmp($data);
                            $req_arr['bank_id'] = $bank_id;
                            $http_response      = 'http_response_ok';
                            $success_message    = 'Added succefully';
                        } else {
                            $http_response = 'http_response_bad_request';
                            $error_message = 'Something went wrong! Plesae try again';
                        }
                    }
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Invalid user details';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    
    
    
    public function setAsPrimary_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
            
        } else {
            
            $flag    = true;
            $req_arr = array();
            
            if (!$this->post('bank_id')) {
                $flag = false;
            } else {
                $req_arr['bank_id'] = $this->post('bank_id', TRUE);
                
            }
            
            if ($flag) {
                $plaintext_user_pass_key  = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id        = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr['user_id']       = $data['fk_user_id']    = $plaintext_user_id;
                $check_session            = $this->lender->checkSessionExist($req_arr);
                if (!empty($check_session) && count($check_session) > 0) {
                    if ($req_arr['bank_id'] > 0) {
                        //pre($req_arr,1);
                        $this->profile->setAsPrimaryNo($req_arr);
                        $this->profile->setAsPrimaryYes($req_arr);
                        
                        $http_response   = 'http_response_ok';
                        $success_message = 'Updated Successfully';
                    } else {
                        $http_response = 'http_response_bad_request';
                        $error_message = 'Something went wrong! Plesae try again';
                    }
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Invalid user details';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getIFSCDetails()
     * @ Added Date               : 14-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get Bank Details from Ifsc code
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    public function getIFSCDetails_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            if (!$this->post('ifsc_code')) {
                $flag = false;
            } else {
                $req_arr['ifsc_code'] = $this->post('ifsc_code', TRUE);
            }
            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $details = array();
                    $details = $this->profile->fetchIfscDetails($req_arr);
                    if(is_array($details) && count($details) > 0) {
                        $http_response         = 'http_response_ok';
                        $result_arr             = $details;
                    } else {

                        $error_message      = 'NO DATA FOUND, PLEASE TRY WITH DIFFERENT IFSC CODE';
                        $http_response      = 'http_response_bad_request';
                    }

                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Wrong username or Password';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    public function getPincodeData_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            if (!$this->post('pin_code')) {
                $flag = false;
            } else {
                $req_arr['pin_code'] = $this->post('pin_code', TRUE);
            }
            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $details = $this->profile->fetchPinCode($req_arr);
                    
                    if (is_array($details) && count($details) > 0) {
                        $http_response = 'http_response_ok';
                        $result_arr    = $details;
                        
                    } else {
                        
                        $error_message = 'NO DATA FOUND, PLEASE TRY WITH DIFFERENT PINCODE';
                        $http_response = 'http_response_bad_request';
                    }
                    //$result_arr  = $details;
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'You are not logged in';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getBankDetails()
     * @ Added Date               : 15-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get single Bank Details
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    public function getBankDetails_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            /*$req_arr = array();
            if (!$this->post('bank_id')) {
                $flag = false;
            } else {
                $req_arr['bank_id'] = $this->post('bank_id', TRUE);
            }*/
            $req_arr['bank_id'] = $this->post('bank_id', TRUE);

            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);

                if (!empty($check_session) && count($check_session) > 0) {
                    $bank_details = $data = array();
                    $bank_details = $this->profile->getBankTmpDetails($req_arr['bank_id']);

                    // pre($bank_details,1);
                    if (is_array($bank_details) && count($bank_details) > 0) {
                        $data['id']                 = $bank_details['id'];
                        $data['fk_user_id']         = $bank_details['fk_user_id'];
                        $data['fk_bank_id']         = $bank_details['fk_bank_id'];
                        
                        $ifsc_bank_details          = $this->profile->fetchIfscBankDetails($bank_details['fk_bank_id']);
                        // pre($ifsc_bank_details,1);
                        $data['ifsc_code']          = $ifsc_bank_details['ifsc_code'];
                        $data['bank_name']          = $ifsc_bank_details['bank_name'];
                        $data['bank_branch']        = $ifsc_bank_details['bank_branch'];
                        $data['bank_city']          = $ifsc_bank_details['bank_city'];
                        $data['account_number']     = $bank_details['account_number'];
                        $data['bank_state']         = $ifsc_bank_details['bank_state'];

                        $data['bank_status'] = (array_key_exists('bank_status', $bank_details)) ? $bank_details['bank_status'] : 'A';                     
                    } 

                    $result_arr             = $data;
                    $http_response          = 'http_response_ok';

                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'You are not logged in';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }



    public function getPrimaryBank_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);

                if (!empty($check_session) && count($check_session) > 0) {
                    $bank_details = $data = array();
                    $bank_details = $this->profile->getPrimaryBank($req_arr1);
                    //pre($bank_details,1);

                    if (is_array($bank_details) && count($bank_details) > 0) {
                        $data['id']                 = $bank_details['id'];
                        $data['fk_user_id']         = $bank_details['fk_user_id'];
                        $data['fk_bank_id']         = $bank_details['fk_bank_id'];
                        
                        $ifsc_bank_details          = $this->profile->fetchIfscBankDetails($bank_details['fk_bank_id']);
                         //pre($ifsc_bank_details,1);
                        $data['ifsc_code']          = $ifsc_bank_details['ifsc_code'];
                        $data['bank_name']          = $ifsc_bank_details['bank_name'];
                        $data['bank_branch']        = $ifsc_bank_details['bank_branch'];
                        $data['bank_city']          = $ifsc_bank_details['bank_city'];
                        $data['account_number']     = $bank_details['account_number'];

                        $data['bank_status'] = (array_key_exists('bank_status', $bank_details)) ? $bank_details['bank_status'] : 'A';                     
                    } 

                   // pre($data,1);

                    $result_arr             = $data;
                    $http_response          = 'http_response_ok';

                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'You are not logged in';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getKycDocuments()
     * @ Added Date               : 14-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get Documents and Template details
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    /*public function getKycDocuments_post()
    {
        $error_message = $success_message = $http_response = '';
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            $raws    = array();
            //log in status checking
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                if (!empty($check_session) && count($check_session) > 0) {
                    
                    //$req_arr['user_mode'] = 'L';
                    $req_arr                         = $this->profile->getDocDetails();
                    $data['kyc_docs']                = $this->profile->getKycDocuments($req_arr);
                    $data['kyc_docs_mandatory']      = $this->profile->getTotalKycDocumentsMandatory($req_arr);
                    $data['kyc_docs_mandatory_data'] = $this->profile->getKycDocumentsMandatory($req_arr);
                    $data['kyc_docs_any']            = $this->profile->getTotalKycDocumentsAny($req_arr);
                    $data['kyc_docs_any_data']       = $this->profile->getKycDocumentsAny($req_arr);
                    $raws['dataset']                 = $data;
                    $result_arr                      = $raws;
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Invalid user details';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }*/
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addEditKYC()
     * @ Added Date               : 15-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Add Kyc Details
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    public function addEditKYC_post()
    {
        //pre($this->post(),1);
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        $raws          = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            if (!$this->post('fk_kyc_template_id')) {
                $flag          = false;
                $error_message = 'Please enter template id';
            } else {
                $data['fk_kyc_template_id'] = $req_arr['fk_kyc_template_id'] = $this->post('fk_kyc_template_id', TRUE);
            }
            //type can be front/back
            //$req_arr['type']   = $this->post('type', TRUE);
            $data['kyc_data']  = $req_arr['kyc_data'] = $this->post('kyc_data', TRUE);
            $req_arr['kyc_id'] = $this->post('id', TRUE);
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $req_arr['user_id'] = $plaintext_user_id;
                    $data['fk_user_id'] = $plaintext_user_id;
                    if ($req_arr['kyc_id'] > 0) {
                        
                        $kyc_details_tmp = $this->profile->getKycTmp($req_arr['kyc_id']);
                        //pre($kyc_details_tmp,1);
                        if (is_array($kyc_details_tmp) && count($kyc_details_tmp) > 0) {
                            $whereId['id'] = $req_arr['kyc_id'];
                            $this->profile->updateKyc($data, $whereId['id']);
                            $http_response   = 'http_response_ok';
                            $success_message = '';
                        } else {
                            $kyc_details = $this->profile->getKyc($req_arr['kyc_id']);
                            if (is_array($kyc_details) && count($kyc_details) > 0) {
                                $data['fk_profile_kyc_id']      = $req_arr['kyc_id'];
                                //$data['id']                     = $req_arr['kyc_id'];
                                $data['front_file_name']        = $kyc_details['front_file_name'];
                                $data['front_s3_media_version'] = $kyc_details['front_s3_media_version'];
                                $data['back_file_name']         = $kyc_details['back_file_name'];
                                $data['back_s3_media_version']  = $kyc_details['back_s3_media_version'];
                                //pre($data,1);
                                $this->profile->addKyc($data);
                                $http_response   = 'http_response_ok';
                                $success_message = 'new request added';
                            } else {
                                $http_response = 'http_response_bad_request';
                                $error_message = 'Wrong kyc id';
                            }
                        }
                    } else {
                        //add
                        if ($req_arr['kyc_id'] < 1) {
                            $kyc_arr           = $this->profile->addKyc($data);
                            $req_arr['kyc_id'] = $kyc_arr;
                            $http_response     = 'http_response_ok';
                            $success_message   = '';
                            
                            
                        } else {
                            $http_response = 'http_response_bad_request';
                            $error_message = 'Something went wrong! Plesae try again';
                        }
                    }
                    //PRE($req_arr,1);
                    
                    if ($req_arr['kyc_id'] > 0) {
                        // image upload 
                        if (!empty($_FILES['front_file']['name'])){
                            $raws['dataset']['front_tmp_name'] = $_FILES['front_file']['tmp_name'];
                            list($width, $height, $type, $attr) = getimagesize($_FILES['front_file']['tmp_name']);
                            
                            $img_name_path = strtolower($_FILES['front_file']["name"]);
                            
                            //$imgerr                  = pathinfo($img_name_path, PATHINFO_EXTENSION);
                            $config['upload_path']   = $this->config->item('temp_upload_file_path');
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            //$filename                = strtotime(date('Y-m-d H:i:s'));
                            $config['file_name']     = $img_name_path;
                            
                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload('front_file')) {
                                $dataIMG              = array();
                                $dataIMG              = $this->upload->data();
                                //$aws_target_file_path = 'resources/' . $req_arr['user_id'] . '/kyc/' . $req_arr['kyc_id'] . '_front.' . $imgerr;
                                $aws_target_file_path = 'resources/' . $req_arr['user_id'] . '/kyc/' . $config['file_name'];
                                $aws_temp_name        = $this->config->item('temp_upload_file_path') . $dataIMG['file_name'];
                                $response             = $this->aws->uploadfile($this->aws->bucket, $aws_target_file_path, $aws_temp_name, 'public-read');
                                $s3_media_version     = $response['VersionId'];
                                if ($s3_media_version != '') {
                                    $res['is_front_file_uploaded'] = '1';
                                    $res['front_file_name']        = $config['file_name'];
                                    $res['front_s3_media_version'] = $s3_media_version;
                                    //pre($res,1);
                                    $this->profile->updateKycTmp($res, $req_arr['kyc_id']);
                                    unlink($aws_temp_name);
                                }
                            }
                        }
                        if (!empty($_FILES['back_file']['name'])) {
                            $raws['dataset']['back_tmp_name'] = $_FILES['back_file']['tmp_name'];
                            list($width, $height, $type, $attr) = getimagesize($_FILES['back_file']['tmp_name']);
                            $img_name_path           = strtolower($_FILES['back_file']["name"]);
                            //$imgerr                  = pathinfo($img_name_path, PATHINFO_EXTENSION);
                            $config['upload_path']   = $this->config->item('temp_upload_file_path');
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            //$filename                = strtotime(date('Y-m-d H:i:s'));
                            $config['file_name']     = $img_name_path;
                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            //pre('back_file',1);
                            if ($this->upload->do_upload('back_file')) {
                                $dataIMG              = array();
                                $dataIMG              = $this->upload->data();
                                // $aws_target_file_path = 'resources/' . $req_arr['user_id'] . '/kyc/' . $req_arr['kyc_id'] . '_back.' . $imgerr;
                                $aws_target_file_path = 'resources/' . $req_arr['user_id'] . '/kyc/' . $config['file_name'];
                                $aws_temp_name        = $this->config->item('temp_upload_file_path') . $dataIMG['file_name'];
                                $response             = $this->aws->uploadfile($this->aws->bucket, $aws_target_file_path, $aws_temp_name, 'public-read');
                                $s3_media_version     = $response['VersionId'];
                                if ($s3_media_version != '') {
                                    $res['is_back_file_uploaded'] = '1';
                                    $res['back_file_name']        = $config['file_name'];
                                    $res['back_s3_media_version'] = $s3_media_version;
                                    //pre($res,1);
                                    $this->profile->updateKycTmp($res, $req_arr['kyc_id']);
                                    unlink($aws_temp_name);
                                }
                            }
                            //echo $this->upload->display_errors();die;
                        }
                        $raws['dataset']['kyc_id'] = $req_arr['kyc_id'];
                        $result_arr                = $raws;
                    }
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Invalid user details';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchAllKyc()
     * @ Added Date               : 15-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get all kyc List
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    public function getAllKYC_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        $raws          = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                //pre($check_session,1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $req_arr            = $data = array();
                    $req_arr['user_id'] = $plaintext_user_id;
                    $kyc_list_tmp       = $this->profile->getAllKycTmp($req_arr);
                    $i                  = 0;
                    
                    // pre($kyc_list_tmp, 1);
                    if (is_array($kyc_list_tmp) && count($kyc_list_tmp) > 0) {
                        foreach ($kyc_list_tmp as $kyc_tmp) {
                            $data[$i]['id']                     = $kyc_tmp['id'];
                            $data[$i]['fk_user_id']             = $kyc_tmp['fk_user_id'];
                            $data[$i]['fk_kyc_template_id']     = $kyc_tmp['fk_kyc_template_id'];
                            $template_details                   = $this->profile->getTemplateDetails($kyc_tmp['fk_kyc_template_id']);
                            $data[$i]['document_name']          = $template_details['document_name'];
                            $data[$i]['document_type']          = $template_details['document_type'];
                            $data[$i]['kyc_data']               = $kyc_tmp['kyc_data'];
                            $data[$i]['front_file_name']        = $kyc_tmp['front_file_name'];
                            $data[$i]['front_s3_media_version'] = $kyc_tmp['front_s3_media_version'];
                            $data[$i]['back_file_name']         = $kyc_tmp['back_file_name'];
                            $data[$i]['back_s3_media_version']  = $kyc_tmp['back_s3_media_version'];
                            $data[$i]['is_front_file_uploaded'] = $kyc_tmp['is_front_file_uploaded'];
                            $data[$i]['is_back_file_uploaded']  = $kyc_tmp['is_back_file_uploaded'];
                            $data[$i]['kyc_status']             = $kyc_tmp['kyc_status'];
                            $data[$i]['admin_message_kyc']      = $kyc_tmp['admin_message_kyc'];
                            if ($kyc_tmp['front_s3_media_version'] != '') {
                                //$data[$i]['front_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc_tmp['id'] . '_front.' . $kyc_tmp['front_file_name'] . '?versionId=' . $kyc_tmp['front_s3_media_version'];
                                $data[$i]['front_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc_tmp['front_file_name'] . '?versionId=' . $kyc_tmp['front_s3_media_version'];
                            } else {
                                $data[$i]['front_img_url'] = '';
                            }
                            if ($kyc_tmp['back_s3_media_version'] != '') {
                                //$data[$i]['back_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc_tmp['id'] . '_back.' . $kyc_tmp['back_file_name'] . '?versionId=' . $kyc_tmp['back_s3_media_version'];
                                
                                $data[$i]['back_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc_tmp['back_file_name'] . '?versionId=' . $kyc_tmp['back_s3_media_version'];
                            } else {
                                $data[$i]['back_img_url'] = '';
                            }
                            $i++;
                        }
                    }
                    $kyc_list = $this->profile->getAllKyc($req_arr);
                    
                    //pre($kyc_list,1);
                    
                    if (is_array($kyc_list) && count($kyc_list) > 0) {
                        foreach ($kyc_list as $kyc) {
                            $is_in_tmp = $this->profile->iSInTmpKyc($kyc);
                            
                            //pre($is_in_tmp,1);
                            if ($is_in_tmp == 0) {
                                $data[$i]['id']                     = $kyc['id'];
                                $data[$i]['fk_user_id']             = $kyc['fk_user_id'];
                                $data[$i]['fk_kyc_template_id']     = $kyc['fk_kyc_template_id'];
                                $template_details                   = $this->profile->getTemplateDetails($kyc['fk_kyc_template_id']);
                                $data[$i]['document_name']          = $template_details['document_name'];
                                $data[$i]['document_type']          = $template_details['document_type'];
                                $data[$i]['kyc_data']               = $kyc['kyc_data'];
                                $data[$i]['front_file_name']        = $kyc['front_file_name'];
                                $data[$i]['front_s3_media_version'] = $kyc['front_s3_media_version'];
                                $data[$i]['back_file_name']         = $kyc['back_file_name'];
                                $data[$i]['back_s3_media_version']  = $kyc['back_s3_media_version'];
                                $data[$i]['is_front_file_uploaded'] = '1';
                                $data[$i]['is_back_file_uploaded']  = '1';
                                $data[$i]['kyc_status']             = 'A';
                                $data[$i]['admin_message_kyc']      = '';
                                if ($kyc['front_s3_media_version'] != '') {
                                    //$data[$i]['front_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc['id'] . '_front.' . $kyc['front_file_name'] . '?versionId=' . $kyc['front_s3_media_version'];
                                    
                                    $data[$i]['front_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc['front_file_name'] . '?versionId=' . $kyc['front_s3_media_version'];
                                } else {
                                    $data[$i]['front_img_url'] = '';
                                }
                                if ($kyc['back_s3_media_version'] != '') {
                                    
                                    //$data[$i]['back_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc['id'] . '_back.' . $kyc['back_file_name'] . '?versionId=' . $kyc['back_s3_media_version'];
                                    $data[$i]['back_img_url'] = $this->config->item('bucket_url') . $req_arr['user_id'] . '/kyc/' . $kyc['back_file_name'] . '?versionId=' . $kyc['back_s3_media_version'];
                                } else {
                                    $data[$i]['back_img_url'] = '';
                                }
                                $i++;
                            }
                        }
                    }
                    /*$raws['is_mandatory_add'] = '0';                    
                    if (is_array($data) && count($data) == 1) {
                    $req_arr['document_type'] = $data[0]['document_type'];
                    $req_arr['user_mode'] = 'L';
                    $req_arr['fk_profession_type_id'] = 5;
                    
                    $kyc_docs_mandatory_data = $this->profile->getKycDocumentsMandatory($req_arr);
                    
                    $raws['template_id']     = $kyc_docs_mandatory_data['template_id'];
                    if ($kyc_docs_mandatory_data['template_id'] == $data[0]['fk_kyc_template_id']) {
                    $raws['is_mandatory_add'] = '1';
                    } else {
                    $raws['is_mandatory_add'] = '0';
                    }
                    } else {
                    if (is_array($data) && count($data) == 2) {
                    $raws['is_mandatory_add'] = '1';
                    }
                    }*/
                    
                    $result_arr['dataset'] = $data;
                    $result_arr['count']   = count($data);
                    //$result_arr['dataset'] = $raws;
                    $http_response         = 'http_response_ok';
                    $success_message       = '';
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'You are not logged in. Please log in and try again';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getKYCDetails()
     * @ Added Date               : 15-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : Get single kyc Details
     * -----------------------------------------------------------------
     * @ return                   : array
     * -----------------------------------------------------------------
     * @ Modified Date            : 
     * @ Modified By              : 
     * 
     */
    public function getKYCDetails_post()
    {
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
        } else {
            $flag    = true;
            $req_arr = array();
            /*if (!$this->post('kyc_id')) {
            $flag = false;
            } else {
            $req_arr['kyc_id'] = $this->post('kyc_id', TRUE);
            }*/
            
            $req_arr['kyc_id'] = $this->post('kyc_id', TRUE);
            
            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                //pre($check_session,1);
                if (!empty($check_session) && count($check_session) > 0) {
                    $details            = array();
                    $req_arr1['kyc_id'] = $req_arr['kyc_id'];
                    $req_arr1['kyc_status'] = $this->post('kyc_status', TRUE);

                    //pre($req_arr['kyc_id'],1);
                    
                    if ($req_arr1['kyc_id'] != '') {

                        //pre($req_arr1,1);
                        $details['kyc_details'] = $this->profile->getKycDetail($req_arr1);
                        //pre($details,1); 
                        if (is_array($details['kyc_details']) && count($details['kyc_details']) > 0) {
                            
                            if (!array_key_exists('kyc_status', $details['kyc_details'])) {
                                $details['kyc_details']['kyc_status'] = 'A';
                            }
                            
                            $template_details                        = $this->profile->getTemplateDetails($details['kyc_details']['fk_kyc_template_id']);
                            $details['kyc_details']['document_name'] = $template_details['document_name'];
                            $details['kyc_details']['document_type'] = $template_details['document_type'];
                            if ($details['kyc_details']['front_s3_media_version'] != '' && $details['kyc_details']['front_s3_media_version'] != 'null') {
                                $details['kyc_details']['front_img_url'] = $this->config->item('bucket_url') . $req_arr1['user_id'] . '/kyc/' . $details['kyc_details']['front_file_name'] . '?versionId=' . $details['kyc_details']['front_s3_media_version'];
                            } else {
                                $details['kyc_details']['front_img_url'] = '';
                            }
                            if ($details['kyc_details']['back_s3_media_version'] != '' && $details['kyc_details']['back_s3_media_version'] != 'null') {
                                $details['kyc_details']['back_img_url'] = $this->config->item('bucket_url') . $req_arr1['user_id'] . '/kyc/' . $details['kyc_details']['back_file_name'] . '?versionId=' . $details['kyc_details']['back_s3_media_version'];
                            } else {
                                $details['kyc_details']['back_img_url'] = '';
                            }
                            $http_response = 'http_response_ok';
                        } else {
                            
                            $http_response = 'http_response_bad_request';
                            $error_message = 'Something went wrong in API';
                        }
                    }
                    
                    $req_arr                 = $this->profile->getDocDetails();
                    $details['kyc_doc_name'] = $this->profile->getKycDocuments($req_arr);
                    $details['kyc_doc_type'] = array(
                        array(
                            'id' => 'A',
                            'docType' => 'ADDRESS PROOF'
                        ),
                        array(
                            'id' => 'F',
                            'docType' => 'FINANCIAL PROOF'
                        ),
                        array(
                            'id' => 'I',
                            'docType' => 'ID PROOF'
                        ),
                        array(
                            'id' => 'D',
                            'docType' => 'DOB PROOF'
                        )
                    );
                    
                    $result_arr = $details;
                    
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Invalid login';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    
    /* public function deleteKycImage_post()
    {
    
    $error_message = $success_message = $http_response = '';
    $result_arr    = array();
    if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    
    $error_message = 'Invalid Token';
    $http_response = 'http_response_unauthorized';
    
    } else {
    
    
    $flag    = true;
    $req_arr = array();
    
    if (!$this->post('version_id')) {
    $flag = false;
    } else {
    $req_arr['version_id'] = $this->post('version_id', TRUE);
    
    }
    
    if (!$this->post('file_name')) {
    $flag = false;
    } else {
    $req_arr['file_name'] = $this->post('file_name', TRUE);
    
    }
    
    if (!$this->post('kyc_id')) {
    $flag = false;
    } else {
    $req_arr['kyc_id'] = $this->post('kyc_id', TRUE);
    
    }
    // image_type can be front/back
    if (!$this->post('image_type')) {
    $flag = false;
    } else {
    $req_arr['image_type'] = $this->post('image_type', TRUE);
    
    }
    
    $raws = array();
    if ($flag) {
    $req_arr1                  = array();
    $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
    $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
    $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
    $req_arr1['user_id']       = $plaintext_user_id;
    $check_session             = $this->lender->checkSessionExist($req_arr1);
    //pre($check_session,1)
    if (!empty($check_session) && count($check_session) > 0) {
    
    $req_arr['user_id'] = $req_arr1['user_id'];
    
    //$tempKyc_details = $this->profile->isinKycDelTemp($req_arr);
    
    //pre($tempKyc_details,1);
    //  AVAILABLE DATA FOR ADMIN APPROVAL
    if (!empty($tempKyc_details) && count($tempKyc_details) > 0) {
    $whereId       = array();
    $whereId['id'] = $tempKyc_details['id'];
    
    //pre($whereId,1);
    if ($req_arr['image_type'] == 'front') {
    $data['is_front_file_uploaded'] = 0;
    $data['front_file_name']        = NULL;
    $data['front_s3_media_version'] = NULL;
    pre($data, 1);
    $this->profile->updateKyc($data, $whereId);
    $aws_target_file_path = 'resources/' . $req_arr['user_id'] . '/kyc/' . $req_arr['file_name'];
    // $response = $this->aws->deletefile($this->aws->bucket,$aws_target_file_path,$req_arr['version_id']);
    }
    
    if ($req_arr['image_type'] == 'back') {
    $data['is_back_file_uploaded'] = 0;
    $data['back_file_name']        = NULL;
    $data['back_s3_media_version'] = NULL;
    $this->profile->updateKyc($data);
    $aws_target_file_path = 'resources/' . $req_arr['user_id'] . '/kyc/' . $req_arr['file_name'];
    
    // $response = $this->aws->deletefile($this->aws->bucket,$aws_target_file_path,$req_arr['version_id']);
    }
    
    $http_response   = 'http_response_ok';
    $success_message = 'Delete requst update to temp';
    
    
    } else {
    
    // NOT AVAILABLE ANY DATA FOR APPROVAL
    $maindata = $this->profile->getKycMain($req_arr);
    //pre($maindata,1);
    if (is_array($maindata) && count($maindata) > 0) {
    //$data['id']                    = $maindata['id'];
    $data['fk_user_id']         = $maindata['fk_user_id'];
    $data['fk_kyc_template_id'] = $maindata['fk_kyc_template_id'];
    $data['kyc_data']           = $maindata['kyc_data'];
    $data['fk_profile_kyc_id']  = $maindata['id'];
    
    if ($req_arr['image_type'] == 'front') {
    $data['is_front_file_uploaded'] = '0';
    $data['front_file_name']        = NULL;
    $data['front_s3_media_version'] = NULL;
    } else {
    $data['is_front_file_uploaded'] = '1';
    $data['front_file_name']        = $maindata['front_file_name'];
    $data['front_s3_media_version'] = $maindata['front_s3_media_version'];
    }
    
    if ($req_arr['image_type'] == 'back') {
    $data['is_back_file_uploaded'] = '0';
    $data['back_file_name']        = NULL;
    $data['back_s3_media_version'] = NULL;
    } else {
    $data['is_back_file_uploaded'] = '1';
    $data['back_file_name']        = $maindata['back_file_name'];
    $data['back_s3_media_version'] = $maindata['back_s3_media_version'];
    }
    
    $data['kyc_addition_datetime'] = $maindata['addition_datetime'];
    $data['fk_admin_id']           = $maindata['fk_admin_id'];
    //pre($data,1);
    $last_temp                     = $this->profile->addKyc($data);
    $result_arr                    = $last_temp;
    $http_response                 = 'http_response_ok';
    $success_message               = 'Delete request added to temp';
    
    } else {
    $http_response = 'http_response_bad_request';
    $error_message = 'Something went wrong';
    }
    
    }
    
    } else {
    $http_response = 'http_response_invalid_login';
    $error_message = 'Wrong username or Password';
    }
    } else {
    $http_response = 'http_response_bad_request';
    $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
    }
    }
    
    
    json_response($result_arr, $http_response, $error_message, $success_message);
    }*/
    
    
    public function deleteProfileImage_post()
    {
        
        $error_message = $success_message = $http_response = '';
        $result_arr    = array();
        if (!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            
            $error_message = 'Invalid Token';
            $http_response = 'http_response_unauthorized';
            
        } else {
            
            
            $flag    = true;
            $req_arr = array();
            
            
            if (!$this->post('version_id')) {
                $flag = false;
            } else {
                $req_arr['version_id'] = $this->post('version_id', TRUE);
                
            }
            
            if (!$this->post('file_extension')) {
                $flag = false;
            } else {
                $req_arr['file_extension'] = $this->post('file_extension', TRUE);
                
            }
            
            $raws = array();
            if ($flag) {
                $req_arr1                  = array();
                $plaintext_user_pass_key   = $this->encrypt->decode($this->post('user_pass_key', TRUE));
                $plaintext_user_id         = $this->encrypt->decode($this->post('user_id', TRUE));
                $req_arr1['user_pass_key'] = $plaintext_user_pass_key;
                $req_arr1['user_id']       = $plaintext_user_id;
                $check_session             = $this->lender->checkSessionExist($req_arr1);
                //pre($check_session,1)
                if (!empty($check_session) && count($check_session) > 0) {
                    
                    //  GET DATA FROM TEMP PROFILE BASIC TABLE               
                    $tempProfileBasic_details = $this->profile->fetchTempProfileBasic($req_arr1);
                    
                    //pre($tempProfileBasic_details,1);
                    //  AVAILABLE DATA FOR ADMIN APPROVAL
                    if (!empty($tempProfileBasic_details) && count($tempProfileBasic_details) > 0) {
                        $data['id']                             = $tempProfileBasic_details['id'];
                        $data['has_profile_picture']            = 0;
                        $data['profile_picture_file_extension'] = NULL;
                        $data['s3_media_version']               = NULL;
                        $this->profile->updateTempProfileBasic($data);
                        $aws_target_file_path = 'resources/' . $req_arr1['user_id'] . '/profile/' . $req_arr1['user_id'] . '.' . $req_arr['file_extension'];
                        $response             = $this->aws->deletefile($this->aws->bucket, $aws_target_file_path, $req_arr['version_id']);
                        
                        
                    } else { // NOT AVAILABLE ANY DATA FOR APPROVAL
                        $maindata = $this->profile->fetchTempProfileMain($req_arr1);
                        if (is_array($maindata) && count($maindata) > 0) {
                            $data['id']         = $maindata['id'];
                            $data['fk_user_id'] = $maindata['fk_user_id'];
                            
                            $data['display_name']           = $maindata['display_name'];
                            $data['f_name']                 = $maindata['f_name'];
                            $data['m_name']                 = $maindata['m_name'];
                            $data['l_name']                 = $maindata['l_name'];
                            $data['residence_street1']      = $maindata['residence_street1'];
                            $data['residence_street2']      = $maindata['residence_street2'];
                            $data['residence_street3']      = $maindata['residence_street3'];
                            $data['residence_post_office']  = $maindata['residence_post_office'];
                            $data['residence_city']         = $maindata['residence_city'];
                            $data['residence_district']     = $maindata['residence_district'];
                            $data['residence_state']        = $maindata['residence_state'];
                            $data['residence_zipcode']      = $maindata['residence_zipcode'];
                            $data['residence_phone']        = $maindata['residence_phone'];
                            $data['permanent_street1']      = $maindata['permanent_street1'];
                            $data['permanent_street2']      = $maindata['permanent_street2'];
                            $data['permanent_street3']      = $maindata['permanent_street3'];
                            $data['permanent_post_office']  = $maindata['permanent_post_office'];
                            $data['permanent_city']         = $maindata['permanent_city'];
                            $data['permanent_district']     = $maindata['permanent_district'];
                            $data['permanent_state']        = $maindata['permanent_state'];
                            $data['permanent_zipcode']      = $maindata['permanent_zipcode'];
                            $data['permanent_phone']        = $maindata['permanent_phone'];
                            $data['fk_profession_type_id']  = $maindata['fk_profession_type_id'];
                            $data['date_of_birth']          = $maindata['date_of_birth'];
                            $data['fathers_name']           = $maindata['fathers_name'];
                            $data['fk_gender_id']           = $maindata['fk_gender_id'];
                            $data['fk_marital_status_id']   = $maindata['fk_marital_status_id'];
                            $data['fk_residence_status_id'] = $maindata['fk_residence_status_id'];
                            
                            $data['has_profile_picture']            = 0;
                            $data['profile_picture_file_extension'] = NULL;
                            $data['s3_media_version']               = NULL;
                            $addTempProfileBasic_id                 = $this->profile->addTempProfileBasic($data);
                            
                        } else {
                            $http_response = 'http_response_bad_request';
                            $error_message = 'Invalid parameter';
                        }
                        
                    }
                    
                } else {
                    $http_response = 'http_response_invalid_login';
                    $error_message = 'Wrong username or Password';
                }
            } else {
                $http_response = 'http_response_bad_request';
                $error_message = ($error_message != '') ? $error_message : 'Invalid parameter';
            }
        }
        
        
        json_response($result_arr, $http_response, $error_message, $success_message);
    }
    
}