<?php
/* * ******************************************************************
 * Profile model for App Api 
  ---------------------------------------------------------------------
 * @ Added by                 : Mousumi Bakshi 
 * @ Framework                : CodeIgniter
 * @ Added Date               : 02-08-2016
  ---------------------------------------------------------------------
 * @ Details                  : It Cotains all the api related methods
  ---------------------------------------------------------------------
 ***********************************************************************/
class Lender_model extends CI_Model{

     public $_table = 'tbl_users';
     public $_tbl_user_loginsessions = 'tbl_user_loginsessions';
     public $_tbl_user_basic         = 'tbl_user_profile_basics';
     public $_tbl_user_types = 'tbl_user_types';
     

 


     
    function __construct(){
        //load the parent constructor
        parent::__construct();        
    }


    public function checkUser($params = array()) {
        //pre($params,1);
        //cheking the user in database
        $this->db->select($this->_table.'.*');
        $this->db->where($this->_table.'.email_id', $params['email_id']);
        //$this->db->where($this->_table.'.login_pwd', md5($params['login_pwd']));
        $this->db->limit(1);
        $query = $this->db->get($this->_table);
        //pre($query,1);

        //die($this->db->last_query());
        if ($query->num_rows() === 1) {
            $user = $query->row();

            
            //pre($user,1);
            $this->updateLastLogin($user->id);
            $result = array();
            $result['user_id'] = $user->id;
            $result['login_pwd'] = $user->login_pwd;
            if ($user->is_active == 0) {
                $result['status'] = 'inactive';
                return $result;
            }else{                  
                $result['status'] = 'active';
                return $result;
            }

        } else {
            return FALSE;
        }
    }


    public function updateLastLogin($id) {
        $current_time  = gmdate("Y-m-d H:i:s");
        $this->db->update(
            $this->_table, 
            array(
                'last_login_timestamp' => $current_time
                
            ), array(
                'id' => $id
            ));
        return $this->db->affected_rows() == 1;
    }

    public function addUserLoginSession($params){
        $this->db->insert($this->_tbl_user_loginsessions, $params);
        return $this->db->insert_id();
    }


    function checkSessionExist($param){

        //pre($param,1);
        $this->db->select($this->_tbl_user_basic . ".f_name, " . $this->_tbl_user_basic . ".l_name, " . $this->_table . ".display_name, " . $this->_table . ".email_id, " . $this->_table . ".mobile_number, " . $this->_table . ".is_email_id_verified, " . $this->_table . ".is_mobile_number_verified, " . $this->_table . ".is_active, " .
            $this->_tbl_user_types . ".user_mode, ". $this->_tbl_user_types . ".is_agent, ". $this->_tbl_user_loginsessions . ".id AS user_pass_key");

        $this->db->where($this->_tbl_user_loginsessions.".id",$param['user_pass_key']);
        $this->db->where($this->_tbl_user_loginsessions.".fk_user_id",$param['user_id']);

        $this->db->join($this->_table, $this->_table.'.id = '.$this->_tbl_user_loginsessions.'.fk_user_id', 'inner');
        $this->db->join($this->_tbl_user_types, $this->_table.'.id = '.$this->_tbl_user_types.'.fk_user_id', 'inner');
        $this->db->join($this->_tbl_user_basic, $this->_tbl_user_basic.'.fk_user_id = '.$this->_tbl_user_loginsessions.'.fk_user_id', 'left');

        $qry = $this->db->get($this->_tbl_user_loginsessions);

        //pre($this->db->last_query(),1);
        return $qry->row_array();
    }



    public function logoutUser($param = array()) {
        $loginsessions_count = 0;
        $this->db->where('id', $param['user_pass_key']);
        $this->db->where('fk_user_id', $param['user_id']);
        $this->db->delete($this->_tbl_user_loginsessions);

        //pre($this->db->last_query(),1);
        $loginsessions_count = $this->db->affected_rows();
        
        return $loginsessions_count;
    }
}
