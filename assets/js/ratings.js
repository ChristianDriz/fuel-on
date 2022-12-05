$(document).ready(function(){

    //FIVE STAR RATING
    $(".ratings-btn").on('click', function(){
        var value = $(this).val();
        var id = $(this).attr('id');
        // alert(id);
        // alert(value);

        $.ajax({
            url: "assets/ajax/getRatings.php",
            type: "GET",
            data: {stationID: value, request: id},
            beforeSend:function(){
                $(".dito").html("<span>Loading...</span>");
            },
            success:function(data){
                $(".dito").html(data);
            }
        });
    });

    // //FOUR STAR RATING
    // $("#fourStar").on('click', function(){
    //     var value = $(this).val();
    //     var id = $(this).attr('id');
    //     // alert(value);

    //     $.ajax({
    //         url: "assets/ajax/getRatings.php",
    //         type: "GET",
    //         data: {stationID: value, request: id},
    //         beforeSend:function(){
    //             $(".dito").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".dito").html(data);
    //         }
    //     });
    // });

    // //THREE STAR RATING
    // $("#threeStar").on('click', function(){
    //     var value = $(this).val();
    //     var id = $(this).attr('id');
    //     // alert(value);

    //     $.ajax({
    //         url: "assets/ajax/getRatings.php",
    //         type: "GET",
    //         data: {stationID: value, request: id},
    //         beforeSend:function(){
    //             $(".dito").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".dito").html(data);
    //         }
    //     });
    // });

    // //TWO STAR RATING
    // $("#twoStar").on('click', function(){
    //     var value = $(this).val();
    //     var id = $(this).attr('id');
    //     // alert(value);

    //     $.ajax({
    //         url: "assets/ajax/getRatings.php",
    //         type: "GET",
    //         data: {stationID: value, request: id},
    //         beforeSend:function(){
    //             $(".dito").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".dito").html(data);
    //         }
    //     });
    // });
    
    // //ONE STAR RATING
    // $("#oneStar").on('click', function(){
    //     var value = $(this).val();
    //     var id = $(this).attr('id');
    //     // alert(value);

    //     $.ajax({
    //         url: "assets/ajax/getRatings.php",
    //         type: "GET",
    //         data: {stationID: value, request: id},
    //         beforeSend:function(){
    //             $(".dito").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".dito").html(data);
    //         }
    //     });
    // });

    // //ALL STAR RATING
    // $("#allStar").on('click', function(){
    //     var value = $(this).val();
    //     var id = $(this).attr('id');
    //     // alert(value);
    
    //     $.ajax({
    //         url: "assets/ajax/getRatings.php",
    //         type: "GET",
    //         data: {stationID: value, request: id},
    //         beforeSend:function(){
    //             $(".dito").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".dito").html(data);
    //         }
    //     });
    // });
});