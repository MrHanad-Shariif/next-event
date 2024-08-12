<?php
$conn = oci_connect('system', 'admin123', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    die('Could not connect: ' . $e['message']);
}
?>
