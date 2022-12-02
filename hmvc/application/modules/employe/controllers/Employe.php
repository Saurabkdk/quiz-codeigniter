<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employe extends MX_Controller {

    public function __construct(){
        parent:: __construct();
        $this->load->Model('employe_model');

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->load->library('session');

        $this->load->helper('url');
    }

    function index(){
        $this->load->view('employeview');
    }

    //function to save and update record
    function saveEmployee()
    {
        $rules = array(
          array(
              'field' => 'EMPLOYENAME',
              'label' => 'Employe name',
              'rules' => 'required'
          ),
          array(
              'field' => 'EMPLOYEID',
              'label' => 'Employe ID',
              'rules' => 'required|numeric'
          ),
            array(
                'field' => 'EMPLOYEMAIL',
                'label' => 'Employe mail',
                'rules' => 'required|valid_email'
            )
        );

        $this->form_validation->set_rules($rules);

        if(!$this->form_validation->run()){

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $status = false;
                $message = strip_tags(validation_errors());
                echo json_encode(
                    array(
                        'status' => $status,
                        'message' => $message,
                        'jsonCheck' => true
                    )
                );
            }
            else{
                $this->load->view('employeview');
            }
        }
        else {

            //insert from jquery

//              $employee['employename'] = $this->input->post('EMPLOYENAME');
//              $employee['employeid'] = $this->input->post('EMPLOYEID');
//              $employee['employemail'] = $this->input->post('EMPLOYEMAIL');

              //insert from php

            $employee['employename'] = $_POST['EMPLOYENAME'];
            $employee['employeid'] = $_POST['EMPLOYEID'];
            $employee['employemail'] = $_POST['EMPLOYEMAIL'];

            $checkId = $this->employe_model->getData($employee['employeid'])->row();

            //check if id is provided
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $employee['id'] = $_GET['id'];
                if($checkId->employeid != $employee['employeid'] || $employee['employeid'] == $_GET['id']) {
                    $checkId = false;
                }
            }

            //check if the provided id already exists
            if ($checkId) {
                $alldata = false;
                $message = 'Id already exists';
            } else {
                $alldata = $this->employe_model->saveData($employee);
            }

            if ($alldata) {
                $status = true;
                $message = 'Your record is saved';

            } else {
                $status = false;
            }

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(
                    array(
                        'status' => $status,
                        'message' => $message,
                        'jsonCheck' => true
                    )
                );
            }
            else{
                echo json_encode(
                    array(
                        'status' => $status,
                        'message' => $message,
                        'jsonCheck' => false
                    )
                );
            }

            if ($status) {
                $this->session->set_flashdata('message', 'The record is saved');
                $this->session->keep_flashdata('message', 'The record is saved');
            }
            else{
                $this->session->set_flashdata('message', 'The record is not saved');
                $this->session->keep_flashdata('message', 'The record is not saved');
            }
//            redirect('../employe/showview');
            if(!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
                redirect('../employe/showview');
            }

        }
    }

    function showview(){
        $id = 0;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        if($id > 0){
            $getData['one'] = $this->employe_model->getData($id)->row();

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

                echo json_encode($getData['one']);
            }
            else {
                $this->load->view('employeview', $getData);
            }
        }
        else{
            $getData['list'] = $this->employe_model->getData($id)->result();

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            
            echo json_encode($getData['list']);
            }
            else{

            $this->load->view('employlist', $getData);
            }
        }
    }

    function deleteview(){
        $id = $this->input->get('id');
        $this->employe_model->deleteData($id);

        $this->session->set_flashdata('message', 'The record is deleted');
        $this->session->keep_flashdata('message', 'The record is deleted');
        redirect('../employe/showview');
    }

}
?>