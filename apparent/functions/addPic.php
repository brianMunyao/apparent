<?php
require('conn.php');
session_start();

$file = addslashes(file_get_contents($_FILES['img']['tmp_name']));
$query = "UPDATE property SET img1='$file' WHERE ownerID=" . $_SESSION['id'];
$result = mysqli_query($conn, $query);

if (mysqli_affected_rows($conn) == 1) {
    echo 1;
} else {
    echo 0;
}
