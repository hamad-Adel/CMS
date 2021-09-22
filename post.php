<?php
require_once 'includes/db.php';
require_once 'admin/includes/helpers/db_functions.php';

$post = getById('posts', 'id', $_GET['id'], [] , ' AND published_at IS  NULL');
$title = isset($post['title']) ? $post['title'] : 'No Post';
require_once 'includes/template/header.php';
?>

    <!-- Navigation -->
  <?php require_once 'includes/template/navigation.php'; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->
                <?php

                if(!empty($post)):
                  ?>
                  <!-- Title -->
                  <h1> <span class="label label-default"> <?= $post['title']?> </span> </h1>

                  <!-- Author -->
                  <p class="lead">
                      by <a href="#"> <?= $post['author_id'] ?> </a>
                  </p>

                  <hr>

                  <!-- Date/Time -->
                  <p><span class="glyphicon glyphicon-time"></span> Posted on <?= $post['created_at']?> </p>

                  <hr>

                  <!-- Preview Image -->
                  <img class="img-responsive" src="admin/uploads/images/<?=$post['image']?>" alt="">

                  <hr>

                  <!-- Post Content -->
                  <p class="lead"> <?= $post['content']?> </p>

                  <hr>

                  <!-- Blog Comments -->

                  <!-- Comments Form -->
                  <?php
                  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) :
                      $comment = [];
                      $comment['body'] = filter_var($_POST['body'], FILTER_SANITIZE_STRING);
                      $comment['post_id'] = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                      $comment['user_id'] = 12;

                      if (insertComment($comment))
                            echo "<div class='alert alert-success'> Comment saved</div>";

                  endif;
                  ?>
                  <div class="well">
                      <h4>Leave a Comment:</h4>
                      <form role="form" method="post">
                          <div class="form-group">
                              <textarea name="body" class="form-control" rows="3"></textarea>
                          </div>
                          <input type="submit" class="btn btn-primary" name="comment" value="Save">
                      </form>
                  </div>

                  <!-- Posted Comments -->

                  <!-- Comment -->

                    <?php
                    // get the post id
                    $id = $_GET['id'] ?? false;
                    if ($id) {
                      $query = mysqli_query($dbh, "SELECT * FROM `comments` WHERE `post_id` = '{$id}' AND `status` = 1 ORDER BY `id` DESC");
                      echo "<h3 class='text-info'>". mysqli_num_rows($query) .' Comments</h3> ';
                      if($query && mysqli_num_rows($query)) {
                        while($row = mysqli_fetch_assoc($query)):
                          ?>
                          <div class="media">
                              <a class="pull-left" href="#">
                                  <img class="media-object" src="http://placehold.it/64x64" alt="">
                              </a>
                              <div class="media-body">
                                  <h4 class="media-heading"> <?= $row['user_id']?>
                                      <small> <?=$row['created_at']?> </small>
                                  </h4>
                                  <?= $row['body'] ?>
                              </div>
                          </div>
                          <?php
                        endwhile;
                      } else
                          echo "<div class='alert alert-info'>No Comments</div>";
                    }
                    ?>

                  <?php
                    else: echo "<div class='alert alert-info'>No Posts found</div>";
                endif;
                  ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
        <?php require_once 'includes/template/sidebar.php';?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
      <?php require_once 'includes/template/footer.php' ?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>




<!--
<div class="media">
    <a class="pull-left" href="#">
        <img class="media-object" src="http://placehold.it/64x64" alt="">
    </a>
    <div class="media-body">
        <h4 class="media-heading">Start Bootstrap
            <small>August 25, 2014 at 9:30 PM</small>
        </h4>
        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
    </div>
</div>



<div class="media">
    <a class="pull-left" href="#">
        <img class="media-object" src="http://placehold.it/64x64" alt="">
    </a>
    <div class="media-body">
        <h4 class="media-heading">Start Bootstrap
            <small>August 25, 2014 at 9:30 PM</small>
        </h4>
        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.

        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="http://placehold.it/64x64" alt="">
            </a>
            <div class="media-body">
                <h4 class="media-heading">Nested Start Bootstrap
                    <small>August 25, 2014 at 9:30 PM</small>
                </h4>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
        </div>

    </div>
</div>


-->
