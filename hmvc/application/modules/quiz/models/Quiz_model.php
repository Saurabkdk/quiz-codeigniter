<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }

    public function savePlayer($player){
        if (isset($player['id'])){

        }
        else{
            try {
                $insertPlayer = $this->db->set('name', $player['name'])
                                         ->insert('quiz_played');
            } catch (Exception $e){
                $insertPlayer = false;
                echo $e;
            }
        }

        if ($insertPlayer){
            return true;
        }
        return false;
    }

    public function getQuestion($id){

        $this->db->where('id', $id);
//        if ($id > 0){
            $quiz['question'] = $this->db->get('quiz_questions')->row();
//        }
//        else{
//            $quiz['question'] = $this->db->get('quiz_questions')->result();
//        }

        $this->db->where('id', $id);
//        if (id > 0) {
            $quiz['answers'] = $this->db->get('quiz_options')->row();
//        }
//        else{
//            $quiz['answers'] = $this->db->get('quiz_options')->result();
//        }

//        $this->db->select('id, question, a, b, c, d, answer');
//        $this->db->from('quiz_questions');
//        $this->db->join('quiz_options', 'quiz_questions.id = quiz_options.id');
//        $quiz = $this->db->get()->result();

        return $quiz;
    }

    public function getPlayerResult($name){
        return $this->db->select('quiz_questions.id, question, a, b, c, d, answer, answer_submitted, name')
                                 ->from('quiz_questions')
                                 ->join('quiz_options', 'quiz_options.id = quiz_questions.id')
                                 ->join('quiz_played', 'quiz_played.question_id = quiz_questions.id')
                                 ->where('quiz_played.name', $name)
                                 ->get()->result();
    }

    function getPlay($id, $name){
        if ($id > 0){
            $values = array('question_id' => $id, 'name' => $name);
            $this->db->where($values);
        }
        return $this->db->get('quiz_played')->row();
    }

    function getPlayerName(){
        $this->db->distinct();
        $this->db->select('name');
        return $this->db->get('quiz_played')->result();
    }

    function save($play){
        if (isset($play['id'])){
            $data = array(
                'name' => $play['name'],
                'question_id' => $play['id'],
                'answer_submitted' => $play['answer'],
                'correct' => $play['correct'],
                'time' => $play['time'],
            );
            $values = array('question_id' => $play['id'], 'name' => $play['name']);
            $this->db->where($values);
            $savePlay = $this->db->update('quiz_played', $data);
        }
        else{
            $savePlay = $this->db->set('name', $play['name'])
                                 ->set('question_id', $play['question_id'])
                                 ->set('answer_submitted', $play['answer'])
                                 ->set('correct', $play['correct'])
                                 ->set('time', $play['time'])
                                 ->set('date_time', $play['date_time'])
                                 ->insert('quiz_played');
        }

        if ($savePlay){
            return true;
        }

        return false;

    }

    function delete($name){
        $this->db->where('name', $name);
        $this->db->delete('quiz_played');
    }

    function showTable($correct, $attempt, $name){
        $table['name'] = $this->db->select('name')
                                  ->where('name', $name)
                                  ->limit(1)
                                  ->get('quiz_played')->row();

        $table['date'] = $this->db->select('date_time')
                                  ->where('name', $name)
                                  ->limit(1)
                                  ->get('quiz_played')->row();

        $this->db->where('name', $name);
        $this->db->from('quiz_played');
        $table['total'] = $this->db->count_all_results();

        $values1 = array('correct' => $correct, 'name' => $name);
        $this->db->where($values1);
        $this->db->from('quiz_played');
        $table['correct'] = $this->db->count_all_results();

        $values2 = array('answer_submitted' => $attempt, 'name' => $name);
        $this->db->where($values2);
        $this->db->from('quiz_played');
        $table['attempt'] = $this->db->count_all_results();

        $table['time'] = $this->db->select_sum('time')
                                  ->where('name', $name)
                                  ->get('quiz_played')->row();

        return $table;

    }

}