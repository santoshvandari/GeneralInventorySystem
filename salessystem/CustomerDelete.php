<?php
include '../common/base.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Perform deletion
    $sql = "DELETE FROM customers WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);

try{
    $stmt->execute();
    header('Location: CustomerList.php');
}catch(exception $e){
    echo "<script>alert('Failed to Delete');
    window.location.href='CustomerList.php';
    </script>";
    echo "Error: " . $con->error;
}
}

?>
