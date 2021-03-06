<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_feedback_model extends CI_Model
{

    public $table = 'feedback';
    public $id = 'record_id';
    public $order = 'DESC';
   
	//public $issuetime = 'issuetime';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('array');
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
       // return $this->db->get($this->table)->result();
	   return $this->db->get($this->table)->result();
    }
  // get data by id
    function get_by_id($id)
    {  
	
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
  // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('advisory', $q);
	$this->db->or_like('date_from', $q);
	$this->db->or_like('date_to', $q);
	$this->db->or_like('issuetime', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('advisory', $q);
	$this->db->or_like('date_from', $q);
	$this->db->or_like('date_to', $q);
	$this->db->or_like('issuetime', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    //checking if the indigenous knowledge exists in the DB
    
   function CheckForExistence($region, $district, $thisregionString)
    {
        $thisregionString = array();
        $thisregion = $this->db->get_where('feedback', array('region'=>$region, 'district'=>$district));
        foreach ($thisregion->result() as $row){
            //punting each row into a string
             $regionvalues = $row->observation." ".$row->implication." ".$row->action_to_take; 
            //pushing the string into the array
             array_push($thisregionString, $regionvalues);
         }
           return $thisregionString;
           
    }


    // insert Indidigenous knowledge into the database
    function insert($data)
    {   // var_dump($data);
	     //exit;
        //$this->db->insert($this->table, $data); 
		$sql = "INSERT INTO $this->table(record_id, names, region, district, observation, implication, action_to_take) VALUES(null, ?, ?, ?, ?, ?, ?)";
		  return $this->db->query($sql, $data);
    }

    // update data
    function update($id, $data)
    {    //var_dump($data);
	     //echo $id;  
	     //exit;
		 
         $sql = "UPDATE $this->table SET advisory = ?, date_from = ?, date_to = ?  WHERE decadal_id = $id";
        return $this->db->query($sql, $data);
    }

    function verify($id){
        $verified = "UPDATE $this->table SET status = 1 WHERE record_id = $id";
        return $this->db->query($verified);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Decadal_forecast_model.php */