<?php

session_start();

/* Include the database files and connect to MySQL server */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();


/* Include the fetch object */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
$fetch = new Fetch();

/* Get quiz details */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Quiz.class.php";
$quizId = $_POST['quizId'];
$quiz = $fetch->fetchQuiz($quizId);

/* Get the questions */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Question.quiz.class.php";
$questions = $quiz->getQuestions();

/* Get the score */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Scores.class.php";
$scores = new Scores();
$usersResults = $scores->calculateUsersScore($_SESSION['score'], $quizId);

/* Add the answer choices to the db */
$scores->addScoresDb();

/* Has the quiz been done before */
$doneBefore = false;
if(isset($_COOKIE['done-quizzes'])){
    $doneArr = json_decode($_COOKIE['done-quizzes']);
    if($doneArr!=null){
        foreach ($doneArr as $eachDoneQuiz){
            if($eachDoneQuiz==$quizId){
                $doneBefore = true;
            }
        }
    }
}

/* Calculate time it took to do quiz */
$timeTaken = time() - $_SESSION['startTime'.$quizId];
$scores->addQuizTime($quizId, $usersResults['total'], $timeTaken, $doneBefore);
$scoreId = mysql_insert_id();
$usersTime = floor($timeTaken / 60) . ":" . $timeTaken % 60;

/* Add to list of done quizzes */
if(!isset($_COOKIE['done-quizzes'])){$doneQuizArr = array();}
else{
    $doneQuizArr = json_decode($_COOKIE['done-quizzes']);
    if($doneQuizArr == ''){$doneQuizArr = array();}
}
if(!$doneBefore){
    array_push($doneQuizArr,$quizId);
    setcookie('done-quizzes', json_encode($doneQuizArr),time()+60*60*24*365, '/');
}

/* Get High scores*/
$highScores = $scores->getHighScores($quizId);

/* Has the user got a high score? */
$highScore = false;
if(!$doneBefore){
    for($i=0; $i<count($highScores); $i++){
        if($highScores[$i]['id']==$scoreId){
            $highScore = true;
        }
    }
}

/* Is quiz already rated by user? */
$rated = false;
if(isset($_COOKIE['ratings'])){
    $m = unserialize($_COOKIE['ratings']); $m=$m[$quizId];
    if (isset($m)){
        if($m!='' && $m!=' '){
            $rated = true;
            $usersQuizRating = $m;
        }
    }
}

/* Get average rating */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Ratings.class.php";
$ratings = new Ratings();
$averageQuizRating = $ratings->getRating($quizId);

/* Increment the number of times quiz has been taken */
mysql_query("UPDATE quizzes SET takes = takes + 1 WHERE ID = $quizId");

/* Get % of people who completed the quiz */
$percentCompleteRate = $scores->getPercentCompletionRate($quizId);


/* Did they pass */
$pass = true;
$passMessage = "";
if($quiz->getTargetTime()!=0){
    $tarTimeTxt =  floor($quiz->getTargetTime() / 60) . ":" . $quiz->getTargetTime() % 60;
    if($timeTaken<$quiz->getTargetTime()){
        $passMessage.="You completed this quiz under the target time (".$tarTimeTxt.")<br />";
    }
    else{
        $passMessage.="You didn't quite complete this quiz under the target time (".$tarTimeTxt.")<br />";
        $pass = false;
    }
}
if($quiz->getPassMark()!=0){
    if($usersResults['total']>=$quiz->getPassMark()){
        $passMessage.="You scored above the pass mark (".$quiz->getPassMark().")<br />";
    }
    else{
        $passMessage.="You didn't quite reach the pass mark (".$quiz->getPassMark().")<br />";
        $pass = false;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $quiz->getName(); ?> | Results | Revision Quizzes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/social-likes_flat.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css?v=2.1.5" media="screen" />

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="alert alert-warning my-alert" id="alert-saveRating">
    <a href="#" class="alert-link">Saving Rating...</a>
</div>

<div class="alert alert-warning my-alert" id="alert-addingName">
    <a href="#" class="alert-link">Updating High Scores</a>
</div>

<p class="hide" id="getQuizId"><?php echo $quizId;   ?></p>
<p class="hide" id="getScoreId"><?php echo $scoreId; ?></p>

<div class="everything everything-quiz">
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <a href="/index.php">
            <div class="navbar-logo">
                <h1>Revision Quizzes</h1>
            </div>
        </a>
        <div class="navbar-items">
            <ul>
                <li>
                    <a href="#">
                        <div class="navbar-entry">
                            <p class="navbar-txt">Browse Quizzes</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="navbar-entry">
                            <p class="navbar-txt">Create Quiz</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="navbar-entry">
                            <p class="navbar-txt">Users</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="quiz-main-content  jumbotron">



        <div class="row">

            <div class="col-md-6 results-sec">
                <div class="quiz-title-wrap">
                    <h2>Results - <?php echo $quiz->getName(); ?></h2>
                    <h3 class="final-score-text">
                        <?php echo "You scored <span class='score-number'>".$usersResults['percent']."%</span> (".$usersResults['total']."/".count($usersResults['array']).")"?>
                    </h3>
                    <p>In a time of <?php echo $usersTime; ?></p>

                    <div id="pass-wrap">
                        <?php if($pass):?>
                            <p class="pass">Pass</p>
                        <?php endif; ?>
                        <p class="note"><?php echo $passMessage; ?></p><br />
                    </div>

                    <a href="/">
                        <div class="navbar-entry">
                            <p class="navbar-txt">Return Home</p>
                        </div>
                    </a>
                    <a href="/start-quiz.php?id=<?php echo $quizId; ?>">
                        <div class="navbar-entry">
                            <p class="navbar-txt">Retake Quiz</p>
                        </div>
                    </a>
                    <a href="#">
                        <div class="navbar-entry">
                            <p class="navbar-txt">Browse Similar</p>
                        </div>
                    </a>

                    <div class="navbar-entry" id="scoreShow-button">
                        <p class="navbar-txt" id="scoreShow-button-txt">View Scores</p>
                    </div>

                    <div class="navbar-entry" id="stats-button">
                        <p class="navbar-txt" id="stats-button-txt">View Stats</p>
                    </div>

                    <div class="navbar-entry" id="share-button">
                        <p class="navbar-txt" id="share-button-txt">Share Online</p>
                    </div>

                    <br />
                    <div id="share-wrap">
                        <h3>Share Quiz</h3>
                        <div class="social-likes" data-counters="no" data-url="http://revision-quizzes.com/start-quiz.php?id=<?php echo $quizId; ?>">
                            <div class="facebook" title="Share quiz on Facebook">Facebook</div>
                            <div class="twitter" data-via="lissy_sykes" data-related="Revision Quizzes" title="Share quiz on Twitter">Twitter</div>
                            <div class="plusone" title="Share quiz on Google+">Google+</div>
                        </div>
                    </div>

                    <div id="stats-wrap" class="done-stats-wrap">
                        <br />
                        <div class="panel panel-default">
                            <div class="panel-heading"><p class="stats-title">Quiz Statistics</p></div>
                            <table class="table stats-table">
                                <tr>
                                    <td class="stats-head"><p>Number of times quiz has been taken</p></td>
                                    <td><p><?php echo $quiz->getTakes(); ?></p></td>
                                </tr>
                                <tr>
                                    <td class="stats-head"><p>Average time spent on quiz</p></td>
                                    <td><p><?php echo $scores->getAverageTime($quizId); ?></p></td>
                                </tr>
                                <tr>
                                    <td class="stats-head"><p>Average Mark</p></td>
                                    <td><p><?php echo $scores->getAverageScore($quizId); ?></p></td>
                                </tr>
                                <tr>
                                    <td class="stats-head"><p>Average Quiz Rating</p></td>
                                    <td><p><?php echo $averageQuizRating; ?>/5</p></td>
                                </tr>
                                <tr>
                                    <td class="stats-head"><p>Percentage of people who completed quiz</p></td>
                                    <td><p><?php echo $percentCompleteRate; ?>%</p></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <br />



                </div>
            </div>
            <div class="col-md-6 results-sec highScores-wrap">
                <?php if($highScore):?>
                    <div class="row newHighScore-wrap">
                        <h3>New High Score</h3>
                        <div id="before-score-updated">
                            <p>You have a new high score! Enter your name below:</p>
                            <input type="text" id="txtScoreName" placeholder="Enter Your Name" class="mediumTxt">
                            <div class="navbar-entry" id="saveScore-button">
                                <p class="navbar-txt" id="saveScore-button-txt">Save Score</p>
                            </div>
                        </div>
                        <div id="after-score-updated">
                            <p>Your Name had been Saved in the High Scores</p>
                            <p id="show-scores-again">Click here to view the scores for this quiz</p>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row rating-wrap">
                    <h3>Rate Quiz</h3>
                    <?php if($rated){ ?>
                        <p>You gave this quiz a <?php echo $m;?> star rating</p>
                        <div class="star-background" style="width: <?php echo ($averageQuizRating / 5 * 98) * 2?>px" ></div>
                        <img class="star-template" src="/img/starsBack.png"/>
                        <p class="note rating-note">Average Rating: <?php echo $averageQuizRating; ?>/5 </p>
                    <?php }  else { ?>
                        <div id="rate-quiz-wrap">
                            <p>Rating this quiz will help us rank it acordingly for other users</p>
                            <img src="/img/starEmp.png" class="rating-star" id="star1"/>
                            <img src="/img/starEmp.png" class="rating-star" id="star2"/>
                            <img src="/img/starEmp.png" class="rating-star" id="star3"/>
                            <img src="/img/starEmp.png" class="rating-star" id="star4"/>
                            <img src="/img/starEmp.png" class="rating-star" id="star5"/>
                        </div>
                        <div id="show-rating-wrap">
                            <p>Your rating has been saved</p>
                            <div class="star-background"></div>
                            <img class="star-template" src="/img/starsBack.png" />
                            <p class="note rating-note">Average Rating: <?php echo $averageQuizRating; ?>/5 </p>
                        </div>
                    <?php } ?>
                </div>


                <div class="panel panel-default highScores-panel row" id="scores-wrap">
                    <div class="panel-heading"><h3>High Scores</h3></div>
                    <table class="table high-scores">
                        <thead>
                            <tr>
                                <td>Rank</td>
                                <td>Name</td>
                                <td>Score</td>
                                <td>Time</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php for($i = 0; $i<count($highScores); $i++){?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <?php if ($highScores[$i]['id']==$scoreId):?>
                                    <td class="underline-text" id="users-score-name">You</td>
                                <?php else: ?>
                                    <td><?php echo $highScores[$i]['name']?></td>
                                <?php endif; ?>
                                <td><?php echo $highScores[$i]['score']?></td>
                                <td><?php echo $highScores[$i]['time']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <a class="fancybox fancybox.iframe" href="/forms/report-quiz.php?id=<?php echo $quizId; ?>" id="reportQuiz-btn"><p class="note click reportQuiz-note">Report Quiz</p></a>
</div>
<footer>
    <div class="footer-wrapper navbar navbar-default navbar-static-bottom">
        <p>&#169; <a href="http://aliciasykes.com">Alicia Sykes</a> 2013</p>
    </div>
</footer>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/unslider.js"></script>
<script type="text/javascript" src="js/jquery.fancybox.js"></script>
<script src="js/results-page.js"></script>
<script src="js/social-likes.min.js"></script>
<sscript src="js/analytics.js"></script>		

</body>
</html>
