
jQuery(document).ready(function($) {
    $(".scroll").click(function(event){
     event.preventDefault();
     $('html,body').animate({scrollTop:$(this.hash).offset().top}, 1000);
    });

    $("#Home").click(function(){
        $('html,body').animate({scrollTop:0}, 'slow');
    });

    $("#knowMe").click(function(){
        $('html,body').animate({scrollTop:570}, 1000);
    });

    $("#mPortfolio").click(function(){
        $('html,body').animate({scrollTop:1150}, 1000);
    });

    $("#skills").click(function(){
        $('html,body').animate({scrollTop:1730}, 1000);
    });

    $("#mContato").click(function(){
        $('html,body').animate({scrollTop:2330}, 1000);
    });


 });
