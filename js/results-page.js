
/* Show or hide stats on click */
$("#stats-button").click(function(){
    $("#stats-wrap").toggle('slow');
    $("#stats-button-txt").text($("#stats-button-txt").text()==='View Stats'? 'Hide Stats' : 'View Stats');
});


/* Show or hide high scores on click */
$("#scoreShow-button").click(function(){
    $("#scores-wrap").toggle('fast');
    $("#scoreShow-button-txt").text($("#scoreShow-button-txt").text()==='View Scores'? 'Hide Scores' : 'View Scores');
});

$("#show-scores-again").click(function(){
    $("#scores-wrap").slideDown('fast');
    $("#scoreShow-button-txt").text('Hide Scores');
});

/* Show sharing options */
$("#share-button").click(function(){
    $("#share-wrap").slideDown('fast');
});

/* The hover rating system */
$(".rating-star").mouseover(function(){
    for(var i = 0; i< 5; i++){
        $(".rating-star:eq("+i+")").attr("src", '/img/starFill.png');
        if($(this).is($(".rating-star:eq("+i+")"))){
            break;
        }
    }
});

$(".rating-star").mouseout(function(){
    $(".rating-star").attr("src", '/img/starEmp.png');
});


/* Save rating with AJAX and fetch rating averages */
$(".rating-star").click(function(){

    var rating = $(this).attr("id").substr(4);  // Users rating (1|2|3|4|5)
    var quizId = $("#getQuizId").text();
    $("#alert-saveRating").slideDown('slow');

    $.ajax({
        url: '/ajax/save-rating.php',
        type: 'GET',
        data: {quizId: quizId, rating: rating},
        success: function(data) {
            $("#rate-quiz-wrap").fadeOut('fast');

            var yellowWidth = (data / 5 * 100) * 2;

            $(".star-background").css('width',yellowWidth)

            $("#show-rating-wrap").delay(0.8).fadeIn('normal');


        },
        error: function(e) {
            //console.log(e.message);
            alert("Error saving rating");
        }
    });

    $("#alert-saveRating").slideUp('slow');

});


/* Add the users name to high score with AJAX  */
$("#saveScore-button").click(function(){

    var scoreId   = $('#getScoreId').text();    // Score Id
    var usersName = $('#txtScoreName').val();  // Users name

    $("#alert-addingName").slideDown('slow');

    $.ajax({
        url: '/ajax/update-score.php',
        type: 'GET',
        data: {scoreId: scoreId, usersName: usersName},
        success: function(data) {
            $("#before-score-updated").fadeOut();
            $("#after-score-updated").fadeIn();
            $("#users-score-name").text(usersName);
        },
        error: function(e) {
            //console.log(e.message);
            alert("Error updating score");
        }
    });

    $("#alert-addingName").slideUp('slow');

});

/* Fancy Box */
$(document).ready(function(){
    $("#reportQuiz-btn").fancybox();
});



