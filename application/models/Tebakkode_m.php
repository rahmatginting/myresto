<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tebakkode_m extends CI_Model {

  function __construct(){
    parent::__construct();
    $this->load->database();
  }

  // Events Log
  function log_events($signature, $body)
  {
    $this->db->set('signature', $signature)
    ->set('events', $body)
    ->insert('eventlog');

    return $this->db->insert_id();
  }

  // Users
  function getUser($userId)
  {
    $data = $this->db->where('user_id', $userId)->get('users')->row_array();
    if(count($data) > 0) return $data;
    return false;
  }
 
   function getUserName($user_id)
  {
    $this->db->select('display_name')
             ->from('users')
             ->where('user_id',$user_id);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->display_name;
     }
     return false;
  }

  function saveUser($profile)
  {
    $this->db->set('user_id', $profile['userId'])
      ->set('display_name', $profile['displayName'])
      ->insert('users');
 
    return $this->db->insert_id();
  }
  
  // Question
  function getQuestion($questionNum)
  {
    $data = $this->db->where('number', $questionNum)
      ->get('questions')
      ->row_array();
 
    if(count($data)>0) return $data;
    return false;
  }
 
  function isAnswerEqual($number, $answer)
  {
    $this->db->where('number', $number)
      ->where('answer', $answer);
 
    if(count($this->db->get('questions')->row()) > 0)
      return true;
 
    return false;
  }
 
  function setUserProgress($user_id, $newNumber)
  {
    $this->db->set('number', $newNumber)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
  }
 
  function setScore($user_id, $score)
  {
    $this->db->set('score', $score)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
  }


  function setTable($user_id, $tableNum)
  {
    $this->db->set('table', $tableNum)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
    //return $restoNum;
  }

 function setResto($user_id, $resto_id)
  {
    $this->db->set('resto', $resto_id)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
    //return $restoNum;
  }


  function checkResto($tableCode)
  {

    $this->db->select('restaurant_id')
             ->from('restaurant_tables')
             ->where('code',$tableCode);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->restaurant_id;
     }
     return false;
  }

  function getResto($user_id)
  {
    $this->db->select('resto')
             ->from('users')
             ->where('user_id',$user_id);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->resto;
     }
     return false;

  }

  function getRestoDesc($resto)
  {
    /*
    $sql = "SELECT name, description, address, image FROM restaurants WHERE id = '". $resto . "'";
    $query = $this->db->query($sql);
    if($query->num_rows() == 0) return false;
    return $query->result_array();
    */

    $data = $this->db->where('id', $resto)
      ->get('restaurants')
      ->row_array();
 
    if(count($data)>0) return $data;
    return false;

  }
  
  function getTable($user_id)
  {
    $this->db->select('table')
             ->from('users')
             ->where('user_id',$user_id);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->table;
     }
     return false;

  }
  // Menu category
  function getCategory($restoID)
  {

    $this->db->select('name')
             ->from('menu_category')
             ->where('restaurant_id',$restoID);
    $query = $this->db->get();

    if($query->num_rows() == 0) return false;
    return $query->result_array();

  }

    function saveProgress($msg)
  {
    $this->db->set('desc', $msg)
      ->insert('progress');
 
    return $this->db->insert_id();
  }

  function getCategoryID($restoID, $category) 
  {
    $this->db->select('code')
             ->from('menu_category')
             ->where('restaurant_id',$restoID)
             ->where('name',$category);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->code;
     }
     return false;

  }

  function setCategory($user_id, $category_id)
  {
    $this->db->set('category', $category_id)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
    //return $restoNum;
  }

 function setMenuProg($user_id, $menu_code)
  {
    $this->db->set('menu', $menu_code)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
    //return $restoNum;
  }

  function getMenuProg($user_id)
  {
    $this->db->select('menu')
             ->from('users')
             ->where('user_id',$user_id);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->menu;
     }
     return false;

  }

  function getMenu($restoID,$categoryID)
  {
    $sql = "SELECT name, code, description, picture FROM restaurant_menu WHERE category_id = '". $categoryID . "' AND restaurant_id = '" . $restoID ."'";
    $query = $this->db->query($sql);
    if($query->num_rows() == 0) return false;
    return $query->result_array();
  }

  function getMenuName($resto, $menu)
  {
    $this->db->select('name')
             ->from('restaurant_menu')
             ->where('code',$menu)
             ->where('restaurant_id',$resto);
    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->name;
     }
     return false;

  }

  function getOrder($user_id)
  {
    $this->db->select('order')
             ->from('users')
             ->where('user_id',$user_id)
             ->order_by('id', 'asc')
             ->limit(1);

    $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->order;
     }
     return false;
  }

 function setOrder($user_id, $oderID)
  {
    $this->db->set('order', $oderID)
      ->where('user_id', $user_id)
      ->update('users');
 
    return $this->db->affected_rows();
  }  

  function saveOrderHed($user_id, $user_name, $resto_id, $table_id)
  {
    //kode disini
    $this->db->set('user_id', $user_id)
    ->set('user_name', $user_name)
    ->set('resto_id', $resto_id)
    ->set('table_id', $table_id)
    ->insert('menu_order');

    //return $this->db->insert_id();
    $last_id = $this->db->insert_id();
    return $last_id; 

  }

  function searchOrderID($user_id, $resto, $table)
  {

    $sql = "SELECT id "; 
    $sql .= "FROM menu_order ";
    $sql .= "WHERE user_id = '". $user_id . "' ";
    $sql .= "AND resto_id = '" . $resto ."' ";
    $sql .= "AND table_id = '" . $table ."' ";
    $sql .= "ORDER BY id DESC ";
    $sql .= "LIMIT 1";
    $query = $this->db->query($sql);

     if ($query->num_rows() > 0) {
         return $query->row()->id;
     }
     return false;    
  }

  
  function saveOrderDet($order_id, $menu_id, $porsi)
  {
    //kode disini
    $this->db->set('id', $order_id)
    ->set('menu', $menu_id)
    ->set('quantity', $porsi)
    ->insert('menu_order_det');

    return $this->db->insert_id();
  }

  function setKeterangan($order_id, $menu_id, $keterangan)
  {
    $this->db->set('description', $keterangan)
      ->where('id', $order_id)
      ->where('menu', $menu_id)
      ->update('menu_order_det');
 
    return $this->db->affected_rows();

  }

  function getMenuOrder($order_id, $restoID)
  {
    $sql =  "SELECT b.name, a.quantity, a.description ";
    $sql .= "FROM menu_order_det a INNER JOIN restaurant_menu b ON a.menu = b.code ";
    $sql .= "WHERE a.id = '". $order_id . "' ";
    $sql .= "AND b.restaurant_id = '" . $restoID ."' ";
        
    $query = $this->db->query($sql);
    if($query->num_rows() == 0) return false;
    return $query->result_array();
    
  }
  
  function setOrderComplete($oderID)
  {
    $this->db->set('status', '1')
      ->where('id', $oderID)
      ->update('menu_order');
 
    return $this->db->affected_rows();
  }

  function setClearProgress($userID) 
  {
    $this->db->set('number', '0')
      ->set('resto', '0')
      ->set('table', '0')
      ->set('category', '0')
      ->set('menu', '0')
      ->set('order', '0')
      ->where('user_id', $userID)
      ->update('users');
 
    return $this->db->affected_rows();
  }
  
  function saveCallWaitress($userID)
  {
    $this->db->select('table')
             ->select('resto')
             ->select('display_name')
             ->from('users')
             ->where('user_id',$userID)
             ->order_by('id', 'asc')
             ->limit(1);
    $query = $this->db->get();
    //$query = $this->db->query($sql);
    foreach($query->result_array() AS $row) {
      $table = $row['id'];
      $resto = $row['resto'];
      $name = $row['display_name'];
    }

    $this->db->set('user_id', $userID)
      ->set('resto_id', $resto)
      ->set('table_id', $table)
      ->set('timestamp', 'current_timestamp')
      ->set('user_name', $name)
      ->set('type', '01')
      ->set('status','0')
      ->set('description','rubah pesanan')
      ->insert('panggilan');
 
    return $this->db->insert_id();
  }

}

