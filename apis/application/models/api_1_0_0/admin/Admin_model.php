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
class Admin_model extends CI_Model{
    public $_tbl_admins = 'tbl_admins';

    function __construct(){
        //load the parent constructor
        parent::__construct();        
        $this->tables = $this->config->item('tables'); 
    }

    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : check_user()
    * @ Added Date               : 14-04-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : This function is used for checking the user whether the user exist or not
    * -----------------------------------------------------------------
    * @ param                    : Array(params)    
    * @ return                   : If user exist return user_id else false  
    * -----------------------------------------------------------------
    * 
    */
    public function checkUser($params = array()) {
        //echo "<pre>"; print_r($params); exit;
        //cheking the user in database
        $this->db->select($this->tables['tbl_admins'].'.*');
        $this->db->where($this->tables['tbl_admins'].'.login_email', $params['username']);
        $this->db->where($this->tables['tbl_admins'].'.login_pwd', md5($params['password']));
        $this->db->limit(1);
        $query = $this->db->get($this->tables['tbl_admins']);
        //die($this->db->last_query());
        if ($query->num_rows() === 1) {
            $user = $query->row();

            $this->updateLastLogin($user->id);
            $result = array();
            if ($user->is_active == 0) {
                $result['status'] = 'inactive';
                $result['user_id'] = $user->id;
                return $result;
            }else{                  
                $result['status'] = 'active';
                $result['user_id'] = $user->id;
                return $result;
            }

        } else {
            return FALSE;
        }
    }

    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : update_last_login()
    * @ Added Date               : 14-06-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : last login by user has been updated using 
    * this function
    * -----------------------------------------------------------------
    * @ param                    : int(id) 
    * @ return                   : int(affected rows)  
    * -----------------------------------------------------------------
    * 
    */
    public function updateLastLogin($id) {
        $current_time  = gmdate("Y-m-d H:i:s");
        $this->db->update(
            $this->tables['tbl_admins'], 
            array(
                'last_login_timestamp' => $current_time,
                'last_activity_timestamp' => $current_time
            ), array(
                'id' => $id
            ));
        //echo $this->db->last_query();die();
        return $this->db->affected_rows() == 1;
    }


    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : check_emailid()
    * @ Added Date               : 14-06-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : Check for exist email id for admin.
    * this function
    * -----------------------------------------------------------------
    * @ param                    : email, and user id if login 
    * @ return                   : array
    * -----------------------------------------------------------------
    * 
    */

    function checkEmailid($email='',$user_id='')
    {
        $this->db->select('*');
        $this->db->from($this->tables['tbl_admins']);
        $this->db->where($this->tables['tbl_admins'].'.login_email',$email);
        if($user_id)
        {
            $this->db->where_not_in($this->tables['tbl_admins'].'.id',$user_id);
        }
        $this->db->where($this->tables['tbl_admins'].'.is_active','1');
        return $this->db->get()->result_array();
    }


    

    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : get_fk_admin_id()
    * @ Added Date               : 29-05-2017
    * @ Added By                 : Vishal
    * -----------------------------------------------------------------
    * @ Description              : get the fk admin id for passcode.
    * this function
    * -----------------------------------------------------------------
    * @ param                    : passcode
    * @ return                   : array
    * -----------------------------------------------------------------
    * 
    */

 function get_fk_admin_id($passCode){
    //print_r($passCode); exit();
    $this->db->select('fk_admin_id');
    $this->db->where('passcode',$passCode);
    $q = $this->db->get($this->tables['tbl_admin_pwd_reset_codes'])->row_array();
    
    return $q;

 }


    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : check_exist_passcode()
    * @ Added Date               : 14-06-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : Check for exist passcode for admin
    * this function
    * -----------------------------------------------------------------
    * @ param                    : user_id 
    * @ return                   : array
    * -----------------------------------------------------------------
    * 
    */

    function checkExistPasscode($user_id){
        $this->db->select("*");
        $this->db->where("fk_admin_id",$user_id);
        $qry = $this->db->get($this->tables['tbl_admin_pwd_reset_codes']);
        return $qry->row_array();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAdminPswdResetCode()
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
    public function getAdminPswdResetCode($param = array()){
        //print_r($fk_admin_id); exit();
        $this->db->where('passcode', $param['passcode']);
        $this->db->where('fk_admin_id', $param['fk_admin_id']);
        $this->db->from($this->tables['tbl_admin_pwd_reset_codes']);
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
    public function changePassword($param = array()){
        $update = array();
        $update['login_pwd'] = $param['password'];
        $this->db->where($this->tables['tbl_admins'].".id", $param['admin_id']);
        $this->db->update($this->tables['tbl_admins'], $update);
        //return $this->db->affected_rows(); 
        return 1;
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : remove_passcode
     * @ Added Date               : 06-05-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : remove_passcode
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
     */
    public function removePasscode($param = array()){   
        $affected_rows_count = 0;
        $this->db->where('passcode',$param['passcode']);        
        $this->db->where('fk_admin_id',$param['fk_admin_id']);        
        $this->db->delete($this->tables['tbl_admin_pwd_reset_codes']);
        return $affected_rows_count = $this->db->affected_rows();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addAdminloginSession
     * @ Added Date               : 06-05-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : add admin login sesssion
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
     */    
    public function addAdminLoginSession($params){
        $this->db->insert($this->tables['tbl_admin_loginsessions'], $params);
        //echo $this->db->last_query();die();
        return $this->db->insert_id();
    }



    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : checkSessionExist()
    * @ Added Date               : 14-06-201checkSessionExist6
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : Check for exist passcode for admin
    * this function
    * -----------------------------------------------------------------
    * @ param                    : user_id 
    * @ return                   : array
    * -----------------------------------------------------------------
    * 
    */
    
    function checkSessionExist($param){
        //$this->db->select($this->tables['tbl_admins'].".*,".$this->tables['tbl_admin_loginsessions'].'.id AS pass_key');
        $this->db->select($this->tables['tbl_admins'].".admin_level, " . $this->tables['tbl_admins'].".f_name, " . $this->tables['tbl_admins'].".l_name, "  . $this->tables['tbl_admin_loginsessions'].'.id AS pass_key');

        $this->db->where($this->tables['tbl_admin_loginsessions'].".id",$param['pass_key']);
        $this->db->where($this->tables['tbl_admin_loginsessions'].".fk_admin_id",$param['admin_user_id']);
        $this->db->join($this->tables['tbl_admins'], $this->tables['tbl_admins'].'.id = '.$this->tables['tbl_admin_loginsessions'].'.fk_admin_id', 'inner');
        $qry = $this->db->get($this->tables['tbl_admin_loginsessions']);
        //echo $this->db->last_query();die();
        return $qry->row_array();
    }



    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : logout_admin()
     * @ Added Date               : 28-07-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Admin logged out 
     * -----------------------------------------------------------------
     * @ param                    : array(params)
     * @ return                   : int(affected_rows)
     * -----------------------------------------------------------------
     * 
    */
    public function logoutAdmin($param = array()) {
        $loginsessions_count = 0;
        $this->db->where('id', $param['pass_key']);
        $this->db->where('fk_admin_id', $param['admin_user_id']);
        $this->db->delete($this->tables['tbl_admin_loginsessions']);
        $loginsessions_count = $this->db->affected_rows();
        
        return $loginsessions_count;
    }


  


    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : check_user()
    * @ Added Date               : 14-04-2016
    * @ Added By                 : Subhankar
    * -----------------------------------------------------------------
    * @ Description              : This function is used for checking the user whether the user exist or not
    * -----------------------------------------------------------------
    * @ param                    : Array(params)    
    * @ return                   : If user exist return user_id else false  
    * -----------------------------------------------------------------
    * 
    */
    public function checkAdminUser($where = array()) {
        $this->db->where($where);
        $query = $this->db->get($this->tables['tbl_admins']);
        $data = $query->row_array();
        return $data;
    }


 
  public function  chk_admin_pass($param = array(),$param1 = array()){
    //print_r($param1['admin_user_id']); exit();
    $this->db->select('login_pwd');
    $this->db->where('id',$param1['admin_user_id']);
    $admin_pass = $this->db->get($this->tables['tbl_admins'])->row_array();
    return $admin_pass;


 }

 public function chagePassword($param = array(),$param1 = array(),$data){
 
 $this->db->where('id',$param1['admin_user_id']);
 $this->db->update($this->tables['tbl_admins'],$data);
 return true;

 }


function getAdminDetails($param = array()){

    $this->db->select('*');
    $this->db->where('id',$param['admin_user_id']);
    $detail = $this->db->get($this->tables['tbl_admins'])->row_array();
    return $detail;
}


function updateProfile($id,$up_array=array()){
  $this->db->where('id',$id);
  $this->db->update($this->tables['tbl_admins'],$up_array);
  return true;


}












    /*****************************************
     * End of admin model
    ****************************************/
}