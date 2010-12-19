<?php

/* Connect to the database */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
$db = new Db();
$db->connect();

/* Get Subject */
if(isset($_GET['subject'])){
    $subject = $_GET['subject'];
    $subject = ucwords(strtolower($subject));}
else{ $subject=null; }

/* Get Sub-category */
if(isset($_GET['subcategory'])){
    $subCategory = $_GET['subcategory'];
    $subCategory = ucwords(strtolower($subCategory));}
else{ $subCategory=null; }

/* Get Levels */
if(isset($_GET['level'])){
    $level = $_GET['level'];
    $level = ucwords(strtolower($level));}
else{ $level=null; }

/* Get Sub-categories */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Actions.quiz.class.php";
$quizActions = new Actions();
$subCategories = $quizActions->fetchSubCategorys($subject);
$allLevels = $quizActions->fetchLevels($subject);


/* Fetch Quizzes */
include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/Ratings.class.php";
$fq = new Fetch();
$subjectQuizzes = $fq->fetchSubjectQuizzes($subject, $subCategory, $level);


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

<div class="everything-outer">
    <div class="everything">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <a href="/">
                <div class="navbar-logo">
                    <h1>Revision Quizzes</h1>
                </div>
            </a>
            <div class="navbar-items">
                <ul>
                    <li>
                        <a href="#">
                            <div class="navbar-entry">
                                <p>Browse Quizzes</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="http://legacy.revisionquizzes.co.uk/create/createQuiz.php">
                            <div class="navbar-entry">
                                <p>Create Quiz</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="navbar-entry">
                                <p>Users</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main container-fluid">
            <div class="row">
                <div class="col-sm-3 sidebar">
                    <div class="sb sb-subCategories">
                        <?php if(count($subCategories)>0){?>
                            <p class="inline-label">Sub-Categories:</p><br />
                            <a href=<?php echo "subject.php?subject=$subject"."&level=".urlencode($level); ?>>
                                <div class="subCat-label <?php if($subCategory==""){echo " selected-sbItem";}?>">
                                    <p>All</p>
                                </div>
                            </a>
                        <?php } ?>
                        <?php for ($i=0; $i< count($subCategories); $i++){ ?>
                            <a href=<?php echo "subject.php?subject=$subject&subcategory=".urlencode($subCategories[$i])."&level=".urlencode($level); ?>>
                                <div class="subCat-label<?php if($subCategory==$subCategories[$i]){echo " selected-sbItem";}?>">
                                    <p><?php echo $subCategories[$i]; ?></p>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="sb sb-levels">
                        <?php if(count($allLevels)>0){?>
                            <p class="inline-label">Levels:</p><br />
                            <a href=<?php echo "subject.php?subject=$subject&subcategory=".urlencode($subCategory); ?>>
                                <div class="subCat-label <?php if($level==""){echo " selected-sbItem";}?>">
                                    <p>All</p>
                                </div>
                            </a>
                        <?php } ?>
                        <?php for ($i=0; $i< count($allLevels); $i++){ ?>
                            <a href=<?php echo "subject.php?subject=$subject&subcategory=".urlencode($subCategory)."&level=".urlencode($allLevels[$i]); ?>>
                                <div class="subCat-label <?php if($level==$allLevels[$i]){echo " selected-sbItem";}?>">
                                    <p><?php echo $allLevels[$i]; ?></p>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="sb sb-sortBy">
                        <p class="inline-label">Sort By:</p><br />
                        <a href="#">
                            <div class="subCat-label">
                                <p>Default</p>
                            </div>
                        </a>
                        <a href="#">
                            <div class="subCat-label selected-sbItem">
                                <p>Most Recent</p>
                            </div>
                        </a>
                        <a href="#">
                            <div class="subCat-label">
                                <p>Highest Rated</p>
                            </div>
                        </a>

                    </div>
                    <div class="sb sb-options">
                        <p class="inline-label">More:</p><br />
                        <a href="#" onclick="alert('Sorry this feature not yet available. Revision Quizzes is still under development');">
                            <div class="subCat-label">
                                <p>Not yet done quizzes</p>
                            </div>
                        </a>
                        <a href="#" onclick="alert('Sorry this feature not yet available. Revision Quizzes is still under development');">
                            <div class="subCat-label">
                                <p>Top Scores</p>
                            </div>
                        </a>
                        <a href="http://legacy.revisionquizzes.co.uk/create/createQuiz.php">
                            <div class="subCat-label">
                                <p>Create a Quiz</p>
                            </div>
                        </a>
                        <a href="/">
                            <div class="subCat-label">
                                <p>Return Home</p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php if(count($subjectQuizzes)>0){ ?>
                <div class="col-sm-8 q-wrap">
                    <br /><h3><?php echo $subject." Quizzes"; echo $subCategory!=null ? " - $subCategory" : null; ?></h3><br /><br />
                    <?php foreach($subjectQuizzes as $quiz):?>
                        <a href="<?php echo 'start-quiz.php?id='.$quiz->getId()?>">
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
                    <?php endforeach; ?>

                </div>
                <?php } else { ?>
                <div class="col-sm-8 q-wrap">
                    <br />
                    <h3>No quizzes for <?php echo $level." ".$subCategory." ".$subject; ?> were found</h3>
                </div>
            <?php } ?>
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
<script src="js/analytics.js"></script>		
</body>
</html>