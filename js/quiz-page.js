
/* Mark Question */
$(document).on('click',".answer-block",function () {

    var answerId = $(this).attr("id").substr(3);
    var clickedBlock = this;

    /* Show Loading Message and Objects */
    $(".white-out").fadeIn('slow');
    $("#alert-marking").slideDown('slow');

    /* Send via AJAX */
    $.ajax({
        url: '/ajax/mark-question.php',
        type: 'POST',
        data: 'answerId='+answerId,
        success: function(data) {

            /* Remove the class used to call this method */
            $('.answer-block').addClass('answer-block-done').removeClass('answer-block');

            /* Get Results from PHP */
            var results = $.parseJSON(data);

            /* For each correct answer light up as green */
            for(var i = 0; i<results['correctAnswers'].length; i++){
                $("#qid"+results['correctAnswers'][i]).addClass('ans-correct');
            }

            /* if the user selected the wrong answer, light up as red */
            if(results['score'] == '0'){
                $(clickedBlock).removeClass('ans-correct').addClass('ans-incorrect');
            }

            /* Add tick or cross next to question list on left side */
            if(results['score'] == '1'){
                $(".current-q-side").html($(".current-q-side").text().replace("...", "&#10003;"));
                $(".result-title").html('Correct').addClass('result-cor');
            }
            else  if(results['score'] == '0'){
                $(".current-q-side").html($(".current-q-side").text().replace("...", "&#10007;"));
                $('p.result-title').html('Incorrect').addClass('result-incor');
            }

            /* Fill in the results box */
            $('#ans-expl').html(results['explanation']);
            $('#question-results').fadeIn();

            /* Show answer explanation */
            $("#ansExpl-wrap").fadeIn();

            /* Remove loading text */
            $(".white-out").fadeOut('slow');
            $("#alert-marking").slideUp('slow');

        },
        error: function(e) {
            //console.log(e.message);
            alert("Error checking answer");
        }
    });
});





/* Show Question Stats on Quiz Page */
var fetched = false;

$("#show-stats").click(function(){
    if(!fetched){
        $("#alert-stats").fadeIn('slow');
    }
    var qid = $("#hiddenQuestionId").text();

    /* Fetch data via AJAX */
    $.ajax({
        url: '/ajax/get-stats.php',
        type: 'GET',
        data: 'questionId='+qid,
        success: function(data) {

            data = $.parseJSON(data);

            for (var i = 0; i<data.length; i++) {
                var newWidth = $("#qid"+data[i]['ansId']).width() * data[i]['percent'] /100+'px';
                $("#qid"+data[i]['ansId']).children('.answer-block-filler').children('.txtPercent').text(data[i]['percent']+'%').css('margin-left',newWidth);
                $("#qid"+data[i]['ansId']).children('.answer-block-filler').css('width',newWidth);
                $("#qid"+data[i]['ansId']).children('.answer-block-filler').toggle("slow")
                $(".txtPercent").fadeIn();
            }



                $("#show-stats").children().text($("#show-stats").children().text()==='Show Stats'? 'Hide Stats' : 'Show Stats');


            $("#alert-stats").fadeOut('slow');
            fetched = true;
        },
        error: function(e) {
            //console.log(e.message);
            alert("Error checking answer");
        }
    });

});
