$(".table-row").css("display", "none");

$(".filter-button").click( function() {
    $(".table-row").css("display", "none");
    let genre = $(this).attr("data-genre").trim();
    console.log(genre);
    $("." + genre).css("display", "block");
});