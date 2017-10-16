<?php
class User_model extends CI_Model{
	public $_cms_master = 'cms_master';
    public $_tbl_users = 'tbl_users';

    function __construct(){
        //load the parent constructor
         parent::__construct();        
        $this->tables = $this->config->item('tables'); 

    }


 public function getAlluser($param = array()){
     //print_r($param);
    $this->db->select('*');
     if(isset($param['user_id'])){
        $this->db->where('id',$param['user_id']);
    }

    $result = $this->db->get($this->_tbl_users)->result_array();
    return $result;

 }



 public function is_verify_update($data=array()){

   if(isset($data['email_verify'])){
        $data_updte = array('is_email_id_verified'=>$data['email_verify'],'is_active'=>$data['is_active']);

     }

    if(isset($data['phoneno_verify'])){

        $data_updte = array('is_mobile_number_verified'=> $data['phoneno_verify'],'is_active'=>$data['is_active']);
       }
    
    $this->db->where('id',$data['user_id']);
    $this->db->update($this->_tbl_users,$data_updte);
    return true;
 }

 

    /*****************************************
     * End of user model
    ****************************************/
}