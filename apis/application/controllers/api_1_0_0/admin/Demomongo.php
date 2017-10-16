<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//phpinfo();die;
//require APPPATH . 'libraries/api/REST_Controller.php';
ini_set("display_errors", "1");
error_reporting(E_ALL);

class Demomongo extends CI_Controller{
    function __construct(){

        parent::__construct();
       // $this->db_name='pocketpasport';
       // $mongo = new MongoClient("mongodb://192.168.0.55:27017");
        $mongo = new MongoClient();
        $this->mongodb = $mongo->selectDB('test');
        $this->user = $this->mongodb->user;

    }

    function test()
    {
        //echo 'hello';
      /*  $m = new MongoClient();
         echo "Connection to database successfully";
         $this->mongodb = $m->test;
         $this->user = $this->mongodb->user;

         echo "Database mydb selected";*/
      $result = $this->user->find();  
      foreach($result as $row)     
      {
         echo '<pre>';
         print_r($row);
         echo '</pre>';
      }
    }
}