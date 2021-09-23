<?php
function showStatisticsBasedOnTable($table) {
    global $dbh;
    $sql = "SELECT * FROM `{$table}`";
    $result = mysqli_query($dbh, $sql);
    return mysqli_num_rows($result);
}