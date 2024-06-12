<?php
include 'db_connect.php';

$students = $conn->query("SELECT id, name, id_no, course, level FROM student ORDER BY name ASC");
$fees = $conn->query("SELECT id, description, amount FROM fees ORDER BY description ASC");
?>

<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
            <div class="col-lg-6 border-right">
                <div class="form-group">
                    <div id="msg"></div>
                    <label for="" class="control-label">Student</label>
                    <select name="student_id" id="student_id" class="custom-select input-sm select2">
                        <option value=""></option>
                        <?php while ($row = $students->fetch_assoc()): ?>
                            <option value="<?php echo $row['id'] ?>" data-year-level="<?php echo $row['course'] ?>" data-section="<?php echo $row['level'] ?>" <?php echo isset($student_id) && $student_id == $row['id'] ? 'selected' : '' ?>>
                                <?php echo ucwords($row['name']) . ' | ' . $row['id_no'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Year Level</label>
                    <input type="text" class="form-control" name="year_level" id="year_level" readonly>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Section</label>
                    <input type="text" class="form-control" name="section" id="section" readonly>
                </div>
                <!-- Move Paid Amount field here -->
                <div class="form-group">
                    <label for="" class="control-label">Paid Amount</label>
                    <input type="hidden" name="paid_amount" id="paid_amount" value="">
                </div>
            </div>
            <div class="col-lg-6">
                <h5><b>Fee Details</b></h5>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ft" class="control-label">Particulars</label>
                            <!-- Populate dropdown with existing fees -->
                            <select id="ft" class="form-control form-control-sm">
                                <option value=""></option>
                                <?php while ($fee = $fees->fetch_assoc()): ?>
                                    <option value="<?php echo $fee['id']; ?>" data-amount="<?php echo $fee['amount']; ?>">
                                        <?php echo $fee['description']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount" class="control-label">Amount</label>
                            <input type="number" step="any" min="0" id="amount" class="form-control form-control-sm text-right">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group pt-1">
                            <label for="" class="control-label">&nbsp;</label>
                            <button class="btn btn-primary btn-sm" type="button" id="add_fee">Add to List</button>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-condensed" id="fee-list">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="50%">Particulars</th>
                            <th width="30%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Fee details will be added dynamically here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-center">Total</th>
                            <th class="text-right">
                                <input type="hidden" name="total_amount" value="0">
                                <span class="tamount">0.00</span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </form>
</div>

<div id="fee_clone" style="display: none">
    <table>
        <tr>
            <td class="text-center"><button class="btn-sm btn-outline-danger" type="button" onclick="rem_list($(this))"><i class="fa fa-times"></i></button></td>
            <td>
                <input type="hidden" name="fid[]">
                <input type="hidden" name="type[]">
                <p><small><b class="ftype"></b></small></p>
            </td>
            <td>
                <input type="hidden" name="amount[]">
                <p class="text-right"><small><b class="famount"></b></small></p>
            </td>
        </tr>
    </table>
</div>

<script>

function updateFeesDropdown(courseId) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: { action: 'get_fees', course_id: courseId },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status === 1) {
                    var fees = data.fees;
                    var selectOptions = fees.map(fee => `<option value="${fee.id}" data-amount="${fee.amount}">${fee.description}</option>`);
                    $('#ft').html('<option value=""></option>' + selectOptions.join('')).change();
                } else {
                    console.log("Error fetching fees.");
                }
            },
            error: function () {
                console.log("Error connecting to the server.");
            }
        });
    }
    $(document).ready(function () {
        $('#student_id').change(function () {
            var selectedStudent = $(this).find('option:selected');
            var yearLevel = selectedStudent.data('year-level');
            var section = selectedStudent.data('section');
            var courseId = selectedStudent.data('course-id');

            // Update the fees dropdown based on the selected course_id
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: { action: 'get_fees', course_id: courseId },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 1) {
                        var fees = data.fees;
                        var selectOptions = fees.map(fee => `<option value="${fee.id}" data-amount="${fee.amount}">${fee.description}</option>`);
                        $('#ft').html('<option value=""></option>' + selectOptions.join('')).change();
                    } else {
                        console.log("Error fetching fees.");
                    }
                },
                error: function () {
                    console.log("Error connecting to the server.");
                }
            });

            $('#year_level').val(yearLevel);
            $('#section').val(section);
        });
        function resetForm() {
            $('#msg').html('');
            $('#manage-payment input:not([type="hidden"])').val('');
            $('#fee-list tbody').empty();
            calculate_total();
        }

        $('#add_fee').click(function () {
            var ft = $('#ft').val();
            var amount = $('#amount').val();

            if (ft == '' || amount == '') {
                alert_toast("Please select a Fee and enter the Amount first.", 'warning');
                return false;
            }

            // Create a new row in the fee-list table with the selected fee
            var tr = $('#fee_clone table tr').clone();
            tr.find('[name="type[]"]').val(ft);
            tr.find('.ftype').text(ft);
            tr.find('[name="amount[]"]').val(amount);
            tr.find('.famount').text(parseFloat(amount).toLocaleString('en-US'));
            $('#fee-list tbody').append(tr);

            $('#ft').val('').change();
            $('#amount').val('');
            calculate_total();
        });

        function calculate_total() {
            var total = 0;
            $('#fee-list tbody').find('[name="amount[]"]').each(function () {
                total += parseFloat($(this).val());
            });

            $('#fee-list tfoot').find('.tamount').text(parseFloat(total).toLocaleString('en-US'));
            $('#fee-list tfoot').find('[name="total_amount"]').val(total);
        }

        function rem_list(_this) {
            _this.closest('tr').remove();
            calculate_total();
        }

        $('#manage-payment').submit(function (e) {
            e.preventDefault();
            console.log("Submit button clicked");
            start_load();
            $('#msg').html('');

            if ($('#fee-list tbody').find('[name="fid[]"]').length <= 0) {
                alert_toast("Please insert at least 1 row in the fees table", 'danger');
                end_load();
                return false;
            }

            // Serialize the form data, including the paid_amount field
            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "ajax.php", // Replace with the actual URL to fetch fees
                data: formData, // Sending the entire form data
                success: function (response) {
                    // Handle the success response
                    console.log("Success: " + response);
                },
                error: function () {
                    console.log("Error connecting to the server.");
                }
            });
        });
    });
</script>
