<?php
if (!session_start()) session_start();
if (!isset($_SESSION['admin_login']))
    header("Location:../index.php");
  $title = 'Users';
  require_once 'includes/template/header.php';
  require_once '../global/database_functions.php';
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
                            Mangae <small>Users</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Blank Page
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                <?php

                if (isset($_GET['msg'])):
                  switch($_GET['msg']):
                    case 'update':
                      echo "<div class='alert alert-success'>User Updated successfully</div>";
                    break;

                    case 'delete':
                      echo "<div class='alert alert-success'>User deleted successfully</div>";
                    break;

                    case 'save':
                      echo "<div class='alert alert-success'>User Saved successfully</div>";
                    break;


                  endswitch;
                endif;

                $page = $_GET['page'] ?? '';
                switch ($page) {

                  // Start Insert user page
                  case 'insert':
                  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) :
                    $user = [];
                    $user['username'] = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                    $user['first_name'] = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                    $user['last_name'] = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                    $user['email'] = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
                    $user['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

                    $image = $_FILES['img']['name'];
                    $tmp_name = $_FILES['img']['tmp_name'];
                    $user['image'] = uniqueImageName($image);
                    if(insert('users', $user))
                      header('Location:users.php?msg=save');
                  endif;
                  break;
                  // End Insert user page

                  // Start Delete user Page
                  case 'delete':
                    if ( isset($_GET['page']) && $_GET['page'] == 'delete' && isset($_GET['id']) ) :

                      $id  = $_GET['id'] ?? NULL;
                      if($id) {
                        $sql = "DELETE FROM `users` WHERE `id`='{$id}'";
                        $img =getImage($id, 'users');
                        if($img) {
                          unlink('uploads/images/users/'.$img);
                        }
                        $query = mysqli_query($dbh, $sql);
                        if(!$query)
                            confirmQuery($query);
                        if(mysqli_affected_rows($dbh))
                              header('Location:users.php?msg=delete');
                      }

                    endif;
                  break;
                  // End Delete Post Page

                  // Start Create Post Page--------------------------------------------------------------------------
                  case 'create':
                  ?>
                  <h3>Create User</h3> <hr>
                  <form class="" action="?page=insert" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" name="username" class='form-control' placeholder='Username'>
                    </div>
                    <div class="form-group">
                      <input type="text" name="first_name" class='form-control' placeholder='First name'>
                    </div>
                    <div class="form-group">
                      <input type="text" name="last_name" class='form-control' placeholder='Last name'>
                    </div>
                    <div class="form-group">
                      <input type="text" name="email" class='form-control' placeholder='Email'>
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class='form-control' placeholder='Password'>
                    </div>
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
                  <?php
                    break;
                  // End Create User Page-------------------------------------------------------------

                  // Start Edit User page-----------------------------------------------------------------
                  case 'edit':
                  $id = $_GET['id'] ?? NULL;
                  if($id) {
                    $query = mysqli_query($dbh, "SELECT * FROM `users` WHERE `id` = '{$id}'");
                    if ($query) {
                      $user = mysqli_fetch_assoc($query);

                      if($user) {
                        ?>
                        <h3>Edit User</h3>
                        <form class="" action="?page=update" method="post" enctype="multipart/form-data">
                          <div class="form-group">
                            <input type="text" name="username" class='form-control' placeholder='Enter username'
                            value="<?=$user ? $user['username'] : ''?>">
                          </div>
                          <input type="hidden" name="id" value="<?=$id?>">
                          <div class="form-group">
                            <input type="text" name="first_name" class="form-control" placeholder="Write first name"
                            value="<?= $user ? $user['first_name'] : ''?>">
                          </div>
                          <div class="form-group">
                              <input type="text" name="last_name" class="form-control" placeholder="Write last name"
                                     value="<?= $user ? $user['last_name'] : ''?>">
                          </div>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Enter email"
                                       value="<?= $user ? $user['email'] : ''?>">
                            </div>
                          <div class="form-group">
                              <div class="form-group">
                                  <label for="role">Role</label>
                                  <select name="role"  id="role">
                                      <option value="">Select Role</option>
                                     <?php
                                     foreach(['User'=>0,'Subscriber'=>1,'Admin'=>2] as $key => $value) : ?>
                                         <option value="<?=$value?>" <?= (int)$user['role'] === $value ? 'selected': '' ?> >
                                             <?=$key?>
                                         </option>
                                     <?php endforeach; ?>
                                  </select>
                              </div>
                          </div>

                          <div class="form-group">
                            <img width="200" height="200" src="uploads/images/users/<?=$user['image']?>" alt="<?=$user['username']?>"> <br>
                            <label for="img">Upload Image</label>
                            <input type="file" name="img" id="img" class='form-control'>
                          </div>
                          <input type="submit" name="update" value="Update" class="btn btn-primary">
                        </form>
                        <?php
                      } else  echo "<div class='alert alert-warning'>No user was found!</div>";
                    }
                    confirmQuery($query);
                  }
                  break;
                  // End Edit user Page -----------------------------------------------------------------------------

                  // Start Update user Page --------------------------------------
                  case 'update':
                  if(isset($_POST['update']) && $_SERVER['REQUEST_METHOD']=='POST'):
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
                    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $role = filter_var($_POST['role'], FILTER_SANITIZE_NUMBER_INT);


                    if ((boolean)$_FILES['img']['name'] === true) {
                      $image = $_FILES['img']['name'];
                      $tmp_name = $_FILES['img']['tmp_name'];
                      $new_name = uniqueImageName($image);
                      move_uploaded_file($tmp_name, 'uploads/images/users/'.$new_name);

                      $old_image = getImage($id, 'users');
                      if($old_image) unlink('uploads/images/users/'.$old_image);
                    }
                    $imgSql = (isset($new_name)) ? ",`image`='{$new_name}'" : NULL;
                    $sql = "UPDATE `users` 
                            SET 
                            `username`='{$username}', `first_name`='{$first_name}', `last_name`='{$last_name}',
                            `email`='{$email}', `role`='{$role}'
                            {$imgSql} WHERE `id` = '{$id}'";
                    $query = mysqli_query($dbh, $sql);
                    if($query) {
                      if(mysqli_affected_rows($dbh))
                              header('Location:users.php?msg=update');
                    }
                    confirmQuery($query);

                  endif;
                  break;
                  // End Update user Page ----------------------------------------


                  // Start Default page
                  default:
                  $users = getAll([], 'users', '', '', '');
                  if ($users) {
                    ?>

                    <div class="table-responsive">
                      <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th><a href="?page=create" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create User</a></th>
                        </tr>
                        <tr>
                          <th>Id</th>
                          <th>Username</th>
                          <th>First name</th>
                          <th>Last name</th>
                          <th>Email</th>
                          <th>Image</th>
                          <th>Created at</th>
                          <th>Role</th>
                           <th>Control</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($users as $user): ?>
                          <tr>
                            <td> <?= $user['id']?> </td>
                            <td> <?= $user['username']?> </td>
                            <td> <?= $user['first_name']?> </td>
                            <td> <?= $user['last_name']?> </td>
                            <td> <?= $user['email']?> </td>
                            <td> <img src="uploads/images/users/<?=$user['image']?>" width="150" height="150" alt="<?=$user['username']?>"> </td>
                            <td> <?= $user['created_at']?> </td>
                            <td>
                                <?php
                                switch((int)$user['role']):
                                    case 1:
                                        echo 'Subscriber';
                                        break;
                                    case 2:
                                        echo 'Admin';
                                        break;
                                    default:
                                        echo 'User';
                                endswitch;
                                ?>
                            </td>
                            <td><?=generateControlButton($user)?></td>
                          </tr>
                        <?php endforeach;?>
                      </tbody>
                    <?php
                  } else echo "<div class='alert alert-info'>No users</div>";
                    break;
                  // End default page
                }

                ?>
                  </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    </div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

