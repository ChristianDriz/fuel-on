$(document).ready(function () {

    $(".sort-btn").on('click', function(){
    var value = $(this).val();
    // alert(value);

        $.ajax({
            url: "assets/ajax/sortFuel.php",
            type: "POST",
            data: 'fuel=' + value,
            beforeSend:function(){
                $(".display").html("<span>Loading...</span>");
            },
            success:function(data){
                $(".display").html(data);
            }
        });
    });

    //PANG UNLEADED
    // $("#unleaded").on('click', function(){
    // var value = $(this).val();
    // // alert(value);
    
    //     $.ajax({
    //         url: "assets/ajax/sortFuel.php",
    //         type: "POST",
    //         data: 'fuel=' + value,
    //         beforeSend:function(){
    //             $(".display").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".display").html(data);
    //         }
    //     });
    // });

    // //PANG PREMIUM
    // $("#premium").on('click', function(){
    // var value = $(this).val();
    // // alert(value);
    
    //     $.ajax({
    //         url: "assets/ajax/sortFuel.php",
    //         type: "POST",
    //         data: 'fuel=' + value,
    //         beforeSend:function(){
    //             $(".display").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".display").html(data);
    //         }
    //     });
    // });

    // //PANG LAHATAN
    // $("#all").on('click', function(){
    // var value = $(this).val();
    // // alert(value);
    
    //     $.ajax({
    //         url: "assets/ajax/sortFuel.php",
    //         type: "POST",
    //         data: 'fuel=' + value,
    //         beforeSend:function(){
    //             $(".display").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".display").html(data);
    //         }
    //     });
    // });       

    // $("#changeFuelPrice").on('change', function(){
    //     var value = $(this).val();
    //     // alert(value);
        
    //     $.ajax({
    //         url: "assets/ajax/sortFuel.php",
    //         type: "POST",
    //         data: 'fuel=' + value,
    //         beforeSend:function(){
    //             $(".display").html("<span>Loading...</span>");
    //         },
    //         success:function(data){
    //             $(".display").html(data);
    //         }
    //     });
    // });    

});