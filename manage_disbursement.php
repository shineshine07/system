<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>

<div class="container-fluid">
    <div class="col-lg-12">
   
            <div class="col-md-12">
                <!-- Add a div to display success message -->
                <div id="successMessage" class="alert alert-success" style="display: none;"></div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>CASH VOUCHER</b>
                    </div>
                    <div class="card-body">
                        <form id="cashVoucherForm" action="process_disbursement.php" method="POST">
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="inputPayee">PAYEE</label>
                                    <input type="text" class="form-control" id="inputPayee" name="payee" placeholder="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputCV">CV.No.</label>
                                    <input type="number" class="form-control" id="inputCV" name="cv" placeholder="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputAmountWords">AMOUNT IN WORDS:</label>
                                    <input type="text" class="form-control" id="inputAmountWords" name="amount_in_words"
                                        placeholder="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputParticulars">PARTICULARS</label>
                                    <!-- Use textarea for multiline input -->
                                    <textarea class="form-control" id="inputParticulars" name="type" rows="4"
                                        placeholder=""></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputTotal">TOTAL</label>
                                    <input type="number" class="form-control" id="inputTotal" name="total"
                                        placeholder="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputAccount">ACCOUNT TITLE</label>
                                    <!-- Use textarea for multiline input -->
                                    <textarea class="form-control" id="inputAccount" name="account_title" rows="4"
                                        placeholder=""></textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputDebit">DEBIT:</label>
                                    <input type="number" class="form-control" id="inputDebit" name="debit"
                                        placeholder="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputCredit">CREDIT:</label>
                                    <input type="number" class="form-control" id="inputCredit" name="credit"
                                        placeholder="">
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-danger">Submit</button>
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
    table#report-list {
        width: 100%;
        border-collapse: collapse;
    }

    table#report-list td,
    table#report-list th {
        border: 1px solid;
    }

    p {
        margin: unset;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include the jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $('#cashVoucherForm').submit(function (e) {
            e.preventDefault();

            var currentValue = $('#inputParticulars').val();
            var sentences = currentValue.split(/\n/);
            var newValue = sentences.map(sentence => sentence.trim()).join(' ');
            $('#inputParticulars').val(newValue);

            var formData = $('#cashVoucherForm').serialize();

            $.ajax({
                type: 'POST',
                url: 'process_disbursement.php',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    console.log(response);

                    // Display the response message in the success div
                    $('#successMessage').text(response.message).show();

                    if (response.status === 'success') {
                        // Reset the form fields
                        $('#cashVoucherForm')[0].reset();

                        // Hide the success message after 3 seconds (3000 milliseconds)
                        setTimeout(function () {
                            $('#successMessage').fadeOut();
                        }, 3000);
                    } else {
                        console.error('Error:', response.message);
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>