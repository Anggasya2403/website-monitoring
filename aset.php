<?php
// Menghubungkan ke database
include('koneksi.php');

// Mengecek apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari formulir
    $tanggal = $_POST['tanggal'];
    $penanggung_jawab = $_POST['penanggung_jawab'];
    $instalasi = $_POST['instalasi'];
    $kategori_aset = $_POST['kategori_aset'];
    $nama_aset = $_POST['nama_aset'];
    $jumlah = $_POST['jumlah'];
    $kondisi = $_POST['kondisi'];
    $catatan = $_POST['catatan'];

    // Menyiapkan query untuk menyimpan data
    $sql = "INSERT INTO aset (tanggal, penanggung_jawab, instalasi, kategori_aset, nama_aset, jumlah, kondisi, catatan)
            VALUES ( '$tanggal', '$penanggung_jawab', '$instalasi', '$kategori_aset', '$nama_aset', '$jumlah', '$kondisi', '$catatan')";

    // Mengeksekusi query
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil disimpan');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Monitoring Aset</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #28a745 50%, #6c757d 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }
        header, footer {
            background: #343a40;
        }
        header h1, footer p {
            color: #f8f9fa;
            margin: 0;
        }
        .btn-logout {
            border-radius: 50px;
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #218838;
            color: #fff;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            background-color: #f8f9facc;
        }
        .card-header {
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            background: linear-gradient(45deg, #007bff, #28a745);
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
        }
        form .form-control, form .form-select {
            border-radius: 50px !important; /* oval input */
            border: 2px solid #6c757d;
            padding-left: 20px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        form .form-control:focus, form .form-select:focus {
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
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>

<header class="py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1>Input Data Monitoring Aset</h1>
       
    </div>
</header>

<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header">
            Form Monitoring Aset
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label for="penanggung_jawab" class="form-label">Penanggung Jawab / PIC</label>
                    <input type="text" id="penanggung_jawab" name="penanggung_jawab" class="form-control" placeholder="Masukkan nama penanggung jawab" required />
                </div>

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
                    <label for="kategori_aset" class="form-label">Kategori Aset</label>
                    <select id="kategori_aset" name="kategori_aset" class="form-select" required>
                        <option value="" disabled selected>Pilih kategori aset</option>
                        <option value="Alat Berat">Alat Berat</option>
                        <option value="Alat Bantu">Alat Bantu</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Atk">Atk</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nama_aset" class="form-label">Nama Aset</label>
                    <input type="text" id="nama_aset" name="nama_aset" class="form-control" placeholder="Masukkan nama aset" required />
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" placeholder="Masukkan jumlah aset" required />
                </div>

                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="form-select" required>
                        <option value="" disabled selected>Pilih kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Butuh Perawatan">Butuh Perawatan</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="catatan" class="form-label">Catatan</label>
                    <input type="text" id="catatan" name="catatan" class="form-control" placeholder="Masukkan catatan (opsional)" />
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-submit">Input Monitoring</button>
                    <a href="index.php" class="btn btn-secondary">Lihat Data Monitoring</a>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="text-center py-3" style="background: #343a40; color: #f8f9fa;">
    <p>Sistem Monitoring Aset Instalasi</p>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>