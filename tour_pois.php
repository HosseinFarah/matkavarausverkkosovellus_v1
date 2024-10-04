<?php
ob_start();
include 'header.php';
if ($loggedIn == 'admin') {
    $loggedIn = secure_page();
    $id = intval($_GET['id']);
    $sql = "DELETE FROM tours WHERE id = $id";
    $sql2 = "DELETE FROM translations WHERE tour_id = $id";
    $request = my_query($sql);
    $request2 = my_query($sql2);
    if ($request) {
        echo "Record deleted successfully";
    } else {
        echo "Failed to delete record";
    }
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit;
} else {
    header("Location: index.php");
    exit;
}
ob_end_flush();