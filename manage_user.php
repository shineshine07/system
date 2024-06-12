<?php 
include('db_connect.php');
session_start();

// Fetch user details if ID is provided
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>
<div class="container-fluid">
    <div id="msg"></div>
    
    <form action="" id="manage-user">    
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
                <div class="input-group-append">
                    <span class="input-group-text" id="togglePassword">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
            <?php if (isset($meta['id'])): ?>
                <small><i>Leave this blank if you don't want to change the password.</i></small>
            <?php endif; ?>
        </div>

        <?php if(isset($meta['type']) && $meta['type'] == 3): ?>
            <!-- Common fields for both admin and staff -->
            <input type="hidden" name="type" value="3">
        <?php else: ?>
            <?php if(!isset($_GET['mtype'])): ?>
                <!-- User Type selection for admin -->
                <div class="form-group">
                    <label for="type">User Type</label>
                    <select name="type" id="type" class="custom-select">
                        <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Staff</option>
                        <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
                    </select>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($_SESSION['login_type'] == 1): // Admin ?>
            <!-- Admin-specific fields or functionalities -->
        <?php elseif ($_SESSION['login_type'] == 2): // Staff ?>
            <!-- Staff-specific fields or functionalities -->
            <div class="form-group">
                <label for="paymentReport">Payments Report</label>
                <input type="text" name="paymentReport" id="paymentReport" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="disbursementReport">Disbursement Report</label>
                <input type="text" name="disbursementReport" id="disbursementReport" class="form-control" value="">
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    $(document).ready(function () {
        // Toggle password visibility
        $('#togglePassword').on('click', function () {
            var passwordInput = $('#password');
            var type = passwordInput.attr('type');

            // Toggle password visibility
            passwordInput.attr('type', type === 'password' ? 'text' : 'password');
        });
    });

    $('#manage-user').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_user',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp){
                console.log('Response from server:', resp);
                if (resp.status === 1) {
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else if (resp.status === 2) {
                    $('#msg').html('<div class="alert alert-danger">' + resp.message + '</div>');
                    end_load();
                } else {
                    $('#msg').html('<div class="alert alert-danger">Failed to save data</div>');
                    end_load();
                }
            }
        });
    });
</script>
