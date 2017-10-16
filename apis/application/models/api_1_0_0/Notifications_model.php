<?php
/* * ******************************************************************
 * User model for Mobile Api 
  ---------------------------------------------------------------------
 * @ Added by                 : Mousumi Bakshi 
 * @ Framework                : CodeIgniter
 * @ Added Date               : 02-03-2016
  ---------------------------------------------------------------------
 * @ Details                  : It Cotains all the api related methods
  ---------------------------------------------------------------------
 ***********************************************************************/
class Notifications_model extends CI_Model
{

     public $_table = 'master_notification_types';
     public $_table_notification = 'tbl_user_notifications';
     
     
    function __construct()
    {
       
        //load the parent constructor
        parent::__construct();        
         
    }
    
    
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addTicket()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function getNotificationTypes($notification_code)
    {
        $this->db->where('notification_code',$notification_code);
        $this->db->from($this->_table);
        return $this->db->get()->row_array();
    }

    public function addUserNotification($param){
        $this->db->insert($this->_table_notification,$param);

    }

    public function getAllNotifications($user_id,$limit,$pageLimit){

        $this->db->where('fk_user_id',$user_id);
        $this->db->order_by('id','desc');
        return $this->db->get($this->_table_notification,$limit,$pageLimit)->result_array();

    }

    public function getAllTotalNotifications($user_id){
        $this->db->where('fk_user_id',$user_id);
        $this->db->from($this->_table_notification);
        return $this->db->count_all_results(); 

    }

    public function getAllNewNotifications($user_id){

        $this->db->where('is_new',1);
        $this->db->where('fk_user_id',$user_id);
        $this->db->from($this->_table_notification);
        return $this->db->count_all_results(); 

    }


    public function updateNewNotifications($user_id){

        $this->db->set('is_new',0);
        $this->db->where('fk_user_id',$user_id);
        $this->db->update($this->_table_notification);
       

    }

    public function updateUnreadNotifications($params){

        $this->db->set('is_unread',0);
        $this->db->where('fk_user_id',$params['user_id']);
        $this->db->where('id',$params['notification_id']);
        $this->db->update($this->_table_notification);
       

    }

    

    

    
    //public 
    /**********************************************************************************************************************************
     * End of user model
     *********************************************************************************************************************************/
}