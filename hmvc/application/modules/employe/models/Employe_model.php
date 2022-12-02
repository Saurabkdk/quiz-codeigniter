<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employe_model extends CI_Model{
    public function __construct(){
        parent:: __construct();
        $this->db=$this->load->database('default',true); // no need for now
    }


     # 2. Using Query Builder
    //  e.g., $return_data = $this->db_name->select('*')
    //  ->from('TABLE_NAME')
    //  ->get()
    //  ->result();

    //now execute the query

    function executeEmployeData(){
        $query=$this->db->post('EMPLOYE');
        $return_result=$this->db->query($sql)->result();

        // $return_data=$this->db->select('*')->from('EMPLOYE')->get()->result();
        return $return_result;
    }

    //save data to the table

    

    // function saveData($employename,$employeid,$employemail)
    // {
    //     // $sql = "SELECT NVL(MAX(ID),0) AS MAX FROM EMPLOYE";
    //     // $max = $this->db->query($sql)->row();
    //     // $next_id = $max->MAX + 1;

    //     $getData=$this->db->set('EMPLOYENAME',$employename)
    //                             ->set('EMPLOYEID',$employeid)
    //                             ->set('EMPLOYEMAIL',$employemail)
    //                             // ->set('ID',$next_id)
    //                             ->insert('EMPLOYE');

    //     //checking
    //     if($getData){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    function saveData($employee)
    {
        if(isset($employee['id'])){
            $data = array(
                'employename' => $employee['employename'],
                'employeid' => $employee['employeid'],
                'employemail' => $employee['employemail']
            );
    
            $this->db->where('employeid', $employee['id']);
            $getData = $this->db->update('employe', $data);
        }
        else{

        $getData=$this->db->set('EMPLOYENAME',$employee['employename'])
                                ->set('EMPLOYEID',$employee['employeid'])
                                ->set('EMPLOYEMAIL',$employee['employemail'])
                                // ->set('ID',$next_id)
                                ->insert('EMPLOYE');

        }

        //checking
        if($getData){
            return true;
        }else{
            return false;
        }
    }

    function getData($id){
        if($id > 0){
            $this->db->where('employeid', $id);
        }
        $list = $this->db->get('employe');
        return $list;
    }

    function deleteData($id){
        $this->db->where('employeid', $id);
        $this->db->delete('employe');
    }

//    function getOneData($id){
//        $this->db->where('employeid', $id);
//        $one = $this->db->get('employe');
//        return $one;
//    }

    // function updateData($employeid, $employename, $employemail, $id){
    //     $data = array(
    //         'employename' => $employename,
    //         'employeid' => $employeid,
    //         'employemail' => $employemail
    //     );

    //     $this->db->where('employeid', $id);
    //     $this->db->update('employe', $data);
    // }

}
?>