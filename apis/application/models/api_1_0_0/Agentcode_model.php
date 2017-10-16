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
class Agentcode_model extends CI_Model
{

     public $_table = 'master_agent_codes';
     public $_table_agent_codes = 'tbl_user_agent_codes';
     public $_table_marital_status = 'master_marital_statuses';
     public $_table_residence_status = 'master_residence_statuses';
     public $_table_degree_type = 'master_degree_types';
     public $_table_degree = 'master_degrees';
     public $_table_field_of_study = 'master_field_of_studies';
     public $_master_pincode = 'master_pincodes';
     
     
    function __construct()
    {
       
        //load the parent constructor
        parent::__construct();        
         
    }
    
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : check_emailid()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function check_agentcode($data)
    {
        $this->db->where('agent_code', $data['referral_code']);
        $this->db->where('is_active', 1);
        $this->db->from($this->_table);
        return $this->db->count_all_results();        
    }

    public function updateAgentCode($data){
        $this->db->where('agent_code', $data['referral_code']);
        $this->db->set('is_active', 0);
        $this->db->update($this->_table);

    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function addUserAgentCode($params)
    {
      
        $this->db->insert($this->_table_agent_codes,$params);
        return $this->db->affected_rows();       
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 03-08-2016
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
     * @ Function Name            : addUser()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchMatrialStatus($params)
    {
        $this->db->select('*');
        $this->db->from($this->_table_marital_status);
        return $this->db->get()->result_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addUser()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function fetchResidenceStatus($params)
    {
        $this->db->select('*');
        $this->db->from($this->_table_residence_status);
        return $this->db->get()->result_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegreeType()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for checking email id is exist or not
    */
    public function getDegreeType($params)
    {
        $this->db->select('*');
        
        $this->db->from($this->_table_degree_type);
        return $this->db->get()->result_array();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegree()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getDegree($params)
    {
        
        $this->db->select('*');
        if($params['search_text']!=''){
            $this->db->like('degree_name', $params['search_text'], 'after');
        }
        $this->db->from($this->_table_degree);
        return $this->db->get()->result_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegreeName()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getDegreeName($fk_degree_id)
    {
        $this->db->select('*');
        $this->db->where('id', $fk_degree_id);
        $this->db->from($this->_table_degree);
        return $this->db->get()->row_array();
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegree()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getFieldOfStudies($params)
    {
        $this->db->select('*');
        if($params['search_text']!=''){
            $this->db->like('field_of_study', $params['search_text'], 'after');
        }
        $this->db->from($this->_table_field_of_study);
        return $this->db->get()->result_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegree()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getFieldOfStudyName($params)
    {
        $this->db->select('*');
        $this->db->where('id', $params['fk_field_of_study_id']);
        $this->db->from($this->_table_field_of_study);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addFieldOfStudies()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function addFieldOfStudies($params)
    {
        $this->db->where('field_of_study', $params['study_name']);
        $this->db->from($this->_table_field_of_study);
        $is_exist=$this->db->count_all_results(); 

        if($is_exist==0){
            $this->db->set('field_of_study', $params['study_name']);
            $this->db->insert($this->_table_field_of_study);
        }

        $this->db->where('field_of_study', $params['study_name']);
        $this->db->from($this->_table_field_of_study);
        $row=$this->db->get()->row_array();
        return $row['id'];
        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addFieldOfStudies()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function addDegree($params)
    {
        $this->db->where('degree_name', $params['degree_name']);
        $this->db->from($this->_table_degree);
        $is_exist=$this->db->count_all_results(); 

        if($is_exist==0){
            $this->db->set('degree_name', $params['degree_name']);
            $this->db->insert($this->_table_degree);
        }

        $this->db->where('degree_name', $params['degree_name']);
        $this->db->from($this->_table_degree);
        $row=$this->db->get()->row_array();
        return $row['id'];
        
    }
    
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegreeTypeName()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getDegreeTypeName($fk_degree_type_id)
    {
        $this->db->select('*');
        $this->db->where('id', $fk_degree_type_id);
        $this->db->from($this->_table_degree_type);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegreeTypeName()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getPincodeDetails($pincode_id)
    {
        $this->db->select('*');
        $this->db->where('pin_code', $pincode_id);
        $this->db->from($this->_master_pincode);
        return $this->db->get()->row_array();
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getDegreeTypeName()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used to get degree
    */
    public function getPincodeDetailsId($pincode_id)
    {
        $this->db->select('*');
        $this->db->where('id', $pincode_id);
        $this->db->from($this->_master_pincode);
        return $this->db->get()->row_array();
    }

    
    //public 
    /**********************************************************************************************************************************
     * End of user model
     *********************************************************************************************************************************/
}