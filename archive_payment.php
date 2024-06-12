<?php include('db_connect.php'); ?>

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
                        <b>List of Archived Payments </b>
                    </div>
                    <div class="card-body">
                        <table id="payments" class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">LRN</th>
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
                       
                                $archivePayments = $conn->query("SELECT * FROM payments WHERE archived = 1 ORDER BY student_id ASC");

                                while ($payment = $archivePayments->fetch_assoc()):
                                    $student_id = $payment['student_id'];

                                    // Fetch student details from the students table
                                    $student_query = $conn->query("SELECT CONCAT_WS(' ', s.firstname, s.middlename, s.lastname) AS name, s.id_no, s.course, s.level, p.total_amount FROM student s INNER JOIN payments p ON s.id = p.student_id WHERE s.id = $student_id");
                                    $student = $student_query->fetch_assoc();

                                    $paid = $payment['paid_amount'];
                                    $balance = $student['total_amount'] - $paid;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td>
                                        <p><b><?php echo $student['id_no'] ?></b></p>
                                    </td>
                                    <td>
                                        <p><b><?php echo ucwords($student['name']) ?></b></p>
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
                                        <button class="btn btn-sm btn-outline-primary view_payment" type="button"
                                                data-id="<?php echo $payment['id'] ?>">View</button>
                                        <button class="btn btn-sm btn-outline-danger unarchive_payment" type="button"
                                                data-id="<?php echo $payment['id'] ?>">Unarchive</button>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $(".unarchive_payment").on("click", function () {
            var paymentsId = $(this).data("id");

            // Send an AJAX request to handle unarchiving
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: { action: "unarchive_payment", payments_id: paymentsId },
                dataType: "json",
                success: function (response) {
                    // Handle the response as needed
                    if (response.success) {
                        // Optional: Reload the page or update the UI dynamically
                        location.reload();
                    } else {
                        // Handle errors if necessary
                        alert("Failed to unarchive payment.");
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors if necessary
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
