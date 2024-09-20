<?php
include 'header.php';
if ($loggedIn == 'admin') {
    $loggedIn = secure_page();
    $id = intval($_GET['id']);
    $sql = "DELETE FROM tours WHERE id = $id";
    $request = my_query($sql);
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
