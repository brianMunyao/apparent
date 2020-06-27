<?php
session_start();
$user = $_SESSION['sess_user'];

require('conn.php');

$houseName = $_REQUEST['houseName'];
$phone = $_REQUEST['phone'];
$bedrooms = $_REQUEST['bedrooms'];
$bathrooms = $_REQUEST['bathrooms'];
$location = $_REQUEST['location'];
$totalRooms = $_REQUEST['totalRooms'];
$vacantRooms = $_REQUEST['vacantRooms'];
$houseType = $_REQUEST['houseType'];
$rent = $_REQUEST['rent'];
$desc = $_REQUEST['desc'];



//make propertyID
$types = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E');

$propertyID = $types[$houseType] . $_SESSION['id'];

//add house data
$query = "INSERT INTO property(propertyID,ownerID,houseName,phone,bedrooms,bathrooms,location,totalRooms,vacantRooms,HouseType,rent,datePosted,houseDesc) VALUES('$propertyID','" . $_SESSION['id'] . "','$houseName','$phone','$bedrooms','$bathrooms','$location','$totalRooms','$vacantRooms','$houseType','$rent',CURDATE(),'$desc')";

$result2 = mysqli_query($conn, $query);
if ($result2) {
    $_SESSION['hh'] = true;
    echo ('success');
    header('Location: landlord/main.php');
} else {
    echo ('failure');
}
