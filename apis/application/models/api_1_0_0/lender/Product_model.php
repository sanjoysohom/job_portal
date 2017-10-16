<?php
/* * ******************************************************************
 * User model for Mobile Api 
  ---------------------------------------------------------------------
 * @ Added by                 : Mousumi Bakshi 
 * @ Framework                : CodeIgniter
 * @ Added Date               : 12-09-2016
  ---------------------------------------------------------------------
 * @ Details                  : It Cotains all the api related product
  ---------------------------------------------------------------------
 ***********************************************************************/
class Product_model extends CI_Model
{

     public $_table = 'master_products';
     public $_table_prod_variants = 'master_product_variants';
     public $_table_tier_usage_fee_discounts = 'master_tier_usage_fee_discounts';
     public $_table_user_custom_adjustment = 'tbl_user_custom_adjustments';
     public $_table_user_loan = 'tbl_user_loans';
     public $_table_user_loan_variant = 'tbl_user_loan_variants';
     public $_table_user_loan_disbursement = 'tbl_user_loan_disbursement';
     public $_table_user_loan_extras = 'tbl_user_loan_extras';
     public $_table_user_profile_basic = 'tbl_user_profile_basics';
     public $_table_user_profile_education = 'tbl_user_profile_educations';
     public $_master_pincode = 'master_pincodes';
     public $_master_payment_type = 'master_payment_types';
     public $_table_user_mpokket_account = 'tbl_user_mpokket_accounts';
     public $_table_mpokket_fund = 'tbl_mpokket_funds';
     public $_table_user_loan_repayment_sch = 'tbl_user_loan_repayment_schedules';
     public $_table_master_user_level = 'master_user_levels';
     public $_table_user_referal = 'tbl_user_referals';
     public $_table_master_mcoin_earnings = 'master_mcoin_earnings';
     public $_table_user_loan_mcoin_earning = 'tbl_user_loan_mcoin_earnings';
     public $_table_user_mcoins_earning = 'tbl_user_mcoins_earnings';
     public $_table_cash_transfer = 'tbl_cash_transfers';
     public $_table_mpokket_export = 'tbl_mpokket_exports';
   
     

     
    function __construct()
    {
       
        //load the parent constructor
        parent::__construct();        
         
    }

    public function getDetailsMPokketAccount($params){

      $this->db->where('fk_user_id',$params['user_id']);
      $this->db->from($this->_table_user_mpokket_account);
      $row=$this->db->get()->row_array();
      return $row;

    }
    public function getwalletAmount($params){
      $amount=0;
      $this->db->select_sum('transfer_amount');
      $this->db->where('transfer_type','F');
      $this->db->where('fk_user_mpokket_account_id',$params['id']);
      $this->db->from($this->_table_mpokket_fund);
      $rowf=$this->db->get()->row_array();
      $fund_amount=$rowf['transfer_amount'];

      $amount=0;
      $this->db->select_sum('transfer_amount');
      $this->db->where('transfer_type','R');
      $this->db->where('fk_user_mpokket_account_id',$params['id']);
      $this->db->from($this->_table_mpokket_fund);
      $row=$this->db->get()->row_array();
      $receipt_amount=$row['transfer_amount'];

      $this->db->select_sum('transfer_amount');
      $this->db->where('transfer_type','P');
      $this->db->where('fk_user_mpokket_account_id',$params['id']);
      $this->db->from($this->_table_mpokket_fund);
      $rowp=$this->db->get()->row_array();
      $payment_amount=$rowp['transfer_amount'];

      $this->db->select_sum('transfer_amount');
      $this->db->where('transfer_type','W');
      $this->db->where('fk_user_mpokket_account_id',$params['id']);
      $this->db->from($this->_table_mpokket_fund);
      $row_w=$this->db->get()->row_array();
      $wallet_amount=$row_w['transfer_amount'];

      $tot_credit_amount=$fund_amount+$receipt_amount;
      $amount=$tot_credit_amount-($payment_amount+$wallet_amount);


      //print_r($row);
     
      return $amount;

    }

    public function getLockingAmount($params){
      $lock_amount=0;
      $this->db->select_sum('calc_arl');
      $this->db->where('loan_offered_by_user_id',$params['user_id']);
      $this->db->where('loan_action_type',NULL);
      $this->db->from($this->_table_user_loan);
      $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
      $row=$this->db->get()->row_array();
      $lock_amount=$row['calc_arl'];
      return $lock_amount;

    }

    public function getAllRequest($params = array()){
       
       //PRE($params,1);
        $this->db->select($this->_table_user_loan.'.fk_user_id,'.$this->_table_user_loan.'.fk_payment_type_id,'.$this->_table_user_loan.'.loan_request_timestamp ,' .$this->_table_user_loan_variant.'.*,'.$this->_table_user_profile_basic.'.display_name,'.$this->_table_user_profile_education.'.name_of_institution,'.$this->_table_user_profile_education.'.id as education_id,'.$this->_master_pincode.'.city_name,'.$this->_master_pincode.'.state_name,'.$this->_master_payment_type.'.payment_type');

        $this->db->where('loan_offered_by_user_id',NULL);

        if($params['search_input_principle']!=''){
          $this->db->where('calc_arl',$params['search_input_principle']);
        }

        if($params['search_input_npm']!=''){
          $this->db->where('input_npm',$params['search_input_npm']);
        }

        if($params['search_fk_payment_type_id']!=''){
          $this->db->where('fk_payment_type_id',$params['search_fk_payment_type_id']);
        }

        if($params['search_city_name']!=''){
          $this->db->like('city_name' ,$params['search_city_name']);
        }

        if($params['search_state_name']!=''){
          $this->db->like('state_name',$params['search_state_name']);
        }

        if($params['search_name_of_institution']!=''){
          $this->db->like('name_of_institution' ,$params['search_name_of_institution']);
        }



        if(!empty($param['order_by']) && !empty($param['order'])){
            $this->db->order_by($param['order_by'],$param['order']);
        }
        
        if(!empty($param['page']) && !empty($param['page_size'])){
            $limit = $param['page_size'];
            $offset = $limit*($param['page']-1);
            $this->db->limit($limit, $offset);
        }

        $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
       $this->db->join($this->_table_user_profile_basic,$this->_table_user_loan.'.fk_user_id='.$this->_table_user_profile_basic.'.fk_user_id');
       $this->db->join($this->_table_user_profile_education,$this->_table_user_loan.'.fk_user_id='.$this->_table_user_profile_education.'.fk_user_id AND '.$this->_table_user_profile_education.'.show_in_profile=1');
       $this->db->join($this->_master_pincode,$this->_table_user_profile_education.'.fk_pincode_id='.$this->_master_pincode.'.id');
       $this->db->join($this->_master_payment_type,$this->_table_user_loan.'.fk_payment_type_id='.$this->_master_payment_type.'.id');

        $allRecords=$this->db->get($this->_table_user_loan)->result_array();
        //die($this->db->last_query());
        return $allRecords;
    }

    public function getProductDisbursed()
    {
        $this->db->select('DISTINCT(calc_da) as amount'); 
        $this->db->from($this->_table_prod_variants);
        //$this->db->where($this->_table.'.is_available2','1');
        $this->db->join($this->_table,$this->_table_prod_variants.'.fk_product_id='.$this->_table.'.id AND '.$this->_table.'.is_available=1');
        $ow=$this->db->get()->result_array();
        return $ow;
    }

    public function getPaymentType()
    {
        $this->db->select(''); 
        $this->db->from($this->_master_payment_type);
        //$this->db->where($this->_table.'.is_available2','1');
        $ow=$this->db->get()->result_array();
        return $ow;
    }




    public function getAllLoans(){
      $this->db->select($this->_table_user_loan_variant.'.*');
       $this->db->where('loan_offered_by_user_id',NULL);
       $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');

       $allRecords=$this->db->get($this->_table_user_loan)->result_array();

        return $allRecords;
    }

    public function allCashFlow($params){

      $this->db->where('fk_user_mpokket_account_id',$params['id']);
      $this->db->from($this->_table_mpokket_fund);
      $rows=$this->db->get()->result_array();
      return $rows;
    }
    public function totalCashGiven($param){
      $cask_given=0;
      $this->db->select_sum($this->_table_user_loan_variant.'.calc_arl');
      $this->db->where('loan_offered_by_user_id',$param['user_id']);
      $this->db->where('loan_action_type','A');
      $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
      $row=$this->db->get($this->_table_user_loan)->row_array();
      $cask_given=$row['calc_arl'];
      return $cask_given;
    }
    public function totalCashReceived($param){
      $cask_Received=0;
      $this->db->select_sum('lender_pl');
      $this->db->where('loan_offered_by_user_id',$param['user_id']);
      $this->db->where('loan_action_type','A');
      $this->db->where('payment_status','4-P');
      $this->db->join($this->_table_user_loan_repayment_sch,$this->_table_user_loan.'.id='.$this->_table_user_loan_repayment_sch.'.fk_user_loan_id');
      $row=$this->db->get($this->_table_user_loan)->row_array();
      $cask_Received=$row['lender_pl'];
      return $cask_Received;
    }
    public function totalCashPending($param){
      $cask_Received=0;
      $this->db->select_sum('lender_pl');
      $this->db->where('loan_offered_by_user_id',$param['user_id']);
      $this->db->where('loan_action_type','A');
      $this->db->where('payment_status!=','4-P');
      $this->db->where('payment_status!=',NULL);
      $this->db->join($this->_table_user_loan_repayment_sch,$this->_table_user_loan.'.id='.$this->_table_user_loan_repayment_sch.'.fk_user_loan_id');
      $row=$this->db->get($this->_table_user_loan)->row_array();
      $cask_Received=$row['lender_pl'];
      return $cask_Received;
    }
    public function totalCashOffered($param){
      $cask_offered=0;
      $this->db->select_sum($this->_table_user_loan_variant.'.calc_arl');
      $this->db->where('loan_offered_by_user_id',$param['user_id']);
      $this->db->where('loan_action_type',NULL);
      $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
      $row=$this->db->get($this->_table_user_loan)->row_array();
      $cask_offered=$row['calc_arl'];
      return $cask_offered;
    }

    public function allCashTaken($param = array()){

      //pre($param,1);
      /*$pageLimit=$param['page_limit'];
      $limit=$param['limit'];*/
      $this->db->select($this->_table_user_loan_repayment_sch.".*,DATE_FORMAT(scheduled_payment_date,'%b %d,%Y') as sch_date,".$this->_table_user_loan_disbursement.".borrower_da,lender_arl");


      if($param['user_type']=='B'){
        $this->db->where('fk_user_id',$param['user_id']);
      }

      if($param['user_type']=='L'){
        $this->db->where('loan_offered_by_user_id',$param['user_id']);
      }

      $this->db->where('loan_action_type','A');

      if($param['search_status']=='C'){
        $this->db->where('is_loan_closed','1');
      }

      if($param['search_status']=='O'){
        $this->db->where('is_loan_closed','0');
      }

      if($param['search_start_date']!='' && $param['search_end_date']!=''){
        $this->db->where("scheduled_payment_date BETWEEN '".$param['search_start_date']."' AND '".$param['search_end_date']."' ");
      }

      if($param['search_tenure']>0){
        $this->db->where('input_npm',$param['search_tenure']);
      }


      if (!empty($param['page']) && !empty($param['page_size'])) {
            $limit  = $param['page_size'];
            $offset = $limit * ($param['page'] - 1);
            $this->db->limit($limit, $offset);
        }
        if ($param['order_by'] && $param['order']) {
            $this->db->order_by($param['order_by'], $param['order']);
        }

      $this->db->order_by('payment_status','asc');
      $this->db->order_by('scheduled_payment_date','desc');
      $this->db->join($this->_table_user_loan_repayment_sch,$this->_table_user_loan.'.id='.$this->_table_user_loan_repayment_sch.'.fk_user_loan_id');
      $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
      $this->db->join($this->_table_user_loan_disbursement,$this->_table_user_loan.'.id='.$this->_table_user_loan_disbursement.'.fk_user_loan_id');
      $row=$this->db->get($this->_table_user_loan)->result_array();
     
      return $row;

    }

      public function getLoanDetail($loan_id){
      $this->db->select($this->_table_user_loan.".fk_user_id,loan_offered_by_user_id,unique_loan_code,DATE_FORMAT(loan_offered_timestamp,'%b %d,%Y') as approve_date,DATE_FORMAT(loan_disbursed_timestamp,'%b %d,%Y') as accepted_date,is_loan_closed,".$this->_table_user_loan_variant.".*,".$this->_table_user_loan_disbursement.".lender_lpfa,mpokket_ufa,mpokket_stufa,mpokket_rufa, borrower_tfdb,lender_arl,borrower_da");


       $this->db->where($this->_table_user_loan.'.id',$loan_id);
       $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
      $this->db->join($this->_table_user_loan_disbursement,$this->_table_user_loan.'.id='.$this->_table_user_loan_disbursement.'.fk_user_loan_id');

       $allRecords=$this->db->get($this->_table_user_loan)->row_array();

        return $allRecords;
    }

    public function getLoanRepaymentSchedule($loan_id){

      $this->db->select($this->_table_user_loan_repayment_sch.".*,DATE_FORMAT(scheduled_payment_date,'%b %d,%Y') as sch_date");
      $this->db->where('fk_user_loan_id',$loan_id);
      $this->db->from($this->_table_user_loan_repayment_sch);
      $row=$this->db->get()->row_array();
      return $row;

    }

    public function assignLoan($id,$lender_id){

      $this->db->where('id',$id);
      $this->db->from($this->_table_user_loan);
      $row=$this->db->get()->row_array();
      if($lender_id>0){
        if($row['loan_offered_by_user_id']==NULL || $row['loan_offered_by_user_id']<1){
          $this->db->where('id',$id);
          $this->db->set('loan_offered_by_user_id',$lender_id);
          $dt=date('Y-m-d H:i:s');
          $this->db->set('loan_offered_timestamp',$dt);
          $this->db->update($this->_table_user_loan);
        }
      }

    }
    public function getUserLoanDtl($params=array())
    {
        
        $this->db->where('id',$params['loan_id']);
        $this->db->from($this->_table_user_loan);
        $ow=$this->db->get()->row_array();
        return $ow;
    }
    public function getUserLoanDisbursedDtl($params=array())
    {
        
        $this->db->where('fk_user_loan_id',$params['loan_id']);
        $this->db->from($this->_table_user_loan_disbursement);
        $ow=$this->db->get()->row_array();
        return $ow;
    }
     public function getUserLoanVarrients($params=array())
    {
        
        $this->db->where('fk_user_loan_id',$params['loan_id']);
        $this->db->from($this->_table_user_loan_variant);
        $ow=$this->db->get()->row_array();
        return $ow;
    }

     public function addmPokketExport($params){
      $this->db->insert($this->_table_mpokket_export,$params);
      
    }
   public function addMpokketFunds($params){
    $this->db->insert($this->_table_mpokket_fund,$params);
    $insert_id = $this->db->insert_id();
    return $insert_id;

  }

  public function getAllARL($amt){
      $this->db->select($this->_table_user_loan.'.*');
      $this->db->where($this->_table_user_loan_variant.'.calc_arl',$amt);
      $this->db->where('loan_offered_by_user_id',NULL);
      $this->db->where('loan_action_type',NULL);
      $this->db->order_by($this->_table_user_loan.'.id');
      $this->db->join($this->_table_user_loan_variant,$this->_table_user_loan.'.id='.$this->_table_user_loan_variant.'.fk_user_loan_id');
      $row=$this->db->get($this->_table_user_loan)->result_array();
     
      return $row;

    }

}