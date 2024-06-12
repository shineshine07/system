<?php include('db_connect.php');?>

<style>
input[type=checkbox] {
    /* Double-sized Checkboxes */
    -ms-transform: scale(1.3);
    /* IE */
    -moz-transform: scale(1.3);
    /* FF */
    -webkit-transform: scale(1.3);
    /* Safari and Chrome */
    -o-transform: scale(1.3);
    /* Opera */
    transform: scale(1.3);
    padding: 10px;
    cursor: pointer;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row ">
            <div class="col-md-12">

            </div>
        </div>
        <div class="row">
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Payments </b>
                    </div>
                    <div class="card-body">
                        <table id="payments" class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                <th class="text-center">#</th>
                                    <th class="">Date</th>
                                    <th class="">LRN </th>
                                    <th class="">OR No. </th>
                                    <th class="">Name</th>
                                    <th class="">Year Level</th>
                                    <th class="">Section</th>
                                    <th class="">Payable Fee</th>
                                    <th class="">Paid</th>
                                    <th class="">Balance</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
$i = 1;
$payments_query = $conn->query("SELECT * FROM payments WHERE archived = 0 ORDER BY student_id ASC");

while ($payment = $payments_query->fetch_assoc()):
    $student_id = $payment['student_id'];

// Fetch student details from the students table
$stmt = $conn->prepare("SELECT CONCAT_WS(' ', s.firstname, s.middlename, s.lastname) as sname, s.id_no, s.course, s.level, p.total_amount FROM student s INNER JOIN payments p ON s.id = p.student_id WHERE s.id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    // Handle student not found error
    exit('Student not found');
}

$paid = $payment['paid_amount'];
$balance = $student['total_amount'] - $paid;
?>

<tr>
    <td class="text-center"><?php echo $i++ ?></td>
    <td>
        <p><b><?php echo $payment['date_created'] ?></b></p>
    </td>
    <td>
        <p><b><?php echo $student['id_no'] ?></b></p>
    </td>
    <td>
        <p><b><?php echo $payment['or_number'] ?></b></p>
    </td>
    <td>
        <p><b><?php echo ucwords($student['sname']) ?></b></p>
    </td>
    <td>
        <p><b><?php echo $student['course'] ?></b></p>
    </td>
    <td>
        <p><b><?php echo ucwords($student['level']) ?></b></p>
    </td>
    <td class="text-right">
        <p><b><?php echo number_format($student['total_amount'], 2) ?></b></p>
    </td>
    <td class="text-right">
        <p><b><?php echo number_format($paid, 2) ?></b></p>
    </td>
    <td class="text-right">
        <p> <b><?php echo number_format($balance,2) ?></b></p>
    </td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-dark view_payment" type="button" data-id="<?php echo $payment['id'] ?>">View</button>
        <button class="btn btn-sm btn-outline-danger archive_payment" type="button" data-id="<?php echo $payment['id'] ?>" data-action="archive_payment">Archive</button>
    </td>
</tr>

                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>
<style>
td {
    vertical-align: middle !important;
}

td p {
    margin: unset
}

img {
    max-width: 100px;
    max-height: :150px;
}
</style>
<script>
$(document).ready(function() {
    $(document).ready(function () {
    var paymentsTable = $('#payments').DataTable(); // Removed the # symbol

    $('#search_button').click(function () {
        paymentsTable.search($('#search_payments').val()).draw();
    });
    });
});
    $('.archive_payment').click(function () {
        var paymentId = $(this).data("id");
    var id = $(this).data('id');
    });

    $('.view_payment').click(function() {
        uni_modal("Payment Details", "view_payment.php?id=" + $(this).data('id') + "&pid=0", "mid-large");
    });

    $('#new_fees').click(function() {
        uni_modal("Payment Details ", "manage_fee.php", "mid-large");
    });

    $('.edit_payment').click(function() {
        uni_modal("Manage Student's Payment Details", "manage_fee.php?id=" + $(this).data('id'), "mid-large");
    });

    $('.archive_payment').click(function () {
    var id = $(this).data('id');
    
    // Assuming you have a function to handle the confirmation
    _conf("Are you sure to archive this payment?", 'archive_payment', [id]);
});

function archive_payment(id) {
    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: { action: 'archive_payment', id: id },
        success: function (response) {
            console.log('Server response:', response);

            if (response.status === 1) {
                // Display a success message on the page
                $('#responseMessage').removeClass('alert-danger').addClass('alert-success').html(response.message).show();

                // Hide the success message after a few seconds (optional)
                setTimeout(function () {
                    $('#responseMessage').hide();
                    // Refresh the page
                    location.reload();
                }, 1000);
            } else {
                // Display an error message on the page
                $('#responseMessage').removeClass('alert-success').addClass('alert-danger').html('Error: ' + response.message).show();
            }
        },
        error: function (error) {
            console.error('Error:', error);

            // Show an alert when there's an error
            alert("Failed to archive payment.");
        }
    });
}

</script>
