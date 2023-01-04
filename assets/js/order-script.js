$(document).ready(function () {
    //to return the scroll position in the header
    $(window).on('unload', function () { 
        var scrollPosition = $('#transaction-row-header').scrollLeft();
        localStorage.setItem('scrollPosition', scrollPosition);
    });
    
    if(localStorage.scrollPosition){
        $('#transaction-row-header').scrollLeft(localStorage.getItem('scrollPosition'));
    }


    //decline order button
    $('.decline-order').click(function (e) { 
        e.preventDefault();
        const url = $(this).attr('href');

        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to cancel this order?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                const { value: reason } = Swal.fire({
                    title: 'Please select your reason',
                    input: 'select',
                    inputPlaceholder: 'Select reason',
                    showCancelButton: true,
                    inputOptions: {
                        reason4: 'Out of stock'
                    },
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                resolve()
                                    $.ajax({
                                        type: "GET",
                                        url,
                                        data: "reason=" +value,
                                        success: function (data) {
                                            Swal.fire({
                                                title: 'Order Declined',
                                                icon: 'success',
                                                button: true,
                                            }).then(() => {
                                                location.reload();
                                            });
                                        }
                                    });
                            }else{
                                resolve('You need to select reason')
                            }
                        })
                    }
                })
            }
        }) 
    });  

    //CONFIRMATION TO ACCEPT AN ORDER
    $('.accept-order').click(function (e) { 
        e.preventDefault();
        // var value = $(this).val();
        const url = $(this).attr('href');
        
        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to accept this order?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url,
                    success: function (data) {
                        Swal.fire({
                            title: 'Order Approved',
                            icon: 'success',
                            button: true,
                        }).then(() => {
                            location.reload();
                        });
                        // $(".dito").html(data);
                    }
                });
            }
        }) 
    });

    //CONFIRMATION TO CANCEL THE ORDER THAT IS NOT PICKED UP
    $('.cancel-order').click(function (e) { 
        e.preventDefault();
        const url = $(this).attr('href');

        Swal.fire({
            title: 'Confirmation',
            text: "Are you sure that this order will be cancelled?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                const { value: reason } = Swal.fire({
                    title: 'Please select a reason',
                    input: 'select',
                    inputPlaceholder: 'Select reason',
                    showCancelButton: true,
                    inputOptions: {
                        reason5: 'Did not pick up the order'
                    },
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                resolve()
                                    $.ajax({
                                        type: "GET",
                                        url,
                                        data: "reason=" + value,
                                        success: function (data) {
                                            Swal.fire({
                                                title: 'Order has been cancelled',
                                                icon: 'success',
                                                button: true,
                                            }).then(() => {
                                                location.reload();
                                            });
                                        }
                                    });
                            }else{
                                resolve('You need to select reason')
                            }
                        })
                    }
                })
            }
        }) 
    });  

    //CONFIRMATION FOR ORDER COMPLETION
    $('.complete-order').click(function () { 
        // var value = $(this).val();
        const url = $(this).attr('href');

        Swal.fire({
            title: 'Confirmation',
            text: "Are you sure that this order is completed?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // alert (value);
                $.ajax({
                    type: "GET",
                    url,
                    success: function (data) {
                        Swal.fire({
                            title: 'Transaction Completed',
                            icon: 'success',
                            button: true
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        }) 
    });
});
