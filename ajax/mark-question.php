<?php

/* Start Session */
session_start();


/* Include the database files and connect to MySQL server */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();


/* Get Id of the users answer */
$answerId = $_POST['answerId'];


/* Check the Answer */
$score = 0;
$query = $db->query("SELECT CorrectAnswer, question_ID FROM answers WHERE ID = '$answerId'");
if(count($query)>0){
    if($query[0]['CorrectAnswer']=='f'){
        $score = 0;
    }
    else if($query[0]['CorrectAnswer']=='t'){
        $score = 1;
    }
    else{
        $score = 'u';
    }
}


/* Update the score array */
$questionId = $query[0]['question_ID'];
$_SESSION['score'][$questionId] = $score;


/* Push the selected answer to array */
array_push($_SESSION['choices'],$answerId);


/* Get the correct answer */
$correctAnswers = array();
$query2 = $db->query("SELECT ID FROM answers WHERE question_ID = '$questionId' AND correctAnswer = 't'");
for($i = 0; $i<count($query2); $i++){
    $correctAnswers[$i] = $query2[$i]['ID'];
}


/* Get answer explanation */
$query3 = $db->query("SELECT Explanation FROM questions WHERE ID = '$questionId'");
$explanation = $query3[0]['Explanation'];


/* Return the result */
$result = array();

$result['score'] = $score;
$result['correctAnswers'] = $correctAnswers;

echo json_encode($result);
