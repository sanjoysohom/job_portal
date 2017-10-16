<?php
/* * ******************************************************************
 * Registartion model for lender website
  ---------------------------------------------------------------------
 * @ Added by                 :  
 * @ Framework                : CodeIgniter
 * @ Added Date               : 09-11-2016
  ---------------------------------------------------------------------
 * @ Details                  : It Cotains all the api related methods
  ---------------------------------------------------------------------
 ***********************************************************************/
class Registration_model extends CI_Model
{

    public $_table = 'tbl_user_angular';
    public $_tbl_user_verification_codes = 'tbl_user_verification_codes';
    public $_tbl_user_types = 'tbl_user_types';
    public $_tbl_user_referals = 'tbl_user_referals';


 
   // public $_table_loginkey = 'tbl_user_loginkeys';

     
    function __construct()
    {
       
        //load the parent constructor
        parent::__construct();        
         
    }


    public function addUser($params)
    {

        $this->db->insert($this->_table, $params);
        $insert_id = $this->db->insert_id(); 
        return $insert_id;
    

    }
    public function check_emailid($email_id)
    {
        $this->db->where('email_id',$email_id);
        $this->db->from($this->_table);
        return $this->db->count_all_results();        
    }

    public function check_mobileno($mobile_number)
    {
        $this->db->where('mobile_number', $mobile_number);
        $this->db->from($this->_table);
        return $this->db->count_all_results();        
    }


    public function getVerificationCode($params){

        //pre($params,1);
        $this->db->select('id');
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->where('verification_type',$params['verification_type']);
        $this->db->where('verification_code',$params['verification_code']);
        $this->db->from($this->_tbl_user_verification_codes);
        $result = $this->db->get()->row_array();
        if((!empty($result)) &&  (count($result) >0)){
          return true;
        }
        else {
          return false;
        }
    }


    public function verifyEmail($userId)
      {
        $params['is_email_id_verified'] ='1';
        $this->db->where('id',$userId);
        $result = $this->db->update($this->_table, $params);
        return $result;
       }

     public function verifyMobile($userId)
      {
        $params['is_mobile_number_verified'] ='1';
        $this->db->where('id',$userId);
        $result = $this->db->update($this->_table, $params);
        return $result;
       }

       public function checkAllIsActive($userId)
      {
       $this->db->select('is_email_id_verified,is_mobile_number_verified,is_active');
        $this->db->where('id',$userId);
        $result = $this->db->get($this->_table)->row_array();
        return $result;
       }
      public function activateUser($userId)
      {
        $params['is_active'] ='1';
        $this->db->where('id',$userId);
        $result = $this->db->update($this->_table, $params);
        return $result;
       }






   public function fetchUser($params)
    {
        $this->db->select(
                      $this->_table . ".id, ".
                      $this->_table . ".customer_id, ".
                      $this->_table . ".email_id, ".
                      $this->_table . ".mobile_number, ".
                      $this->_table . ".display_name, ".
                      $this->_table . ".is_email_id_verified, ".
                      $this->_table . ".is_mobile_number_verified, ".
                      $this->_table . ".is_active, ".
                      $this->_tbl_user_types . ".user_mode, ".
                      $this->_tbl_user_types . ".is_agent "
                  );

        //$this->db->join($this->_table, $this->_table.'.id = '.$this->_tbl_user_types.'.fk_user_id', 'inner');
        $this->db->join($this->_tbl_user_types, $this->_tbl_user_types.'.fk_user_id = '.$this->_table.'.id', 'inner');
        $this->db->where($this->_table . ".id", $params);
        $this->db->from($this->_table);
        return $this->db->get()->row_array();
    } 



    public function addUserType($params)

    { 
     
      $data['user_mode']              = $params['user_mode'];
      $data['fk_profession_type_id']  = $params['fk_profession_type_id'];
      $this->db->where('fk_user_id',$params['fk_user_id']);
      $this->db->update($this->_tbl_user_types,$data);

      $result =$this->db->affected_rows();
      return $result;

    }

    public function ValidateRefCode($params)
    {
      //pre($params,1);
      $this->db->select('fk_user_id AS fk_refered_by_user_id' );
      $this->db->where('user_code',$params);
      $result = $this->db->get($this->_tbl_user_types)->row_array();
      return $result;


    }
     public function addUserReferals($params){
        $this->db->insert($this->_tbl_user_referals,$params);

    }
}


