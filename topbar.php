<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-dark topbar mb-20 static-top shadow">

            <div class="holy" style="color:white">
                <h5><b>HOLY INFANT ACADEMY</b></h5>
            </div>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                        aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                    placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                <div class=" dropdown">
                    <a href="#" class="text-white dropdown-toggle" id="account_settings" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['login_name'] ?> <img
                            class="img-profile rounded-circle" style="width:20px;" src="assets/img/adminnn.png"></a></a>
                    <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -7.9em;">
                        <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i
                                class="fa fa-cog"></i> Manage Account</a>
                        <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i>
                            Logout</a>
                    </div>
                </div>
    </div>
</div>

</nav>
<style>
    .holy{
        padding-left: 200px;
    }
</style>
<script>
$('#manage_my_account').click(function() {
    uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
})
</script>