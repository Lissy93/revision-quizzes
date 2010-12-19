<?php

session_start();

/* Include the database files and connect to MySQL server */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();


/* Include the fetch object */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
$fetch = new Fetch();

/* Check the quiz ID */
$quizSelected = false;
$quizStarted = false;
$lastQuestion = false;
$quizId = 0; // NOTE: $quizId should only be used if $quizSelected == true
$qn = 0; // Question number
$message = ""; // Message to tell the user if there is an error

if(isset($_POST['next-qn'])){
    $qn = $_POST['next-qn'];
}
else{
    $qn = 1;
}

if(isset($_POST['quizId'])){
    if($fetch->doesQuizExist($_POST['quizId'])){
        $quizId = $_SESSION['quizId'] = $_POST['quizId'];
        $quizSelected = true;
    }
    else{
        $message = "Quiz Doesn't Exist";
    }
}

else if(isset($_SESSION['quizId'])){
    if($fetch->doesQuizExist($_SESSION['quizId'])){
        $quizId = $_SESSION['quizId'];
        $quizSelected = true;

        if(isset($_SESSION[$quizId]['results'])){
            $quizStarted = true;
        }
    }
}
else{
    $message = "No Quiz Selected";
}


if($quizSelected && $quizId!=0){

    /* Create Quiz Object  */
    $quiz = $fetch->fetchQuiz($quizId);


    /* Quiz Actions */
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Actions.quiz.class.php";
    $actions = new Actions();
    $question = $quiz->getQuestions(); $question = $question[$qn-1]; //TODO this is crap
    $answers = $question->getAnswers();

    /* Check additional details */
    $additionalContent = true;
    if($question->getNotes()==""&&$question->getImage()==""){
        $additionalContent = false;
    }

    /* CHeck if answer explanation */
    $isAnswerExpl = false;
    if($question->getExp()!="" && $question->getExp()!=" "){
        $isAnswerExpl = true;
    }

    /* CHeck if last question */
    if(count($fetch->makeQuestionObjectsArr($quizId))==$qn){
        $lastQuestion = true;
    }

    /* Start the timer if first question */
    if($qn == 1){
        $_SESSION['startTime'.$quizId] = time(); // current time
    }

}

else{


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

<div class="white-out">

</div>

<div class="alert alert-warning my-alert" id="alert-marking">
    <a href="#" class="alert-link">Checking Answer...</a>
</div>

<div class="alert alert-warning my-alert" id="alert-stats">
    <a href="#" class="alert-link">Fetching Stats...</a>
</div>

<p id="hiddenQuestionId" class="hide"><?php echo $question->getId(); ?></p>

<div class="everything">
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



                <div class="quiz-col col-md-1">
                    <div class="question-wrap quiz-sec">
                        <?php for ($i=0; $i<count($quiz->getQuestions()); $i++){  ?>
                            <div class="q-side <?php echo $qn == $i+1 ? 'current-q-side':''; ?>">
                                <p>Question
                                    <?php echo $i+1;
                                    $ques = $quiz->getQuestions();
                                    $qObjId = $ques[$i]->getId();
                                    if(isset($_SESSION['score'][$qObjId])){
                                        if($_SESSION['score'][$qObjId]==1){ echo "&#10003;"; }
                                        else if($_SESSION['score'][$qObjId]==0){ echo "&#10007;"; }
                                    }
                                    else { echo " ...";}  ?>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-md-11">
                    <div class="main-wrap quiz-sec">
                        <div class="quiz-question">
                            <p class="question-text"><?php echo $question->getQuestion(); ?></p>
                            <div class="row">
                                <div class="col-md-<?php echo ($additionalContent? 6 : 9); ?>">
                                    <?php if ($question->getType()=='mc'){ ?>
                                        <?php for($i=0; $i<count($answers); $i++){ ?>
                                            <div class="answer-block" id="qid<?php echo $answers[$i]->getId(); ?>">
                                                <div class="answer-block-filler" ><p class="txtPercent"></p></div>
                                                <p><?php echo $answers[$i]->getAnswer(); ?></p>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="col-md-<?php echo ($additionalContent? 6 : 3); ?>">
                                    <?php if($isAnswerExpl):?>
                                        <div id="ansExpl-wrap">
                                            <p><?php echo $question->getExp(); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($additionalContent):?>
                                        <?php if ($question->getImage() != null){?>
                                            <img src="/pic/<?php echo $question->getImage(); ?>" />
                                        <?php }?>
                                        <?php if ($question->getNotes() != null){?>
                                            <p><?php echo $question->getNotes(); ?><p>
                                        <?php }?>
                                    <?php endif; ?>
                                    <div id="question-results">
                                        <p class="result-title">Incorrect</p>
                                        <p id="ans-expl"></p>
                                        <?php if(!$lastQuestion){ ?>
                                        <form action="/quiz.php" method="post">
                                            <input type="hidden" name="quizId" value="<?php echo $quizId;?>" />
                                            <input type="hidden" name="next-qn" value="<?php echo $qn+1;?>" />
                                            <button type="submit" class="next-button button">Next Question</button>
                                        </form>
                                        <?php } else if ($lastQuestion){?>
                                            <form action="/results.php" method="post">
                                                <input type="hidden" name="quizId" value="<?php echo $quizId;?>" />
                                                <button type="submit" class="next-button button">Finish</button>
                                            </form>
                                        <?php } ?>
                                        <div class="button stats-button" id="show-stats"><p>Show Stats</p></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php else : ?>

            <div class="main-wrap quiz-sec">
                <h2>Error</h2>
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
<script src="js/quiz-page.js"></script>
<script src="js/analytics.js"></script>		
</body>
</html>