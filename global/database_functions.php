<?php

function insert($table, $data)
{
  global $dbh;
  $columns = array_keys($data);
  $values = array_values($data);

  $str = "INSERT INTO `{$table}` SET ";
  for($i = 0, $ii =count($columns); $i < $ii; $i++)
  {
      $str .= "`{$columns[$i]}` = '{$values{$i}}', ";
  }
  $sql =  rtrim($str, ' ,');
  echo '<p>'.$sql.'</p>';
  $query = mysqli_query($dbh, $sql);
  if ($query && mysqli_affected_rows($dbh)) {
    return true;
  }
  confirmQuery($query);
  return fasle;
}

function getAll($fields=[], $table, $selector='', $value='', $and='')
{
  global $dbh;
  $sql = "SELECT ";
  $fields = (is_array($fields) && !empty($fields)) ? implode(', ', $fields) : '*';
  $and = $and ? 'AND  '. $and : '';
  $selector = ($selector && $value) ? " WHERE  `{$selector}` = {$value}" : '';
  $sql .= $fields . " FROM `{$table}` {$selector}" . $and ;

  $query = mysqli_query($dbh, $sql);
  $count = mysqli_num_rows($query);
  if($query && $count) {
    $data = [];
    while($row = mysqli_fetch_assoc($query)):
      $data[] = $row;
    endwhile;
    return $data;
  }
  return false;
}
