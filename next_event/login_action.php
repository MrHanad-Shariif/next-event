<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT user_id, username FROM Users WHERE username = :username AND password = :password";
$stmt = oci_parse($conn, $sql);

oci_bind_by_name($stmt, ':username', $username);
oci_bind_by_name($stmt, ':password', $password);

oci_execute($stmt);

$row = oci_fetch_assoc($stmt);
if ($row) {
    $_SESSION['user_id'] = $row['USER_ID'];
    $_SESSION['username'] = $row['USERNAME'];
    header("Location: dashboard.php");
} else {
    echo "Invalid username or password.";
}

oci_free_statement($stmt);
oci_close($conn);
?>
