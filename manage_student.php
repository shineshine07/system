<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM student where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-student">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="" class="control-label">Id No.</label>
            <input type="text" class="form-control" name="id_no"  value="<?php echo isset($id_no) ? $id_no :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">First Name</label>
            <input type="text" class="form-control" name="firstname"  value="<?php echo isset($firstname) ? $firstname :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Middle Name</label>
            <input type="text" class="form-control" name="middlename"  value="<?php echo isset($middlename) ? $middlename :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Last Name</label>
            <input type="text" class="form-control" name="lastname"  value="<?php echo isset($lastname) ? $lastname :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Year Level</label>
            <input type="text" class="form-control" name="course"  value="<?php echo isset($course) ? $course :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Section</label>
            <input type="text" class="form-control" name="level"  value="<?php echo isset($level) ? $level :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Contact</label>
            <input type="text" class="form-control" name="contact"  value="<?php echo isset($contact) ? $contact :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="email" class="form-control" name="email"  value="<?php echo isset($email) ? $email :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea name="address" id="" cols="30" rows="3" class="form-control" required=""><?php echo isset($address) ? $address :'' ?></textarea>
        </div>
    </form>
</div>
<div id="msg" class="form-group" style="position: fixed; top: 50px; right: 50px; z-index: 9999; width: 240px; font-size: 15px;"></div> 
<script>
    $('#manage-student').on('reset',function(){
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#manage-student').submit(function(e){
    e.preventDefault();
    start_load();
    $('#msg').html('');

    $.ajax({
        url: 'ajax.php?action=save_student',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        dataType: 'json',  // Set dataType to 'json'
        success: function (resp) {
            if (resp.status == 1) {
                alert_toast("Student saved successfully", 'success');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $('#msg').html('<div class="alert alert-danger mx-2">' + resp.message + '</div>');
            }
        },
        error: function () {
            $('#msg').html('<div class="alert alert-danger mx-2">Error saving student</div>');
        },
        complete: function () {
            end_load();
        }
    });
});


    $('.select2').select2({
        placeholder:"Please Select here",
        width:'100%'
    })
</script>