<?php
include('base.php');
// Check user role
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get user ID from the URL
$id = $_GET['id'] ?? '';

if ($id) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    try {
        $stmt->execute();
        echo "<script>alert('User deleted successfully!')
        window.location.href='userlist.php'
        ;</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to  deleted User!')
        window.location.href='userlist.php'
        ;</script>";
    }


    $stmt->close();
} else {
    echo "<script>alert('Failed to  deleted User!')
    window.location.href='userlist.php'
    ;</script>";
}
?>
