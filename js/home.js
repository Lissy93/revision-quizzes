$(function() {
    $('.banner').unslider();
});



/* Back to browse page */
$("#lnk-back").click(function(){
    $('#searchResults-wrap a').empty();
    $('#searchResults-wrap').fadeOut().delay(400);
    $("#browseQuiz-wrap").fadeIn();
});


/* Load search results with AJAX */
$("#cmdSearch").click(function(){
    if($("#txtSearch").val().length>0){
        loadResults('search',500,$("#txtSearch").val());
        $("#results-heading").html('Search Results');
    }
    else{
        loadResults('all',500);
        $("#results-heading").html('All Quizzes');
    }
});

$("#sm-recent").click(function(){
    loadResults('recent',1000);
    $("results-heading").html('Recently Added Quizzes');
});

$("#sm-suggested").click(function(){
    loadResults('suggested',1000);
    $("results-heading").html('Suggested Quizzes');
});

$("#sm-topRated").click(function(){
    loadResults('topRated',1000);
    $("results-heading").html('Top Rated Quizzes');
});

$("#sm-featured").click(function(){
    loadResults('featured',1000);
    $("results-heading").html('Featured Quizzes');
});



function loadResults(whichQuizzes, limit, searchTerm){

    var contentBlock = "";
    var results;

    $("#browseQuiz-wrap").fadeOut().delay(100);

    $("#loadingQuizzes-wrap").fadeIn().delay(400);

    $.ajax({
        url: '/ajax/get-quizzes.php',
        type: 'GET',
        data: {whichQuizzes: whichQuizzes, limit: limit, term: searchTerm},
        success: function(data) {

            results = JSON.parse(data);

            for(var i=0; i<results.length; i++){
                var descript = results[i]['description']; if (descript == null){descript = " "; }
                contentBlock =
                    "<a href='start-quiz.php?id="+results[i]['ID']+"'>"+
                        "<div class='qt-tile' title='"+results[i]['name']+" - "+descript+"'>"+
                        "<p class='qt-title'>"+results[i]['name'].substring(0,60)+"</p>"+
                        "<p class='qt-description'>"+descript.substring(0,60)+((descript.length > 60) ? '...' : '')+"</p>"+
                        "<div class='qt-firstDetails'>"+
                        "<p class='qt-level-subject'>"+results[i]['level']+" "+results[i]['subject']+"</p>"+
                        "</div>"+
                        "</div>"+
                        "</a>";

                $(contentBlock).appendTo("#searchResults-wrap");
            }
            $("#searchResults-wrap").delay(100).fadeIn();

        },
        error: function(e) {
            //console.log(e.message);
            alert("Error fetching quizzes");
        }
    });

    $("#loadingQuizzes-wrap").fadeOut();

}