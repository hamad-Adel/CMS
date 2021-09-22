<?php
!session_start() && session_start();
$title = 'Comments';
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
                      echo "<div class='alert alert-success'>Comment Updated successfully</div>";
                    break;

                    case 'delete':
                      echo "<div class='alert alert-success'>Comment deleted successfully</div>";
                    break;

                    case 'save':
                      echo "<div class='alert alert-success'>Comment Saved successfully</div>";
                    break;

                    case 'publish':
                        echo "<div class='alert alert-success'>Comment Published successfully</div>";
                    break;

                    case 'unpublished':
                        echo "<div class='alert alert-success'>Comment unpublished successfully</div>";
                    break;


                  endswitch;
                endif;

                $page = $_GET['page'] ?? '';
                switch ($page) :

                  // Start Insert post page
                  case 'insert':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) :
                      $comment = [];
                      $comment['content'] = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                      $comment['post_id'] = filter_var($_POST['post'], FILTER_SANITIZE_NUMBER_INT);
                      $comment['author_id'] = filter_var($_POST['author'], FILTER_SANITIZE_STRING);
                      if (insertComment($comment))
                            header('Location:comments.php?msg=save');
                    endif;
                  break;
                  // End Insert post page

                  // Start Delete Post Page
                  case 'delete':
                    if ( isset($_GET['page']) && $_GET['page'] == 'delete' && isset($_GET['id']) ) :
                      $id  = $_GET['id'] ?? NULL;
                      if($id && delete('comments', $id))
                              header('Location:comments.php?msg=delete');

                    endif;
                  break;
                  // End Delete Post Page

                  // Start Create Comment Page--------------------------------------------------------------------------
                  case 'create':
                  ?>
                  <h3>Create Comment</h3> <hr>
                  <form class="" action="?page=insert" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <textarea name="comment" rows="8" cols="5" class='form-control' placeholder="Write a comment"></textarea>
                    </div>
                    <div class="form-group">
                      <select class='form-control' name="post">
                        <option value="">Select Post</option>
                        <?php
                        if(!empty(getAllPosts())) :
                          foreach(getAllPosts() as $post)
                            echo "<option value='{$post['id']}'>{$post['title']}</option>";
                        endif;
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <select class='form-control' name="author">
                        <option value="">Select Author</option>
                        <option value="1">Admin</option>
                        <option value="2">Ahmed Hassan</option>
                        <option value="3">Hala Adel</option>
                        <option value="4">Sarah Montassir</option>
                      </select>
                    </div>
<!--                    <div class="form-group">-->
<!--                      <label for="pending">Pending</label>-->
<!--                      <input type="radio" id='pending' name="status" value="0" checked>-->
<!--                      <label for="active">Active</label>-->
<!--                      <input type="radio" id='active' name="status" value="1">-->
<!--                    </div>-->
                    <input type="submit" name="create" value="Create" class="btn btn-primary">
                  </form>
                  <?php
                    break;
                  // End Create Comment Page-------------------------------------------------------------

                  // Start Edit Post page-----------------------------------------------------------------
                  case 'edit':
                  $id = $_GET['id'] ?? NULL;
                  $comment = getCommentById($id);
                      if($comment) {
                        ?>
                        <h3>Edit Comment</h3>
                        <form class="" action="?page=update" method="post" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?=$comment['id']?>">

                          <div class="form-group">
                            <textarea name="comment" rows="6" cols="5" class='form-control' placeholder="Write a comment">
                              <?=trim($comment['content'])?>
                            </textarea>
                          </div>
                          <div class="form-group">
                            <select class='form-control' name="post">
                              <option value="">Select Post</option>
                              <?php
                              foreach(getAllPosts() as $post) :
                              ?> <option value=<?=$post['id']?>  <?=($post['id'] == $comment['post_id']) ? 'selected':''  ?> >
                                  <?=$post['title']?>
                                 </option>";
                              <?php
                              endforeach;
                               ?>
                            </select>
                          </div>

                          <div class="form-group">
                            <select class='form-control' name="author">
                              <option value="">---Select Author---</option>
                              <option value="1">Admin</option>
                              <option value="2" selected>Ahmed Hassan</option>
                              <option value="3">Hala Adel</option>
                              <option value="4">Sarah Montassir</option>
                              <option value="12">user 12</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="pending">Pending</label>
                            <input type="radio" id='pending' name="status" value="0" <?= is_null($comment['published_at']) ? 'checked' : ''?>>
                            <label for="active">Active</label>
                            <input type="radio" id='active' name="status" value="1" <?=  !is_null($comment['published_at'])  ? 'checked' : ''?> >
                          </div>

                          <input type="submit" name="update" value="Update" class="btn btn-primary">
                        </form>
                        <?php
                      } else  echo "<div class='alert alert-warning'>No post was found!</div>";
                  break;
                  // End Edit Post Page -----------------------------------------------------------------------------

                  // Start Update Post Page --------------------------------------
                  case 'update':
                    if(isset($_POST['update']) && $_SERVER['REQUEST_METHOD']=='POST'):
                      $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                      $comment = [];
                      $comment['content'] = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                      $comment['post_id'] = filter_var($_POST['post'], FILTER_SANITIZE_NUMBER_INT);
                      $comment['author_id'] = filter_var($_POST['author'], FILTER_SANITIZE_STRING);


                      if (UpdateComment($id , $comment));
                                header('Location:comments.php?msg=update');
                    endif;
                  break;
                  // End Update Post Page ----------------------------------------


                  // unpublished page
                case 'unpublished':
                    if(isset($_GET['page']) && $_GET['page'] === 'unpublished'):
                        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                         UpdateComment($id, ['published_at'=> NULL]) &&   header('Location:comments.php?msg=unpublished');


                    endif;
                  // end unpublished page


                 // Start publish page
                case 'publish':
                    if(isset($_GET['page']) && $_GET['page'] === 'publish'):
                        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                        UpdateComment($id, ['published_at'=> date("Y-m-d H:i:s")]) &&   header('Location:comments.php?msg=publish');
                    endif;
                 // End publish page


                  // Start Defualt page
                  default:
                  if (getAllComments()) {
                    ?>
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover">
                        <tr>
                          <th><a href="?page=create" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create Comment</a></th>
                        </tr>
                        <tr>
                          <th>Id</th>
                          <th>Post</th>
                          <th>User</th>
                          <th>status</th>
                          <th>Created at</th>
                        </tr>
                        <tbody>
                          <?php
                          foreach(getAllComments() as $comment):
                            echo '<tr>';
                              echo '<td>'.$comment['id'].'</td>';
                              echo '<td>'.$comment['post_id'].'</td>';
                              echo '<td>'.$comment['author_id'].'</td>';
                              echo $comment['published_at'] ? "<td><span class='label label-success'>Published at {$comment['published_at']}</span></td>" : "<td><span class='label label-danger'>Pending</span></td>";
                              echo '<td>'.$comment['created_at'].'</td>';
                              echo "<td><a class='btn btn-info btn-xs' href='?page=edit&id={$comment['id']}'> <i class='fa fa-edit fa-lg'></i> </a></td>";
                              if ($comment['published_at']) :
                                  echo "<td><a class='btn btn-warning btn-xs' href='?page=unpublished&id={$comment['id']}'> <i class='fa fa-thumbs-o-down'></i> </a></td>";
                              else:
                                  echo "<td><a class='btn btn-success btn-xs' href='?page=publish&id={$comment['id']}'> <i class='fa fa-thumbs-o-up'></i> </a></td>";
                              endif;
                              echo "<td><a class='btn btn-danger btn-xs' href='?page=delete&id={$comment['id']}'> <i class='fa fa-trash'></i> </a></td>";
                            echo '</tr>';
                          endforeach;
                          ?>
                        </tbody>
                    <?php

                  } else echo "<div class='alert alert-info'>
                                  No comments found
                                  <a class='btn btn-default btn-sm' href='?page=create'>Create comment</a>
                              </div>";

                 // End default page
                   break;
                 endswitch;
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
