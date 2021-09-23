<!-- Start header  -->
<?php
    !session_start() && session_start();
    isset($_SESSION['admin_login']) && header("Location: admin/");
    $title = 'Latest Posts';
    require_once 'includes/template/header.php'
?>

<?php require_once 'includes/db.php';?>
<!-- End header -->
    <!-- Navigation -->
    <?php require_once 'includes/template/navigation.php'; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    Latest posts
                    <small></small>
                </h1>

                <?php
                // get latest 10 Posts
                $sql = 'SELECT * FROM `posts` WHERE `published_at` IS NOT NULL';
                if (isset($_GET['category_id'])) {
                    $sql .= ' AND `Category_id` = ' . $_GET['category_id'];
                }
                $sql .= ' LIMIT 10';
                $query = mysqli_query($dbh, $sql);
                if($query) :
                  if(mysqli_affected_rows($dbh)) {
                    $posts = [];
                    while($row = mysqli_fetch_assoc($query)):
                      $posts[] = $row;
                    endwhile;
                  }
                endif;
                if(empty($posts))
                      echo "<div class='alert alert-info'>No posts</div>";
              else {
                foreach($posts as $post):
                  ?>
                  <!-- Start Posts -->
                  <h2>
                      <a href="post.php?id=<?=$post['id']?>"><?=$post['title']?></a>
                  </h2>
                  <p class="lead">
                      by <a href="index.php"> <?=$post['author_id']?> </a>
                  </p>
                  <p><span class="glyphicon glyphicon-time"></span> Posted on <?=$post['created_at']?> at 10:00 PM</p>
                  <img class="img-responsive" src="admin/uploads/images/1630883476c7434603e46b074c2d9e4405307470c8Sql Images.jpg" alt="<?=$post['title']?>">
                  <hr>
                  <p> <?=miniString($post['content'], 150)?> </p>
                  <a class="btn btn-primary btn-sm" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                  <hr>
                  <!-- End Posts -->
                <?php endforeach;  } ?>

                <!-- Pager -->
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Older</a>
                    </li>
                    <li class="next">
                        <a href="#">Newer &rarr;</a>
                    </li>
                </ul>

            </div>

            <!-- Blog Sidebar Widgets Column -->
          <?php require_once 'includes/template/sidebar.php' ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
      <?php require_once 'includes/template/footer.php';?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!--Ajax search-->
    <script src="js/ajaxSearch.js"></script>

</body>

</html>
