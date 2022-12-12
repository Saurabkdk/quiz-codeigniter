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

    //getting values for the select inputs
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
                //getting provinces based on country
                $getData = $this->patient_model->getData('provinces', $pVal, []);
            }

            if ($provinceId > 0){
                $mVal = array(
                    'province_id' => $provinceId
                );
                //getting municipalitites based on province
                $getData = $this->patient_model->getData('municipalities', $mVal, []);
            }

            echo json_encode(
                array(
                    'record' => $getData,
                )
            );

        }
        else {

            redirect(base_url().'patient/addPatient');

        }
    }

    //function to add and edit a patient's information
    function addPatient(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            //get the list of all the patients present
            $patientList = $this->patient_model->getData('patients', [], []);

            $pName = $this->input->get('patientName');

            if (isset($pName)){
                $patientList = $this->patient_model->getData('patients', ['name' => $pName], []);
            }

            //validate all data required to be filled up
            $rules = array(
                array(
                    'field' => 'patient[0]',
                    'label' => 'Name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[1]',
                    'label' => 'Age',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[2]',
                    'label' => 'Gender',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[3]',
                    'label' => 'Language',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[4]',
                    'label' => 'Country',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[5]',
                    'label' => 'Province',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[6]',
                    'label' => 'Municipality',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[7]',
                    'label' => 'Address',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'patient[8]',
                    'label' => 'Mobile Number',
                    'rules' => 'required|numeric|min_length[10]|max_length[10]'
                ),
                array(
                    'field' => 'patient[9]',
                    'label' => 'Date',
                    'rules' => 'required|min_length[10]'
                ),
            );

            $this->form_validation->set_rules($rules);

            //display error message if validation not true
            if (!$this->form_validation->run()) {

                $status = false;
                $message = strip_tags(validation_errors());

                echo json_encode(
                    array(
                        'status' => $status,
                        'message' => $message,
                        'patients' => $patientList
                    )
                );

                $this->session->set_flashdata('message', $message);
                $this->session->keep_flashdata('message', $message);

            } else {
                //if validaition true, get all values from jquery
                $patient = $this->input->post('patient');

                $lang = $patient[3];
                $language = implode(' ', $lang);

                //check if id is passed
                if ($patient[10] > 0){
                    //store data in an array
                    $patientData = array(
                        'id' => $patient[10],
                        'name' => $patient[0],
                        'age' => $patient[1],
                        'gender' => $patient[2],
                        'language' => $language,
                        'country' => $patient[4],
                        'province' => $patient[5],
                        'municipality' => $patient[6],
                        'address' => $patient[7],
                        'phone' => $patient[8],
                        'date' => $patient[9]
                    );

                    //update the data in database with model help
                    $addPatient = $this->patient_model->updatePatient('patients', $patientData, $patient[10]);
                }
                //if an id is not passed
                else {

                    //store data in array
                    $patientData = array(
                        'id' => null,
                        'name' => $patient[0],
                        'age' => $patient[1],
                        'gender' => $patient[2],
                        'language' => $language,
                        'country' => $patient[4],
                        'province' => $patient[5],
                        'municipality' => $patient[6],
                        'address' => $patient[7],
                        'phone' => $patient[8],
                        'date' => $patient[9]
                    );
                    //add the data into database
                    $addPatient = $this->patient_model->addPatient('patients', $patientData);
                }


                echo json_encode(
                    array(
                        'status' => $addPatient,
                        'patients' => $patientList
                    )
                );

            }
        }
        else{
            //getting the list of the patients
            $patientList['list'] = $this->patient_model->getData('patients', [], []);

            //getting the age of the patient from his/her birth date
            if (count($patientList['list']) > 0) {
                foreach ($patientList['list'] as $list) {
                    $list->id = sprintf('%08d', $list->id);
                    $current = time();
                    $dob = strtotime($list->age);
                    $list->age = round(($current - $dob) / (365 * 60 * 60 * 24));
                }
            }

            $cVal = array();

            //getting the list of the countries
            $patientList['countries'] = $this->patient_model->getData('country', $cVal, []);

            //loading the view
            $this->load->view('addPatient', $patientList);
        }

    }

    function aboutPatient(){
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

        }
        else{
            //check if id is sent as parameter
            if (isset($_GET['id'])) {
                //get the details of th patient
                $patientDetails['details'] = $this->patient_model->getData('patients', ['id' => $_GET['id']], []);

                //get all the countries
                $patientDetails['countries'] = $this->patient_model->getData('country', [], []);

                //load the view
                $this->load->view('addPatient', $patientDetails);
            }
            else{
                //redirect back if no id is passed
                redirect(base_url().'patient/addPatient');
            }

        }
    }

    //for billing purposes
    function billing(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

            //get all the values from jquery
            $table = $this->input->post('table');
            $data1 = $this->input->post('data1');
            $data2 = $this->input->post('data2');

            //loop through the variable to validate all the test fields
            foreach ($data2 as $key => $date2) {

                $billRules = array(
                    array(
                        'field' => 'table[1]',
                        'label' => 'Table',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'Table name must be passed'
                        )
                    ),
                    array(
                        'field' => 'table[0]',
                        'label' => 'Table',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'Table name must be passed'
                        )
                    ),
                    array(
                        'field' => 'data2['. $key .'][0]',
                        'label' => 'Patient Id',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data2['. $key .'][1]',
                        'label' => 'Test Item',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data2['. $key .'][2]',
                        'label' => 'Quantity',
                        'rules' => 'required|numeric'
                    ),
                    array(
                        'field' => 'data2['. $key .'][3]',
                        'label' => 'Unit',
                        'rules' => 'required|numeric'
                    ),
                    array(
                        'field' => 'data2['. $key .'][4]',
                        'label' => 'Price',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data2['. $key .'][5]',
                        'label' => 'Billing Date',
                        'rules' => 'required'
                    ),
                );

                $this->form_validation->set_rules($billRules);
            }

            //if validation does not pass, show error message
                if (!$this->form_validation->run()) {

                    $status = false;
                    $message = strip_tags(validation_errors());

                    echo json_encode(
                        array(
                            'status' => $status,
                            'message' => $message
                        )
                    );
                }
            else {

                //validation for the bill table
                $billRules = array(
                    array(
                        'field' => 'table[0]',
                        'label' => 'Table',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'Table name must be passed'
                        )
                    ),
                    array(
                        'field' => 'data1[0]',
                        'label' => 'Patient Id',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data1[1]',
                        'label' => 'Date',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data1[2]',
                        'label' => 'Subtotal',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data1[3]',
                        'label' => 'Discount Percent',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data1[4]',
                        'label' => 'Discount Amount',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'data1[5]',
                        'label' => 'Net Total',
                        'rules' => 'required'
                    ),
                );

                $this->form_validation->set_rules($billRules);

                //if validation not true, show error message
                if (!$this->form_validation->run()) {

                    $status = false;
                    $message = strip_tags(validation_errors());

                    echo json_encode(
                        array(
                            'status' => $status,
                            'message' => $message
                        )
                    );
                } else {

                    //if all the validations are true

                    //setting the date to appropriate format
                    $date = $data1[1];
                    $billDate = str_replace(array(',', '/'), array('', '-'), substr($date, 0, -3));
                    $date = strtotime($billDate);
                    $date = date('Y/m/d h:i:s', $date);

                    //check if id is set
                    if ($data1[6] == 0) {
                        //store value in array
                        $dataArr = array(
                            'id' => null,
                            'patient_id' => $data1[0],
                            'billing_date' => $date,
                            'subtotal' => $data1[2],
                            'discount_percent' => $data1[3],
                            'discount_amount' => $data1[4],
                            'nettotal' => $data1[5]
                        );

                        //add the data into database
                        $addBill = $this->patient_model->addPatient($table[0], $dataArr);
                    }
                    //if id is not passed
                    else{
                        //store data in array
                        $dataArr = array(
                            'id' => $data1[6],
                            'patient_id' => $data1[0],
                            'billing_date' => $date,
                            'subtotal' => $data1[2],
                            'discount_percent' => $data1[3],
                            'discount_amount' => $data1[4],
                            'nettotal' => $data1[5]
                        );

                        //update the data in database
                        $addBill = $this->patient_model->updatePatient($table[0], $dataArr, $dataArr['id']);
                    }

                    $status = false;

                    if ($addBill) {
                        $status = true;
                    }
//
                    if ($status) {
                        $dataArray = [];
                        $dataArray1 = [];

                        $get = $date;
                        $getId = $this->patient_model->getData('bills', ['billing_date' => $get], []);
                        $id = $getId[0]->id;

                        foreach ($data2 as $key => $value) {
                            if ($value[6] > 0) {
                                $dataArr1 = array(
                                    'id' => $value[6],
                                    'bill_id' => $id,
                                    'patient_id' => $value[0],
                                    'test_item' => $value[1],
                                    'quantity' => $value[2],
                                    'unit' => $value[3],
                                    'price' => $value[4],
                                    'billing_date' => $date
                                );
                                $dataArray[$key] = $dataArr1;
                            }
                            else {
                                $dataArr1 = array(
                                    'id' => null,
                                    'bill_id' => $id,
                                    'patient_id' => $value[0],
                                    'test_item' => $value[1],
                                    'quantity' => $value[2],
                                    'unit' => $value[3],
                                    'price' => $value[4],
                                    'billing_date' => $date
                                );
                                $dataArray1[$key] = $dataArr1;
                            }
                        }

                        foreach ($dataArray as $dataArr1) {
                            $addTest = $this->patient_model->updatePatient($table[1], $dataArr1, $dataArr1['id']);
                        }

                        foreach ($dataArray1 as $dataArr1) {
                            $addTest = $this->patient_model->addPatient($table[1], $dataArr1);
                        }

                        $status = false;

                        if ($addTest){
                            $status = true;
                        }

                    }

                    echo json_encode(
                        array(
                            'status' => $status,
                        )
                    );
                }
            }

        }
        else {
            $patient['id'] = 0;
            $patient['work'] = 1;

            //check is id is passed as parameter
            if (isset($_GET['id'])) {
                $patient['id'] = $_GET['id'];
            }

            //check if the id passed exists in database
            $checkIdExists = $this->patient_model->getData('patients', ['id' => $patient['id']], []);

            //check if billId is passed as parameter
            if (isset($_GET['billId'])){
                $patient['id'] = $_GET['billId'];
            }

            //if billId is passed
            if (isset($_GET['billId'])){
                //check if that id exists
                $checkIdExists = $this->patient_model->getData('bills', ['id' => $_GET['billId']], []);

                //if id exists, load data
                if ($checkIdExists != 0) {
                    $patient['tests'] = $this->patient_model->getData('tests', ['bill_id' => $_GET['billId']], []);
                }
                else{
                    $patient['work'] = 0;
                }
            }

            //get all the bills from database
            $patient['bills'] = $this->patient_model->getData('bills', [], 'billing_date');

            //check if the id exists in database
            if (count($checkIdExists) == 0 && $patient['id'] != 0 && !isset($_GET['billId'])){
                $this->session->set_flashdata('noId', 'Patient Id '. $patient['id'] . ' does not exist');
                $this->session->keep_flashdata('noId', 'Patient Id '. $patient['id'] . ' does not exist');
                $patient['work'] = 0;
            }

            //if id exists, load the bills
            if (count($checkIdExists) != 0 && isset($_GET['bill']) && $patient['id'] != 0){
                $patient['bills'] = $this->patient_model->getData('bills', ['patient_id' => $patient['id']], 'billing_date');
            }

            //load the view
            $this->load->view('billing', $patient);
        }
    }

    function tests(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            //get the data passed from jquery
            $id = $this->input->get('id');
            $billDate = $this->input->get('billDate');

            //load the tests
            $getTests = $this->patient_model->getData('tests', ['bill_id' => $id, 'billing_date' => $billDate], []);

            echo json_encode(
                array(
                    'data' => $getTests
                )
            );

        }
    }

}