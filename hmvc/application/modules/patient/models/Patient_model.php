<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }

    function getData($table, $value, $order){
        if (count($value) > 0){
            $this->db->where($value);
        }
        if (count($order) > 0){
            $this->db->order_by($order, 'DESC');
        }
        return $this->db->get($table)->result();
    }

    function addPatient($table, $data){
        return $addPatient = $this->db->insert($table, $data);
    }

    function updatePatient($table, $data, $id){
        $this->db->where('id', $id);
        return $this->db->update($table, $data);
    }

}