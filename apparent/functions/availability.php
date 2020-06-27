<?php
require('conn.php');
session_start();

$item = $_REQUEST['item'];
$value = $_REQUEST['value'];

$items = array(0 => 'fullname', 1 => 'username', 2 => 'email', 3 => 'password');

if ($item == 0) {
    echo '';
} elseif ($item == 1 || $item == 2) {
    $query = "SELECT $items[$item] FROM users WHERE $items[$item]='" . $value . "'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) != 0) {
        echo $items[$item] . ' already exists';
    } else {
        echo '';
    }

    if ($item == 2) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email.";
        } else {
            echo '';
        }
    }
} elseif ($item == 3) {
    if (strlen($value) < 8) {
        echo "Password too short";
    } else {
        echo '';
    }
}
