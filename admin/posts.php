<?php
!session_start() && session_start();
$title = 'Posts';
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
                            Blank Page
                            <small>Subheading</small>
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
                      echo "<div class='alert alert-success'>Post Updated successfully</div>";
                    break;

                    case 'delete':
                      echo "<div class='alert alert-success'>Post deleted successfully</div>";
                    break;

                    case 'save':
                      echo "<div class='alert alert-success'>Post Saved successfully</div>";
                    break;


                  endswitch;
                endif;

                $page = $_GET['page'] ?? '';
                switch ($page) {

                  // Start Insert post page
                  case 'insert':
                  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) :
                    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                    $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
                    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
                    $author = filter_var($_POST['author'], FILTER_SANITIZE_NUMBER_INT);
                    $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
                    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

                    $image = $_FILES['img']['name'];
                    $tmp_name = $_FILES['img']['tmp_name'];
                    $new_name = uniqueImageName($image);
                    move_uploaded_file($tmp_name, 'uploads/images/'.$new_name);

                    $slug = str_replace(' ','-',$title);
                    $sql = "INSERT INTO `posts` SET 
                    `category_id`='{$category}', `title`='{$title}',`slug` = '{$slug}',
                    `author_id`='{$author}', `content`='{$content}'";
                    $query = mysqli_query($dbh, $sql);
                    if(!$query)
                        confirmQuery($query);
                    header('Location:posts.php?msg=save');
                  endif;
                  break;
                  // End Insert post page

                  // Start Delete Post Page
                  case 'delete':
                    if ( isset($_GET['page']) && $_GET['page'] == 'delete' && isset($_GET['id']) ) :
                      $id  = $_GET['id'] ?? NULL;
                      if($id) {
                        $sql = "DELETE FROM `posts` WHERE `id`='{$id}'";
                        $img =getPostImage($id);
                        if($img) {
                          unlink('uploads/images/'.$img);
                        }
                        $query = mysqli_query($dbh, $sql);
                        if(!$query)
                            confirmQuery($query);
                        if(mysqli_affected_rows($dbh))
                              header('Location:posts.php?msg=delete');
                      }

                    endif;
                  break;
                  // End Delete Post Page

                  // Start Create Post Page--------------------------------------------------------------------------
                  case 'create':
                  ?>
                  <h3>Create Post</h3> <hr>
                  <form class="" action="?page=insert" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" name="title" class='form-control' placeholder='Enter post title'>
                    </div>
                    <div class="form-group">
                      <select class='form-control' name="category">
                        <option value="">Select Category</option>

                        <?php
                        if(!empty(getAllCategories())) :
                          foreach(getAllCategories() as $cat):
                            echo "<option value='{$cat['id']}'>{$cat['title']}</option>";
                          endforeach;
                        endif;
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="text" name="tags" class="form-control" placeholder="Write post tags">
                    </div>
                    <div class="form-group">
                      <select class='form-control' name="author">
                        <option value="">Author</option>
                        <option value="1">Admin</option>
                        <option value="2">Ahmed Hassan</option>
                        <option value="3">Hala Adel</option>
                        <option value="4">Sarah Montassir</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="pending">Pending</label>
                      <input type="radio" id='pending' name="status" value="0" checked>
                      <label for="active">Active</label>
                      <input type="radio" id='active' name="status" value="1">
                    </div>
                    <div class="form-group">
                      <textarea name="content" rows="8" cols="5" class='form-control' placeholder="Post Content"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="img">Upload Image</label>
                      <input type="file" name="img" id="img" class='form-control'>
                    </div>
                    <input type="submit" name="create" value="Create" class="btn btn-primary">
                  </form>
                  <?php
                    break;
                  // End Create Post Page-------------------------------------------------------------

                  // Start Edit Post page-----------------------------------------------------------------
                  case 'edit':
                  $id = $_GET['id'] ?? NULL;
                  if($id) {
                    $query = mysqli_query($dbh, "SELECT * FROM `posts` WHERE `id` = '{$id}'");
                    if ($query) {
                      $post = mysqli_fetch_assoc($query);

                      if($post) {
                        ?>
                        <h3>Edit Post</h3>
                        <form class="" action="?page=update" method="post" enctype="multipart/form-data">
                          <div class="form-group">
                            <input type="text" name="title" class='form-control' placeholder='Enter post title'
                            value="<?=$post ? $post['title'] : ''?>">
                          </div>
                          <input type="hidden" name="id" value="<?=$id?>">
                          <div class="form-group">
                            <select class='form-control' name="category">
                              <option value="">Select Category</option>
                              <?php
                              if(!empty(getAllCategories())) :
                                foreach(getAllCategories() as $cat):
                                  ?>
                                  <option value=<?=$cat['id']?> <?=$cat['id'] == $post['category_id']?'selected':''?> > <?=$cat['title']?></option>
                                  <?php
                                endforeach;
                              endif;
                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <select class='form-control' name="author">
                              <option value="">Author</option>
                              <option value="1">Admin</option>
                              <option value="2" selected>Ahmed Hassan</option>
                              <option value="3">Hala Adel</option>
                              <option value="4">Sarah Montassir</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="pending">Pending</label>
                            <input type="radio" id='pending' name="status" value="0">
                            <label for="active">Active</label>
                            <input type="radio" id='active' name="status" value="1" >
                          </div>
                          <div class="form-group">
                            <textarea name="content" rows="8" cols="5" class='form-control' placeholder="Post Content">
                              <?=$post ? $post['content'] : ''?>
                            </textarea>
                          </div>
                          <div class="form-group">
                            <img width="200" height="200" src="uploads/images/1630883476c7434603e46b074c2d9e4405307470c8Sql Images.jpg" alt="<?=$post['title']?>"> <br>
                            <label for="img">Upload Image</label>
                            <input type="file" name="img" id="img" class='form-control'>
                          </div>
                          <input type="submit" name="update" value="Update" class="btn btn-primary">
                        </form>
                        <?php
                      } else  echo "<div class='alert alert-warning'>No post was found!</div>";
                    }
                    confirmQuery($query);
                  }
                  break;
                  // End Edit Post Page -----------------------------------------------------------------------------

                  // Start Update Post Page --------------------------------------
                  case 'update':
                  if(isset($_POST['update']) && $_SERVER['REQUEST_METHOD']=='POST'):
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                    $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
                    $author = filter_var($_POST['author'], FILTER_SANITIZE_NUMBER_INT);
                    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);


                    if ((boolean)$_FILES['img']['name'] === true) {
                      $image = $_FILES['img']['name'];
                      $tmp_name = $_FILES['img']['tmp_name'];
                      $new_name = uniqueImageName($image);
                      move_uploaded_file($tmp_name, 'uploads/images/'.$new_name);

                      $old_image = getPostImage($id);
                      if($old_image) {unlink('uploads/images/'.$old_image);}
                    }
                    $imgSql = (isset($new_name)) ? ",`image`='{$new_name}'" : NULL;
                    $slug = str_replace(' ', '-', $title);
                    $sql = "UPDATE `posts` SET 
                            `title`='{$title}',`slug`='{$slug}', `category_id`='{$category}',
                            `author_id`='{$author}', `content`='{$content}' 
                            WHERE `id` = '{$id}'";
                    $query = mysqli_query($dbh, $sql);
                    var_dump($query);
                    if($query) {
                      if(mysqli_affected_rows($dbh))
                              header('Location:posts.php?msg=update');
                    }
                    confirmQuery($query);

                  endif;
                  break;
                  // End Update Post Page ----------------------------------------


                  // Start Defualt page
                  default:
                    ?>
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover">
                        <tr>
                          <th><a href="?page=create" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create Post</a></th>
                        </tr>
                        <tr>
                          <th>Id</th>
                          <th>Category</th>
                          <th>Title</th>
                          <th>Author</th>
                          <th>Created at</th>
                        </tr>
                        <tbody>
                          <?php
                          $query = mysqli_query($dbh, 'SELECT * FROM `posts`');
                          if($query) {
                            while($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                              <td><?=h($row['id'])?></td>
                              <td><?=h($row['category_id'])?></td>
                              <td><?=h($row['title'])?></td>
                              <td><?=h($row['author_id'])?></td>
                              <td><?=h($row['created_at'])?></td>
<!--                                --><?php
//                                foreach(explode(',', $row['tags']) as $tag):
//                                  echo "<label class='label label-info'>{$tag}</label>&nbsp;";
//                                endforeach;
//                                ?>
                              <td>
                                <a class="btn btn-default btn-sm" href="../post.php?id=<?=h($row['id'])?>"><i class="fa fa-eye fa-lg"></i></a>
                              </td>
                              <td>
                                <a class="btn btn-info btn-sm" href="?page=edit&id=<?=h($row['id'])?>"><i class="fa fa-edit fa-lg"></i> </a>
                              </td>
                              <td>
                                <a class="btn btn-danger btn-sm" href="?page=delete&id=<?=h($row['id'])?>"><i class="fa fa-trash fa-lg"></i> </a>
                              </td>
                            </tr>
                            <?php
                          endwhile;
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <?php
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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

