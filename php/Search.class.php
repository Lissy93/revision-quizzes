<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 02/03/14
 * Time: 12:14
 */

class Search {

    private $db;

    function __construct( ){
        $this->db = new Db();
        $this->db->connect();
    }

    function search($term,$limit = 300){
        $term = mysql_real_escape_string($term);
        $termsArr = explode(" ",$term);
        $f = new Fetch();

        $primaryResults = array();
        foreach($termsArr as $term){
            $primaryResults = array_merge($primaryResults, $f->makeQuizObjectsArr($this->db->query("SELECT * FROM quizzes WHERE Name LIKE '%$term%'")));
        }


        $results = $primaryResults; // Results to return

        return $results;
    }


} 