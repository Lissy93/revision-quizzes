<?php

/* Start Session */
session_start();

/* Include files */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Search.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Ratings.class.php";

/* Get parameters */
$whichQuizzes = $_GET['whichQuizzes'];  // Which quizzes to fetch
$limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
$searchTerm = isset($_GET['term']) ? $_GET['term'] : "";

/* Call corresponding function */
$fetch = new Fetch();
$search = new Search();
$results = null;

if($whichQuizzes == 'recent'){
    $quizObjects = $fetch->fetchRecentQuizzes($limit);
}
else if ($whichQuizzes == 'suggested'){
    $quizObjects = $fetch->fetchSuggestedQuizzes($limit);
}
else if($whichQuizzes == 'topRated'){
    $quizObjects = $fetch->fetchTopQuizzes($limit);
}
else if($whichQuizzes == 'featured'){
    $quizObjects = $fetch->fetchFeaturedQuizzes($limit);
}
else if($whichQuizzes == 'search' && $searchTerm!=""){
    $quizObjects = $search->search($searchTerm, $limit);
}
else if($whichQuizzes == 'all'){
    $quizObjects = $fetch->fetchAllQuizzes($limit);
}


/* Put results into a string array */
$results = array();
for($i=0; $i<count($quizObjects);$i++){
    $results[$i]['ID'] = $quizObjects[$i]->getId();
    $results[$i]['name'] = $quizObjects[$i]->getName();
    $results[$i]['description'] = $quizObjects[$i]->getDescription();
    $results[$i]['level'] = $quizObjects[$i]->getLevel();
    $results[$i]['subject'] = $quizObjects[$i]->getSubject();
    $results[$i]['takes'] = $quizObjects[$i]->getTakes();
    $results[$i]['creatorName'] = $quizObjects[$i]->getCreatorName();
}

/* Return results */

$results2 = json_encode($results);

echo $results2;



