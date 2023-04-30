<?php
@session_start();
$file_access = true;
include '../dbconnection.php';
include 'session.php';
include '../constants.php';
if (@$_GET['page'] == 'print' && isset($_GET['print'])) printClearance($_GET['print']);
$fullname =  getUserName($_SESSION['user_id'], $conn);
if (isset($_GET['error'])) {
    echo "<script>alert('Payment could not done yet! or Network Error!'); window.location = 'user.php?page=reg';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?php echo SITE_NAME, ' - Users\'s Account' ?> </title>
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <script src="../js/alpine.js"></script>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar  navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="navbar-nav">
                    <a class="nav-link" href="#"><?php echo SITE_NAME ?></a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-info elevation-4">
            <a href="user.php" class="brand-link">

            <span class="brand-text font-weight-light"><?php echo date("D d, M y"); ?></span>
            </a>
            
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                        <img src="<?php
                                    echo getImage($_SESSION['user_id'], $conn);
                                    ?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $fullname; ?></a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item has-treeview menu-open">
                            <a href="user.php" class="nav-link <?php echo (@$_GET['page'] == '') ? 'active' : '';?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Home
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user.php?page=reg" class="nav-link <?php echo (@$_GET['page'] == 'reg') ? 'active' : '';?>">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>Search Tickets</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user.php?page=paid" class="nav-link <?php echo (@$_GET['page'] == 'paid') ? 'active' : '';?>">
                                <i class="fa fa-book nav-icon"></i>
                                <p>My Bookings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user.php?page=feedback" class="nav-link <?php echo (@$_GET['page'] == 'feedback') ? 'active' : '';?>">
                                <i class="fa fa-mail-bulk nav-icon"></i>
                                <p>Feedbacks</p>
                            </a>
                        </li>
                        <li>
                        <li class="nav-item">
                            <a href="user.php?page=logout" class="nav-link">
                                <i class="nav-icon fas fa-power-off"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"> User's Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>
                <?php
                if (!isset($_GET['page']))
                    include 'user/index.php';
                elseif ($_GET['page'] == 'reg')
                    include 'user/reg.php';
                elseif ($_GET['page'] == 'pay')
                    include 'user/make_payment.php';
                elseif ($_GET['page'] == 'paid')
                    include 'user/paid.php';
                elseif ($_GET['page'] == 'upload')
                    include 'user/upload.php';
                elseif ($_GET['page'] == 'status')
                    include 'user/status.php';
                elseif ($_GET['page'] == 'logout') {
                    @session_destroy();
                    echo "<script>alert('You are logged out'); window.location='../';</script>";
                    exit;
                } elseif ($_GET['page'] == 'print') {
                    printClearance($user_id);
                    include 'user/status.php';
                } else {
                    //Feedback
                    include 'user/feedback.php';
                }
                //TODO:
                ?>
                <!-- /.content -->
        </div>
        <aside class="control-sidebar control-sidebar-dark">

            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                <?php echo $title; ?>
            </div>
            <strong>All Rights Reserved - <?php echo date("Y");?></strong>
        </footer>
    </div>


    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/demo.js"></script>
    <script src="dist/js/pages/dashboard3.js"></script>
    <script>
    $(function() {
        $("#example1").DataTable();
    });
    </script>

</body>
</html>