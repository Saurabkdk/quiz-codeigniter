<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->Model('patient_model');

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->load->library('session');

        $this->load->helper('url');
    }

    function index(){

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $countryId = 0;
            $provinceId = 0;
            if ($this->input->post('element') == 'province') {
                $countryId = $this->input->post('id');
            }
            if ($this->input->post('element') == 'municipality') {
                $provinceId = $this->input->post('id');
            }

            $getData = [];

            if ($countryId > 0){
                $pVal = array(
                    'country_id' => $countryId
                );
                $getData = $this->patient_model->getData('provinces', $pVal);
            }

            if ($provinceId > 0){
                $mVal = array(
                    'province_id' => $provinceId
                );
                $getData = $this->patient_model->getData('municipalities', $mVal);
            }

            echo json_encode(
                array(
                    'record' => $getData,
                )
            );

        }
        else {

            redirect(base_url().'quiz/patient/addPatient');

        }
    }

    function addPatient(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'age',
                    'label' => 'Age',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'gender',
                    'label' => 'Gender',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'language',
                    'label' => 'Language',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'country',
                    'label' => 'Name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'province',
                    'label' => 'Province',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'municipality',
                    'label' => 'Municipality',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'address',
                    'label' => 'Address',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'phone',
                    'label' => 'Mobile Number',
                    'rules' => 'required|numeric|min_length[10]|max_length[10]'
                ),
            );

            $this->form_validation->set_rules($rules);

            if (!$this->form_validation->run()) {

                $status = false;
                $message = strip_tags(validation_errors());

                echo json_encode(
                    array(
                        'status' => $status,
                        'message' => $message
                    )
                );

                $this->session->set_flashdata('message', $message);
                $this->session->keep_flashdata('message', $message);

            } else {

                $lang = $this->input->post('lang');
                $language = implode(' ', $lang);

                $patientData = array(
                    'id' => null,
                    'name' => $this->input->post('name'),
                    'age' => $this->input->post('age'),
                    'gender' => $this->input->post('gender'),
                    'language' => $language,
                    'country' => $this->input->post('country'),
                    'province' => $this->input->post('province'),
                    'municipality' => $this->input->post('municipality'),
                    'address' => $this->input->post('address'),
                    'phone' => $this->input->post('phone'),
                    'date' => $this->input->post('date')
                );

                $addPatient = $this->patient_model->addPatient('patients', $patientData);

                echo json_encode(
                    array(
                        'status' => $addPatient,
                        'message' => "Patient data added"
                    )
                );

            }
        }
        else{
            $patientList['list'] = $this->patient_model->getData('patients', []);
            $dob = strtotime($patientList['list'][0]->age);
            $current = time();
            foreach ($patientList['list'] as $list) {
//            $list->id = sprintf('%08d', $list->id);
                $list->age = round(($current - $dob) / (365 * 60 * 60 * 24));
            }

            $cVal = array();
            $patientList['countries'] = $this->patient_model->getData('country', $cVal);
            $this->load->view('addPatient', $patientList);
        }

    }

    function patientList(){
        $listVal = [];
        $patientList['list'] = $this->patient_model->getData('patients', $listVal);
        $dob = strtotime($patientList['list'][0]->age);
        $current = time();
        foreach ($patientList['list'] as $list) {
//            $list->id = sprintf('%08d', $list->id);
            $list->age = round(($current - $dob) / (365 * 60 * 60 * 24));
        }
        $this->load->view('patientList', $patientList);
    }

    function billing(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

            $table = $this->input->post('table');
            $data = $this->input->post('data');

            if ($table == 'bills'){
                $dataArray = [];
                $dataArr = array(
                    'id' => null,
                    'patient_id' => $data[0],
                    'billing_date' => $data[1],
                    'subtotal' => $data[2],
                    'discount_percent' => $data[3],
                    'discount_amount' => $data[4],
                    'nettotal' => $data[5]
                );
                $dataArray[] = $dataArr;

            }
            else{
                $dataArray = [];
                $get = $data[0];
                $getId = $this->patient_model->getData('bills', ['billing_date' => $get[5]]);
                $id = $getId[0]->id;
                foreach ($data as $key=> $value){

                    $dataArr = array(
                        'id' => $id,
                        'patient_id' => $value[0],
                        'test_item' => $value[1],
                        'quantity' => $value[2],
                        'unit' => $value[3],
                        'price'=> $value[4]
                    );
                    $dataArray[$key] = $dataArr;
                }
            }

            foreach ($dataArray as $dataArr) {
                $addBill = $this->patient_model->addPatient($table, $dataArr);
            }

            $status = false;

            if ($addBill){
                $status = true;
            }

            echo json_encode(
                array(
                    'status' => $status,
                )
            );
        }
        else {
            $patientId['id'] = 0;
            if (isset($_GET['id'])) {
                $patientId['id'] = $_GET['id'];
            }
            $this->load->view('billing', $patientId);
        }
    }

}