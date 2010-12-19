<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alicia
 * Date: 20/12/13
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

class Question {

    private $id;        // The unique ID of the question from the database
    private $qn;        // The question number 1...n
    private $question;  // The actual question
    private $type;      // The type of question (mc|short|long)
    private $notes;     // Notes to be displayed along with the question
    private $image;     // Path to image
    private $exp;       // Explanation of the answer

    private $answers;   // An array of answer objects


    public function setExp($exp){
        $this->exp = $exp;
    }

    public function getExp(){
        return $this->exp;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setNotes($notes){
        $this->notes = $notes;
    }

    public function getNotes(){
        return $this->notes;
    }

    public function setQn($qn){
        $this->qn = $qn;
    }

    public function getQn(){
        return $this->qn;
    }

    public function setQuestion($question){
        $this->question = $question;
    }

    public function getQuestion(){
        return $this->question;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function getType(){
        return $this->type;
    }

    public function setImage($image){
        $this->image = $image;
    }

    public function getImage(){
        return $this->image;
    }

    public function setAnswers($answers){
        $this->answers = $answers;
    }

    public function getAnswers(){
        return $this->answers;
    }

}