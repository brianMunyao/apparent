<?php
session_start();
unset($_SESSION['sess_user']);
unset($_SESSION['hh']);
unset($_SESSION['id']);
session_destroy();

header('Location: ../index.php');
