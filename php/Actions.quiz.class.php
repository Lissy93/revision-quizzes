<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alicia
 * Date: 21/12/13
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */

class Actions {


    private $db;

    function __construct( ){
        $this->db = new Db();
        $this->db->connect();
    }


    function calculateAverageScore($quizId){
        //todo fill in this function

        return 0;
    }

    function fetchMoreQuizzesFromCreator($quizId){
        //todo fill in this function

        return array();
    }

    function fetchSubCategorys($subject){
        $quizzes = $this->db->query("SELECT subCat FROM quizzes WHERE subject = '$subject'");
        $results= array();
        for($i=0; $i<count($quizzes); $i++){

            $subCat = ucwords(strtolower($quizzes[$i]['subCat']));
            if($subCat!=""){
                array_push($results,$subCat);}
        }
        $results = array_values(array_unique($results));
        return $results;
    }

    function fetchLevels($subject){
        $quizzes = $this->db->query("SELECT level FROM quizzes WHERE subject = '$subject'");
        $results= array();
        for($i=0; $i<count($quizzes); $i++){
            $level = ucwords(strtolower($quizzes[$i]['level']));
            if($level!=""){
                array_push($results,$level);}
        }
        $results = array_values(array_unique($results));
        return $results;
    }

}