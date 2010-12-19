<?php


/* Start Session */
session_start();

/* Include files */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Scores.class.php";

/* Get ID of question */
$questionId = $_GET['questionId'];

/* Call method to calculate results */
$scores = new Scores();
$results = $scores->getStats($questionId);

/* Return results */
$results = json_encode($results);
echo $results;