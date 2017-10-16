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
class Conversations_model extends CI_Model
{
    public $_tbl_user_support_tickets = 'tbl_user_support_tickets';
    public $_tbl_user_support_ticket_threads = 'tbl_user_support_ticket_threads';
    public $_tbl_users = 'tbl_users';
    public $_tbl_user_profile_basics = 'tbl_user_profile_basics';


    public $_tbl_admins = 'tbl_admins';
    
    function __construct()
    {
        // 
        //load the parent constructor
        parent::__construct();
        // $this->tables = $this->config->item('tables'); 
    }




    public function fetchAllTicket($param = array(), $id)
    {
        $this->db->where('fk_user_id', $id);
        if (!empty($param['page']) && !empty($param['page_size'])) {
            $limit  = $param['page_size'];
            $offset = $limit * ($param['page'] - 1);
            $this->db->limit($limit, $offset);
        }
        if ($param['order_by'] && $param['order']) {
            $this->db->order_by($param['order_by'], $param['order']);
        }
        if (!empty($param['filterByStatus'])) {
            $this->db->like('tust.status', $param['filterByStatus']);
        }
        $this->db->from($this->_tbl_user_support_tickets . ' AS tust');
        return $this->db->get()->result_array();
    }


    public function fetchAllTicketCount($param = array(), $id)
    {
        $this->db->select('tust.id');
        $this->db->where('fk_user_id', $id);
        /*if (!empty($param['page']) && !empty($param['page_size'])) {
            $limit  = $param['page_size'];
            $offset = $limit * ($param['page'] - 1);
            $this->db->limit($limit, $offset);
        }*/
        /*if ($param['order_by'] && $param['order']) {
            $this->db->order_by($param['order_by'], $param['order']);
        }*/
        if (!empty($param['filterByStatus'])) {
            $this->db->like('tust.status', $param['filterByStatus']);
        }
        return $result = $this->db->count_all_results($this->_tbl_user_support_tickets . ' AS tust');
    }














    public function fetchTicketConversation($params){

        //pre($params,1);
        $this->db->select($this->_tbl_user_support_tickets.'.id,ticket_id,title,status,'.$this->_tbl_user_support_ticket_threads.'.fk_user_id,fk_admin_id,description,is_unread,'.$this->_tbl_user_support_ticket_threads.'.added_timestamp');
        $this->db->where($this->_tbl_user_support_tickets.'.id',$params['id']);
         $this->db->where($this->_tbl_user_support_tickets.'.fk_user_id',$params['user_id']);
         $this->db->from($this->_tbl_user_support_tickets);
         $this->db->join($this->_tbl_user_support_ticket_threads,$this->_tbl_user_support_tickets.'.id='.$this->_tbl_user_support_ticket_threads.'.fk_support_ticket_id');

        return $this->db->get()->result_array();
      

    }


    public function getConversationDetails($where = array()){

        //pre($where,1);
        $this->db->select('tust.id, tust.ticket_id, tust.status, tust.added_timestamp, tust.title, tust.fk_user_id, (select(max(tusth.fk_admin_id))) as fk_admin_id, tupb.profile_picture_file_extension, tupb.s3_media_version');
        $this->db->where($where);
        $this->db->join($this->_tbl_user_profile_basics . ' AS tupb', 'tupb.fk_user_id=tust.fk_user_id', 'left');
        $this->db->join($this->_tbl_user_support_ticket_threads . ' tusth', 'tusth.fk_support_ticket_id=tust.id', 'right');
        $result = $this->db->get($this->_tbl_user_support_tickets . ' AS tust')->row_array();
        $result['added_timestamp'] = date("M j, Y g:i a", strtotime($result['added_timestamp']));

        
        $result['description'] = $this->getAllTicket_descriptions($result['id']);

        $profile_picture_file_url    = ($result['profile_picture_file_extension'] != null) ? $this->config->item('bucket_url') . $result['fk_user_id'] . '/profile/' . $result['fk_user_id'] . '.' . $result['profile_picture_file_extension'] . '?versionId=' . $result['s3_media_version'] : "assets/img/avatar/avatar_1.svg";
        $result['user_profile_pic_url'] = $profile_picture_file_url;        
        $result['admin_profile_pic'] = $this->getAdminProfile_Image($result['fk_admin_id']);
        
        $result['all_conversation_threads'] = $this->getAllConversationThreads($result['id']);

        //pre($result,1);
        return $result;
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllTicket_descriptions()
     * @ Added Date               : 16-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : get Support Tickets descriptions
     * -----------------------------------------------------------------
     * @ param                    : Array(params)    
     * @ return                   : Array
     * -----------------------------------------------------------------
     * 
     */
    public function getAllTicket_descriptions($ticket_id)
    {
        //print_r($param);die;
        
        $this->db->select('tusth.description');
        $this->db->join($this->_tbl_user_support_tickets . ' AS tust', 'tust.id=tusth.fk_support_ticket_id', 'left');
        $this->db->where('tust.id', $ticket_id);
        $this->db->where('tusth.fk_admin_id', null);
        $this->db->order_by('tusth.added_timestamp', 'ASC');
        $this->db->limit(1);
        $result = $this->db->get($this->_tbl_user_support_ticket_threads . ' AS tusth')->row_array();
        return $result['description'];
    }
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllConversations()
     * @ Added Date               : 16-11-2016
     * @ Added By                 : Amit pandit
     * -----------------------------------------------------------------
     * @ Description              : get Support Tickets conversations
     * -----------------------------------------------------------------
     * @ param                    : Array(params)    
     * @ return                   : Array
     * -----------------------------------------------------------------
     * 
     */
    public function getAllConversationThreads($ticket_id)
    {
        $this->db->select('tusth.*');
        $this->db->where('tusth.fk_support_ticket_id', $ticket_id);
        $this->db->order_by('tusth.added_timestamp', 'ASC');
        $result = $this->db->get($this->_tbl_user_support_ticket_threads . ' AS tusth')->result_array();

        $newArray                  = array();
        foreach ($result as $key => $value) {
            $tempArray                = array();
            $tempArray                = $value;
            $tempArray['added_timestamp'] = date("M j, Y g:i a", strtotime($value['added_timestamp']));
            $newArray[]               = $tempArray;
        }
        return $newArray;
    }


    
    public function getAdminProfile_Image($id)
    {
        $this->db->select('ta.has_profile_picture');
        //$this->db->where('ta.has_profile_picture','is not null');
        $this->db->where('ta.id', $id);
        $result = $this->db->get($this->_tbl_admins . ' AS ta')->row_array();
        if ($result['has_profile_picture'] == 0) {
            $admin_profile_pic_url                   = "";
            $result['user_profile_picture_file_url'] = $admin_profile_pic_url;
            return $result['user_profile_picture_file_url'];
        } else {
            $this->db->select('ta.file_extension');
            $this->db->where('ta.id', $id);
            $result = $this->db->get($this->_tbl_admins . ' AS ta')->row_array();
            return $id . "." . $result['file_extension'];
        }
        //pre($result);die;
        //pre($result);die;
 

    }

    public function addTicket($params)
    {   
        
        $this->db->insert($this->_tbl_user_support_tickets,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;      
    }
    public function updateTickitID($ticket_id,$id){
        $this->db->set('ticket_id',$ticket_id);
        $this->db->where('id',$id);
        $this->db->update($this->_tbl_user_support_tickets);
      

    }
    public function addTickeThreads($params)
    {
        $this->db->insert($this->_tbl_user_support_ticket_threads,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;      
    }
}
