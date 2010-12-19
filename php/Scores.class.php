<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alicia
 * Date: 31/12/13
 * Time: 17:20
 * To change this template use File | Settings | File Templates.
 */

class Scores {

    function __construct( ){
        $this->db = new Db();
        $this->db->connect();
    }

    public function getStats($questionId){

        /* Fetch data with query and declare variables */
        $dbNumCorr = $this->db->query("SELECT ID, numCorr FROM answers WHERE question_ID = $questionId");
        $timesDone = 0;
        $results = array();

        /* Find the number of times that question has been done*/
        for($i=0; $i<count($dbNumCorr); $i++){
            $timesDone += $dbNumCorr[$i]['numCorr'];
        }

        /* Calculate percentage for each answer and add to results array */
        for($i=0; $i<count($dbNumCorr); $i++){
            $results[$i]['ansId'] = $dbNumCorr[$i]['ID'];
            $results[$i]['percent'] = round($dbNumCorr[$i]['numCorr'] / $timesDone * 100);
        }

        return $results;
    }



    public function calculateUsersScore($marks, $quizId){

        /* Variables */
        $incomplete = false;
        $quizResults = array();
        $total = 0;

        /* Get question ID's from database */
        $dbQuery = $this->db->query("SELECT ID FROM questions WHERE quiz_ID = '$quizId'");
        $numQuestions = count($dbQuery);

        /* Iterate though db data and add to array */
        for($i=0; $i<$numQuestions; $i++){
            if(isset($marks[$dbQuery[$i]['ID']])){
                $quizResults[$dbQuery[$i]['ID']] = $marks[$dbQuery[$i]['ID']]; }
            else{ $incomplete = true; } // We're missing a question from the score array :(
        }

        /* Get total */
        foreach($quizResults as $val){ $total += $val; }

        /* Results to return */
        $results['total'] = $total;
        $results['array'] = $quizResults;
        $results['percent'] = round($total / $numQuestions * 100);
        $results['complete'] = $incomplete;

        return $results;

    }


    public function addScoresDb(){

        $answerChoices = $_SESSION['choices'];

        for($i = 0; $i<count($answerChoices); $i++){
            mysql_query("UPDATE answers SET NumCorr = NumCorr + 1 WHERE ID = '$answerChoices[$i]'");
        }

    }


    public function addQuizTime($quizId, $score, $timeTaken, $doneBefore, $user='unknown'){
        $points = $this->calculatePoints($timeTaken, $score, $doneBefore);
        mysql_query("INSERT INTO scores (quiz_ID, user, score, time, points)
                     VALUES ('$quizId','$user','$score','$timeTaken','$points');");
    }

    function calculatePoints($time, $score, $doneBefore){
        if ($doneBefore){
            return 0; // The user can't get into the high scores if they've done the quiz before
        }
        else{
            $points = round((((1/$time)*1000)+($score*2))*10);
            return $points;
        }
    }


    public function getHighScores($quizId, $limit = 10){
        $queryResults = $this->db->query("SELECT ID, user, score, points,  time FROM scores WHERE quiz_ID = '$quizId' ORDER BY score DESC, time ASC LIMIT $limit");
        $results = array();
        for($i = 0; $i<count($queryResults); $i++){
            $results[$i]['id'] = $queryResults[$i]['ID'];
            $results[$i]['name'] = $queryResults[$i]['user'];
            $results[$i]['score'] = $queryResults[$i]['score'];
            $results[$i]['points'] = $queryResults[$i]['points'];
            $results[$i]['time'] = floor($queryResults[$i]['time'] / 60) . ":" . sprintf("%02s", $queryResults[$i]['time'] % 60);
        }
        return $results;
    }


    public function getAverageScore($quizId){
        $queryResults = $this->db->query("SELECT score FROM scores WHERE quiz_ID = '$quizId'");
        $numRecords = count($queryResults);
        $total = 0;
        for($i=0; $i<$numRecords; $i++){
            $total += $queryResults[$i]['score'];
        }
        if($numRecords>0){
            $average = round($total / $numRecords);}
        else{$average = '-';}
        return $average;
    }

    public function getAverageTime($quizId){
        $queryResults = $this->db->query("SELECT time FROM scores WHERE quiz_ID = '$quizId'");
        $numRecords = count($queryResults);
        $total = 0;
        for($i=0; $i<$numRecords; $i++){
            $total += $queryResults[$i]['time'];
        }


        $average = round($total / $numRecords);
        $result = floor($average/ 60) . ":" . sprintf("%02s", $average % 60);
        return $result;
    }


    public function updateUsersName($scoreId, $usersName){
        $usersName = mysql_real_escape_string($usersName);
        mysql_query("UPDATE scores SET user = '$usersName' WHERE ID = $scoreId");
    }

    public function getPercentCompletionRate($quizId){
        $takes = $this->db->query("SELECT takes FROM quizzes WHERE ID = $quizId"); $takes = $takes[0]['takes'];
        $firstQuestionId = $this->db->query("SELECT ID FROM questions WHERE quiz_ID = $quizId AND QuestionNumber = 1");
        $firstQuestionId = $firstQuestionId[0]['ID'];
        $firstQAnswers = $this->db->query("SELECT NumCorr FROM answers WHERE question_ID = $firstQuestionId");
        $t = 0;
        for($i=0; $i<count($firstQAnswers); $i++){
            $t += $firstQAnswers[$i]['NumCorr'];
        }
        $result = round($takes/$t * 100);
        if($result>100){$result = 100;}

        return $result;
    }
}