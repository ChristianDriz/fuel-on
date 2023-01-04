<div class="no-data">
    <h4>No Data</h4>
</div>

<style>
    .no-data {
        position: absolute;
        left: 50%;
        top: 35%;
        text-align: center;
        }

    .no-data h4 {
        font-weight: 600;
    }

    .no-data .btn {
        background-color: #fea600;
        color: #ffffff;
        width: 120px;
        border-radius: 10px;
    }

    .no-data .btn:hover {
        background-color: #fea600;
        color: #ffffff;
        width: 120px;
        border-radius: 10px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('.get-back-btn').click(function () { 
        window.history.back();
    });
</script>