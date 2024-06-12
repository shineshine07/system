<?php
// voucher_receipt.php

include 'db_connect.php';

// Retrieve payee ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch data for the specified payee from your database
// Replace the following with your actual SQL query
$query = "SELECT * FROM disbursement WHERE id = $id";
$result = mysqli_query($conn, $query);

// Process the query result and populate the variables
if ($result) {
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $payee_name = isset($data['payee']) ? $data['payee'] : '';
        $cv_number = isset($data['cv']) ? $data['cv'] : '';
        $date = isset($data['date']) ? $data['date'] : '';
        $type = isset($data['type']) ? $data['type'] : '';
        $amount_in_words = isset($data['amount_in_words']) ? $data['amount_in_words'] : '';
        $total = isset($data['total']) ? $data['total'] : '';
        $account_title = isset($data['account_title']) ? $data['account_title'] : '';
        $debit = isset($data['debit']) ? $data['debit'] : '';
        $credit = isset($data['credit']) ? $data['credit'] : '';

        // Use $data to populate other variables as needed
    } else {
        // Handle errors or redirect to a page indicating no data found
        die("No data found for the specified payee.");
    }
} else {
    // Handle errors
    die("Query failed: " . mysqli_error($conn));
}
?>
<table class="table table-bordered" id='report-list'>
    <?php include 'db_connect.php'; ?>

        <div class="container-fluid">
            <div class="row" id="cash_voucher_container">
                <center>
                    <div class="what  ">
                        <img src="assets/img/baang.png" style="width: 500px;">
                    </div>
                </center>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 box_3">
                            <div class="fbox_top1">
                                <br>
                                <span><b>PAYEE: </b><?php echo $payee_name; ?></span>

                                <p class='sfofficer'></p>
                            </div>
                            <div class="fbox_bottom">

                                <span></span>
                                <br />
                                <br />
                                <p class='sfofficer'></p>
                                <p class='t_center'></p>
                            </div>
                        </div>
                        <div class="col-md-6 box_box1">
                            <br>
                            <span><b>C.V NO.: </b><?php echo $cv_number; ?></span>
                            <br />
                            <br />
                            <br />
                            <br />
                            <p class='sfofficer'></p>
                            <p class='t_center'></p>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 box_4">
                                    <div class="fbox_top1">
                                        <br>
                                        <span><b>PESOS: </b><?php echo $amount_in_words; ?></span>


                                    </div>
                                    <div class="fbox_bottom">
                                        <br>
                                        <br>
                                        <span></span>
                                        <br />
                                        <br />
                                        <p class='sfofficer'></p>
                                        <p class='t_center'></p>
                                    </div>
                                </div>
                                <div class="col-md-6 box_box2">
                                    <br>
                                    <span><b>DATE: </b><?php echo $date; ?></span>
                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                    <p class='sfofficer'></p>
                                    <p class='t_center'></p>
                                </div>

                                <div class="container-fluid">
                                    <div class="row ">
                                        <div class="col-md-6 box_2">
                                            <div class="fbox_top">
                                                <span>
                                                    <center><b>P A R T I C U L A R S</b></center>
                                                </span>

                                                <p class='sfofficer'></p>
                                            </div>
                                            <div class="fbox_bottom">

                                                <span></span>
                                                <br />
                                                <span></b><?php echo $type; ?></span>
                                                <p class='sfofficer'></p>
                                                <p class='t_center'></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 box_box">
                                            <span></span>
                                            <br />
                                            <br />
                                            <span></span>
                                            <br />
                                            <p class='sfofficer'></p>
                                            <p class='t_center'></p>
                                        </div>


                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-4 box">
                                                    <div class="fbox_top">
                                                        <span>
                                                            <center><b>ACCOUNT TITLE</b></center>
                                                        </span>

                                                        <p class='sfofficer'></p>

                                                    </div>
                                                    <div class="fbox_bottom">
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <span></b><?php echo $account_title; ?></span>
                                                        <br />
                                                        <p class='sfofficer'></p>
                                                        <p class='t_center'></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 box">
                                                    <span>
                                                        <center><b>DEBIT</b></center>
                                                    </span>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <span></b><?php echo $debit; ?></span>
                                                    <br />
                                                    <p class='sfofficer'></p>
                                                    <p class='t_center'></p>

                                                </div>
                                                <div class="col-md-4 box">
                                                    <div class="fbox_top1">
                                                        <span>
                                                            <center><b>CREDIT</b></center>:
                                                        </span>
                                                        <br />
                                                        <br />
                                                        <br />
                                                        <span></b><?php echo $credit; ?></span>
                                                        <br />
                                                        <br />
                                                    </div>
                                                    <div class="fbox_bottom">
                                                        <p class='t_center'>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                            <div class="row ">
                                                <div class="col-md-4 box1">
                                                    <div class="fbox_top">
                                                        <span> Prepared By:</span>
                                                        <br />
                                                        <br />
                                                        <p class='sfofficer'><b>MARISOL L. DIGAUM</b>
                                                        </p>
                                                        <p class='t_center'>SCHOOL FINANCE OFFICER</p>
                                                    </div>
                                                    <div class="fbox_bottom">
                                                        <span> Checked By:</span>
                                                        <br>
                                                        <br>
                                                        <p class='sfofficer'><b>SONIA A. LAGUNDA-ON,
                                                                LPT/MACDDS</b></p>
                                                        <p class='t_center'>ACTING SCHOOL PRINCIPAL</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 box1">
                                                    <span> Approved By:</span>
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <p class='sfofficer'><b>REV. FR. PROCESO G.
                                                            APOLINAR</b></p>
                                                    <p class='t_center'>SCHOOL DIRECTOR</p>

                                                </div>
                                                <div class="col-md-4 box1">
                                                    <div class="fbox_top">
                                                        <span> Payment Received By:</span>
                                                        <br />
                                                        <br />
                                                        <br />
                                                        <br />
                                                        <br />
                                                        <br />
                                                    </div>
                                                    <div class="fbox_bottom">
                                                        <p class='t_center'>SIGNATURE OVER PRINTED NAME
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
          
            <style>
            @media print {
                @page {
                    size: 8.5in 11in;
                    margin: 0;
                    margin-top: -100px;
                    margin-left: 50px;
                }

               
            }

            .box {
                border: 1px solid black;
                height: 200px;
                width: 235px;
                /* Adjust the height as needed */
                float: left;


            }

            .fbox_top {
                border-bottom: 1px solid black;
                width: 100%;
            }

            .sfofficer {
                text-align: center;
                text-decoration: underline;

            }

            .t_center {
                text-align: center;
            }

            .box1 {
                border: 1px solid black;
                height: 250px;
                width: 235px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;


            }

            .what {
                margin-left: 50px;
                margin-top: -100px;
            }

            .box_2 {
                border: 1px solid black;
                height: 250px;
                width: 355px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;

            }

            .box_box {
                border: 1px solid black;
                height: 250px;
                width: 355px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;
            }

            .box_3 {
                border: 1px solid black;
                height: 50px;
                width: 355px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;

            }

            .box_box1 {
                border: 1px solid black;
                height: 50px;
                width: 355px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;
            }

            .box_4 {
                border: 1px solid black;
                height: 50px;
                width: 355px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;

            }

            .box_box2 {
                border: 1px solid black;
                height: 50px;
                width: 355px;
                /* Adjust the height as needed */
                padding: 0px;
                float: left;

            }

            .pls {
                padding-top: -100%;
            }
            </style>

            <noscript>
                <style>
                table#report-list {
                    width: 100%;
                    border-collapse: collapse
                }

                table#report-list td,
                table#report-list th {
                    border: 1px solid
                }

                p {
                    margin: unset;
                }

                .text-center {
                    text-align: center
                }

                .text-right {
                    text-align: right
                }
                </style>
            </noscript>