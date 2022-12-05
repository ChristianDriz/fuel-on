$(document).ready(function () {
    $('.filter-btn').click(function () { 
        var from_date = $('.from-date').val();
        var to_date = $('.to-date').val();
        
        // alert(from_date);
        // alert(to_date);
        // alert("asdas");

        $.ajax({
            type: "GET",
            url: "assets/includes/sort-sales.php",
            data: {from_date: from_date, to_date: to_date},
            beforeSend:function(){
                $(".data-div").html("<span>Loading...</span>");
            },
            success: function (data) {
                $(".data-div").html(data);
            }
        });
    });
});