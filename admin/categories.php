<?php
!session_start() && session_start();
$title ='Categories';
if (!isset($_SESSION['admin_login']))
    header("Location:../index.php");
require_once 'includes/template/header.php';
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
                            Welcome to admin
                            <small>Hamad</small>
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

                  <div class="col-md-6">
                    <?php
                    // Insert new Category
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert'])):
                      $categoryTitle = filter_var($_POST['category_title'], FILTER_SANITIZE_STRING);
                      echo (addCategory($categoryTitle)) ? "<p class='alert alert-success'>Category saved</p>":'';
                    endif;

                    /**
                     * Start Edit Category
                     * */

                    if (isset($_GET['action']) && $_GET['action']=='edit') {
                      $id = $_GET['id'] ?? null;
                      $query = mysqli_query($dbh, "SELECT * FROM categories WHERE id='{$id}' LIMIT 1");
                      if(mysqli_affected_rows($dbh)) {
                        $title = mysqli_fetch_assoc($query)['title'];
                      }

                      if(isset($_POST['update']) && isset($id)) {
                        $newTitle = filter_var($_POST['new_category_title'], FILTER_SANITIZE_STRING);
                        if (empty($newTitle)) {
                          echo "<div class='alert alert-danger'>Category title is required</div>";
                        } else {
                          $query = mysqli_query($dbh, "UPDATE `categories` SET `title`='{$newTitle}' WHERE `id`='{$id}' LIMIT 1");
                          if(mysqli_affected_rows($dbh))
                                  echo "<div class='alert alert-success'>Category updated successfulyy</div>";
                        }

                      }
                      ?>
                      <form class="" action="" method="post">
                        <div class="form-group">
                          <label for="cat-title">Edit category</label>
                          <input type="text" id='cat-title' class="form-control" name="new_category_title" placeholder="Category Title" value="<?=$title ? $title : ''?>">
                        </div>
                        <input type="submit" name="update" value="Update" class="btn btn-primary">
                      </form>
                      <hr>
                      <?php
                    } /**
                     * End Edit Category
                     */
                    ?>
                    <form class="" action="" method="post">
                      <div class="form-group">
                        <label for="cat-title">Add category</label>
                        <input type="text" id='cat-title' class="form-control" name="category_title" placeholder="Category Title" value="">
                      </div>
                      <input type="submit" name="insert" value="Save" class="btn btn-primary">
                    </form>
                  </div>

                  <div class="col-md-6">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Title</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // delete category if delete btn was pressed
                        if(isset($_GET['action']) && $_GET['action']=='delete') :
                          $id = $_GET['id'] ?? null;
                          deleteCategory($id);
                        endif;
                        // get all categories
                        $cats = getAllCategories();
                        if(!empty($cats)):
                          foreach($cats as $cat):
                            ?>
                            <tr>
                              <td><?=$cat['id']?></td>
                              <td><?=$cat['title']?></td>
                              <td>
                                <a href="?action=edit&id=<?=$cat['id']?>"><i class="fa fa-edit fa-lg"></i></a>
                                <a href="?action=delete&id=<?=$cat['id']?>"><i class="fa fa-trash fa-lg"></i></a>
                              </td>
                            </tr>
                            <?php
                          endforeach;
                          endif;
                        ?>
                      </tbody>
                    </table>
                  </div>

                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
