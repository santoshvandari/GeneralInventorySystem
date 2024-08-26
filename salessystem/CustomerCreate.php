<?php
include '../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssss', $name, $email, $phone, $address);

    if ($stmt->execute()) {
        header("Location: CustomerList.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Customer</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <h1>Create Customer</h1>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">
        <label for="address">Address:</label>
        <textarea id="address" name="address"></textarea>
        <button type="submit">Save</button>
    </form>
</body>
</html>
