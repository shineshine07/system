<?php
include 'db_connect.php';

$students = $conn->query("SELECT id, CONCAT_WS(' ', firstname, middlename, lastname) AS name, id_no, level, course FROM student ORDER BY name ASC");
$fees = $conn->query("SELECT id, description, amount FROM fees ORDER BY description ASC");

?>

<div class="container-fluid" style="width: 60%; background-color: white; border: 1px solid #ddd; padding: 20px; border-radius: 10px;">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">

        <div class="form-group">
    <div id="msg"></div>
    <label for="" class="control-label">Student</label>
    <div class="input-group">
        <select name="student_id" id="student_id" class="custom-select input-sm select2" style="width: 100%; font-family: inherit; font-size: 18px;">
            <option value="" selected></option>
            <?php while ($row = $students->fetch_assoc()): ?>
                <option value="<?php echo $row['id'] ?>" data-year-level="<?php echo $row['course'] ?>" data-section="<?php echo $row['level'] ?>" <?php echo isset($student_id) && $student_id == $row['id'] ? 'selected' : ''; ?>>
                    <?php echo ucwords($row['name']) . ' | ' . $row['id_no'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
</div>


<div class="form-group">
    <label for="" class="control-label">OR Number</label>
    <input type="number" class="form-control" name="or_number" id="or_number" style="width: 100%; font-family: inherit; font-size: 16px;">
</div>

<div class="form-group">
    <label for="" class="control-label">Year Level</label>
    <input type="text" class="form-control" name="year_level" id="year_level" style="width: 100%; font-family: inherit; font-size: 16px;" readonly>
</div>

<div class="form-group">
    <label for="" class="control-label">Section</label>
    <input type="text" class="form-control" name="section" id="section" style="width: 100%; font-family: inherit; font-size: 16px;" readonly>
</div>

        <h5><b>Fee Details</b></h5>
        <hr>

        <div class="form-group">
    <label class="control-label">Payable Particulars</label>
    <div class="dropdown-checkbox">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPayableParticulars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Select Payable Particulars
        </button>
        <div class="dropdown-menu" style="width: 20%; padding-left:15px;" aria-labelledby="dropdownPayableParticulars">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                <label class="form-check-label" for="selectAllCheckbox">
                    Select All
                </label>
            </div>
            <?php $fees->data_seek(0); // Reset the pointer to the beginning of the result set ?>
            <?php while ($fee = $fees->fetch_assoc()): ?>
                <div class="form-check">
                    <input class="form-check-input payable-particular-checkbox" type="checkbox" value="<?php echo $fee['id']; ?>" id="payableCheckbox_<?php echo $fee['id']; ?>" data-amount="<?php echo $fee['amount']; ?>">
                    <label class="form-check-label" for="payableCheckbox_<?php echo $fee['id']; ?>">
                        <?php echo $fee['description']; ?>
                    </label>
                </div>
            <?php endwhile; ?>
            

            <!-- Add a checkbox for Balance -->
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="balanceCheckbox">
                <label class="form-check-label" for="balanceCheckbox">
                    Balance
                </label>
            </div>
            

            <!-- Add a container to display unpaid particulars -->
            <div id="unpaidParticularsContainer" style="display: none;">
                <!-- The unpaid particulars will be dynamically added here -->
            </div>

        </div>
    </div>
</div>

        <!-- Select Button -->
        <div class="form-group pt-1">
            <label for="" class="control-label">&nbsp;</label>
            <button class="btn btn-danger btn-sm" type="button" id="select_particulars">Select</button>
        </div>
        <div class="form-group">
            <label for="selected_particulars" class="control-label">Selected Particulars</label>
            <div id="selected_particulars" style="white-space: pre-wrap;"></div> 

        <!-- Display Total Amount -->
        <div class="form-group">
            <label for="total_amount" class="control-label">Total Payable Fee</label>
            <div id="total_amount" class="form-control"></div>
        </div>

        <!-- Select Particulars Dropdown -->
        <div class="form-group">
            <label class="control-label">Particulars</label>
            <div class="dropdown-checkbox">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownParticulars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select Particulars
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownParticulars" id="selectedParticularsDropdown">
                    <!-- Particulars checkboxes will be dynamically added here -->
                </div>
            </div>
        </div>

        <div class="form-group pt-1">
            <label for="" class="control-label">&nbsp;</label>
            <button class="btn btn-danger btn-sm" type="button" id="calculate_total">Calculate Total</button>
        </div>

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
                        <input type="hidden" name="amount" value="0">
                        <span class="tamount">0.00</span>
                    </th>
                </tr>
            </tfoot>
        </table>

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
                        <p class="fee-description"></p>
                        <input type="hidden" name="amount[]">
                        <p class="text-right"><small><b class="famount"></b></small></p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="form-group">
            <label for="paid_amount" class="control-label">Paid Amount</label>
            <input type="number" step="any" min="0" id="paid_amount" class="form-control form-control-sm text-right" name="paid_amount">
        </div>
        <br>
        <!-- Submit Button -->
        <div class="form-group pt-1">
            <label for="" class="control-label">&nbsp;</label>
            <button class="btn btn-danger btn-sm" type="submit" id="submit_payment">Submit</button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
  $(document).ready(function () {
    $('#student_id').select2({
        placeholder: 'Search for a student',
        minimumInputLength: 2,
        ajax: {
            url: 'ajax.php',
            dataType: 'json',
            type: 'POST',
            data: function (params) {
                return {
                    action: 'search_students',
                    term: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});

 
    $(document).ready(function () {
  

        $('#saveOthersBtn').click(function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    var particular = $('#othersParticular').val();
    var amount = $('#othersAmount').val();

    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {
            action: 'save_others_fee',
            particular: particular,
            amount: amount
        },
        success: function(response) {
            Toastify({
                text: "Fee saved successfully",
                duration: 3000, // 3 seconds
                gravity: "top", // Display at the top
                position: 'right', // Display on the right
                stopOnFocus: true, // Stop the toast from automatically hiding on focus
                className: "info" // Apply a custom class for styling
            }).showToast();

            // Optionally, you can reload the page or perform other actions
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('An error occurred while saving.');
        }
    });
});


    $(document).ready(function() {
        $('#select_particulars').click(function() {
            // Get the values of the 'Others' fields
            var particular = $('#othersParticular').val();
            var amount = $('#othersAmount').val();

            // Send an AJAX request to 'ajax.php' to save the values
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    particular: particular,
                    amount: amount
                },
                success: function(response) {
                    // Optionally, you can display a success message or perform other actions
                    console.log('Data saved successfully');
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error('Error saving data:', error);
                }
            });
        });
    });

    
    $('#select_particulars').click(function () {
        var selectedParticulars = [];
        var totalAmount = 0;

    
        $('.payable-particular-checkbox:checked').each(function () {
            selectedParticulars.push({
                description: $(this).next('label').text(),
                amount: parseFloat($(this).data('amount'))
            });
            totalAmount += parseFloat($(this).data('amount'));
        });

        
        var selectedParticularsText = '';
        $.each(selectedParticulars, function (index, particular) {
            selectedParticularsText += particular.description + ' - ' + particular.amount.toFixed(2) + '\n';
        });
        $('#selected_particulars').text(selectedParticularsText);
        $('#total_amount').text(totalAmount.toFixed(2));
    });
});
$(document).ready(function() {
        $('#select_particulars').click(function() {
            var selectedParticulars = [];
            $('.payable-particular-checkbox:checked').each(function() {
                var id = $(this).val();
                var description = $(this).next('label').text();
                var amount = $(this).data('amount');
                selectedParticulars.push({ id: id, description: description, amount: amount });
            });


            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: { selectedParticulars: selectedParticulars },
                success: function(response) {
                    $('#selected_particulars').text(response.selectedParticulars);
                    $('#total_amount').text(response.totalAmount);
                }
            });
        });
    });

$('#balanceCheckbox').change(function () {
        var studentId = $('#student_id').val();
        var isChecked = $(this).is(':checked');

        // Make sure studentId is not empty
        if (studentId.trim() === "") {
            alert_toast("Invalid student ID", 'danger');
            return;
        }

        // Fetch balance and update UI
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "get_balance",
                student_id: studentId,
                is_checked: isChecked
            },
            dataType: 'json',
            success: function (response) {
                // Handle the response
                if (response.status === 1) {
                    var totalAmount = response.balance;

                    // Deduct the paid amount
                    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
                    totalAmount -= paidAmount;

                    // Display the updated balance
                    $('#total_amount').text(totalAmount.toLocaleString('en-US'));

                    // Fetch and display payment history when the balance is checked
                    if (isChecked) {
                        fetchPaymentHistory(studentId);
                    }

                    alert_toast("Balance fetched successfully: " + totalAmount.toLocaleString('en-US'), 'success');

                } else {
                    // Handle the error case
                    alert_toast("Error fetching balance: " + response.message, 'danger');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("AJAX Request Failed");
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                alert_toast("Error connecting to the server.", 'danger');
            }
        });
    });


    function fetchPaymentHistory(studentId) {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: "get_payment_history",
            student_id: studentId
        },
        dataType: "json",
        success: function (response) {
            if (response.status === 1) {
                // Clear existing dropdown items
                $('#selectedParticularsDropdown').empty();

                // Add retrieved payment history to the dropdown
                $.each(response.payment_history, function (index, item) {
                    var checkboxInput = $("<input>")
                        .attr("type", "checkbox")
                        .attr("value", item.fee_description)
                        .attr("id", "particularCheckbox_" + index)
                        .data("amount", item.amount);

                    var checkboxLabelElement = $("<label>")
                        .attr("for", "particularCheckbox_" + index)
                        .text(item.fee_description);

                    // Add a line break before each checkbox and label pair
                    $('#selectedParticularsDropdown').append("<br>");
                    $('#selectedParticularsDropdown').append(checkboxInput);
                    $('#selectedParticularsDropdown').append(checkboxLabelElement);
                });
            } else {
                alert_toast("Error getting payment history: " + response.message, 'danger');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error: " + error);
            alert_toast("Error connecting to the server.", 'danger');
        }
    });
}

// Function to save payment history to fee details
function savePaymentHistoryToFeeDetails(studentId, selectedItems) {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: "save_payment_history_to_fee_details",
            student_id: studentId,
            selected_items: selectedItems
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 1) {
                // Handle success if needed
                console.log("Data saved successfully");
            } else {
                // Handle error if needed
                console.log("Error saving data: " + response.message);
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log("AJAX Request Failed");
            console.log("Status: " + textStatus);
            console.log("Error: " + errorThrown);
            // Handle error if needed
        }
    });
}

// Update your existing click event for 'select_particulars' as follows:
$('#select_particulars').click(function () {
    var isBalanceChecked = $('#balanceCheckbox').is(':checked');
    var studentId = $('#student_id').val();

    if (isBalanceChecked) {
        fetchPaymentHistory(studentId);
    } else {
        var selectedParticulars = [];

        $('.payable-particular-checkbox:checked').each(function () {
            selectedParticulars.push({
                description: $(this).next('label').text(),
                amount: parseFloat($(this).data('amount'))
            });
        });

        if (selectedParticulars.length === 0) {
            return;
        }
    }

    var studentId = $('#student_id').val();
    if (studentId.trim() === "") {
        alert_toast("Please select a student.", 'warning');
        return;
    }

    // Prepare data for the AJAX request
    var requestData = {
        action: "save_payment_history",
        student_id: studentId,
        selected_particulars: selectedParticulars,
        particulars: isBalanceChecked ? [] : selectedParticulars
    };

    // Send AJAX request to save the data to the database
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: requestData,
        dataType: 'json',
        success: function (response) {
            // Handle the success response
            console.log(response);
        },
        error: function (xhr, textStatus, errorThrown) {
            // Handle the error
            console.log("Error:", textStatus, errorThrown);
        }
    });
});



// Function to check if a particular is paid
function isParticularPaid(particular, paymentHistory) {
    for (var i = 0; i < paymentHistory.length; i++) {
        if (paymentHistory[i].fee_description === particular.fee_description && paymentHistory[i].amount === particular.amount) {
            return true; // Particular is paid
        }
    }
    return false; // Particular is unpaid
}
// Function to save selected particular to fee_details
function saveFeeDetails(selectedParticular) {
    var studentId = $('#student_id').val();

    // Check if a student is selected
    if (studentId.trim() === "") {
        alert_toast("Please select a student.", 'warning');
        return;
    }

    // Prepare data for the AJAX request
    var requestData = {
        action: "save_fee_details",
        student_id: studentId,
        particular: selectedParticular
    };

    // Send AJAX request to save the data to the database
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: requestData,
        dataType: 'json',
        success: function (response) {
            // Handle the success response
            if (response.status === 1) {
                alert_toast("Fee detail saved successfully", 'success');
            } else {
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            // Handle the error
            console.log("AJAX Request Failed");
            console.log("Status: " + textStatus);
            console.log("Error: " + errorThrown);
            alert_toast("Error connecting to the server.", 'danger');
        }
    });
}

// Update the 'calculate_total' click event to pass only the first selected particular
$('#calculate_total').click(function () {
    var total = 0;
    var selectedParticular = null;

    // Clear existing rows in the fee-list tbody
    $('#fee-list tbody').empty();

    // Iterate through the selectedParticularsDropdown and add rows to fee-list tbody
    var selectedParticular = null; // Declare this variable outside the click event handler

$('#selectedParticularsDropdown input:checked').each(function () {
    var description = $(this).val();
    var amount = parseFloat($(this).data('amount'));

    // Add a new row to fee-list tbody
    var newRow = $('#fee_clone table tr').clone();
    newRow.find('[name="fid[]"]').val(''); // Assuming this is an ID, set it as needed
    newRow.find('[name="type[]"]').val(description);
    newRow.find('.ftype').text(description);
    newRow.find('[name="amount[]"]').val(amount);
    newRow.find('.famount').text(amount.toLocaleString('en-US'));
    total += amount;

    $('#fee-list tbody').append(newRow);

    // Collect the first selected particular for saving
    if (!selectedParticular) {
        selectedParticular = {
            description: description,
            amount: amount
        };

        // Assuming you are making an AJAX call to save the data
        $.ajax({
    type: 'POST',
    url: 'ajax.php',
    data: {
        action: 'save_payment_history_to_fee_details',
        description: selectedParticular.description,
        amount: selectedParticular.amount,
        // Add other necessary data for saving
        // For example, you might need to include student_id or payment_id
    },
    success: function (response) {
        console.log(response);
    },
    error: function (error) {
        console.error(error);
    }
});

    }
});


    // Add the paid amount to the total
    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
    total += paidAmount;

    // Update the total amount in the tfoot
    $('#fee-list tfoot').find('.tamount').text(parseFloat(total).toLocaleString('en-US'));
    $('#fee-list tfoot').find('[name="amount"]').val(total);

    // Update the total balance in the total_balance field
    $('#total_balance').text(parseFloat(total).toLocaleString('en-US'));

    // Save the first selected particular to fee_details
    if (selectedParticular) {
        saveFeeDetails(selectedParticular);
    }
});

    $('#student_id').change(function () {
        var selectedStudent = $(this).find('option:selected');
        var yearLevel = selectedStudent.data('year-level');
        var section = selectedStudent.data('section');

        $('#year_level').val(yearLevel);
        $('#section').val(section);
    });

    var selectAllCheckbox = document.getElementById('selectAllCheckbox');
    var individualCheckboxes = document.querySelectorAll('.payable-particular-checkbox');

    selectAllCheckbox.addEventListener('change', function () {
        individualCheckboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    $('#select_particulars').click(function () {
        var selectedParticulars = [];

        $('.payable-particular-checkbox:checked').each(function () {
            selectedParticulars.push({
                description: $(this).next('label').text(),
                amount: parseFloat($(this).data('amount'))
            });
        });

        var maxFeeNameLength = Math.max(...selectedParticulars.map(particular => particular.description.length));
        var maxAmountLength = Math.max(...selectedParticulars.map(particular => particular.amount.toLocaleString('en-US').length));

        var selectedParticularsText = '';

        selectedParticulars.forEach(function (particular) {
            var spaceFeeName = ' '.repeat(maxFeeNameLength - particular.description.length + 5);
            var spaceAmount = ' '.repeat(maxAmountLength - particular.amount.toLocaleString('en-US').length + 50);

            selectedParticularsText += particular.description + spaceFeeName + '\t' + spaceAmount + particular.amount.toLocaleString('en-US') + '\n';
        });

        $('#selected_particulars').text(selectedParticularsText);

        var totalAmount = selectedParticulars.reduce(function (total, particular) {
            return total + parseFloat(particular.amount);
        }, 0);

        $('#total_amount').text(' ' + parseFloat(totalAmount).toLocaleString('en-US'));
    });


    $("#select_particulars").on("click", function () {
        $("#selectedParticularsDropdown").empty();

        $(".payable-particular-checkbox:checked").each(function () {
            var id = $(this).val();
            var description = $("label[for='payableCheckbox_" + id + "']").text();

            $("#selectedParticularsDropdown").append('<div class="form-check">' +
                '<input class="form-check-input particular-checkbox" type="checkbox" value="' + id + '" id="particularCheckbox_' + id + '" data-amount="' + $(this).data("amount") + '">' +
                '<label class="form-check-label" for="particularCheckbox_' + id + '">' + description + '</label>' +
                '</div>');
        });
    });

    function calculate_total() {
        var total = 0;

        $('#fee-list tbody').find('[name="amount[]"]').each(function () {
            total += parseFloat($(this).val());
        });

        var paidAmount = parseFloat($('#paid_amount').val()) || 0;
        total += paidAmount;

        $('#fee-list tfoot').find('.tamount').text(parseFloat(total).toLocaleString('en-US'));
        $('#fee-list tfoot').find('[name="total_amount"]').val(total);
    }

    function rem_list(_this) {
        _this.closest('tr').remove();
        calculate_total();
    }

    $('.dropdown-checkbox .dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    $('.dropdown-checkbox input[type="checkbox"]').on('change', function () {
        var selectedFee = $(this).val();
        var amount = $(this).data('amount');
        $('#amount').val(amount);
    });

    $('#add_fee, #calculate_total').click(function () {
        var selectedParticulars = [];

        $('#selectedParticularsDropdown input.particular-checkbox:checked').each(function () {
            selectedParticulars.push({
                id: $(this).val(),
                amount: $(this).data('amount'),
                description: $(this).next('label').text()
            });
        });

        if (selectedParticulars.length === 0) {
            return false;
        }

        $('#fee-list tbody').empty();
        selectedParticulars.forEach(function (particular) {
            var tr = $('#fee_clone table tr').clone();
            tr.find('[name="type[]"]').val(particular.id);
            tr.find('.ftype').text(particular.description);
            tr.find('[name="amount[]"]').val(particular.amount);
            tr.find('.famount').text(parseFloat(particular.amount).toLocaleString('en-US'));
            $('#fee-list tbody').append(tr);
        });

        calculate_total();
    });
    $('#select_particulars').click(function () {
    var studentId = $('#student_id').val();
    var selectedParticulars = [];

    $('.payable-particular-checkbox:checked').each(function () {
        selectedParticulars.push({
            description: $(this).next('label').text(),
            amount: parseFloat($(this).data('amount'))
        });
    });

    // Check if a student is selected
    if (studentId.trim() === "") {
        alert_toast("Please select a student.", 'warning');
        return;
    }

    // Check if at least one particular is selected
    if (selectedParticulars.length === 0) {
        alert_toast("Please select at least one particular.", 'warning');
        return;
    }

    // Prepare data for the AJAX request
    var requestData = {
        action: "save_payment_history",
        student_id: studentId,
        particulars: selectedParticulars
    };

    // Send AJAX request to save the data to the database
    $.ajax({
    type: "POST",
    url: "ajax.php",
    data: {
        action: "save_payment_history",
        student_id: studentId,
        selected_particulars: selectedParticulars
    },
    dataType: 'json',
    success: function (response) {
        // Handle the success response
        console.log(response);
    },
    error: function (xhr, textStatus, errorThrown) {
        // Handle the error
        console.log("Error:", textStatus, errorThrown);
    }
});
    });


    $('#manage-payment').submit(function (e) {
    e.preventDefault();
    start_load();
    $('#msg').html('');

    var formData = $(this).serializeArray();

    var totalAmount = parseFloat($('#total_amount').text().replace(',', '')) || 0;
    formData.push({ name: 'total_amount', value: totalAmount });

    var feeDetails = [];
    $('#fee-list tbody tr').each(function () {
        var feeType = $(this).find('[name="type[]"]').val();
        var feeAmount = $(this).find('[name="amount[]"]').val();
        feeDetails.push({ id: feeType, amount: feeAmount });
    });

    formData.push({ name: 'fee_details', value: JSON.stringify(feeDetails) });

    // Additional data for paid amount
    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
    formData.push({ name: 'paid_amount', value: paidAmount });

    $.ajax({
        type: "POST",
        url: "ajax.php?action=save_payment",
        data: formData,
        success: function (response) {
            console.log(response);
            if (response.status === 1) {
                var totalAmount = response.total_amount;

                $('#fee-list tfoot').find('.tamount').text(parseFloat(totalAmount).toLocaleString('en-US'));
                $('#total_amount').text(parseFloat(totalAmount).toLocaleString('en-US'));

                // Assuming you want to clear the form or perform other actions after saving
                // Clear the paid amount input
                $('#paid_amount').val('');

                // Clear the fee list
                $('#fee-list tbody').empty();

                alert_toast("Payment saved successfully", 'success');

                // Reload the page after successful payment
                location.reload();
            } else {
                alert_toast("Error saving payment: " + response.message, 'danger');
            }

            end_load();
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log("AJAX Request Failed");
            console.log("Status: " + textStatus);
            console.log("Error: " + errorThrown);
            alert_toast("Error connecting to the server.", 'danger');
            end_load();
        }
    });
});

</script>