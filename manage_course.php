<?php 
include 'db_connect.php'; 
?>

<div class="container-fluid">
    <form action="" id="manage-course">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="col-lg-12">
            <hr>
            <div class="row">
                <div class="form-group">
                    <label for="ft" class="control-label">Fee Type</label>
                    <input type="text" id="ft" class="form-control-sm">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Amount</label>
                    <input type="number" step="any" min="0" id="amount" class="form-control-sm text-right">
                </div>
                 <div class="form-group pt-1">
                    <label for="" class="control-label">&nbsp;</label>
                    <button class="btn btn-primary btn-sm" type="button" id="add_fee">Add to List</button>
                </div>
            </div>
            <hr>
            <table class="table table-condensed" id="fee-list">
                <thead>
                    <tr>
                        <th width="5%"></th>
                        <th width="50%">Type</th>
                        <th width="45%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(isset($id)):
                        $fees = $conn->query("SELECT * FROM fees where id = $id");
                        $total = 0;
                        while($row=$fees->fetch_assoc()): 
                            $total += $row['amount'];
                    ?>
                        <tr>
                            <td class="text-center"><button class="btn-sm btn-outline-danger" type="button" onclick="rem_list($(this))" ><i class="fa fa-times"></i></button></td>
                            <td>
                                <input type="hidden" name="fid[]" value="<?php echo $row['id'] ?>">
                                <input type="hidden" name="type[]" value="<?php echo $row['description'] ?>">
                                <p><small><b class="ftype"><?php echo $row['description'] ?></b></small></p>
                            </td>
                            <td>
                                <input type="hidden" name="amount[]" value="<?php echo $row['amount'] ?>">
                                <p class="text-right"><small><b class="famount"><?php echo number_format($row['amount']) ?></b></small></p>
                            </td>
                        </tr>
                    <?php
                        endwhile; 
                        endif; 
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-center">Total</th>
                        <th class="text-right">
                            <input type="hidden" name="total_amount" value="<?php echo isset($total) ? $total : 0 ?>">
                            <span class="tamount"><?php echo isset($total) ? number_format($total,2) : '0.00' ?></span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        </div>
    </form>
</div>
<div id="fee_clone" style="display: none">
     <table >
            <tr>
                <td class="text-center"><button class="btn-sm btn-outline-danger" type="button" onclick="rem_list($(this))" ><i class="fa fa-times"></i></button></td>
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
    $('#manage-course').on('reset',function(){
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#add_fee').click(function(){
        var ft = $('#ft').val()
        var amount = $('#amount').val()
        if(amount == '' || ft == ''){
            alert_toast("Please fill the Fee Type and Amount field first.",'warning')
            return false;
        }
        var tr = $('#fee_clone tr').clone()
        tr.find('[name="type[]"]').val(ft)
        tr.find('.ftype').text(ft)
        tr.find('[name="amount[]"]').val(amount)
        tr.find('.famount').text(parseFloat(amount).toLocaleString('en-US'))
        $('#fee-list tbody').append(tr)
        $('#ft').val('').focus()
        $('#amount').val('')
        calculate_total()
    })
    function calculate_total(){
        var total = 0;
        $('#fee-list tbody').find('[name="amount[]"]').each(function(){
            total += parseFloat($(this).val())
        })
        $('#fee-list tfoot').find('.tamount').text(parseFloat(total).toLocaleString('en-US'))
        $('#fee-list tfoot').find('[name="total_amount"]').val(total)

    }
    function rem_list(_this){
        _this.closest('tr').remove()
        calculate_total()
    }
    $('#manage-course').submit(function (e) {
    e.preventDefault();
    start_load();
    $('#msg').html('');

    $.ajax({
        url: 'ajax.php?action=save_course',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (resp) {
            try {
                var data = JSON.parse(resp);
                if (data.status == 1) {
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    $('#msg').html('<div class="alert alert-danger mx-2">' + data.message + '</div>');
                }
            } catch (e) {
                $('#msg').html('<div class="alert alert-danger mx-2">Error processing response.</div>');
            }
            end_load();
        },
        error: function () {
            $('#msg').html('<div class="alert alert-danger mx-2">Error sending request.</div>');
            end_load();
        }
    });
});


    $('.select2').select2({
        placeholder:"Please Select here",
        width:'100%'
    })
</script>