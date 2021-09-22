<?php
// Database credentials
const DB_CREDENTIALS = [
  'HOST'=>'localhost',
  'USER'=>'root',
  'PASSWORD'=>'password',
  'DB_NAME'=>'cms_native'
];

// Database connection [$dbh = database handler]
$dbh = mysqli_connect(DB_CREDENTIALS['HOST'], DB_CREDENTIALS['USER'], DB_CREDENTIALS['PASSWORD'], DB_CREDENTIALS['DB_NAME']);
if(!$dbh)
  die('connection Error'. mysqli_connect_error());
