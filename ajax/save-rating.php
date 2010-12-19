<?php

/* Start Session */
session_start();

/* Include files */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Ratings.class.php";

/* Get data */
$quizId = $_GET['quizId'];
$rating = $_GET['rating'];

/* New ratings object */
$ratings = new Ratings();

/* Add Rating */
$ratings->saveRating($quizId,$rating);

/* Get Ratings */
$quizRating = $ratings->getRating($quizId);

/* Return Results */
echo $quizRating;


