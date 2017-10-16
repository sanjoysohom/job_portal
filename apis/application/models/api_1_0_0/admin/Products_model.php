<?php
class Products_model extends CI_Model{

	public $_tbl_admins = 'tbl_admins';
    public $table_category                      = "tbl_category";
    public $table_products                     = "tbl_products";
    function __construct(){
        //load the parent constructor
        parent::__construct();        
        $this->tables = $this->config->item('tables'); 

    }

function checkSessionExist($param){
	//print_r($param); exit();
 $this->db->select($this->tables['tbl_admins'].".admin_level, " . $this->tables['tbl_admins'].".f_name, " . $this->tables['tbl_admins'].".l_name, "  . $this->tables['tbl_admin_loginsessions'].'.id AS pass_key');

        $this->db->where($this->tables['tbl_admin_loginsessions'].".id",$param['pass_key']);
        $this->db->where($this->tables['tbl_admin_loginsessions'].".fk_admin_id",$param['admin_user_id']);
        $this->db->join($this->tables['tbl_admins'], $this->tables['tbl_admins'].'.id = '.$this->tables['tbl_admin_loginsessions'].'.fk_admin_id', 'inner');
        $qry = $this->db->get($this->tables['tbl_admin_loginsessions']);
        return $qry->row_array();

}


function getAllProducts($param = array()){
     $this->db->select('pro.*,cat.category_name as c_name');

        if(!empty($param['order_by']) && !empty($param['order'])){
            $this->db->order_by($param['order_by'],$param['order']);
        }

        if(!empty($param['searchByName']))
        {
        $this->db->like('product_name ',$param['searchByName']);
        
        }

        if(!empty($param['page']) && !empty($param['page_size']))
        {  //print_r($param);
    

            $limit = $param['page_size'];
            $offset = $limit*($param['page']-1);
            $this->db->limit($limit, $offset);
        }
        $this->db->join($this->table_category." as cat",'cat.id=pro.fk_category_id');
        $result = $this->db->get($this->table_products." as pro")->result_array();
       // echo $this->db->last_query();
        return $result;

}

    public function add_products($param = array()){
    //print_r($this->tables['tbl_employee']); exit();
    $this->db->insert($this->table_products,$param);
    $insert_id = $this->db->insert_id(); 
    return $insert_id;

    }

    public function getAllProductsCount($param = array())
    {
        $this->db->select('count(*) as count_products');
        if(!empty($param['order_by']) && !empty($param['order'])){
            $this->db->order_by($param['order_by'],$param['order']);
        }

        if(!empty($param['searchByName']))
        {
         $this->db->like('product_name',$param['searchByName']);
       
        }

        $result = $this->db->get($this->tables['tbl_products'])->row_array();
        //echo $this->db->last_query();die();
        return $result;
    }


 
     function getProductsById($param = array()){
      $this->db->select('pro.*,cat.category_name');
      if($param['productsID']){
      $this->db->where('pro.id',$param['productsID']);
      }
      $this->db->join($this->table_category." as cat",'cat.id=pro.fk_category_id');
      $products_dtail = $this->db->get($this->tables['tbl_products']." as pro")->row_array();
      return $products_dtail;

     }


    function updateProducts($where=array(),$param=array()){
     
     $this->db->where('id',$where['id']);
     $update=$this->db->update($this->tables['tbl_products'],$param);
     return $update;
    }
    public function checkDuplicateProducts($param = array())
    {
        $this->db->select("*");
        if($param['product_name'])
        {
            $this->db->where('product_name', $param['product_name']);
        }
        if(isset($param['id']))
        {
            $this->db->where('id != ', $param['id']);
        }
        $qry = $this->db->get($this->tables['tbl_products']);
        return $qry->result_array();
    }

    /*function getProductsById($param = array())
    {
      $this->db->select('*');
      if($param['productsID']){
      $this->db->where('id',$param['productsID']);
      }

      $emp_dtail = $this->db->get($this->tables['tbl_products'])->row_array();
      return $pro_dtail; 
    }*/

    function productsDelete($param = array()){
        $this->db->where('id',$param['productsID']);
        $this->db->delete($this->tables['tbl_products']);
        //  echo $this->db->last_query();die();
        return true;
    }
     



    /*****************************************
     * End of user model
    ****************************************/
}
