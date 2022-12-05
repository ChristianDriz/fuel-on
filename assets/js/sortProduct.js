$(document).ready(function () {

    $("#changePrice").on('change', function(){
    var value = $(this).val();
    // alert(value);

        $.ajax({
            url: "assets/ajax/sortProduct.php",
            type: "POST",
            data: 'request=' + value,
            beforeSend:function(){
                $(".pradak").html("<span>Loading...</span>");
            },
            success:function(data){
                $(".pradak").html(data);
            }
        });
    });
});