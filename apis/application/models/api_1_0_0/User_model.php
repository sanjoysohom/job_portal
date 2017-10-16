<?php
/* * ******************************************************************
 * User model for Mobile Api 
  ---------------------------------------------------------------------
 * @ Added by                 : Subhankar 
 * @ Framework                : CodeIgniter
 * @ Added Date               : 02-03-2016
  ---------------------------------------------------------------------
 * @ Details                  : It Cotains all the api related methods
  ---------------------------------------------------------------------
 ***********************************************************************/
class User_model extends CI_Model
{

     public $_table = 'tbl_users';
     public $_table_device = 'tbl_user_mobile_devices';
     public $_table_loginkey = 'tbl_user_loginkeys';
     public $_table_verification_code = 'tbl_user_verification_codes';
     public $_table_user_type = 'tbl_user_types';
     public $_master_profession_type = 'master_profession_types';
     public $_table_pwd_reset = 'tbl_user_pwd_reset_codes';
     public $_table_profile_basics = 'tbl_user_profile_basics';
     public $_table_social_login = 'tbl_user_social_logins';
     public $_table_timezone = 'master_timezones';
     public $_master_gender = 'master_genders';
     public $_tbl_user_referal = 'tbl_user_referals';
     public $_tbl_user_basic = 'tbl_user_profile_basics';
     public $_tbl_mcoin_earning = 'master_mcoin_earnings';
     public $_tbl_user_mcoins_earning = 'tbl_user_mcoins_earnings';
     public $_tbl_history_user_email = 'tbl_history_user_emails';
     public $_tbl_history_user_mobile_number = 'tbl_history_user_mobile_numbers';
     public $_tbl_user_level = 'tbl_user_levels';
     public $_table_user_approval = 'tbl_user_approvals';
     public $_table_admin_data_collection = 'tbl_admin_data_collections';
     public $_table_tbl_timezone = 'master_tbl_timezones';
     

     
    function __construct()
    {
       
        //load the parent constructor
        parent::__construct();        
         
    }



    public function fetchMobileDevice($id){
        $this->db->select($this->_table_device.'.*');
        $this->db->where($this->_table_loginkey.'.fk_user_id',$id);
        $this->db->from($this->_table_loginkey);
        $this->db->join($this->_table_device,$this->_table_loginkey.'.fk_user_mobile_device_id='.$this->_table_device.'.id');
        return $this->db->get()->row_array();


    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : check_emailid()
     * @ Added Date               : 12-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function check_emailid($email_id)
    {
        $this->db->where('email_id',$email_id);
        $this->db->from($this->_table);
        return $this->db->count_all_results();        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : check_emailid()
     * @ Added Date               : 25-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function check_mobileno($mobile_number)
    {
        $this->db->where('mobile_number', $mobile_number);
        $this->db->from($this->_table);
        return $this->db->count_all_results();        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 25-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function addUser($params)
    {
        $this->db->set('email_id', $params['email_id']);
        //$this->db->set('is_mobile_number_verified ', 1);
        $this->db->set('display_name', $params['email_id']);
        $this->db->set('mobile_number', $params['mobile_number']);
        $this->db->set('login_pwd', password_hash($params['login_pwd'],PASSWORD_BCRYPT));
        $this->db->set('customer_id', $params['customer_id']);
        $this->db->insert($this->_table);
        return $this->db->affected_rows();    
    }


/*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 25-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchUser($params)
    {

        $this->db->where('email_id', $params['username']);
        $this->db->or_where('mobile_number', $params['username']);
        $this->db->from($this->_table);
        return $this->db->get()->row_array();
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : checkloginbyemail()
     * @ Added Date               : 26-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for login checking for email
    */
    public function checkloginbyemail($params)
    {
        $this->db->where('email_id', $params['username']);
        //$this->db->where('login_pwd', md5($params['password']));
        $this->db->from($this->_table);
        return $this->db->count_all_results();               
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : checkloginbymobile()
     * @ Added Date               : 26-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for login checking by mobile
    */
    public function checkloginbymobile($params)
    {
        $this->db->where('mobile_number', $params['username']);
        //$this->db->where('login_pwd', md5($params['password']));
        $this->db->from($this->_table);
        return $this->db->count_all_results();               
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : checkMobileDeviceTable()
     * @ Added Date               : 26-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for login checking by mobile
    */
    public function checkMobileDeviceTable($params){

        $this->db->where('device_uid', $params['device_uid']);
        $this->db->from($this->_table_device);
       return $this->db->count_all_results();     


    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addMobileDeviceTable()
     * @ Added Date               : 26-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for login checking by mobile
    */
    public function addMobileDeviceTable($params){

        $this->db->set('appversion', $params['appversion']);
        $this->db->set('device_uid', $params['device_uid']);
        $this->db->set('device_token', $params['device_token']);
        $this->db->set('device_name', $params['device_name']);
        $this->db->set('device_model', $params['device_model']);
        $this->db->set('device_version', $params['device_version']);
        $this->db->set('device_os', $params['device_os']);
        $this->db->set('badge_count', $params['badge_count']);
        $this->db->set('push_mode', $params['push_mode']);
        $this->db->insert($this->_table_device);
        return $this->db->affected_rows();   
        

    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : upadteMobileDeviceTable()
     * @ Added Date               : 26-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for login checking by mobile
    */
    public function upadteMobileDeviceTable($params){

        $this->db->set('appversion', $params['appversion']);
        $this->db->set('device_token', $params['device_token']);
        $this->db->set('device_name', $params['device_name']);
        $this->db->set('device_model', $params['device_model']);
        $this->db->set('device_version', $params['device_version']);
        $this->db->set('device_os', $params['device_os']);
        $this->db->set('badge_count', $params['badge_count']);
        $this->db->set('push_mode', $params['push_mode']);

        $this->db->where('device_uid', $params['device_uid']);
        
        $this->db->update($this->_table_device);
        return $this->db->affected_rows();   
        

    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 25-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchDevice($params)
    {

        $this->db->where('device_uid', $params['device_uid']);
        
        $this->db->from($this->_table_device);
        return $this->db->get()->row_array();
    }

     public function loginkeysChecking($params){

        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->where('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->from($this->_table_loginkey);
        return $this->db->count_all_results();
        

    }


    public function loginkeysCheckingUser($params){

       
        $this->db->where('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->from($this->_table_loginkey);
        return $this->db->get()->row_array();
        

    }

    public function loginkeysCheckingUserById($user_id){

       
        $this->db->where('fk_user_id', $user_id);
        $this->db->from($this->_table_loginkey);
        return $this->db->get()->row_array();
        

    }

    public function addLoginKeys($params){
        $this->db->set('id', 'UUID()',FALSE);
        $this->db->set('fk_user_id', $params['fk_user_id']);
        $this->db->set('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->insert($this->_table_loginkey);
        

    }

    public function deleteLoginKeys($params){
       $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->or_where('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->delete($this->_table_loginkey);
        

    }

    public function updateLoginKeys($params){
        $current_date=date('Y-m-d H:i:s');
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->where('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->set('login_timestamp', $current_date);
        $this->db->update($this->_table_loginkey);
        

    }

    public function fetchLoginKeys($params){

        $this->db->select('*');
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->where('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->from($this->_table_loginkey);
        return $this->db->get()->row_array();
    }

    public function addUserVerificationCode($params){
        $this->db->where('fk_user_id',$params['fk_user_id']);
        $this->db->where('verification_type',$params['verification_type']);
        $this->db->delete($this->_table_verification_code);
        $this->db->insert($this->_table_verification_code,$params);
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUserTypes()
     * @ Added Date               : 27-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : Add data into user_types table
    */
    public function addUserTypes($params){
        $this->db->insert($this->_table_user_type,$params);
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getUserTypes()
     * @ Added Date               : 27-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : Add data into user_types table
    */
    public function getUserTypes($params){
        $this->db->select('*');
        $this->db->where('fk_user_id', $params['fk_user_id']);
       
        $this->db->from($this->_table_user_type);
        return $this->db->get()->row_array();
    }


/*
     * --------------------------------------------------------------------------
     * @ Function Name            : getUserTypes()
     * @ Added Date               : 27-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : Add data into user_types table
    */
    public function getVerificationType($params){
        $this->db->select('*');
        $this->db->where('fk_user_id', $params['fk_user_id']);
        if($params['verification_type']=='E' || $params['verification_type']=='M'){
            $this->db->where('verification_type', $params['verification_type']);
        }
        $this->db->from($this->_table_verification_code);
        return $this->db->get()->row_array();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : login_status_checking()
     * @ Added Date               : 28-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : login status checking
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function login_status_checking($param = array()){

        $this->db->where('id', $param['pass_key']);
        $this->db->where('fk_user_id', $param['user_id']);
        $this->db->from($this->_table_loginkey);
        //echo $this->db->last_query(); exit;
        //return $this->db->count_all_results(); 
        return $this->db->get()->row_array();
        //return $result;
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : logout_user()
     * @ Added Date               : 28-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : User logged out user keys deleted from user_keys table
     * -----------------------------------------------------------------
     * @ param                    : array(params)
     * @ return                   : int(affected_rows)
     * -----------------------------------------------------------------
     * 
    */
    public function logout_user($param = array()) {
        $loginkeys_count = $mobile_devices_count = 0;
        $this->db->where('id', $param['key_id']);
        $this->db->delete($this->_table_loginkey);
        $loginkeys_count = $this->db->affected_rows();
        if($loginkeys_count > 0){
            $this->db->where('id', $param['mobile_device_id']);
            $this->db->delete($this->_table_device);
            $mobile_devices_count = $this->db->affected_rows();            
        }
        return $mobile_devices_count;
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchUserPswdResetCode()
     * @ Added Date               : 28-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Pswd Reset Code status checking
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function fetchUserPswdResetCode($param = array()){
        $this->db->where('fk_user_id', $param['id']);
        $this->db->from($this->_table_pwd_reset);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUserPswdResetCode()
     * @ Added Date               : 28-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Insert Pswd Reset Code
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function addUserPswdResetCode($params){
        $this->db->insert($this->_table_pwd_reset,$params);
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getUserPswdResetCode()
     * @ Added Date               : 28-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Pswd Reset Code status checking
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function getUserPswdResetCode($param = array()){

        $this->db->where('passcode', $param['passcode']);
        $this->db->where('fk_user_id', $param['fk_user_id']);
        $this->db->from($this->_table_pwd_reset);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : change_password
     * @ Added Date               : 29-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : change_password
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
    */
    public function change_password($param = array()){
        $update = array();
        $update['login_pwd'] = $param['password'];
        $this->db->where($this->_table.".id", $param['user_id']);
        $this->db->update($this->_table, $update);
        //return $this->db->affected_rows(); 
        return 1;
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : remove_passcode
     * @ Added Date               : 01-08-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : remove_passcode
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
    */
    public function remove_passcode($param = array()){   
        $affected_rows_count = 0;
        $this->db->where('passcode',$param['passcode']);        
        $this->db->where('fk_user_id',$param['fk_user_id']);        
        $this->db->delete($this->_table_pwd_reset);
        return $affected_rows_count = $this->db->affected_rows();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetch_time_zone
     * @ Added Date               : 25-03-2016
     * @ Added By                 : Subhankar
     * -----------------------------------------------------------------
     * @ Description              : fetch_time_zone
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
     */
     public function fetch_time_zone($user_id){
        $this->db->select('t.timezone');
        $this->db->join($this->tables['master_tbl_timezones'].' as t', 't.id=k.fk_current_timezone_id');
        $this->db->where('k.fk_user_id', $user_id);
        $result = $this->db->get($this->tables['tbl_user_loginkeys'].' as k')->row_array();
        return $result['timezone'];
     }


     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateUserMode()
     * @ Added Date               : 29-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update user mode
    */
    public function updateUserMode($params){

        $this->db->set('user_mode', $params['user_mode']);
        $this->db->set('is_agent', $params['is_agent']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->update($this->_table_user_type);
        return $this->db->affected_rows();   
        

    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateProfessionType()
     * @ Added Date               : 29-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update user mode
    */
    public function updateProfessionType($params){

        $this->db->set('fk_profession_type_id', $params['fk_profession_type_id']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        
        $this->db->update($this->_table_user_type);
        return $this->db->affected_rows();   
        

    }


    
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : checkAgentCode()
     * @ Added Date               : 29-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update user mode
    */
    public function checkAgentCode($params){

        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->where('fk_user_mobile_device_id', $params['fk_user_mobile_device_id']);
        $this->db->from($this->_table_loginkey);
        return $this->db->count_all_results();
    }

    public function getProfessionType(){
        $this->db->where('is_active','1');
        $result = $this->db->get($this->_master_profession_type)->result_array();
        return $result;
    }
    public function getGender(){
        $result = $this->db->get($this->_master_gender)->result_array();
        return $result;
    }
    
    public function checkReferral_Code($data){
        $this->db->where('user_code', $data['referral_code']);
        return $this->db->get($this->_table_user_type)->row_array();
    }

   

    public function addUserReferals($params){
        $this->db->insert($this->_tbl_user_referal,$params);

    }

    public function isReferred($params){
        $this->db->where('fk_user_id',$params['user_id']);
        $this->db->from($this->_tbl_user_referal);
        return $this->db->get()->row_array();

    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 25-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function addSocialLogins($params)
    {
        
        $this->db->insert($this->_table_social_login,$params);
        return $this->db->affected_rows();       
    }

       /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 25-07-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function updateSocialLogins($params,$id)
    {
        $this->db->where('id',$id);
        $this->db->update($this->_table_social_login,$params);
        return $this->db->affected_rows();       
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : checkVerificationCode()
     * @ Added Date               : 01-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for veryfied email address
    */
    public function checkVerificationCode($params)
    {
        
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->where('verification_code', $params['verification_code']);
        $this->db->where('verification_type', $params['verification_type']);
        $this->db->from($this->_table_verification_code);
        return $this->db->count_all_results();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateEmailVerified()
     * @ Added Date               : 01-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for veryfied email address
    */
    public function updateEmailVerified($params)
    {
        
        $this->db->where('id', $params['id']);
        $this->db->set('is_email_id_verified', '1');
        $this->db->update($this->_table);
        return $this->db->affected_rows();       
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : chnageEmail()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for veryfied email address
    */
    public function editEmail($params)
    {
        
        $this->db->where('id', $params['id']);
        $this->db->set('email_id', $params['email_id']);
        $this->db->set('display_name', $params['email_id']);
        $this->db->update($this->_table);
        return $this->db->affected_rows();       
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : chnageEmail()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for veryfied email address
    */
    public function chnageEmail($params)
    {
        
        $this->db->where('id', $params['id']);
        $this->db->set('email_id', $params['email_id']);
        $this->db->set('display_name', $params['email_id']);
        $this->db->set('is_email_id_verified', 1);
        $this->db->update($this->_table);
        return $this->db->affected_rows();       
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : chnageEmail()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for veryfied email address
    */
    public function chnageMobile($params)
    {
        
        $this->db->where('id', $params['id']);
        $this->db->set('mobile_number', $params['mobile_number']);
        $this->db->set('is_mobile_number_verified', 1);
        $this->db->update($this->_table);
        return $this->db->affected_rows();       
    }

     public function editMobile($params)
    {
        
        $this->db->where('id', $params['id']);
        $this->db->set('mobile_number', $params['mobile_number']);
        $this->db->update($this->_table);
        return $this->db->affected_rows();       
    }
    

    public function getTimezoneid($params){
        $this->db->select('*');
        $this->db->where('timezone', $params['timezone']);
        $this->db->from($this->_table_timezone);
        return $this->db->get()->row_array();
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateUser()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update user table
    */
    public function updateUser($params){

        $this->db->where('id', $params['id']);
        $this->db->update($this->_table_user_type,$params);
        return $this->db->affected_rows();   
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateUser()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update user password
    */
    public function update_password($params){

        $this->db->set('login_pwd', password_hash($params['password'],PASSWORD_BCRYPT));
        $this->db->where('id', $params['user_id']);
        $this->db->update($this->_table);
        return $this->db->affected_rows();   
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchUserDeatils()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchUserDeatils($id)
    {

        $this->db->where('id', $id);
        $this->db->from($this->_table);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchFacebookSocialLogins()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchFacebookSocialLogins($data)
    {

        
        $this->db->where('facebook_account_id', $data['account_id']);
        $this->db->from($this->_table_social_login);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchFacebookSocialLogins()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchGoogleSocialLogins($data)
    {

        $this->db->where('googleplus_account_id', $data['account_id']);
        $this->db->from($this->_table_social_login);
        return $this->db->get()->row_array();
    }

      /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchFacebookSocialLogins()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchSocialLogins($data)
    {

        $this->db->where('fk_user_id', $data['user_id']);
        $this->db->from($this->_table_social_login);
        return $this->db->get()->row_array();
    }

    
     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchUserDeatils()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchUserDeatilsAll($id)
    {
        $this->db->select($this->_table.'.*', $this->_table_user_type.'.user_code,user_mode,is_agent',$this->_tbl_user_basic.'.profile_picture_file_extension,s3_media_version');
        $this->db->where($this->_table.'.id', $id);
        $this->db->from($this->_table);
        $this->db->join($this->_table_user_type,$this->_table.'.id='.$this->_table_user_type.'.fk_user_id');
        $this->db->join($this->_tbl_user_basic,$this->_table.'.id='.$this->_tbl_user_basic.'.fk_user_id','left');
        return $this->db->get()->row_array();
    }

    //public 
    /**********************************************************************************************************************************
     * End of user model
     *********************************************************************************************************************************/
    public function fetchMastermCoin($id){
        $this->db->where('fk_mcoin_activity_id',$id);
        $this->db->from($this->_tbl_mcoin_earning);
        return $this->db->get()->row_array();


    }

    public function addUserMcoins($params){
        $this->db->insert($this->_tbl_user_mcoins_earning,$params);
        return $this->db->affected_rows(); 

    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addHistoryEmail()
     * @ Added Date               : 08-09-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
    */
    public function addHistoryEmail($params)
    {
       
        $this->db->insert($this->_tbl_history_user_email,$params);
        return $this->db->affected_rows();    
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUserLevel()
     * @ Added Date               : 08-09-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
    */
    public function addUserLevel($params)
    {
       
        $this->db->insert($this->_tbl_user_level,$params);
        return $this->db->affected_rows();    
    }

    public function getUserLevel($param){
        $this->db->where('fk_user_id',$param['user_id']);
        $this->db->from($this->_tbl_user_level);
        return $this->db->get()->row_array();
    }

    public function updateUserLevel($param){
        $this->db->where('fk_user_id',$param['user_id']);
        $this->db->set('total_mcoin_points',$param['total_mcoin_points']);
        $this->db->update($this->_tbl_user_level);
        //return $this->db->get()->row_array();
    }

    public function isUserApprove($params)
    {
        
    $this->db->where('fk_user_id', $params['user_id']);
    $this->db->from($this->_table_user_approval);
    return $this->db->count_all_results();
    }

    public function addHistoryMobile($params){
         $this->db->insert($this->_tbl_history_user_mobile_number,$params);
        return $this->db->affected_rows();    

    }

    
    public function checkEmailDataCollection($email_id)
    {
        $this->db->where('user_email',$email_id);
        $this->db->where('is_approved','1');
        $this->db->from($this->_table_admin_data_collection);
        return $this->db->count_all_results();        
    }

    public function checkTodayData()
    {

        $todate=date('Y-m-d');
        $sql="SELECT * FROM tbl_users where date(user_since_timestamp)='".$todate."' ";

        $res=$this->db->query($sql);
        return $res->result_array();       
    }

    public function checkTimeZone($timezone)
    {

        $this->db->where('timezone',$timezone);
        $this->db->from($this->_table_tbl_timezone);
        return $this->db->get()->row_array();     
    }

    public function getServerTimeZone($user_id,$date)
    {

        $userLoginData=$this->loginkeysCheckingUserById($user_id);

        if($userLoginData['fk_current_timezone_id']>0){
            $timezone_id=$userLoginData['fk_current_timezone_id'];
        }else{
            $timezone_id=196;
        }

        $this->db->where('id',$timezone_id);
        $this->db->from($this->_table_tbl_timezone);
        $offsetDtl= $this->db->get()->row_array();  
        if($offsetDtl['utc_offset']!=''){
            $offset=$offsetDtl['utc_offset'];
        }else{
            $offset='+05:30';
        }

        $serverTimezone='UTC';

        $sql="select CONVERT_TZ('".$date."','".$serverTimezone."','".$offset."') as timezoneDate;" ;

        $res=$this->db->query($sql);
        $result= $res->row_array();   
        return $result['timezoneDate'] ;   
    }




}