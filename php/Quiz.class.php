<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alicia
 * Date: 26/11/13
 * Time: 20:03
 * To change this template use File | Settings | File Templates.
 */

class Quiz {

    /* Instance Variables */

    private $id;            // The unique integer ID of quiz
    private $name;          // Title of a quiz
    private $description;   // Optional short description, max 200 char
    private $level;         // Level as text
    private $subject;       // Subject as text
    private $subCategory;   // The sub-category of the quiz
    private $takes;         // The number of times a quiz has been taken
    private $creator;       // A user object for the creator
    private $creatorName;   // The text name of creator
    private $targetTime;    // The target time to complete the quiz
    private $timeLimit;     // The time limit
    private $passMark;      // The pass mark
    private $privacy;       // public | private

    private $questions;      // An array of Question objects


    /* Magic Methods */

    public function __sleep()
    {
        return array($this->id,$this->name,$this->description, $this->level, $this->subject, $this->takes,  $this->creatorName);
    }

    public function __wakeup()
    {
        $this->connect();
    }

    /* Getters and Setters */

    public function setCreator($creator){
        $this->creator = $creator;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreatorName($creatorName){
        $this->creatorName = $creatorName;
    }

    public function getCreatorName(){
        return $this->creatorName;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setLevel($level){
        $this->level = $level;
    }

    public function getLevel(){
        return $this->level;
    }


    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;
    }


    public function getSubCategory()
    {
        return $this->subCategory;
    }



    public function setName($name){
        $this->name = $name;
    }


    public function getName(){
        return $this->name;
    }

    public function setPassMark($passMark){
        $this->passMark = $passMark;
    }

    public function getPassMark(){
        return $this->passMark;
    }

    public function setPrivacy($privacy){
        $this->privacy = $privacy;
    }

    public function getPrivacy(){
        return $this->privacy;
    }

    public function setQuestions($questions){
        $this->questions = $questions;
    }

    public function getQuestions(){
        return $this->questions;
    }

    public function setSubject($subject){
        $this->subject = $subject;
    }

    public function getSubject(){
        return $this->subject;
    }

    public function setTakes($takes){
        $this->takes = $takes;
    }

    public function getTakes(){
        return $this->takes;
    }

    public function setTargetTime($targetTime){
        $this->targetTime = $targetTime;
    }

    public function getTargetTime(){
        return $this->targetTime;
    }

    public function setTimeLimit($timeLimit){
        $this->timeLimit = $timeLimit;
    }

    public function getTimeLimit(){
        return $this->timeLimit;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }






}