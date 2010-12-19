<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alicia
 * Date: 03/01/14
 * Time: 11:17
 * To change this template use File | Settings | File Templates.
 */

class Ratings {

    function __construct( ){
        $this->db = new Db();
        $this->db->connect();
    }

    public function saveRating($quizId, $rating){
        mysql_query("INSERT INTO ratings (quiz_ID, rating) VALUES ('$quizId','$rating')");
        if(!isset($_COOKIE['ratings'])){setcookie('ratings',serialize(array()),time()+60*60*24*365, '/');}


        $newCookie = unserialize($_COOKIE['ratings']);
        $newCookie[$quizId] = $rating;
        $newCookie = serialize($newCookie);

        setcookie('ratings', $newCookie,time()+60*60*24*365, '/');
    }

    public function getRating($quizId){
        $queryData = $this->db->query("SELECT rating FROM ratings WHERE quiz_ID = '$quizId'");
        $num = count($queryData);
        $total = 0;
        for($i=0; $i<$num; $i++){ $total += $queryData[$i]['rating']; }
        if($total !=0 && $num !=0){ $result = round($total/$num,3); }
        else { $result = 0;}
        return $result;
    }

    public function countNumRatings($quizId){
        $queryData = $this->db->query("SELECT rating FROM ratings WHERE quiz_ID = '$quizId'");
        return count($queryData);
    }

}
