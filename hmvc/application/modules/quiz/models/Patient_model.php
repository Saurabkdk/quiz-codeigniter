<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }

    function getData($table, $value){
        if (count($value) > 0){
            $this->db->where($value);
        }
        return $this->db->get($table)->result();
    }

    function addPatient($table, $data){
        return $addPatient = $this->db->insert($table, $data);
    }

}