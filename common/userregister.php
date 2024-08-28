<?php
include('base.php'); // Include your database connection file
session_start(); // Ensure session management
// Check user role
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role = $_POST['role'];

    // Insert the user into the database
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);

    try{
        $stmt->execute();
       echo "<script>alert('User registered successfully!')
        window.location.href='userlist.php'
        ;</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to  register User!')
        window.location.href='userlist.php'
        ;</script>";
    }
    $stmt->close();
}
?>

  <style>
        body {
            background-color: #f8f9fa;
        }
        .registration-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .alert-info {
            margin-top: 20px;
        }
    </style>
    <div class="container mt-5">
        <div class="registration-container">
            <h2 class="text-center mb-4">User Registration</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="salesperson">Salesperson</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
<?php include('footer.php'); ?>