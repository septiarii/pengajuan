<?php
session_start();
require("../user/Konfig.php");  // Include database connection

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: halaman.php");  // Redirect to home page if already logged in
    exit();
}

// Handle form submission for login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Sanitize user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user data from tb_admin table based on the provided username
    $sql = "SELECT * FROM tb_admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['alamat'] = $user['alamat'];
            $_SESSION['no_tlp'] = $user['no_tlp'];

            // Redirect to home page
            header("Location: halaman.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Username not found.";
    }
}

// Handle form submission for registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Sanitize user input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the tb_admin table
        $sql = "INSERT INTO tb_admin (username, password, nama, email, alamat, no_tlp) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $username, $hashed_password, $nama, $email, $alamat, $no_tlp);

        if ($stmt->execute()) {
            $success_message = "Registration successful. You can now log in.";
        } else {
            $error_message = "Error registering user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        /* Apply overall background color and text styles */
        body {
            background-color: #f4f6f9; /* Light grey background for a clean look */
            background-image: url('../uploads/image copy 7.png'); /* Add your image URL here */
            background-size: cover; /* Ensures the image covers the entire screen */
            background-position: center; /* Centers the image */
            color: #333; /* Dark text color for readability */
            font-family: 'Arial', sans-serif;
            padding-top: 50px; /* Add some spacing from the top */
        }

        /* Center align the main container */
        .container {
            max-width: 500px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for a more elegant appearance */
            margin: 0 auto;
            border: 1px solid #ddd; /* Light border for a neat look */
        }

        /* Header style */
        h2 {
            color:rgb(1, 10, 20); /* Soft blue for the header */
            font-weight: bold;
        }

        /* Form input styles */
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #4A90E2; /* Blue border when focused */
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.5); /* Light blue glow */
        }

        /* Button styles */
        .btn-primary, .btn-success {
            background-color: #4A90E2; /* Soft blue button */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }

        .btn-primary:hover, .btn-success:hover {
            background-color: #357ABD; /* Darker blue on hover */
        }

        /* Alert messages (success, error) */
        .alert {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .alert-success {
            background-color: #D4EDDA;
            color: #155724;
        }

        /* Modal styles */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .modal-body {
            background-color: #f4f6f9;
        }

        /* Links inside modal */
        a.btn-link {
            color: #4A90E2;
            text-decoration: none;
        }

        a.btn-link:hover {
            text-decoration: underline;
        }

        /* Add padding and margins where necessary */
        .mt-5 {
            margin-top: 5rem;
        }

        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        /* For smaller screens */
        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>
        <?php if (isset($success_message)) { ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="text-center">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="#registerModal" data-bs-toggle="modal" class="btn btn-link">Register Di Sini</a>
        </div>
    </div>

    <!-- Modal for Registration -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Address</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_tlp" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="no_tlp" name="no_tlp" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="register" class="btn btn-success">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close();
?>
