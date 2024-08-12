<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $query = "DELETE FROM Events WHERE event_id = :event_id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':event_id', $event_id);

    if (oci_execute($stmt)) {
        $_SESSION['message'] = "<div class='alert alert-success'>Event deleted successfully.</div>";
    } else {
        $e = oci_error($stmt);
        $_SESSION['message'] = "<div class='alert alert-danger'>Error: " . htmlspecialchars($e['message']) . "</div>";
    }

    oci_free_statement($stmt);
    oci_close($conn);

    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
