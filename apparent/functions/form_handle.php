<?php
require('conn.php');
session_start();
$hasHouse = false;

//login handler
if (isset($_POST['action']) == 'login') {
    $logStatus = array();

    $user = $_POST['username'];
    $pass = md5($_POST['password']);

    $query = "SELECT id FROM users WHERE username='" . $user . "' and password='" . $pass . "'";
    $result = mysqli_query($conn, $query);

    $numrows = mysqli_num_rows($result);
    if ($numrows == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];

            $_SESSION['sess_user'] = $user;
            $_SESSION['id'] = $id;
        }

        //
        //hasHouse??
        $houseResult = mysqli_query($conn, "SELECT ownerID FROM property WHERE ownerID=" . $id);
        if (mysqli_num_rows($houseResult) == 1) {
            $hasHouse = true;
        } else {
            $hasHouse = false;
        }

        $logStatus[] = array('login' => true, 'username' => $user, 'hasHouse' => $hasHouse);
    } else {
        $logStatus[] = array('login' => false, 'username' => '', 'hasHouse' => false);
    }

    echo json_encode($logStatus);
}

//signup handler
if ($_POST['action'] == 'signup') {
    $err = false;

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $gender = trim($_POST['gender']);

    if (strlen(trim($_POST['password'])) < 8) {
        //err
        $err = true;
    } else {
        $password = md5($_POST['password']);
    }

    //check username availability
    $usernameResult = mysqli_query($conn, "SELECT username FROM users WHERE username='" . $username . "'");
    if (mysqli_num_rows($usernameResult) != 0) {
        //username not available
        $err = true;
    }

    //check email availability
    $emailResult = mysqli_query($conn, "SELECT email FROM users WHERE email='" . $email . "'");
    if (mysqli_num_rows($emailResult) != 0) {
        //email not available
        $err = true;
    }

    if (!$err) {
        $insertQuery = "INSERT INTO users(fullname,username,email,gender,password) VALUES('$fullname','$username','$email','$gender','$password')";

        $insertResult = mysqli_query($conn, $insertQuery);
        if (mysqli_num_rows($insertResult) == 1) {
            $_SESSION['sess_user'] = $username;
            $hasHouse = false;

            $logStatus[] = array('login' => true, 'username' => $username, 'hasHouse' => $hasHouse);
        } else {
            $logStatus[] = array('login' => false, 'username' => '', 'hasHouse' => false);
        }
    }

    echo json_encode($logStatus);
}
