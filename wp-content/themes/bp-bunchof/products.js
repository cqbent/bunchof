$(document).ready(function (){
$(".trigger").click(function(){
    $( $(this).find("a").attr('href') ).slideToggle("slow");
  });

 $(".trigger").click(function () {
      $(".splash").hide("fast");
    });
});