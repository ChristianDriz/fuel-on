<?php
    session_start();
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION["userID"];

    }
    else{
        header('location: index.php');
    }

    include '../db.conn.php';

    require_once("../classes/dbHandler.php");
    $data = new Config();


    if(isset($_GET['from_date']) && isset($_GET['to_date'])){
        $from_date = $_GET['from_date'];
        $to_date = $_GET['to_date'];
    }

    $data = $data->salesReportSorted($from_date, $to_date, $userID);

        if(empty($data)){
    ?>
        <div class="no-sales"><img src="assets/img/transaction.png">
            <h5>No transaction for this date</h5>
        </div>
    <?php
        }else{
    ?>
        <div>
            <table class="mydatatable row-border">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product Details</th>
                        <th class="text-center" style="width: 12%;">Date&nbsp;</th>
                        <th class="text-center">Unit Cost</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $grandtotal = 0;
                        foreach($data as $row){
                            $grandtotal += $row['total'];

                            $date = $row['transac_date'];
                            $createdate = date_create($date);
                            $new_date = date_format($createdate, "M d, Y");
                    ?>
                    <tr>
                        <td></td>
                        <td><?php echo $row['product_name']?></td>
                        <td class="text-center"><?php echo $new_date?></td>
                        <td class="text-end">₱<?php echo number_format($row['price'], 2)?></td>
                        <td class="text-end"><?php echo $row['quantity']?></td>
                        <td class="text-end">₱<?php echo number_format($row['total'], 2)?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="generate"><a class="btn" target="_blank" href="generate-sales-report.php?from_date=<?=$from_date?>&to_date=<?=$to_date?>">Generate Report</a></th>
                        <th class="text-end" colspan="3">TOTAL</th>
                        <th class="text-end">₱<?php echo number_format($grandtotal, 2)?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <script>
            let sortedtable = new DataTable('.mydatatable', {
                scrollY: 450,
                scrollCollapse: true,
                paging: false,
                searching: false,
                ordering:  false,

                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columnDefs: [ {
                    className: 'control',
                    orderable: false,
                    targets:   0
                } ],
                order: [ 1, 'asc' ]
            });
        </script>
    <?php
    }
