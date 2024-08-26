<?php
include '../includes/dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssi', $name, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        header("Location: CustomerList.php");
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM customers WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <h1>Edit Customer</h1>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $customer['name']; ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $customer['email']; ?>" required>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $customer['phone']; ?>">
        <label for="address">Address:</label>
        <textarea id="address" name="address"><?php echo $customer['address']; ?></textarea>
        <button type="submit">Save</button>
    </form>
</body>
</html>
