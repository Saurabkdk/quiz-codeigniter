<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$_SESSION['admin'] = false;

class Quiz extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->Model('quiz_model');

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->load->library('session');

        $this->load->helper('url');

    }

    function index(){
        $this->load->view('load');
    }

    function player(){
        $rules = array(
            array(
                'field' => 'playerName',
                'label' => 'Username',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Please insert a username'
                )
            )
        );

        $this->form_validation->set_rules($rules);

        if (!$this->form_validation->run()){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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
            }
            else{
                $this->load->view('load');
            }
        }
        else{

            $player = $this->input->post('playerName');

            if (isset($_SESSION['player'])){
                unset($_SESSION['player']);
            }else{
                $_SESSION['player'] = true;
            }

            $playerCheck = false;
            $names = [];

            $playerNames = $this->quiz_model->getPlayerName();

            foreach ($playerNames as $key => $playerName){
                $names[$key] = $playerName;
            }

            for($i = 0; $i < count($names); $i++){
                if ($player == $names[$i]->name){
                    $playerCheck = true;
                    $i = count($names) + 1;
                }
            }

            if ($playerCheck) {
                $status = false;
                $message = "Username already exists";
            }
            else{
                $status = true;
                $message = "Best of luck";
            }

            $this->session->set_flashdata('message', $message);
            $this->session->keep_flashdata('message', $message);


            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                echo json_encode(
                    array(
                        'status' => $status,
                        'message' => $message,
                        'player' => $player
                    )
                );
            }

        }

    }

    function question(){

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $id = $this->input->post('id');
        }
        else{
            $id = 0;
        }

        $quiz['question'] = $this->quiz_model->getQuestion($id)['question'];
        $quiz['options'] = $this->quiz_model->getQuestion($id)['answers'];


        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            echo json_encode(
                array(
                    'question' => $quiz['question'],
                    'options' => $quiz['options']
                )
            );
        }
        else{
            $this->load->view('question');
            unset($_SESSION['player']);
        }
    }

    function save(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $playDetails = $this->input->post('playDetails');
        }
        else{
            $playDetails = [];
        }

        $time = 20 - $playDetails[0];

        $getPlay = $this->quiz_model->getPlay($playDetails[1], strval($playDetails[4]));

        if (isset($getPlay->id)){
            $play = array(
                'id' => $getPlay -> id,
                'name' => $playDetails[4],
                'question_id' => $playDetails[1],
                'answer' => $playDetails[2],
                'correct' => $playDetails[3],
                'time' => $time,
                'date_time' => $playDetails[5]
            );
        }
        else{
            $play = array(
                'name' => $playDetails[4],
                'question_id' => $playDetails[1],
                'answer' => $playDetails[2],
                'correct' => $playDetails[3],
                'time' => $time,
                'date_time' => $playDetails[5]
            );
        }

        $save = $this->quiz_model->save($play);

        if ($save){
            $status = true;
        }
        else{
            $status = false;
        }

            echo json_encode(
                array(
                    'status' => $status
                )
            );

    }

    function login(){
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//           $admin = $this->input->post['admin'];
            if (isset($_POST['admin'])) {
                $admin = $_POST['admin'];
            } else {
                $admin = 'wrong';
            }
            $password = 'IAmAdmin';

            if ($admin == $password) {
                $_SESSION['admin'] = true;
            }

            if (isset($_SESSION['admin'])) {
                $adminLogin = $_SESSION['admin'];
            } else {
                $adminLogin = false;
            }

            if ($adminLogin) {
                $status = true;
                $message = 'Welcome admin';
            } else {
                $status = false;
                $message = 'Credential did not match';
            }

            echo json_encode(
                array(
                    'status' => $status,
                    'message' => $message,
                    'admin' => $adminLogin
                )
            );

        } else {

            $_SESSION['player'] = true;

            $allPlayers = [];

            $all = [];

            $playerName = $this->quiz_model->getPlayerName();

            echo '<br>';

            foreach ($playerName as $key => $player) {
                $tableDetails = $this->quiz_model->showTable(1, 0, $player->name);
                $allPlayers['sn'] = $key + 1;
                $allPlayers['name'] = $tableDetails['name']->name;
                $allPlayers['total'] = $tableDetails['total'];
                $allPlayers['correct'] = $tableDetails['correct'];
                $allPlayers['attempt'] = $allPlayers['total'] - $tableDetails['attempt'];
                $allPlayers['date'] = $tableDetails['date']->date_time;
                $allPlayers['time'] = $tableDetails['time']->time;
                $all[$key] = $allPlayers;
            }

            $players['player'] = $all;

            $this->load->view('admin', $players);
        }
    }

    function logout(){
        unset($_SESSION['admin']);
        redirect(base_url().'quiz/login');
    }

    function delete(){

            $name = $_GET['name'];

            $deleteData = $this->quiz_model->delete($name);

            redirect(base_url().'quiz/index');

    }

    function playerResult(){
        if (isset($_SESSION['admin'])) {
            $name = $_GET['name'];
            $playerResult['playerResult'] = $this->quiz_model->getPlayerResult($name);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(
                    array(
                        'data' => $playerResult
                    )
                );
            } else {
                $this->load->view('question', $playerResult);
                unset($_SESSION['player']);
            }
        }
        else{
            redirect(base_url().'quiz/login');
        }
    }

}