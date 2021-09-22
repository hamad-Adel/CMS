<?php
if (!function_exists('h')) {
    function h($value) {
        return htmlspecialchars($value, ENT_QUOTES);
    }
}

function generateControlButton($user)
{
    $output = "<a class='btn btn-primary' href='users.php?page=edit&id={$user['id']}'>Edit</a>";
   return $output .= "<a class='btn btn-danger' href='users.php?page=delete&id={$user['id']}'>Delete</a>";
}