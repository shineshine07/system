<?php
include('db_connect.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';
$description = isset($_GET['description']) ? $_GET['description'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';
?>
<style>
    input[type=checkbox] {
        /* Double-sized Checkboxes */
        -ms-transform: scale(1.3); /* IE */
        -moz-transform: scale(1.3); /* FF */
        -webkit-transform: scale(1.3); /* Safari and Chrome */
        -o-transform: scale(1.3); /* Opera */
        transform: scale(1.3);
        padding: 10px;
        cursor: pointer;
    }
</style>
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
                        <b>List of Fees</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Particulars</th>
                                    <th class="">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $course = $conn->query("SELECT * FROM fees  order by description asc ");
                                while ($row = $course->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="">
                                            <p> <b><?php echo $row['description'] ?></b></p>
                                        </td>
                                        <td class="text-right">
                                            <p> <b><?php echo number_format($row['amount'], 2) ?></b></p>
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

<!-- Add/Edit Fees Form -->
<div class="modal" id="manage_course" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fees_form">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="description">Particulars</label>
                        <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>">
                    </div>
                    <div class="form-group">
                        <label for="amount">Total Amount</label>
                        <input type="number" step="any" min="0" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
			<button type="button" class="btn btn-danger" id="save_fee">Save</button>
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Add/Edit Fees Form -->

<script>
    $(document).ready(function () {
        $('table').dataTable()
    })

    $('#new_course').click(function () {
        $('#manage_course .modal-title').text('New Fees Entry');
        $('#manage_course').modal('show');
    });

    // Open the edit form with data
    $('.edit_fees').click(function () {
        var id = $(this).attr('data-id');
        var description = $(this).attr('data-description');
        var amount = $(this).attr('data-amount');

        $('#manage_course .modal-title').text('Edit Fees Entry');
        $('#manage_course input[name="id"]').val(id);
        $('#manage_course input[name="description"]').val(description);
        $('#manage_course input[name="amount"]').val(amount);

        $('#manage_course').modal('show');
    });

    $('#save_fee').click(function () {
        var form_data = $('#fees_form').serialize();

        $.ajax({
            url: 'ajax.php?action=save_fee', // Change this URL to the correct one in your application
            method: 'POST',
            data: form_data,
            success: function (resp) {
                try {
                    var data = JSON.parse(resp);
                    if (data.status == 1) {
                        alert_toast("Data successfully saved.", 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        // Handle error messages
                        // For now, we'll log the error to the console
                        console.error(data.message);
                    }
                } catch (e) {
                    console.error('Error processing response.');
                }
            },
            error: function () {
                console.error('Error sending request.');
            }
        });

        // After saving, close the modal
        $('#manage_course').modal('hide');
    });

    $('.delete_fees').click(function () {
        _conf("Are you sure to delete this fee?", "delete_fees", [$(this).attr('data-id')])
    })

    function delete_fees($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_fees',
            method: 'POST',
            data: {
                id: $id
            },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function () {
                        location.reload()
                    }, 500)

                }
            }
        })
    }
</script>

