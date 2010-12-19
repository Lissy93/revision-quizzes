<?php


/* Connect to the database */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();


/* Delete questions that are linked to a non-existent quiz*/
$questions = $db->query("SELECT * FROM questions ");
$numQuestions = count($questions);
$badQuestions = array();    // list of question ID's to be deleted

for($i=0; $i<$numQuestions; $i++){
    $quizId = $questions[$i]['quiz_ID'];
    if(!checkQuizIdExists($quizId)){
        $badQuestions[] = $questions[$i]['ID'];
    }
    deleteBadQuestions($badQuestions);
}

echo count($badQuestions)." questions with invalid quiz ID's<br>";      // print message


/* Delete quizzes with no questions */
$quizzes = $db->query("SELECT * FROM quizzes ");
$numQuizzes = count($quizzes);
$badQuizzes = array();    // list of quiz ID's to be deleted

for($i=0; $i<$numQuizzes; $i++){
    if(!doesQuizHaveQuestions($quizzes[$i]['ID'])){
        $badQuizzes[] = $quizzes[$i]['ID'];
    }
}
deleteBadQuizzes($badQuizzes);

echo count($badQuizzes)." quizzes with no questions<br>";               // print message



/* Check if quiz ID is valid */
function checkQuizIdExists($quizId){
    $db = new Db();
    $quizzes = $db->query("SELECT ID FROM quizzes ");
    $numQuizzes = count($quizzes);

    for($i=0; $i<$numQuizzes; $i++){
        $currentQuizId = $quizzes[$i]['ID'];
        if($currentQuizId == $quizId){
            return true; // found quiz
        }
    }
    return false; // if we've got this far without finding the quiz, it clearly doesn't exist
}

/* Deletes every question corresponding to an array of integer ID's passed as parameter */
function deleteBadQuestions($badQuestions){
    for($i = 0; $i< count($badQuestions); $i++){
        mysql_query("DELETE FROM questions WHERE ID='$badQuestions[$i]'");
    }
}


/* Check if quiz with ID has got questions associated with it */
function doesQuizHaveQuestions($quizId){
    $db = new Db();
    $questions = $db->query("SELECT ID FROM questions WHERE quiz_ID = '$quizId' ");
    $numQuestions = count($questions);
    if($numQuestions>0){
        return true;
    }
    else{
        return false;
    }
}


/* Delete bad quizzes from array of int quiz ID's */
function deleteBadQuizzes($badQuizzes){
    for($i = 0; $i< count($badQuizzes); $i++){
        mysql_query("DELETE FROM quizzes WHERE ID='$badQuizzes[$i]'");
    }
}




