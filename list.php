<?php
// Koneksi ke database
$host = "localhost";
$user = "root"; // sesuaikan dengan user mysql Anda
$pass = "";     // sesuaikan dengan password mysql Anda
$db   = "arsip_dokumen";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data arsip dokumen dari database
$sql = "SELECT id, instalasi, tanggal, jenis_dokumen, asal_dokumen, file_dokumen, created_at FROM dokumen ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Arsip Dokumen</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #28a745 50%, #6c757d 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            padding: 20px;
        }
        .table-container {
            background-color: #f8f9facc; /* abu-abu terang transparan */
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            max-width: 1000px;
            margin: auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            color: #343a40;
        }
        table {
            border-radius: 20px;
            overflow: hidden;
        }
        thead {
            background: linear-gradient(45deg, #007bff, #28a745);
            color: white;
        }
        thead th {
            border: none;
            font-weight: 600;
            text-align: center;
        }
        tbody tr:hover {
            background-color: #d4edda;
        }
        tbody td {
            vertical-align: middle;
            text-align: center;
        }
        .btn-back {
            border-radius: 50px;
            background: linear-gradient(45deg, #6c757d, #28a745);
            color: white;
            font-weight: 600;
            padding: 10px 30px;
            border: none;
            transition: background 0.4s ease;
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .btn-back:hover {
            background: linear-gradient(45deg, #28a745, #6c757d);
            color: white;
            text-decoration: none;
        }
        .file-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        .file-link:hover {
            text-decoration: underline;
            color: #28a745;
        }
        @media (max-width: 768px) {
            .table-container {
                padding: 20px;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tbody tr {
                margin-bottom: 1rem;
                border-radius: 15px;
                background-color: #e9ecef;
                padding: 15px;
            }
            tbody td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: left;
            }
            tbody td::before {
                position: absolute;
                top: 15px;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: 700;
                color: #343a40;
            }
            tbody td:nth-of-type(1)::before { content: "NO."; }
            tbody td:nth-of-type(2)::before { content: "Instalasi"; }
            tbody td:nth-of-type(3)::before { content: "Tanggal"; }
            tbody td:nth-of-type(4)::before { content: "Jenis Dokumen"; }
            tbody td:nth-of-type(5)::before { content: "Asal Dokumen"; }
            tbody td:nth-of-type(6)::before { content: "File"; }
            tbody td:nth-of-type(7)::before { content: "Tanggal Input"; }
        }
    </style>
</head>
<body>
    <div class="table-container shadow">
        <h2>Daftar Arsip Dokumen</h2>
        <a href="arsip.php" class="btn btn-back">Kembali Ke Form Input</a>
        <a href="index.php" class="btn btn-back">Kembali Ke Home</a>
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Instalasi</th>
                            <th>Tanggal</th>
                            <th>Jenis Dokumen</th>
                            <th>Asal Dokumen</th>
                            <th>File Dokumen</th>
                            <th>Tanggal Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['instalasi']); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis_dokumen']); ?></td>
                                <td><?php echo htmlspecialchars($row['asal_dokumen']); ?></td>
                                <td>
                                    <?php if (file_exists($row['file_dokumen'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['file_dokumen']); ?>" target="_blank" class="file-link">Lihat File</a>
                                    <?php else: ?>
                                        <span class="text-muted">File tidak ditemukan</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($row['created_at']))); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center fw-semibold">Belum ada data arsip dokumen.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>