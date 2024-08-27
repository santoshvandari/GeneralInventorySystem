<?php
include('dashboard.php');

// Check user role
if ($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}


// Get user ID from the URL
$id = $_GET['id'] ?? '';
$user = [];

// Fetch user data if ID is provided
if ($id) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}else{
    header("Location: userlist.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($password) {
        $password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $sql = "UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi", $username, $password, $role, $id);
    } else {
        $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $id);
    }
    try{
        $stmt->execute();
        echo "<script>alert('User updated successfully!')
        window.location.href='userlist.php'
        ;</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to  update User!')
        window.location.href='userlist.php'
        ;</script>";
    }finally{
        $stmt->close();
    }
}
?>

    <div class="container mt-5">
        <h2 class="mb-4">Edit User</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="salesperson" <?php echo $user['role'] == 'salesperson' ? 'selected' : ''; ?>>Salesperson</option>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>


<?php include('footer.php'); ?>
