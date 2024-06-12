<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Archived Disbursements</b>
                        <!-- Add any other relevant buttons or controls -->
                    </div>
                    <div class="card-body">
                        <table id="disbursementTable" class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">CV.No.</th>
                                    <th class="">Payee</th>
                                    <th class="text-center">Amount In Words</th>
                                    <th class="">Particulars</th>
                                    <th class="">Total</th>
                                    <th class="">Account Title</th>
                                    <th class="">Debit</th>
                                    <th class="">Credit</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        $i = 1;
                        $archiveDisbursements = $conn->query("SELECT * FROM disbursement WHERE archived = 1 ORDER BY CV ASC");
                        while ($row = $archiveDisbursements->fetch_assoc()) :
                        ?>
                            <tr>
                            <td class="text-center"><?php echo number_format($row['cv'])?></td>
                                        <td>
                                            <p><b><?php echo ucwords($row['payee']) ?></b></p>
                                        </td>
                                        <td class="text-right">
                                             <p><b><?php echo ucwords($row['amount_in_words'], 2) ?></b></p>
                                        </td>
                                        <td>
                                            <p><b><?php echo $row['type'] ?></b></p>
                                        </td>
                                        <td class="text-right">
                                            <p><b><?php echo number_format($row['total'], 2) ?></b></p>
                                        </td>
                                        <td>
                                            <p><b><?php echo $row['account_title'] ?></b></p>
                                        </td>
                                        <td class="text-right">
                                            <p><b><?php echo number_format($row['debit'], 2) ?></b></p>
                                        </td>
                                        <td class="text-right">
                                            <p><b><?php echo number_format($row['credit'], 2) ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary cash_voucher" type="button" data-id="<?php echo $row['id'] ?>">View</button>
                                            <?php if ($row['archived'] == 0) : ?>
                                            <button class="btn btn-sm btn-outline-warning archive_disbursement" type="button" data-id="<?php echo $row['id'] ?>" data-action="archive_disbursement">Archive</button>
                                        <?php else : ?>
                                            <button class="btn btn-sm btn-outline-success archive_disbursement" type="button" data-id="<?php echo $row['id'] ?>" data-action="unarchive_disbursement">Unarchive</button>
                                        <?php endif; ?>
                                     </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $(".archive_disbursement[data-action='unarchive_disbursement']").on("click", function() {
        var disbursementId = $(this).data("id");

        // Send an AJAX request to handle unarchiving
        $.ajax({
            type: "POST",
            url: "ajax.php", // Replace with the actual PHP file handling the request
            data: { action: "unarchive_disbursement", disbursement_id: disbursementId },
            dataType: "json",
            success: function(response) {
                // Handle the response as needed
                if (response.success) {
                    // Optional: Reload the page or update the UI dynamically
                    location.reload();
                } else {
                    // Handle errors if necessary
                    alert("Failed to unarchive disbursement.");
                }
            },
            error: function(xhr, status, error) {
                // Handle errors if necessary
                console.error(xhr.responseText);
            }
        });
    });
});
</script>