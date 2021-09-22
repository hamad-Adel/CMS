<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])):
  require_once '../includes/db.php';
  $search =  filter_var($_POST['search'], FILTER_SANITIZE_STRING);
  $sql = "SELECT * FROM posts WHERE  tags LIKE '%{$search}%'";
  $query = mysqli_query($dbh, $sql);
  if (!$query)
      die('Query Error ' . mysqli_error($dbh));

  $count = mysqli_num_rows($query);
  if (!$count)
      echo "<div class='alert alert-warning'>No Result with {$search} </div>";


if($count) {
  echo "<div class='alert alert-info'>";
  while($row = mysqli_fetch_assoc($query)):
    echo "<a href='localhost/cms/{$row['title']}/{$row['id']}'>{$row['title']}</a> <br>";
  endwhile;
  echo '</div>';
}
mysqli_free_result($query);
endif;
