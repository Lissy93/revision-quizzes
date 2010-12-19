<?php

    /* Connect to the database */
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
    $db = new Db();
    $db->connect();

    /* Fetch Quizzes */
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Fetch.class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Ratings.class.php";
    $fq = new Fetch();
    $recentQuizzes = $fq->fetchRecentQuizzes(3);
    $allQuizzes = $fq->fetchAllQuizzes(8);
    $recentQuizzes = $fq->fetchRecentQuizzes(8);
    $featuredQuizzes = $fq->fetchFeaturedQuizzes(8);
    $topRatedQuizzes = $fq->fetchTopQuizzes(8);
    $suggestedQuizzes = null;

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
            <div class="navbar-logo">
                <h1>Revision Quizzes</h1>
            </div>
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
                        <a href="#">
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

        <div class="main-content jumbotron">

            <!-- Search -->
            <div class="search-wrap-outer">
            <div class="search-wrap">
                <form action="search.php" method="get">
                    <input type="text" name="txtSearch" class="src-box" id="txtSearch" placeholder="Search all Quizzes"/>
                    <button type="button" class="src-button navbar-entry" id="cmdSearch">Search</button>
                </form>
            </div>
            </div>

            <!-- Quiz Listing Container -->
            <div class="quizListing-wrap">

                <!-- Show Loading Message and Animation -->
                <div class="loadingQuizzes-wrap" id="loadingQuizzes-wrap">
                    <div class="center">
                        <h3>Loading...</h3>
                        <img src="/img/loading.gif">
                    </div>
                </div>

                <!-- Show Results -->
                <div class="searchResults-wrap" id="searchResults-wrap">
                    <p id="results-heading">Results</p>
                    <p class="lnk-back" id="lnk-back">&#8592; Back</p><br />
                </div>

                <!-- Show Start Browse COntainer -->
               <div class="browseQuiz-wrap" id="browseQuiz-wrap">
                  <div class="quiz-column">
                      <h3>New Quizzes</h3><p class="lnk-showMore" id="sm-recent">Show More</p>
                      <?php foreach($recentQuizzes as $quiz):?>
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
                      <?php endforeach; ?>
                  </div>

                   <div class="quiz-column">
                       <h3>Top Rated</h3><p class="lnk-showMore" id="sm-topRated">Show More</p>
                       <?php foreach($topRatedQuizzes as $quiz):?>
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
                        <?php endforeach; ?>
                    </div>

                   <?php if($suggestedQuizzes!=null):?>
                       <div class="quiz-column last-col">
                           <h3>Suggested Quizzes</h3><p class="lnk-showMore" id="sm-suggested">Show More</p>
                           <?php foreach($suggestedQuizzes as $quiz):?>
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
                           <?php endforeach; ?>
                       </div>

                   <?php else: ?>
                       <div class="quiz-column last-col">
                           <h3>Featured Quizzes</h3><p class="lnk-showMore" id="sm-featured">Show More</p>
                           <?php foreach($featuredQuizzes as $quiz):?>
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
                           <?php endforeach; ?>
                       </div>
                   <?php endif; ?>

                </div>
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