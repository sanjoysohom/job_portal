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
class Profile_model extends CI_Model{

     public $_table = 'tbl_users';

     public $_table_tmp_profile_education = 'tbl_user_tmp_profile_educations';
     public $_table_profile_education = 'tbl_user_profile_educations';


     public $_table_profile_basics = 'tbl_user_profile_basics';
     public $_table_tmp_profile_basics = 'tbl_user_tmp_profile_basics';
     public $_table_genders = 'master_genders';
     public $_table_marital_statuses = 'master_marital_statuses';
     public $_table_profession_types = 'master_profession_types';
     public $_master_kyc_document = 'master_kyc_documents';
     public $_table_profile_kyc = 'tbl_user_profile_kycs';
     public $_table_profile_kyc_tmp = 'tbl_user_tmp_profile_kycs';
     public $_table_residence_status = 'master_residence_statuses';
     public $_master_kyc_template = 'master_kyc_templates';
     public $_master_bank = 'master_banks';
     public $_table_profile_bank = 'tbl_user_profile_banks';
     public $_table_profile_bank_tmp = 'tbl_user_tmp_profile_banks';
     public $_master_degree = 'master_degrees';
 
     public $_master_degree_type = 'master_degree_types';


     
    function __construct(){
        //load the parent constructor
        parent::__construct();        
    }

    /*
     * --------------------------------------------------------------------------

     * @ Function Name            : addEducation()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for add education in tmp table
    */
    public function addEducation($params)
    {
        $this->db->insert($this->_table_tmp_profile_education,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateEducation()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update education in tmp table
    */
    public function updateEducation($params)
    {
        $this->db->where('id', $params['id']);
        $this->db->update($this->_table_tmp_profile_education,$params);
        return $this->db->affected_rows();      
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getEducation()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get single education details from main table
    */
    public function getEducation($education_id)
    {
        $this->db->where('id', $education_id);
        $this->db->from($this->_table_profile_education);
        return $this->db->get()->row_array();      
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getEducationTmp()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get single education details from tmp table
    */
    public function getEducationTmp($education_id)
    {
        $this->db->where('id', $education_id);
        $this->db->from($this->_table_tmp_profile_education);
        return $this->db->get()->row_array();      
    }

    
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllEducation()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getAllEducationTmp($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_tmp_profile_education);
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

        /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllEducation()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getEducationTmpShowInProfile($params)
    {
        $this->db->where('show_in_profile', '1');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_tmp_profile_education);
        $dt= $this->db->get()->row_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

     public function updateEducationTmpShowInProfile($id)
    {
        $this->db->set('show_in_profile', '0');
        $this->db->where('id', $id);
        $this->db->update($this->_table_tmp_profile_education);
    
    }

    public function getEducationShowInProfile($params)
    {
        $this->db->where('show_in_profile', '1');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_profile_education);
        $dt= $this->db->get()->row_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

     public function updateEducationShowInProfile($id)
    {
        $this->db->set('show_in_profile', '0');
        $this->db->where('id', $id);
        $this->db->update($this->_table_profile_education);
        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllEducation()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getAllEducation($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_profile_education);
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : iSInTmpTable()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function iSInTmpTable($params)
    {

        $this->db->where('fk_profile_education_id', $params['id']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->from($this->_table_tmp_profile_education);
        return $rt=$this->db->count_all_results(); 
        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllEducation()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getEducationUserWise($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['education_id']);
        $this->db->from($this->_table_tmp_profile_education);
        $dt= $this->db->get()->row_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }
 /*
     * @ Function Name            : getProfileDetails()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : get profile details
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function getProfileDetails($param = array()){
        $this->db->select($this->_table.'.display_name, utpb.*, mg.gender, mms.marital_status, mpt.profession_type,rst.residence_status');
        $this->db->where($this->_table.'.id', $param['fk_user_id']);
        $this->db->from($this->_table);
        $this->db->join($this->_table_tmp_profile_basics .' utpb' , 'utpb.fk_user_id = '.$this->_table.'.id', 'inner');
        $this->db->join($this->_table_genders .' mg', 'mg.id=utpb.fk_gender_id', 'left');
        $this->db->join($this->_table_marital_statuses .' mms', 'mms.id=utpb.fk_marital_status_id', 'left');
        $this->db->join($this->_table_profession_types .' mpt', 'mpt.id=utpb.fk_profession_type_id', 'left');
        $this->db->join($this->_table_residence_status .' rst', 'rst.id=utpb.fk_residence_status_id', 'left');
        
        $getProfileDetails = $this->db->get()->row_array();

        if(!is_array($getProfileDetails) && count($getProfileDetails) < 1){
            $this->db->select($this->_table.'.display_name, upb.*, mg.gender, mms.marital_status, mpt.profession_type,rst.residence_status');
            $this->db->where($this->_table.'.id', $param['fk_user_id']);
            $this->db->from($this->_table);
            $this->db->join($this->_table_profile_basics .' upb' , 'upb.fk_user_id = '.$this->_table.'.id', 'inner');      
            $this->db->join($this->_table_genders .' mg', 'mg.id=upb.fk_gender_id', 'left');
            $this->db->join($this->_table_marital_statuses .' mms', 'mms.id=upb.fk_marital_status_id', 'left');
            $this->db->join($this->_table_profession_types .' mpt', 'mpt.id=upb.fk_profession_type_id', 'left');
            $this->db->join($this->_table_residence_status .' rst', 'rst.id=upb.fk_residence_status_id', 'left');
            return $this->db->get()->row_array();            
        }
        return $getProfileDetails;
    }

 
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : addTempProfileBasic()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Add Profile Basic in Temp table
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function addTempProfileBasic($params){
        $this->db->insert($this->_table_tmp_profile_basics,$params);
        $insert_id = $this->db->insert_id(); 
        return $insert_id;
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchTempProfileBasic()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Fetch Profile Basic from Temp table
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function fetchTempProfileBasic($param = array()){
        $this->db->where('fk_user_id', $param['user_id']);
        $this->db->from($this->_table_tmp_profile_basics);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchTempProfileBasic()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Fetch Profile Basic from Temp table
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function fetchTempProfileMain($param = array()){
        $this->db->where('fk_user_id', $param['user_id']);
        $this->db->from($this->_table_profile_basics);
        return $this->db->get()->row_array();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateTempProfileBasic
     * @ Added Date               : 07-01-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Update Profile Basic in Temp table
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
    */
    public function updateTempProfileBasic($param = array()){  
        $this->db->where('id', $param['id']);
        $this->db->update($this->_table_tmp_profile_basics, $param);
        $affected_rows = $this->db->affected_rows(); 
        return $affected_rows;
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getBasicProfileDetailsFromMain
     * @ Added Date               : 07-01-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Fetch Profile Basic from Main table
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
    */
    public function getBasicProfileDetailsFromMain($param = array()){              
        $this->db->select($this->_table.'.display_name, upb.*, mg.gender, mms.marital_status, mpt.profession_type');
        $this->db->where($this->_table.'.id', $param['fk_user_id']);
        $this->db->from($this->_table);
        $this->db->join($this->_table_profile_basics .' upb' , 'upb.fk_user_id = '.$this->_table.'.id', 'inner');      
        $this->db->join($this->_table_genders .' mg', 'mg.id=upb.fk_gender_id', 'left');
        $this->db->join($this->_table_marital_statuses .' mms', 'mms.id=upb.fk_marital_status_id', 'left');
        $this->db->join($this->_table_profession_types .' mpt', 'mpt.id=upb.fk_profession_type_id', 'left');
        return $this->db->get()->row_array(); 
    } 

    /*
     * --------------------------------------------------------------------------

     * @ Function Name            : addEducation()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for add education in tmp table
    */
    public function updateEducationTmp($params)
    { 
        $this->db->set('s3_media_version',$params['s3_media_version']);
        $this->db->set('file_extension',$params['file_extension']);
        $this->db->set('is_file_uploaded',1);
        $this->db->where('id',$params['education_id']);
        $this->db->update($this->_table_tmp_profile_education);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getKycDocuments($params){
        $this->db->select($this->_master_kyc_document.'.data_input_prompt as document_name,'.$this->_master_kyc_template.'.id, priority_level');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_document, $this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.fk_document_id');
        $result = $this->db->get($this->_master_kyc_template)->result_array();
        return $result;
    
    }

    public function getTotalKycDocumentsMandatory($params){
        $this->db->select($this->_master_kyc_document.'.data_input_prompt as document_name,'.$this->_master_kyc_template.'. id,priority_level');
        $this->db->where('priority_level','M');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_document, $this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.fk_document_id');
        $this->db->from($this->_master_kyc_template);
        return $rt=$this->db->count_all_results(); 
    
    }

    public function getKycDocumentsMandatory($params){
        $this->db->select($this->_master_kyc_document.'.data_input_prompt as document_name,'.$this->_master_kyc_template.'. id,priority_level,'.$this->_master_kyc_template.'.id as template_id');
        $this->db->where('priority_level','M');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_document, $this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.fk_document_id');
        $result = $this->db->get($this->_master_kyc_template)->row_array();
        return $result;
       
    
    }

    public function getTotalKycDocumentsAny($params){
        $this->db->select($this->_master_kyc_document.'.data_input_prompt as document_name,'.$this->_master_kyc_template.'.id, priority_level');
        $this->db->where('priority_level','A');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_document, $this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.fk_document_id');
        $this->db->from($this->_master_kyc_template);
       return $rt=$this->db->count_all_results(); 
    
    }

    public function getKycDocumentsAny($params){
        $this->db->select($this->_master_kyc_document.'.data_input_prompt as document_name,'.$this->_master_kyc_template.'.id, priority_level');
        $this->db->where('priority_level','A');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_document, $this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.fk_document_id');
         $result = $this->db->get($this->_master_kyc_template)->result_array();
        return $result;
    
    }

    /*
     * --------------------------------------------------------------------------

     * @ Function Name            : addKyc()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for add education in tmp table
    */
    public function addKyc($params)
    {
        $this->db->insert($this->_table_profile_kyc_tmp,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateKyc()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update education in tmp table
    */
    public function updateKyc($params)
    {
        $this->db->where('id', $params['id']);
        $this->db->update($this->_table_profile_kyc_tmp,$params);
        return $this->db->affected_rows();      
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getKyc()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get single education details from main table
    */
    public function getKyc($kyc_id)
    {
        $this->db->where('id', $kyc_id);
        $this->db->from($this->_table_profile_kyc);
        return $this->db->get()->row_array();      
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getKycTmp()
     * @ Added Date               : 05-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get single education details from tmp table
    */
    public function getKycTmp($kyc_id)
    {
        $this->db->where('id', $kyc_id);
        $this->db->from($this->_table_profile_kyc_tmp);
        return $this->db->get()->row_array();      
    }

    
    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllKycTmp()
     * @ Added Date               : 05-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getAllKycTmp($params)
    {
        $this->db->select($this->_table_profile_kyc_tmp.'.*,'.$this->_master_kyc_document.'. document_name,'.$this->_master_kyc_document.'.id as doc_id');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_profile_kyc_tmp);
        $this->db->join($this->_master_kyc_template,$this->_master_kyc_template.'.id='.$this->_table_profile_kyc_tmp.'.fk_kyc_template_id');
         $this->db->join($this->_master_kyc_document,$this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.  fk_document_id');
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllKyc()
     * @ Added Date               : 05-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getAllKyc($params)
    {
        $this->db->select($this->_table_profile_kyc.'.*,'.$this->_master_kyc_document.'. document_name,'.$this->_master_kyc_document.'.id as doc_id');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_profile_kyc);
        $this->db->join($this->_master_kyc_template,$this->_master_kyc_template.'.id='.$this->_table_profile_kyc.'.fk_kyc_template_id');
         $this->db->join($this->_master_kyc_document,$this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.  fk_document_id');
        $dt= $this->db->get()->result_array();  
        return $dt;

        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getKycDetail()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get details of kyc
    */
    public function getKycDetail($params)
    {
        $this->db->select($this->_table_profile_kyc_tmp.'.*,'.$this->_master_kyc_document.'. document_name,'.$this->_master_kyc_document.'.id as doc_id');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where($this->_table_profile_kyc_tmp.'.id', $params['kyc_id']);
        $this->db->from($this->_table_profile_kyc_tmp);
        $this->db->join($this->_master_kyc_template,$this->_master_kyc_template.'.id='.$this->_table_profile_kyc_tmp.'.fk_kyc_template_id');
         $this->db->join($this->_master_kyc_document,$this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.  fk_document_id');
        $dt= $this->db->get()->row_array();  

        if(is_array($dt) && count($dt)>0){
            return $dt;
        }else{
            $this->db->select($this->_table_profile_kyc.'.*,'.$this->_master_kyc_document.'. document_name,'.$this->_master_kyc_document.'.id as doc_id');
            $this->db->where('fk_user_id', $params['user_id']);
            $this->db->where($this->_table_profile_kyc.'.id', $params['kyc_id']);
            $this->db->from($this->_table_profile_kyc);
            $this->db->join($this->_master_kyc_template,$this->_master_kyc_template.'.id='.$this->_table_profile_kyc.'.fk_kyc_template_id');
             $this->db->join($this->_master_kyc_document,$this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.  fk_document_id');
            $dt= $this->db->get()->row_array();  
            return $dt;
        
        }

        
    }


    /*
     * --------------------------------------------------------------------------

     * @ Function Name            : updateKycTmp()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for add education in tmp table
    */
    public function updateKycTmp($params,$kyc_id)
    { 
        $this->db->where('id',$kyc_id);
        $this->db->update($this->_table_profile_kyc_tmp,$params);
        
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : iSInTmpKyc()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function iSInTmpKyc($params)
    {

        $this->db->where('fk_profile_kyc_id', $params['id']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->from($this->_table_profile_kyc_tmp);
        return $rt=$this->db->count_all_results();
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateProfileBasic()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update education in tmp table
    */
    public function updateProfileBasic($params,$id)
    {
        $this->db->where('fk_user_id', $id);
        $this->db->update($this->_table_tmp_profile_basics,$params);
        return $this->db->affected_rows();      
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchIfscDetails()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : Fetch IFSC details
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function fetchIfscDetails($param = array()){
        $this->db->where('ifsc_code', $param['ifsc_code']);
        $this->db->from($this->_master_bank);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getBank()
     * @ Added Date               : 09-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get bank details
    */
    public function getBank($bank_id)
    {
        $this->db->where('id', $bank_id);
        $this->db->from($this->_table_profile_bank);
        return $this->db->get()->row_array();      
    }

    /*
     * --------------------------------------------------------------------------

     * @ Function Name            : addBankTmp()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for add education in tmp table
    */
    public function addBankTmp($params)
    {
        $this->db->insert($this->_table_profile_bank_tmp,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getKycTmp()
     * @ Added Date               : 05-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get single education details from tmp table
    */
    public function getBankTmp($bank_id)
    {
        $this->db->where('id', $bank_id);
        $this->db->from($this->_table_profile_bank_tmp);
        return $this->db->get()->row_array();      
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateKyc()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for update education in tmp table
    */
    public function updateBank($params)
    {
        $this->db->where('id', $params['id']);
        $this->db->update($this->_table_profile_bank_tmp,$params);
        return $this->db->affected_rows();      
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllBankTmp()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getAllBankTmp($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_profile_bank_tmp);
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : fetchIfscBankDetails()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : Fetch IFSC details
     * -----------------------------------------------------------------
     * @ param                    : array(param) 
     * @ return                   : 
     * -----------------------------------------------------------------
     * 
    */
    public function fetchIfscBankDetails($id){
        $this->db->where('id', $id);
        $this->db->from($this->_master_bank);
        return $this->db->get()->row_array();
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getAllBank()
     * @ Added Date               : 10-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getAllBank($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_table_profile_bank);
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

        /*
     * --------------------------------------------------------------------------
     * @ Function Name            : iSInTmpTableBank()
     * @ Added Date               : 03-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function iSInTmpTableBank($params)
    {

        $this->db->where('fk_profile_bank_id', $params['id']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->from($this->_table_profile_bank_tmp);
        return $rt=$this->db->count_all_results();
        
    }


    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getEducationTmp()
     * @ Added Date               : 02-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get single education details from tmp table
    */
    public function getBankTmpDetails($bank_id)
    {
        $this->db->where('id', $bank_id);
        $this->db->from($this->_table_profile_bank_tmp);
        $dt= $this->db->get()->row_array();   
        if(is_array($dt) && count($dt)>0){
            return $dt;
        }else{
            $this->db->where('id', $bank_id);
            $this->db->from($this->_table_profile_bank);
            $dt= $this->db->get()->row_array();   
            return $dt;

        }
    }

    public function getTemplateDetails($id){
          $this->db->select($this->_master_kyc_document.'.id, document_name,'.$this->_master_kyc_template.'. priority_level,document_type');
        $this->db->where($this->_master_kyc_template.'.id',$id);
        
        $this->db->join($this->_master_kyc_document, $this->_master_kyc_document.'.id='.$this->_master_kyc_template.'.fk_document_id');
        $result = $this->db->get($this->_master_kyc_template)->row_array();
        return $result;
    }

     /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getEducationUserMain()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for latest degree
    */
    public function getEducationUserMain($params)
    {
        $this->db->select($this->_table_profile_education.'.*,'.$this->_master_degree.'.degree_name');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->order_by("year_of_joining", "desc");
        $this->db->limit(1, 0);
        $this->db->join($this->_master_degree, $this->_master_degree.'.id='.$this->_table_profile_education.'.fk_degree_id');
        $dt= $this->db->get($this->_table_profile_education)->row_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getEducationUserTmp()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getEducationUserTmp($params)
    {
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['education_id']);
        $dt= $this->db->get($this->_table_tmp_profile_education)->row_array();  
        return $dt;
      
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : getEducationUserTmp()
     * @ Added Date               : 16-08-2016
     * @ Added By                 : Mousumi Bakshi
     * -----------------------------------------------------------------
     * @ Description              : This function is used for get all education details from main table
    */
    public function getEducationMain($params)
    {
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['education_id']);
        $dt= $this->db->get($this->_table_profile_education)->row_array();  
        return $dt;
      
    }

    /*
     * --------------------------------------------------------------------------
     * @ Function Name            : updateTempProfileBasic
     * @ Added Date               : 07-01-2016
     * @ Added By                 : Subhankar Pramanik
     * -----------------------------------------------------------------
     * @ Description              : Update Profile Basic in Temp table
     * -----------------------------------------------------------------
     * @ param                    : array(param)
     * @ return                   : int()
     * -----------------------------------------------------------------
     * 
    */
    public function updateTempEducation($param = array()){  
        $this->db->where('id', $param['id']);
        $this->db->update($this->_table_tmp_profile_education, $param);
        $affected_rows = $this->db->affected_rows(); 
        return $affected_rows;
    }

    public function updateEducationMain($param = array()){  
        $this->db->where('id', $param['id']);
        $this->db->update($this->_table_profile_education, $param);
        $affected_rows = $this->db->affected_rows(); 
        return $affected_rows;
    }

    public function deleteProfileBasic($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->delete($this->_table_tmp_profile_basics);
    }

    public function deleteProfileKyc($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $this->db->delete($this->_table_profile_kyc_tmp);
    }

    public function deleteProfileEducation($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['education_id']);
        $this->db->delete($this->_table_tmp_profile_education);
    }

    public function deleteProfileBank($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['bank_id']);
        $this->db->delete($this->_table_profile_bank_tmp);
    }

    public function isinKycMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $this->db->from('tbl_user_profile_kycs');
        return $rt=$this->db->count_all_results(); 

    }

    public function isinKycTemp($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $this->db->from($this->_table_profile_kyc_tmp);
        return $rt=$this->db->count_all_results(); 

    }

    public function getKycMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $dt= $this->db->get('tbl_user_profile_kycs')->row_array();  
        return $dt;

    }

    public function isinEducationMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['education_id']);
        $this->db->from($this->_table_profile_education);
        return $this->db->count_all_results(); 

    }

    public function isinBankMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['bank_id']);
        $this->db->from($this->_table_profile_bank);
        return $this->db->count_all_results(); 

    }

    public function getBankDetails($params){
        $this->db->select($this->_table_profile_bank.'.account_number,'.$this->_master_bank.'.*');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->join($this->_master_bank,$this->_table_profile_bank.'.fk_bank_id='.$this->_master_bank.'.id');
        $this->db->from($this->_table_profile_bank);
        $this->db->limit(0,1);
        return $this->db->get()->row_array(); 


    }

    public function getUpdateIsPrimaryNo($params)
    {

        $this->db->set('is_primary', 'N');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->update($this->_table_profile_bank);
    }

    public function getUpdateIsPrimaryYes($params)
    {

        $this->db->set('is_primary', 'Y');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['bank_id']);
        $this->db->update($this->_table_profile_bank);
    }

    public function getTotalIsPrimaryNo($params){
        $this->db->where('is_primary', 'Y');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id!=', $params['bank_id']);
        $this->db->from($this->_table_profile_bank);
        return $this->db->count_all_results(); 

    }

    public function getUpdateAllIsPrimaryNo($params)
    {

        $this->db->set('is_primary', 'N');
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->update($this->_table_profile_bank);
    }

   

    //public 
    /**********************************************************************************************************************************
     * End of user model
     *********************************************************************************************************************************/
}