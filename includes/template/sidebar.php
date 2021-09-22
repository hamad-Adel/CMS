<div class="col-md-4">

    <!-- Blog Search Well -->
    <div class="well">
        <h4>Blog Search</h4>
        <?php
        if (isset($_POST['submit'])) {
            $searchWord = $_POST['search'];
            $query = mysqli_query($dbh, "SELECT `id`, `title`, `slug` FROM `posts` WHERE `title` LIKE '%$searchWord%'");
            if ($query->num_rows) {
                foreach(mysqli_fetch_all($query, MYSQLI_ASSOC) as $post):
                    var_dump($post['slug']);
                endforeach;
            } else
                echo "<div class='alert alert-info'>No Results matching your search criteria</div>";
        }
        ?>
        <form action="" method="post" id="search">
          <div class="input-group">
              <input type="text" class="form-control" name="search" >
              <span class="input-group-btn">
                  <button class="btn btn-default" name="submit" type="submit">
                      <span class="glyphicon glyphicon-search"></span>
              </button>
              </span>
          </div>
        </form>


        <!-- /.input-group -->
    </div>
<!--  Login form  -->
    <div class="well">
        <?php  if(isset($_SESSION['error'])):
           echo  '<div class="alert alert-warning">' . $_SESSION['error'] .'</div>';
           unset($_SESSION['error']);
        endif;
        ?>
        <h4>Login</h4>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Enter Username">
            </div>
            <div class="input-group">
                <input type="password" name="password" type="password" class="form-control" placeholder="Enter Password">
                <span class="input-group-btn">
                    <button class="btn btn-primary" name="login" type="submit">Login</button>
                </span>
            </div>
        </form>
    </div>
<!--    End Login Form-->

    <!-- Blog Categories Well -->
    <div class="well">
        <h4>Blog Categories</h4>
        <div class="row">
            <div class="col-lg-6">
                <ul class="list-unstyled">
                   <?php
                   if($catgories = getAll(['id', 'title'], 'categories')) :
                    foreach ($catgories as $category):
                        echo "<li><a href='index.php?category_id=${category['id']}'>".$category['title'].'</a></li>';
                    endforeach;
                   endif;
                   ?>
                </ul>
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <div class="well">
        <h4>Side Widget Well</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
    </div>
</div>
