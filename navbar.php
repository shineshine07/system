<nav id="sidebar" >
<a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon rotate-n-10">
                    <img src="assets/img/logo2.png" style="width: 100px;"></i>

                </div>
            </a>
			<div class="sidebar-list">
        <!-- Home -->
        <a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home "></i></span> Home</a>

        <?php if ($_SESSION['login_type'] == 1): ?>
            <!-- Payments -->
            <div class="mx-2 text-white"></div>
            <a href="index.php?page=manage_fee" class="nav-item nav-manage_fee"><span class='icon-field'><i class="fa fa-receipt "></i></span> Payment</a>
            <a href="index.php?page=payment_list" class="nav-item nav-payment_list"><span class='icon-field'><i class="fa fa-receipt "></i></span> List of Payments</a>
            <a href="index.php?page=fees_list" class="nav-item nav-fees_list"><span class='icon-field'><i class="fa fa-scroll "></i></span> List of Fees</a>
            <a href="index.php?page=student_list" class="nav-item nav-student_list"><span class='icon-field'><i class="fa fa-users "></i></span> List of Students</a>
            <a href="index.php?page=manage_disbursement" class="nav-item nav-manage_disbursement"><span class='icon-field'><i class="fa fa-receipt "></i></span> Disbursement</a>
            <a href="index.php?page=disbursement_list" class="nav-item nav-disbursement_list"><span class='icon-field'><i class="fa fa-receipt "></i></span> List of Disbursement</a>
            <br>
            <!-- Report with Dropdown -->
            <div class="mx-2 text-white">
                <div class="dropdown">
                <a class="mx-2 text-white" href="#" role="button" id="reportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 13px;"> <!-- Adjust font size here -->
    <span class='icon-field'><i class="fa fa-th-list"></i></span> General Reports
    <i class="fa fa-caret-down"></i> <!-- Dropdown symbol --> </a>
                    <div class="dropdown-menu" aria-labelledby="reportDropdown">
                    <a class="dropdown-item" href="index.php?page=payment_details"><span class='icon-field'><i class="fa fa-th-list"></i></span> Payment Details</a>
                        <a class="dropdown-item" href="index.php?page=payments_report"><span class='icon-field'><i class="fa fa-th-list"></i></span> Payments Report</a>
                        <a class="dropdown-item" href="index.php?page=disbursement_report"><span class='icon-field'><i class="fa fa-th-list"></i></span> Disbursement Report</a>
        </div>
                </div>
            </div>
            <br>
            
             <!-- Report with Dropdown -->
             <div class="mx-2 text-white">
                <div class="dropdown">
                <a class="mx-2 text-white" href="#" role="button" id="reportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 13px;"> <!-- Adjust font size here -->
    <span class='icon-field'><i class="fa fa-th-list"></i></span> Data Maintenance
    <i class="fa fa-caret-down"></i> <!-- Dropdown symbol --> </a>
                    <div class="dropdown-menu" aria-labelledby="reportDropdown">
                    <a class="dropdown-item" href="index.php?page=courses"><span class='icon-field'><i class="fa fa-th-list"></i></span> Fees</a>
                    <a class="dropdown-item" href="index.php?page=students"><span class='icon-field'><i class="fa fa-th-list"></i></span> Students</a>
                    <a class="dropdown-item" href="index.php?page=disbursement"><span class='icon-field'><i class="fa fa-th-list"></i></span> Disbursement</a>
                    <a class="dropdown-item" href="index.php?page=archive_disbursement"><span class='icon-field'><i class="fa fa-th-list"></i></span> Archived Disbursement</a>
                        <a class="dropdown-item" href="index.php?page=archive_payment"><span class='icon-field'><i class="fa fa-th-list"></i></span> Archived Payments</a>
                    </div>
                </div>
            </div>
            <br>
        

            <!-- Systems -->
            <div class="mx-2 text-white">Systems</div>
            <a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users "></i></span> Users</a>
            <!-- Uncomment the line below if needed -->
            <!-- <a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i class="fa fa-cogs"></i></span> System Settings</a> -->
      
      
            <?php elseif ($_SESSION['login_type'] == 2): ?>
            <!-- Staff user - Show only Home, Payments Report, and Disbursement Report -->
            <div class="mx-2 text-white">Report</div>
            <a href="index.php?page=payments_report" class="nav-item nav-payments_report"><span class='icon-field'><i class="fa fa-th-list"></i></span> Payments Report</a>
            <a href="index.php?page=disbursement_report" class="nav-item nav-disbursement_report"><span class='icon-field'><i class="fa fa-th-list"></i></span> Disbursement Report</a>
        <?php endif; ?>
    </div>
</nav>

<style>
a.dropdown-item {
  margin-bottom: -1px; 
  color: black;
  font-weight: 600;
}
a.dropdown-item:hover, .dropdown-item.active {
  background-color: black;
  color: #fffafa;
}
</style>


<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
