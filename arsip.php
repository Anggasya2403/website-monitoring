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

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $instalasi = $_POST['instalasi'];
    $tanggal = $_POST['tanggal'];
    $jenis_dokumen = $_POST['jenis_dokumen'];
    $asal_dokumen = $_POST['asal_dokumen'];

    if (isset($_FILES['file_dokumen'])) {
        if ($_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png'];
            $file_name = $_FILES['file_dokumen']['name'];
            $file_tmp = $_FILES['file_dokumen']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = uniqid() . "." . $file_ext;
                $upload_dir = "uploads/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Prepare statement dengan pengecekan error
                    $sql = "INSERT INTO dokumen (instalasi, tanggal, jenis_dokumen, asal_dokumen, file_dokumen) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt === false) {
                        $message = "Gagal menyiapkan statement: " . $conn->error;
                    } else {
                        $stmt->bind_param("sssss", $instalasi, $tanggal, $jenis_dokumen, $asal_dokumen, $upload_path);
                        if ($stmt->execute()) {
                            $message = "Data berhasil disimpan.";
                        } else {
                            $message = "Gagal menyimpan data: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                } else {
                    $message = "Gagal mengupload file.";
                }
            } else {
                $message = "Ekstensi file tidak diizinkan.";
            }
        } elseif ($_FILES['file_dokumen']['error'] === UPLOAD_ERR_NO_FILE) {
            $message = "File dokumen wajib diupload.";
        } else {
            $message = "Terjadi kesalahan saat upload file. Kode error: " . $_FILES['file_dokumen']['error'];
        }
    } else {
        $message = "File dokumen wajib diupload.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Input Arsip Dokumen</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #28a745 50%, #6c757d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background-color: #f8f9facc; /* abu-abu terang dengan transparansi */
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            max-width: 480px;
            width: 100%;
        }
        .form-control {
            border-radius: 50px !important; /* oval input */
            border: 2px solid #6c757d;
            padding-left: 20px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 8px #28a745;
            outline: none;
        }
        label {
            font-weight: 600;
            color: #343a40;
        }
        .btn-submit {
            border-radius: 50px;
            background: linear-gradient(45deg, #007bff, #28a745);
            border: none;
            font-weight: 600;
            padding: 10px 30px;
            transition: background 0.4s ease;
            color: white;
        }
        .btn-submit:hover {
            background: linear-gradient(45deg, #28a745, #007bff);
            color: white;
        }
        .btn-secondary {
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: 600;
            margin-left: 10px;
            border: none;
            background: linear-gradient(45deg, #6c757d, #28a745);
            color: white;
            transition: background 0.4s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-secondary:hover {
            background: linear-gradient(45deg, #28a745, #6c757d);
            color: white;
            text-decoration: none;
        }
        .message {
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px 15px;
            border-radius: 12px;
        }
        .message.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="form-container shadow">
        <h2 class="text-center mb-4" style="color:#343a40;">Input Arsip Dokumen</h2>
        <?php if ($message): ?>
            <?php
                $isError = stripos($message, 'gagal') !== false || stripos($message, 'error') !== false;
            ?>
            <div class="message <?php echo $isError ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label for="instalasi" class="form-label">Instalasi</label>
                <select id="instalasi" name="instalasi" class="form-select" required>
                    <option value="" disabled selected>Pilih instalasi</option>
                    <option value="Bengkel">Bengkel</option>
                    <option value="Gudang">Gudang</option>
                    <option value="Pengamatan Laut">Pengamatan Laut</option>
                    <option value="Kapal Negara">Kapal Negara</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                <input type="text" class="form-control" id="jenis_dokumen" name="jenis_dokumen" placeholder="Masukkan jenis dokumen" required>
            </div>
            <div class="mb-3">
                <label for="asal_dokumen" class="form-label">Asal Dokumen</label>
                <input type="text" class="form-control" id="asal_dokumen" name="asal_dokumen" placeholder="Masukkan asal dokumen" required>
            </div>
            <div class="mb-4">
                <label for="file_dokumen" class="form-label">File Dokumen</label>
                <input class="form-control" type="file" id="file_dokumen" name="file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" required>
            </div>
            <div class="btn-group">
                 <button type="submit" class="btn btn-submit">Simpan   </button>
                 <a href="list.php" class="btn btn-secondary">Lihat Arsip</a>
                
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>