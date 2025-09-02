<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    //$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Gagal mendaftar. Username mungkin sudah digunakan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Aset Instalasi Bengkel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <h1>Registrasi</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="ser">User</option>
                <option value="admin">Admin</option>
            </select>
            
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
