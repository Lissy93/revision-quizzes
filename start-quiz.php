<?php

session_start();

/* Include the database files and connect to MySQL server */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();


/* Include the fetch object */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
$fetch = new Fetch();

/* Include the scores object */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Scores.class.php";
$scores = new Scores();

/* Get average rating */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Ratings.class.php";
$ratings = new Ratings();

/* Check the quiz ID */
$quizSelected = false;
$quizId = 0; // NOTE: $quizId should only be used if $quizSelected == true
if(isset($_GET['id'])){
    if($fetch->doesQuizExist($_GET['id'])){
        $quizSelected = true;
        $quizId = $_GET['id'];
    }
}
else if(isset($_POST['id'])){
    if($fetch->doesQuizExist($_POST['id'])){
        $quizSelected = true;
        $quizId = $_POST['id'];
    }
}

if($quizSelected){

    /* Create Quiz Object  */
    $quiz = $fetch->fetchQuiz($quizId);

    /* Prepare the session for starting the quiz */
    $_SESSION['currentQuizId'] = $quizId;

    /* Average rating */
    $averageQuizRating = $ratings->getRating($quizId);

    /* Has quiz been done before */
    $doneBefore = false;

    $numActualQuestions = 0;
    $numQuestionsLast = 0;
    $scoreLastTime = 0;
    $lastTimeMessage = "";

    $questions = $quiz->getQuestions();
    for($i=0; $i<count($questions); $i++){
        $numActualQuestions++;
        if(isset($_SESSION['score'][$questions[$i]->getId()])){
            $numQuestionsLast++;
            if($_SESSION['score'][$questions[$i]->getId()]==1){
                $scoreLastTime++;
            }
        }
    }

    /* Quiz Actions */
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Actions.quiz.class.php";
    $actions = new Actions();
    $averageScore = $scores->getAverageScore($quizId).'/'.$numActualQuestions;
    $moreQuizzesFromCreator = $actions->fetchMoreQuizzesFromCreator($quizId);

    /* Get score from last time, if was done before */
    if($numActualQuestions>$numQuestionsLast && $numQuestionsLast > 0){
        $lastTimeMessage = "You started this quiz earlier, but didn't complete it";
        $doneBefore = true;
    }
    else if($numActualQuestions==$numQuestionsLast){
        $lastTimeMessage = "You got ".$scoreLastTime."/".$numQuestionsLast." last time";
        $doneBefore = true;
    }

    /* Unset the session data from last time if quiz done before */
    if($doneBefore){
        for($i=0; $i<count($questions); $i++){
            if(isset($_SESSION['score'][$questions[$i]->getId()])){
                unset($_SESSION['score'][$questions[$i]->getId()]);
            }
        }
    }

    /* Delete data in choices session */
    $_SESSION['choices'] = array();
}

else{

    /* Prepare a message to print to the user if quiz not found */
    if(!isset($_GET['id'])&&!isset($_POST['id'])){
        $message = 'No quiz selected';
    }
    else if(isset($_GET['id'])){ if (!$fetch->doesQuizExist($_GET['id'])){
        $message = "Quiz ID '".($_GET['id'])."' not found";
    }}
    else if(isset($_POST['id'])){ if (!$fetch->doesQuizExist($_POST['id'])){
        $message = "Quiz ID '".$_POST['id']."' not found";
    }}
    else{
        $message = "Unknown Error";
    }

}





?>

<!DOCTYPE html>
<html>
<head>
    <title>Revision Quizzes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/styles.css" rel="stylesheet">

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
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

    <?php if($quizSelected) : ?>

        <div class="quiz-row row">

            <div class="quiz-col col-md-2">
                <div class="question-wrap quiz-sec">
                    <?php for ($i=0; $i<count($quiz->getQuestions()); $i++){?>
                        <p>Question <?php echo $i+1; ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-10">
                <div class="main-wrap quiz-sec">

                    <div class="quiz-title-wrap">
                        <h2><?php echo $quiz->getName(); ?></h2>
                        <p class="description"><?php echo $quiz->getDescription(); ?></p>
                        <p>
                            <?php if ($quiz->getLevel()!=null){echo $quiz->getLevel();}
                                  echo " ";
                                  if ($quiz->getSubject()!=null){echo $quiz->getSubject();} ?>
                        </p>

                        <div class="star-background star-background-small" style="width: <?php echo ($averageQuizRating / 5 * 98)?>px" ></div>
                        <img class="star-template star-template-small" src="/img/starsBack.png"/>
                        <br />
                        <p class="note">Average Rating: <?php echo round($averageQuizRating,1); ?>/5 </p>

                    </div>

                    <div class="quiz-stats-wrap">

                        <?php if($quiz->getCreator()!=null){ ?>
                            <p class="inline-label">Creator</p>
                            <p class="inline-value"><?php echo $quiz->getCreator(); ?></p>
                            <br />
                        <?php } ?>

                        <?php if($quiz->getTakes()>0){ ?>
                            <p class="inline-label">Number of times taken</p>
                            <p class="inline-value"><?php echo $quiz->getTakes(); ?></p>
                            <br />
                        <?php } ?>

                        <?php if($quiz->getTargetTime()!="00:00:00" && $quiz->getTargetTime()!=0){ ?>
                            <p class="inline-label">Target Time:</p>
                            <p class="inline-value"><?php echo $quiz->getTargetTime(); ?> Seconds</p>
                            <br />
                        <?php } ?>

                        <?php if($quiz->getTimeLimit()!="00:00:00"){ ?>
                            <p class="inline-label">Time Limit:</p>
                            <p class="inline-value"><?php echo $quiz->getTimeLimit(); ?></p>
                            <br />
                        <?php } ?>

                        <?php if($quiz->getPassMark()!="0"){ ?>
                            <p class="inline-label">Pass Mark:</p>
                            <p class="inline-value"><?php echo $quiz->getPassMark(); ?></p>
                            <br />
                        <?php } ?>

                        <?php if($averageScore!="0"){ ?>
                            <p class="inline-label">Average Score:</p>
                            <p class="inline-value"><?php echo $averageScore; ?></p>
                            <br />
                        <?php } ?>

                    </div>

                    <div class="quiz-start-wrap">
                        <p><?php echo $lastTimeMessage; ?></p>
                        <form action="/quiz.php" method="post">
                            <input type="hidden" name="quizId" value="<?php echo $quizId;?>" />
                            <button type="submit" class="start-button button">Start</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>


    <?php else : ?>

        <div class="main-wrap sec">
            <h3>Error</h3>
            <p><?php echo $message; ?></p>
        </div>

    <?php endif; ?>


</div>

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
<script src="js/home.js"></script>
<script src="js/analytics.js"></script>		
</body>
</html>