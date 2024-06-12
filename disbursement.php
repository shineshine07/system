<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        
                <div class="card">
                    <div class="card-header">
                    <b>List of Disbursements</b>
                        <span class="float:right"><span class="float:right"><a class="btn btn-danger btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_disbursement">
						<i class="fa fa-plus"></i> New 
                            </a>
                        </span>
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
                                $disbursements = $conn->query("SELECT * FROM disbursement WHERE archived = 0 ORDER BY CV ASC");
                                while ($row = $disbursements->fetch_assoc()) :
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
                                            <button class="btn btn-sm btn-outline-dark cash_voucher" type="button" data-id="<?php echo $row['id'] ?>">View</button>
                                            <button class="btn btn-sm btn-outline-danger archive_disbursement" type="button" data-id="<?php echo $row['id'] ?>" data-action="archive_disbursement">Archive</button>
                                     </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div id="responseMessage" class="alert" style="display: none;"></div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
         $(".archive_disbursement").on("click", function () {
            var disbursementId = $(this).data("id");
            var action = $(this).data("action");
    });
});
    $('.cash_voucher').click(function(){
		uni_modal("Cash Voucher","cash_voucher.php?id="+$(this).attr('data-id')+"&pid=0","mid-large")
		
	})

    $('#new_disbursement').click(function () {
        uni_modal("New Disbursement", "manage_disbursement.php", "mid-large")
    })

$('.archive_disbursement').click(function () {
    var id = $(this).data('id');
    var action = $(this).data('action');

    // Assuming you have a function to handle the confirmation
    _conf("Are you sure to archive this disbursement?", 'archive_disbursement', [id]);
});

function archive_disbursement(id, cv) {
    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: { action: 'archive_disbursement', id: id },
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
            alert("Failed to archive disbursement.");
        }
    });
}


</script>