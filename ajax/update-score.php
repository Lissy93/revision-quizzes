<?php

/* Start Session */
session_start();

/* Include files */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Scores.class.php";

/* Get data */
$scoreId    = $_GET['scoreId'];
$usersName  = $_GET['usersName'];

/* New scores object */
$scores = new Scores();

/* Update Score */
$scores->updateUsersName($scoreId, $usersName);

echo "helo ".$usersName;