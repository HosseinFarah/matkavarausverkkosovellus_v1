<?php
ob_start();
include 'header.php';
if ($loggedIn == 'admin') {
    $loggedIn = secure_page();
    $id = intval($_GET['id']);
    $sql = "DELETE FROM reservations WHERE id = $id";
    $request = my_query($sql);
    if ($request) {
        echo "Record deleted successfully";
    } else {
        echo "Failed to delete record";
    }
    // redirect back the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit;
} else {
    header("Location: index.php");
    exit;
}
ob_end_flush();