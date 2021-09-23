<?php
    !session_start() && session_start();
    $title = 'Profile - '. $_SESSION['username'];
    if (!isset($_SESSION['admin_login']))
        header("Location:../index.php");
    require_once 'includes/template/header.php';
    require_once '../global/database_functions.php';
    $sql = "SELECT * FROM `users` WHERE id = {$_SESSION['id']} AND `role` > 0";
    $result = mysqli_query($dbh, $sql);
    if ($result->num_rows):
        $user = mysqli_fetch_assoc($result);
    endif;
?>
<div id="wrapper">
    <!-- Navigation -->
    <?php require_once 'includes/template/navigation.php';?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?=$_SESSION['username']?> <small>Profile</small>
                    </h1>
<!--                    <ol class="breadcrumb">-->
<!--                        <li>-->
<!--                            <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>-->
<!--                        </li>-->
<!--                        <li class="active">-->
<!--                            <i class="fa fa-file"></i> Blank Page-->
<!--                        </li>-->
<!--                    </ol>-->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <form class="" action="?page=insert" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" name="username" class='form-control' placeholder='Username'
                                value="<?=$user['username']?>">
                            </div>
                            <div class="form-group">
                                <input type="text" name="first_name" class='form-control' placeholder='First name'
                                value="<?=$user['first_name']?>">
                            </div>
                            <div class="form-group">
                                <input type="text" name="last_name" class='form-control' placeholder='Last name'
                                value="<?=$user['last_name']?>">
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" class='form-control' placeholder='Email'
                                value="<?=$user['email']?>">
                            </div>
<!--                            <div class="form-group">-->
<!--                                <input type="password" name="password" class='form-control' placeholder='Password'>-->
<!--                            </div>-->
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role"  id="role">
                                    <option value="">Select Role</option>
                                    <option value="0">User</option>
                                    <option value="1">Subscriber</option>
                                    <option value="2">Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="img">Upload Image</label>
                                <input type="file" name="img" id="img" class='form-control'>
                            </div>
                            <input type="submit" name="create" value="Create" class="btn btn-primary">
                        </form>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
</div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    </body>

    </html>