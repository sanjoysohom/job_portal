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
class Profile_model extends CI_Model
{

    /* public $_table = 'tbl_users';
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
     //public $_tbl_user_basic = 'tbl_user_profile_basics';
     public $_tbl_mcoin_earning = 'master_mcoin_earnings';
     public $_tbl_user_mcoins_earning = 'tbl_user_mcoins_earnings';
     public $_tbl_history_user_email = 'tbl_history_user_emails';
     public $_tbl_history_user_mobile_number = 'tbl_history_user_mobile_numbers';
     public $_tbl_user_level = 'tbl_user_levels';
     public $_table_user_approval = 'tbl_user_approvals';
     public $_table_admin_data_collection = 'tbl_admin_data_collections';*/
     //public $_tbl_user_profile_basics = 'tbl_user_profile_basics';
      
    public $_table                          = 'tbl_users';
    public $_tbl_user_basic                 = 'tbl_user_profile_basics';
    public $_tbl_user_tmp_profile_basics    = 'tbl_user_tmp_profile_basics';
    public $_tbl_history_user_profile_basics= 'tbl_history_user_profile_basics';
    public $_tbl_user_profile_educations    = 'tbl_user_profile_educations';
    public $_tbl_user_tmp_profile_educations= 'tbl_user_tmp_profile_educations';
    public $_tbl_history_user_profile_educations = 'tbl_history_user_profile_educations';
    public $_tbl_user_profile_kycs          = 'tbl_user_profile_kycs';
    public $_tbl_user_tmp_profile_kycs      = 'tbl_user_tmp_profile_kycs';
    public $_tbl_history_user_profile_kycs  = 'tbl_history_user_profile_kycs';
    public $_tbl_user_profile_banks         = 'tbl_user_profile_banks';
    public $_tbl_user_tmp_profile_banks     = 'tbl_user_tmp_profile_banks';
    public $_tbl_history_user_profile_banks = 'tbl_history_user_profile_banks';
    public $_master_kyc_templates           = 'master_kyc_templates';
    public $_master_kyc_documents           = 'master_kyc_documents';
    public $_table_user_type                 = 'tbl_user_types';
    public $_master_profession_types        = 'master_profession_types';
    public $_tbl_user_connections           = 'tbl_user_connections';
    public $_tbl_user_mcoins_earnings       = 'tbl_user_mcoins_earnings';
    public $_master_genders                 = 'master_genders';
    public $_master_marital_statuses        = 'master_marital_statuses';
    public $_master_degree_types            = 'master_degree_types';
    public $_master_degrees                 = 'master_degrees';
    public $_master_field_of_studies        = 'master_field_of_studies';
    public $_master_pincodes                = 'master_pincodes';
    public $_tbl_user_referals              = 'tbl_user_referals';
    public $_master_mcoin_earnings          = 'master_mcoin_earnings';
    public $_tbl_user_levels                = 'tbl_user_levels';
    public $_master_user_levels             = 'master_user_levels';
    public $_master_banks                   = 'master_banks';
    public $_tbl_user_custom_adjustments    = 'tbl_user_custom_adjustments';
    public $_master_mpokket_accounts        = 'master_mpokket_accounts';
    public $_tbl_user_mpokket_accounts      = 'tbl_user_mpokket_accounts';
    public $_tbl_user_approvals             = "tbl_user_approvals";
    public $_master_reward_earnings         = "master_reward_earnings";
    public $_tbl_agent_reward_earnings      = 'tbl_agent_reward_earnings';

    public $_tbl_user_mobile_devices        = 'tbl_user_mobile_devices';
    public $_tbl_user_loginkeys             = 'tbl_user_loginkeys';
    public $_master_residence_statuses      = 'master_residence_statuses';

    

     
    function __construct()
    {
       
        //load the parent constructor
        parent::__construct();        
         
    }



    public function fetchProfileDeatilsAll($id)
    {
        $this->db->select($this->_table.'.*', $this->_table_user_type.'.user_code,user_mode,is_agent',$this->_tbl_user_basic.'.profile_picture_file_extension,s3_media_version');
        $this->db->where($this->_table.'.id', $id);
        $this->db->from($this->_table);
        $this->db->join($this->_table_user_type,$this->_table.'.id='.$this->_table_user_type.'.fk_user_id');
        $this->db->join($this->_tbl_user_basic,$this->_table.'.id='.$this->_tbl_user_basic.'.fk_user_id','left');
        return $this->db->get()->row_array();
    }

    public function fetchTempProfileMain($param = array()){
        $this->db->where('fk_user_id', $param['user_id']);
        $this->db->from($this->_tbl_user_basic);
        return $this->db->get()->row_array();
    }


    public function getMainUserBasicDetails($param=array())
    {
        $this->db->select('up.*, mpt.profession_type, mg.gender, ms.marital_status ,mrs.residence_status');
        $this->db->join($this->_master_profession_types." as mpt","mpt.id = up.fk_profession_type_id", "LEFT");
        $this->db->join($this->_master_genders." as mg","mg.id = up.fk_gender_id", "LEFT");
        $this->db->join($this->_master_marital_statuses." as ms","ms.id = up.fk_marital_status_id", "LEFT");
        $this->db->join($this->_master_residence_statuses." as mrs","mrs.id = up.fk_residence_status_id", "LEFT");



        if($param['user_id'])
        {
            $this->db->where('up.fk_user_id',$param['user_id']);
        }
        $row = $this->db->get($this->_tbl_user_basic." as up")->row_array();

        //echo $this->db->last_query();die;
        return $row;
    }


    


    /***********************************************/

public function fetchTempProfileBasic($param = array()){
        //pre($param,1);
        $this->db->select('tutpb.* ,mms.marital_status,mg.gender,mpt.profession_type,mrs.residence_status');
        $this->db->where('fk_user_id', $param['user_id']);
        //$this->db->from($this->_tbl_user_tmp_profile_basics);
        $this->db->join($this->_master_marital_statuses .' mms', 'mms.id=tutpb.fk_marital_status_id', 'left');
        $this->db->join($this->_master_genders .' mg', 'mg.id=tutpb.fk_gender_id', 'left');
        $this->db->join($this->_master_profession_types .' mpt', 'mpt.id=tutpb.fk_profession_type_id', 'left');
        $this->db->join($this->_master_residence_statuses." as mrs","mrs.id = tutpb.fk_residence_status_id", "LEFT");
        return $this->db->get($this->_tbl_user_tmp_profile_basics. ' as tutpb ' )->row_array();
    }


public function getBasicProfileDetailsFromMain($param = array()){              
        $this->db->select($this->_table.'.display_name, upb.*, mg.gender, mms.marital_status, mpt.profession_type');
        $this->db->where($this->_table.'.id', $param['fk_user_id']);
        $this->db->from($this->_table);
        $this->db->join($this->_tbl_user_basic .' upb' , 'upb.fk_user_id = '.$this->_table.'.id', 'inner');      
        $this->db->join($this->_master_genders .' mg', 'mg.id=upb.fk_gender_id', 'left');
        $this->db->join($this->_master_marital_statuses .' mms', 'mms.id=upb.fk_marital_status_id', 'left');
        $this->db->join($this->_master_profession_types .' mpt', 'mpt.id=upb.fk_profession_type_id', 'left');
        return $this->db->get()->row_array(); 
    } 

public function updateTempProfileBasic($param = array()){  
        $this->db->where('id', $param['id']);
        $this->db->update($this->_tbl_user_tmp_profile_basics, $param);
        $affected_rows = $this->db->affected_rows(); 
        return $affected_rows;
    }


public function addTempProfileBasic($params){
        $this->db->insert($this->_tbl_user_tmp_profile_basics,$params);
        $insert_id = $this->db->insert_id(); 
        return $insert_id;
    }

public function updateProfileBasic($params,$id)
    {
        $this->db->where('fk_user_id', $id);
        $this->db->update($this->_tbl_user_tmp_profile_basics,$params);
        return $this->db->affected_rows();      
    }


    public function fetchMatrialStatus()
    {
        $this->db->select('*');
        $this->db->from($this->_master_marital_statuses);
        return $this->db->get()->result_array();
    }

    public function fetchResidenceStatus()
    {
        $this->db->select('*');
        $this->db->from($this->_master_residence_statuses);
        return $this->db->get()->result_array();
    }

    public function fetchPinCode($param = array()){
        $this->db->select('city_name,state_name');
        $this->db->where('pin_code', $param['pin_code']);

        $this->db->from($this->_master_pincodes);
        return $this->db->get()->row_array();
    }



    public function getAllBankTmp($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_tbl_user_tmp_profile_banks);
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

    public function fetchIfscBankDetails($id){
        $this->db->where('id', $id);
        $this->db->from($this->_master_banks);
        return $this->db->get()->row_array();
    }

    public function getAllBank($params)
    {

        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_tbl_user_profile_banks);
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }

    public function iSInTmpTableBank($params)
    {

        $this->db->where('fk_profile_bank_id', $params['id']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->from($this->_tbl_user_tmp_profile_banks);
        return $rt=$this->db->count_all_results();
        
    }

//*********bank**////


    public function checkifBankAddedTmp($userId)
    {
        $this->db->where('fk_user_id', $userId);
        $this->db->from($this->_tbl_user_tmp_profile_banks);
        return $this->db->get()->row_array();      
    }

    public function checkifBankAddedMain($userId)
    {
        $this->db->where('fk_user_id', $userId);
        $this->db->from($this->_tbl_user_profile_banks);
        return $this->db->get()->row_array();      
    }



    public function getBankTmp($bank_id)
    {
        $this->db->where('id', $bank_id);
        $this->db->from($this->_tbl_user_tmp_profile_banks);
        return $this->db->get()->row_array();      
    }

public function updateBank($params)
    {
        $this->db->where('id', $params['id']);
        $this->db->update($this->_tbl_user_tmp_profile_banks,$params);
        return $this->db->affected_rows();      
    }

   public function getBank($bank_id)
    {
        $this->db->where('id', $bank_id);
        $this->db->from($this->_tbl_user_profile_banks);
        return $this->db->get()->row_array();      
    }

    public function addBankTmp($params)
    {
        $this->db->insert($this->_tbl_user_tmp_profile_banks,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

   public function fetchIfscDetails($param = array()){
        $this->db->where('ifsc_code', $param['ifsc_code']);
        $this->db->from($this->_master_banks);
        return $this->db->get()->row_array();
    }
public function getBankTmpDetails($bank_id)
    {
        $this->db->where('id', $bank_id);
        $this->db->from($this->_tbl_user_tmp_profile_banks);
        $dt= $this->db->get()->row_array();   
        if(is_array($dt) && count($dt)>0){
            return $dt;
        }else{
            $this->db->where('id', $bank_id);
            $this->db->from($this->_tbl_user_profile_banks);
            $dt= $this->db->get()->row_array();   
            return $dt;

        }
    }



public function getPrimaryBank($param = array())
    {
   
        $this->db->where('fk_user_id', $param['user_id']);
        $this->db->where('is_primary', 'Y');
        $this->db->from($this->_tbl_user_profile_banks);
        $dt= $this->db->get()->row_array();   
        return $dt;


    }






    public function isinBankMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['bank_id']);
        $this->db->from($this->_tbl_user_profile_banks);
        return $this->db->count_all_results(); 

    }
/****KYC  ***///



public function getDocDetails($params = array()){
        //pre($params,1);
        $this->db->select('fk_profession_type_id,document_type,user_mode');
        $this->db->where('user_mode','L');
       
        $result = $this->db->get($this->_master_kyc_templates)->row_array();
        return $result;
    
    }






    public function getKycDocuments($params){
        //pre($params,1);
        $this->db->select($this->_master_kyc_documents.'.data_input_prompt as document_name,'.$this->_master_kyc_templates.'.id, priority_level');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_documents, $this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.fk_document_id');
        $result = $this->db->get($this->_master_kyc_templates)->result_array();
        return $result;
    
    }

    public function getTotalKycDocumentsMandatory($params){
        $this->db->select($this->_master_kyc_documents.'.data_input_prompt as document_name,'.$this->_master_kyc_templates.'. id,priority_level');
        $this->db->where('priority_level','M');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_documents, $this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.fk_document_id');
        $this->db->from($this->_master_kyc_templates);
        return $rt=$this->db->count_all_results(); 
    
    }

    public function getKycDocumentsMandatory($params){
        $this->db->select($this->_master_kyc_documents.'.data_input_prompt as document_name,'.$this->_master_kyc_templates.'. id,'.$this->_master_kyc_templates.'.id as template_id');
        $this->db->where('priority_level','M');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_documents, $this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.fk_document_id');
        $result = $this->db->get($this->_master_kyc_templates)->row_array();
        return $result;
       
    
    }

    public function getTotalKycDocumentsAny($params){
        $this->db->select($this->_master_kyc_documents.'.data_input_prompt as document_name,'.$this->_master_kyc_templates.'.id');
        $this->db->where('priority_level','A');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_documents, $this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.fk_document_id');
        $this->db->from($this->_master_kyc_templates);
       return $rt=$this->db->count_all_results(); 
    
    }
     public function getKycDocumentsAny($params){
        $this->db->select($this->_master_kyc_documents.'.data_input_prompt as document_name,'.$this->_master_kyc_templates.'.id');
        $this->db->where('priority_level','A');
        $this->db->where('document_type',$params['document_type']);
        $this->db->where('user_mode',$params['user_mode']);
        $this->db->where('fk_profession_type_id',$params['fk_profession_type_id']);
        $this->db->join($this->_master_kyc_documents, $this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.fk_document_id');
         $result = $this->db->get($this->_master_kyc_templates)->result_array();
        return $result;
    
    }

    public function getKycTmp($kyc_id)
    {
        $this->db->where('id', $kyc_id);
        $this->db->from($this->_tbl_user_tmp_profile_kycs);
        return $this->db->get()->row_array();      
    }
    public function updateKyc($params,$whereId)
    {
        $this->db->where('id', $whereId);
        $this->db->update($this->_tbl_user_tmp_profile_kycs,$params);
        return $this->db->affected_rows();      
    }
    
     public function getKyc($kyc_id)
    {
        $this->db->where('id', $kyc_id);
        $this->db->from($this->_tbl_user_profile_kycs);
        return $this->db->get()->row_array();      
    }
    public function addKyc($params)
    {
        $this->db->insert($this->_tbl_user_tmp_profile_kycs,$params);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function updateKycTmp($params,$kyc_id)
    { 
        $this->db->where('id',$kyc_id);
        $this->db->update($this->_tbl_user_tmp_profile_kycs,$params);
        
    }
    public function getAllKycTmp($params)
    {
        $this->db->select($this->_tbl_user_tmp_profile_kycs.'.*,'.$this->_master_kyc_documents.'. document_name,'.$this->_master_kyc_documents.'.id as doc_id');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_tbl_user_tmp_profile_kycs);
        $this->db->join($this->_master_kyc_templates,$this->_master_kyc_templates.'.id='.$this->_tbl_user_tmp_profile_kycs.'.fk_kyc_template_id');
         $this->db->join($this->_master_kyc_documents,$this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.  fk_document_id');
        $dt= $this->db->get()->result_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }
public function getTemplateDetails($id){
          $this->db->select($this->_master_kyc_documents.'.id, document_name,'.$this->_master_kyc_templates.'. priority_level,document_type');
        $this->db->where($this->_master_kyc_templates.'.id',$id);
        
        $this->db->join($this->_master_kyc_documents, $this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.fk_document_id');
        $result = $this->db->get($this->_master_kyc_templates)->row_array();
        return $result;
    }

public function getAllKyc($params)
    {
        $this->db->select($this->_tbl_user_profile_kycs.'.*,'.$this->_master_kyc_documents.'. document_name,'.$this->_master_kyc_documents.'.id as doc_id');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->from($this->_tbl_user_profile_kycs);
        $this->db->join($this->_master_kyc_templates,$this->_master_kyc_templates.'.id='.$this->_tbl_user_profile_kycs.'.fk_kyc_template_id');
         $this->db->join($this->_master_kyc_documents,$this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.  fk_document_id');
        $dt= $this->db->get()->result_array();  
        return $dt;

        
    }
    public function iSInTmpKyc($params)
    {

        $this->db->where('fk_profile_kyc_id', $params['id']);
        $this->db->where('fk_user_id', $params['fk_user_id']);
        $this->db->from($this->_tbl_user_tmp_profile_kycs);
        return $rt=$this->db->count_all_results();
    }

public function getKycDetail($params)
    {
        $dt = array();
        
        if($params['kyc_status'] != 'A'){

            $this->db->select($this->_tbl_user_tmp_profile_kycs.'.*,'.$this->_master_kyc_documents.'. document_name,'.$this->_master_kyc_documents.'.id as doc_id');
            $this->db->where('fk_user_id', $params['user_id']);
            $this->db->where($this->_tbl_user_tmp_profile_kycs.'.id', $params['kyc_id']);
            $this->db->from($this->_tbl_user_tmp_profile_kycs);
            $this->db->join($this->_master_kyc_templates,$this->_master_kyc_templates.'.id='.$this->_tbl_user_tmp_profile_kycs.'.fk_kyc_template_id');
             $this->db->join($this->_master_kyc_documents,$this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.  fk_document_id');
            $dt= $this->db->get()->row_array(); 
        } 

        if(is_array($dt) && count($dt)>0){
            return $dt;
        }else{
            $this->db->select($this->_tbl_user_profile_kycs.'.*,'.$this->_master_kyc_documents.'. document_name,'.$this->_master_kyc_documents.'.id as doc_id');
            $this->db->where('fk_user_id', $params['user_id']);
            $this->db->where($this->_tbl_user_profile_kycs.'.id', $params['kyc_id']);
            $this->db->from($this->_tbl_user_profile_kycs);
            $this->db->join($this->_master_kyc_templates,$this->_master_kyc_templates.'.id='.$this->_tbl_user_profile_kycs.'.fk_kyc_template_id');
             $this->db->join($this->_master_kyc_documents,$this->_master_kyc_documents.'.id='.$this->_master_kyc_templates.'.  fk_document_id');
            $dt= $this->db->get()->row_array();  
            return $dt;        
        }
}
   public function isinKycMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $this->db->from($this->_tbl_user_profile_kycs);
        return $rt=$this->db->count_all_results(); 

    }

    public function isinKycTemp($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $this->db->from($this->_tbl_user_tmp_profile_kycs);
        return $rt=$this->db->count_all_results(); 

    }

     public function getKycMain($params){
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['kyc_id']);
        $dt= $this->db->get($this->_tbl_user_profile_kycs)->row_array();  
        return $dt;

    }

    

    public function getEducationUserMain($params)
    {
        $this->db->select($this->_tbl_user_profile_educations.'.*,'.$this->_master_degrees.'.degree_name');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->order_by("year_of_joining", "desc");
        $this->db->limit(1, 0);
        $this->db->join($this->_master_degrees, $this->_master_degrees.'.id='.$this->_tbl_user_profile_educations.'.fk_degree_id');
        $dt= $this->db->get($this->_tbl_user_profile_educations)->row_array();  
        //print_r($dt);  
        return $dt;
       // die();  
    }



      public function getTotalIsPrimaryNo($params){
        $this->db->where('is_primary', 'Y');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id!=', $params['bank_id']);
        $this->db->from($this->_tbl_user_profile_banks);
        return $this->db->count_all_results(); 

    }


    public function setAsPrimaryNo($params){
        $this->db->set('is_primary', 'N');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('is_primary', 'Y');
        $this->db->update($this->_tbl_user_profile_banks);
    }


    public function setAsPrimaryYes($params)
    {
        $this->db->set('is_primary', 'Y');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('id', $params['bank_id']);
        $this->db->where('is_primary', 'N');
        $this->db->update($this->_tbl_user_profile_banks);
    }


    public function getBankDetails($params){
        $this->db->select($this->_tbl_user_profile_banks.'.account_number,'.$this->_master_banks.'.*');
        $this->db->where('fk_user_id', $params['user_id']);
        $this->db->where('is_primary', 'Y');
        $this->db->join($this->_master_banks,$this->_tbl_user_profile_banks.'.fk_bank_id='.$this->_master_banks.'.id');
        $this->db->from($this->_tbl_user_profile_banks);
        $this->db->limit(0,1);
        return $this->db->get()->row_array(); 


    }

public function fetchUserDeatils($id)
    {

        $this->db->where('id', $id);
        $this->db->from($this->_table);
        return $this->db->get()->row_array();
    }


/*public function getDetailsMPokketAccount($params){

      $this->db->where('fk_user_id',$params['user_id']);
      $this->db->from($this->_tbl_user_mpokket_accounts);
      $row=$this->db->get()->row_array();
      return $row;

    }*/












 }