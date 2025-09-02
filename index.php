<?php
session_start();
include('koneksi.php');

// Proses logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (md5($password) === $user['password']) {
            $_SESSION['users'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}

// Jika sudah login, ambil semua data aset
if (isset($_SESSION['users'])) {
    $sql_select = "SELECT * FROM aset ORDER BY id DESC";
    $result = $conn->query($sql_select);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Monitoring Aset</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

    <!-- FontAwesome for icons in login -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
    /* Warna gradien biru, hijau, abu-abu */
    body.login-body {
        background: linear-gradient(135deg, #007bff 0%, #28a745 50%, #6c757d 100%);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-container {
        max-width: 400px;
        padding: 30px;
        background-color: #f8f9facc;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        text-align: center;
    }
    .login-container h1 {
        color: #343a40;
        margin-bottom: 25px;
        font-weight: 700;
    }
    .form-group label {
        font-weight: 600;
        color: #343a40;
    }
    .form-control {
        border-radius: 50px;
        border: 2px solid #6c757d;
        padding: 15px 45px 15px 45px;
        margin-bottom: 20px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 8px #28a745;
        outline: none;
    }
    .btn-custom {
        background: linear-gradient(45deg, #007bff, #28a745);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 15px;
        font-weight: 700;
        width: 100%;
        transition: background-color 0.3s ease;
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
        font-size: 0.9em;
        margin-bottom: 15px;
        font-weight: 600;
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
        pointer-events: none;
        font-size: 1.2rem;
    }

    /* Header */
    header.bg-primary {
        background: linear-gradient(45deg, #007bff, #28a745);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    header h1 {
        font-weight: 700;
        color: white;
        margin-bottom: 10px;
    }
    nav a.btn {
        border-radius: 50px;
        font-weight: 600;
        margin: 0 5px;
        padding: 8px 20px;
        transition: background 0.3s ease, color 0.3s ease;
    }
    nav a.btn-light {
        background-color: #f8f9fa;
        color: #343a40;
        border: 2px solid transparent;
    }
    nav a.btn-light:hover {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }
    nav a.btn-secondary {
        background-color: #6c757d;
        color: white;
        border: 2px solid transparent;
    }
    nav a.btn-secondary:hover {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }

    /* Container */
    .container.mt-4 {
        max-width: 1200px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Card */
    .card {
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .card-header.bg-secondary {
        background: linear-gradient(45deg, #6c757d, #28a745);
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
        text-align: center;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    /* Table */
    table.dataTable thead th {
        background: linear-gradient(45deg, #007bff, #28a745);
        color: white;
        font-weight: 700;
        text-align: center;
    }
    table.dataTable tbody td {
        vertical-align: middle;
        text-align: center;
    }
    table.dataTable tbody tr:hover {
        background-color: #d4edda;
    }

    /* Responsive */
    @media (max-width: 768px) {
        nav a.btn {
            margin: 5px 0;
            width: 100%;
        }
    }
    </style>
</head>
<body class="<?= isset($_SESSION['users']) ? '' : 'login-body' ?>">

<?php if (!isset($_SESSION['users'])): ?>
    <!-- FORM LOGIN -->
    <div class="login-container">
        <h1>Login SIASBENG</h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="login" value="1" />
            <div class="form-group form-control-icon">
                <i class="fas fa-user"></i>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus>
            </div>

            <div class="form-group form-control-icon">
                <i class="fas fa-lock"></i>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-custom btn-block">Login</button>
        </form>
    </div>

<?php else: ?>
    <!-- HALAMAN UTAMA MONITORING ASET -->

<header class="bg-primary py-3 mb-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h1 class="mb-3 mb-md-0">Sistem Monitoring Aset & Arsip Dokumen</h1>
        <nav>
            <a href="aset.php" class="btn btn-light btn-sm">Input Monitoring Aset</a>
            <a href="arsip.php" class="btn btn-light btn-sm">Input Arsip Dokumen</a>
            <a href="list.php" class="btn btn-light btn-sm">Lihat Arsip Dokumen</a>
            <a href="?logout=1" class="btn btn-secondary btn-sm">Logout</a>
        </nav>
    </div>
</header>

<div class="container mt-4">

    <!-- Tabel Data Monitoring Aset -->
    <div class="card">
        <div class="card-header bg-secondary">
            <h4 class="mb-0">Data Monitoring Aset</h4>
        </div>
        <div class="card-body table-responsive">
            <table id="asetTable" class="table table-bordered table-striped align-middle" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Penanggung Jawab</th>
                        <th>Instalasi</th>
                        <th>Kategori Aset</th>
                        <th>Nama Aset</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['tanggal']); ?></td>
                                <td><?= htmlspecialchars($row['penanggung_jawab']); ?></td>
                                <td><?= htmlspecialchars($row['instalasi']); ?></td>
                                <td><?= htmlspecialchars($row['kategori_aset']); ?></td>
                                <td><?= htmlspecialchars($row['nama_aset']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['jumlah']); ?></td>
                                <td><?= htmlspecialchars($row['kondisi']); ?></td>
                                <td><?= htmlspecialchars($row['catatan']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- pdfmake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi DataTables dengan tombol export dan print
    $('#asetTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'print',
            {
                extend: 'excelHtml5',
                title: 'Data Monitoring Aset'
            },
            {
                extend: 'pdfHtml5',
                title: 'Data Monitoring Aset',
                orientation: 'landscape',
                pageSize: 'A4'
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            },
            zeroRecords: "Data tidak ditemukan",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)"
        }
    });
});
</script>

<?php endif; ?>
</body>
</html>