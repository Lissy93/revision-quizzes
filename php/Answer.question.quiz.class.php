<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alicia
 * Date: 20/12/13
 * Time: 12:39
 * To change this template use File | Settings | File Templates.
 */

class Answer {

    /* For all answers */
    private $id;        // The unique id of the answer
    private $numCor;    // The number of people who got this correct

    /* For multiple choice questions only */
    private $answer;    // The text answer to display
    private $correct;   // boolean is it correct

    /* For short question only */
    private $shortAnswer;   // Short sentence that should match users input

    /* For long questions only*/
    private $rightWords;    // Array of correct key words
    private $wrongWords;    // Array of incorrect key words



    public function setAnswer($answer){
        $this->answer = $answer;
    }

    public function getAnswer(){
        return $this->answer;
    }

    public function setCorrect($correct){
        $this->correct = $correct;
    }

    public function getCorrect(){
        return $this->correct;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setNumCor($numCor){
        $this->numCor = $numCor;
    }

    public function getNumCor(){
        return $this->numCor;
    }

    public function setRightWords($rightWords){
        $this->rightWords = $rightWords;
    }

    public function getRightWords(){
        return $this->rightWords;
    }

    public function setShortAnswer($shortAnswer){
        $this->shortAnswer = $shortAnswer;
    }

    public function getShortAnswer(){
        return $this->shortAnswer;
    }

    public function setWrongWords($wrongWords){
        $this->wrongWords = $wrongWords;
    }

    public function getWrongWords(){
        return $this->wrongWords;
    }


}