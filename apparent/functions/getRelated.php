<?php
session_start();
require("conn.php");

$houses = array();

$housename = $_REQUEST['hName'];
$location = $_REQUEST['location'];
$hType = $_REQUEST['type'];
$houseTypesReverse = array('Bedsitter' => 0, 'Single Room' => 1, '2 Bedroom' => 2, '3 Bedroom' => 3, '4 Bedroom' => 4);

$houseTypes = array(0 => 'Bedsitter', 1 => 'Single Room', 2 => '2 Bedroom', 3 => '3 Bedroom', 4 => '4 Bedroom');
$query = "SELECT phone,houseName,houseDesc,location,houseType,bedrooms,bathrooms,rent,totalRooms,vacantRooms,datePosted,img1,img2,img3,img4 FROM property WHERE NOT houseName='$housename' AND (location='$location' OR houseType='$houseTypesReverse[$hType]')";


$result = mysqli_query($conn, $query);
$numrows = mysqli_num_rows($result);

if ($numrows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $hName = $row['houseName'];
        $hDesc = $row['houseDesc'];
        $phone = $row['phone'];
        $loc = $row['location'];
        $hType = $row['houseType'];
        $bedrooms = $row['bedrooms'];
        $bathrooms = $row['bathrooms'];
        $rent = $row['rent'];
        $tRooms = $row['totalRooms'];
        $vRooms = $row['vacantRooms'];
        $date = $row['datePosted'];
        $img1 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img1']) . '" alt=""/>';
        $img2 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img2']) . '" alt=""/>';
        $img3 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img3']) . '" alt=""/>';
        $img4 = '<img src="data:image/jpeg;base64,' . base64_encode($row['img4']) . '" alt=""/>';


        array_push($houses, ['hName' => $hName, 'hDesc' => $hDesc, 'phone' => $phone, 'loc' => $loc, 'hType' => $hType, 'bedR' => $bedrooms, 'bathR' => $bathrooms, 'rent' => $rent, 'tR' => $tRooms, 'vR' => $vRooms, 'img1' => $img1, 'img2' => $img2, 'img3' => $img3, 'img4' => $img4]);
    }
}
echo json_encode($houses);
