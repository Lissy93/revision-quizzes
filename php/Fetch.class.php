<?php

/**
 *
 *
 */

class Fetch {

    private $db;

    function __construct( ){
        $this->db = new Db();
        $this->db->connect();
    }

    function fetchQuiz($quizId){
        $data = $this->db->query("SELECT * FROM quizzes WHERE ID = $quizId");
        $result = $this->makeQuizObject($data);
        return $result;
    }

    function fetchAllQuizzes($limit = 999){
        $data = $this->db->query("SELECT * FROM quizzes LIMIT $limit");
        $result = $this->makeQuizObjectsArr($data);
        return $result;
    }


    function fetchRecentQuizzes($limit=20){
        $data = $this->db->query("SELECT * FROM  quizzes ORDER BY ID DESC LIMIT $limit");
        $result = $this->makeQuizObjectsArr($data);
        return $result;
    }


    function fetchFeaturedQuizzes($limit=20){
        $featuredQuizList = '385,256,400,349,334,512';
        $data = $this->db->query("SELECT * FROM quizzes where ID IN ($featuredQuizList)");
        $result = $this->makeQuizObjectsArr($data);
        return $result;
    }

    function fetchSubjectQuizzes($subject, $subcategory=null, $level = null, $sortBy = "Most Recent", $limit = 100){
        $qPart1 = $subcategory!=null? "AND subCat LIKE '$subcategory' " : '';
        $qPart2 = $level!= null? "AND level LIKE '$level' " : '';
        $qPart3 = $sortBy=="Most Recent" ? "ORDER BY ID DESC " : "ORDER BY ID DESC ";
        $data = $this->db->query("SELECT * FROM  quizzes WHERE subject LIKE '%$subject%' $qPart1 $qPart2 $qPart3 LIMIT $limit");
        $result = $this->makeQuizObjectsArr($data);
        return $result;
    }

    function fetchTopQuizzes($limit=20){
        $allQuizzes = $this->db->query("SELECT * FROM quizzes WHERE takes > 10");
        $res1 = array();
        $r = new Ratings();
        for($i=0; $i<count($allQuizzes);$i++){
            $res1['id'][$i] = $allQuizzes[$i]['ID'];
            $res1['rating'][$i] = $r->getRating($allQuizzes[$i]['ID']);
            $res1['numRat'][$i] = $r->countNumRatings($allQuizzes[$i]['ID']);
        }
        arsort($res1['rating']);
        arsort($res1['numRat']);

        $listOfIds = array();

        foreach($res1['rating'] as $key=>$elem){
            if($res1['numRat'][$key]>3){
                $listOfIds[] = $res1['id'][$key];
            }
        }

        $listOfIds = implode(",",$listOfIds);
        $data = $this->db->query("SELECT * FROM quizzes where ID IN ($listOfIds) LIMIT $limit");
        $result = $this->makeQuizObjectsArr($data);
        return $result;
    }

    function fetchSuggestedQuizzes($limit=20){
        //TODO
    }

    function doesQuizExist($quizId){
        $query = mysql_query("SELECT ID FROM quizzes WHERE ID = '$quizId'");
        if (!$query) {
            die('Query failed to execute');
        }

        if (mysql_num_rows($query) > 0) {
            return true;
        }
        return false;
    }





    function makeQuizObjectsArr($data){

        include_once $_SERVER['DOCUMENT_ROOT']."/php/Quiz.class.php";   //Quiz class

        $numQuizzes = count($data); // The number of quizzes

        $results = array(); // Results, to return

        /* Loop through each quiz transferring it into Quiz object and adding it to the array */
        for($i=0; $i<$numQuizzes; $i++){
            $results[$i] = new Quiz();
            $results[$i]->setId($data[$i]['ID']);
            $results[$i]->setName($data[$i]['Name']!=null ?  $data[$i]['Name'] : 'Untitled');
            $results[$i]->setDescription($data[$i]['Description']);;
            $results[$i]->setLevel($data[$i]['Level']);
            $results[$i]->setSubject($data[$i]['Subject']);
            $results[$i]->setSubCategory($data[$i]['subCat']);
            $results[$i]->setTakes($data[$i]['takes']);
            $results[$i]->setCreator($data[$i]['creator']);
            $results[$i]->setTargetTime($data[$i]['tarTime']);
            $results[$i]->setTimeLimit($data[$i]['timeLimit']);
            $results[$i]->setPassMark($data[$i]['passMark']);
            $results[$i]->setPrivacy($data[$i]['privacy']);

            $results[$i]->setQuestions($this->makeQuestionObjectsArr($data[0]['ID']));
        }

        return $results;
    }

    function makeQuizObject($data){
        include_once $_SERVER['DOCUMENT_ROOT']."/php/Quiz.class.php";   //Quiz class

        /* Transfer Quiz Array to Quiz Object */
        $result = new Quiz();   // To return
        $result->setId($data[0]['ID']);
        $result->setName($data[0]['Name']);
        $result->setDescription($data[0]['Description']);;
        $result->setLevel($data[0]['Level']);
        $result->setSubject($data[0]['Subject']);
        $result->setSubCategory($data[0]['subCat']);
        $result->setTakes($data[0]['takes']);
        $result->setCreator($data[0]['creator']);
        $result->setTargetTime($data[0]['tarTime']);
        $result->setTimeLimit($data[0]['timeLimit']);
        $result->setPassMark($data[0]['passMark']);
        $result->setPrivacy($data[0]['privacy']);

        $result->setQuestions($this->makeQuestionObjectsArr($data[0]['ID']));

        return $result;

    }

    function makeQuestionObjectsArr($quizId){

        include_once $_SERVER['DOCUMENT_ROOT']."/php/Question.quiz.class.php";   //Question class

        $data = $this->db->query("SELECT * FROM questions WHERE quiz_ID = $quizId");

        $numQuestions = count($data); // The number of quuestions

        $results = array(); // Results, to return

        /* Loop through each question transferring it into Question object and adding it to the array */
        for($i=0; $i<$numQuestions; $i++){
            $results[$i] = new Question();
            $results[$i]->setId($data[$i]['ID']); // question id
            $results[$i]->setQn($data[$i]['QuestionNumber']);
            $results[$i]->setQuestion($data[$i]['Question']);
            $results[$i]->setType($data[$i]['Type']);
            $results[$i]->setNotes($data[$i]['Notes']);
            $results[$i]->setImage($data[$i]['Image']);
            $results[$i]->setExp($data[$i]['Explanation']);

            $results[$i]->setAnswers($this->makeAnswerObjectArr($data[$i]['ID']));
        }

        return $results;

    }

    function makeAnswerObjectArr($questionId){

        include_once $_SERVER['DOCUMENT_ROOT']."/php/Answer.question.quiz.class.php";   //Question class

        $data = $this->db->query("SELECT * FROM answers WHERE question_ID = $questionId");

        $numAnswers = count($data); // The number of answers

        $results = array(); // Results, to return

        /* Loop through each answer transferring it into an Answer object and adding it to the array */
        for($i=0; $i<$numAnswers; $i++){
            $results[$i] = new Answer();
            $results[$i]->setId($data[$i]['ID']); // question id
            $results[$i]->setNumCor($data[$i]['NumCorr']);
            $results[$i]->setAnswer($data[$i]['Answer']);
            $results[$i]->setCorrect($data[$i]['CorrectAnswer']);
            $results[$i]->setShortAnswer($data[$i]['shortAnswer']);
            $results[$i]->setRightWords($data[$i]['rightWords']);
            $results[$i]->setWrongWords($data[$i]['wrongWords']);
        }

        return $results;


    }
}
