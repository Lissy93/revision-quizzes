<?php

/* Connect to the database */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();

/* Fetch Quizzes */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
$fq = new Fetch();
$recentQuizzes = $fq->fetchRecentQuizzes(3);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Revision Quizzes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/styles.css" rel="stylesheet">

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<nav class="navbar navbar-default top-navbar" role="navigation">
    <div class="navbar-logo">
        <h1>Revision Quizzes</h1>
    </div>
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


<div class="main-content jumbotron">

    <div class="row">
        <div class="col-md-12">
            <div class="featured-wrap sec">
                <h3>Featured</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="search-wrap sec ">
                <h3>Search</h3>
                <div class="src-container">
                    <form action="search.php" method="get">
                        <input type="text" name="txtSearch" class="src-box"/>
                        <button type="submit" class="src-button">Search</button>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="latest-wrap sec">
                <h3>New Quizzes</h3>
                <div class="qt-container">
                    <?php foreach ($recentQuizzes as &$quiz) { ?>
                        <a href="<?php echo "start-quiz.php?id=".$quiz->getId()?>">
                            <div class="qt-tile" title="<?php echo $quiz->getName()."\r\n".$quiz->getDescription(); ?>">

                                <p class="qt-title"><?php echo substr($quiz->getName(),0,60);
                                    echo (strlen($quiz->getName()) >60?"....":""); ?></p>

                                <p class="qt-description"><?php echo substr($quiz->getDescription(),0,70);
                                    echo (strlen($quiz->getDescription()) >70?"....":"");?></p>
                                <div class="qt-firstDetails">
                                    <p class="qt-level-subject"><?php echo $quiz->getLevel()." ".$quiz->getSubject(); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="bylevel-wrap sec">
                <h3 clas="byLevelTitle">Quizzes by Level</h3>
                <p class="bylevelOpp">Popular Levels:</p>
                <p class="bylevelOpp">GCSE</p>
                <p class="bylevelOpp">A Level</p>
                <p class="bylevelOpp">Degree</p>
                <br />
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bycat-wrap sec">
                <h3>Quizzes by Category</h3>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
                <a href="#">
                    <div class="qt-tile">
                        <p class="qt-title">[TITLE OF QUIZ]</p>
                        <p class="qt-description">[OPTIONAL SHORT DESCRIPTION OF THE SECIFIED QUIZ HERE]</p>
                    </div>
                </a>
            </div>
        </div>
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
</body>
</html>