<?php
session_start();
include('koneksi.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password (gunakan md5 sesuai database Anda)
        if (md5($password) === $user['password']) {
            // Login berhasil
            $_SESSION['users'] = $user;
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sistem Monitoring Aset & Arsip Dokumen</title>

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #28a745 50%, #6c757d 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px 40px;
            background-color: #f8f9facc; /* abu-abu terang transparan */
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            text-align: center;
        }
        .login-container h1 {
            color: #343a40;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .form-group label {
            font-weight: 600;
            color: #343a40;
        }
        .form-control {
            border-radius: 50px !important; /* oval input */
            border: 2px solid #6c757d;
            padding-left: 45px;
            height: 50px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 8px #28a745;
            outline: none;
        }
        .form-control-icon {
            position: relative;
        }
        .form-control-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
            font-size: 1.2rem;
            pointer-events: none;
        }
        .btn-custom {
            background: linear-gradient(45deg, #007bff, #28a745);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px;
            font-weight: 700;
            width: 100%;
            transition: background 0.3s ease;
        }
        .btn-custom:hover {
            background: linear-gradient(45deg, #28a745, #007bff);
            color: white;
        }
        .btn-custom:focus {
            outline: none;
            box-shadow: 0 0 8px #28a745;
        }
        .error {
            color: #dc3545;
            font-weight: 600;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>Login SIMoASip</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group form-control-icon">
                <i class="fas fa-user"></i>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus>
            </div>

            <div class="form-group form-control-icon">
                <i class="fas fa-lock"></i>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-custom">Login</button>
        </form>
    </div>

    <!-- Bootstrap 4 JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>