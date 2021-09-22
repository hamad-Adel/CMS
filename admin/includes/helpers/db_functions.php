<?php
// Insert new category
function addCategory($categoryTitle)
{
  global $dbh;
  if (empty($categoryTitle)) {
        echo "<div class='alert alert-danger'>Category title is required</div>";
        return false;
  } else {
    $sql = "INSERT INTO categories set title='{$categoryTitle}'";
    $query = mysqli_query($dbh, $sql);
    if($query):
        if(mysqli_affected_rows($dbh))
              return true;
        return false;
    endif;
  }
}

// Delete category
function deleteCategory($id)
{
  global $dbh;
  if ($id) {
    $query = mysqli_query($dbh, "DELETE FROM `categories` WHERE `id`='$id'");
    echo '<p>'."DELETE FROM `categories` WHERE `id`='$id'".'</p>';
    var_dump(mysqli_affected_rows($dbh));
    exit;
    if(mysqli_affected_rows($dbh))
          header('Location:categories.php');
  }
}

function getAllCategories()
{
  global $dbh;
  $query = mysqli_query($dbh, 'SELECT * FROM `categories`');
  if ($query) {
    if (!mysqli_num_rows($query))
          echo "<div class='alert alert-info'>No categories to list !</div>";
    $cats = [];
    while($row = mysqli_fetch_assoc($query)):
      $cats[] = $row;
    endwhile;
    return $cats;
  }
  confirmQuery($query);
}


function confirmQuery($query) {
  global $dbh;
  if(!$query) {
    die('Error: ' . mysqli_error($dbh));
  }
}


function getImage($id, $from)
{
  global $dbh;
  $query = mysqli_query($dbh, "SELECT `image` from `{$from}` WHERE `id`='{$id}'");
  if($query) {
    if(mysqli_affected_rows($dbh))
        return mysqli_fetch_assoc($query)['image'];
    return false;
  }
  return false;
}


function getAllPosts()
{
  global $dbh;
  $query = mysqli_query($dbh, 'SELECT * FROM `posts`');
  if($query && mysqli_affected_rows($dbh)) {
    $data = [];
    while($row = mysqli_fetch_assoc($query)):
      $data[] = $row;
    endwhile;
    return $data;
  }
  return false;
}


function getAllComments()
{
  global $dbh;
  $query = mysqli_query($dbh, 'SELECT * FROM `comments`');
  if($query && mysqli_affected_rows($dbh)) {
    $data = [];
    while($row = mysqli_fetch_assoc($query)):
      $data[] = $row;
    endwhile;
    return $data;
  }
  return false;
}


// [
//   'name'=>'hamad',
//   'age'=>25,
//   'job'=>'web developer'
// ];

function insertComment($data)
{
  global $dbh;
  $columns = array_keys($data);
  $values = array_values($data);

  $str = 'INSERT INTO `comments` SET ';
  for($i = 0, $ii =count($columns); $i < $ii; $i++)
  {
      $str .= "`{$columns[$i]}` = '{$values{$i}}', ";
  }
  $sql =  rtrim($str, ' ,');

  $query = mysqli_query($dbh, $sql);
  if ($query && mysqli_affected_rows($dbh)) {
    return true;
  }
  confirmQuery($query);
  return fasle;
}


function getCommentById($id)
{
  global $dbh;
  $query = mysqli_query($dbh, "SELECT * FROM `comments` WHERE `id`={$id}");
  if($query && mysqli_num_rows($query))
    return mysqli_fetch_assoc($query);

  return false;
}

function UpdateComment($id, $data)
{
  global $dbh;
  $columns = array_keys($data);
  $values = array_values($data);
  $str = 'UPDATE `comments` SET ';
  for($i = 0, $ii =count($columns); $i < $ii; $i++)
  {
      $str .= "`{$columns[$i]}` = ";
      if (gettype($values[$i]) === 'string') {
          $str .= "'{$values[$i]}',";
      } else if(gettype($values[$i]) === 'NULL' ) {
          $str .= gettype($values[$i]) . ',';
      }
      else {
          $str .= $values[$i].',' ;
      }
  }
  // $sql .= rtrim($sql, ',');
  $sql =  rtrim($str, ',');
  $sql .= " WHERE `id` = '{$id}'";
  echo '<p>'.$sql.'</p>';
  $query = (mysqli_query($dbh, $sql));
  if($query && mysqli_affected_rows($dbh))
      return true;

  confirmQuery($query);
  return false;
}


function delete($table, $id)
{
  global $dbh;
  $query = mysqli_query($dbh, "DELETE FROM `{$table}` WHERE `id`={$id} LIMIT 1") ;
  if($query && mysqli_affected_rows($dbh) )
        return true;
  confirmQuery($query);
  return false;
}


function getById($table, $selector, $value, $fields='', $and='')
{
  global $dbh;
  $sql = "SELECT ";
  $fields = (is_array($fields) && !empty($fields)) ? implode(', ', $fields) : '*';
  $and = $and ? $and : '';
  $sql .= $fields . " FROM `{$table}` WHERE `{$selector}`= '{$value}' " . $and ;

  $query = mysqli_query($dbh, $sql);
  $count = mysqli_num_rows($query);

  if($query && $count) {
    $data = [];
    while($row = mysqli_fetch_assoc($query)):
      $data[] = $row;
    endwhile;

    return $count == 1 ? array_shift($data) : $data;
 }

  return [];
}


function getCount($table, $selector, $value)
{
  global $dbh;
  $query = mysqli_query($dbh, "SELECT count(id) FROM `{$table}` WHERE `{$selector}` = {$value}");
  $row = mysqli_fetch_array($query);
  return $row[0];
}

/*
 * mysqli_num_rows()
 -------------------
 this command is only valid for statements like SELECT or SHOW that return an actual result set

 * mysql_affected_rows()
 -----------------------
 To retrieve the number of rows affected by a INSERT, UPDATE, REPLACE or DELETE query
*/
