<?php
// include('../includes/dbconnection.php'); // Include your database connection file
// session_start();
include('dashboard.php');
// Check user role
if ($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Fetch users from the database
$sql = "SELECT id, username, role, created_at FROM users";
$result = $con->query($sql);
?>
    <style>
        .container {
            margin-top: 20px;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>

    <div class="container">
        <h2 class="mb-4">User List</h2>
        <a href="userregister.php" class="btn btn-primary mb-3">Register New User</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="useredit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="userdelete.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php include('footer.php'); ?>
