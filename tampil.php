<?php
// koneksi database
include('koneksi.php');
// Proses input data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $penanggung_jawab = $_POST['penanggung_jawab'];
    $instalasi = $_POST['instalasi'];
    $kategori_aset = $_POST['kategori_aset'];
    $nama_aset = $_POST['nama_aset'];
    $jumlah = $_POST['jumlah'];
    $kondisi = $_POST['kondisi'];
    $catatan = $_POST['catatan'];
    $sql = "INSERT INTO aset (tanggal, penanggung_jawab, instalasi, kategori_aset, nama_aset, jumlah, kondisi, catatan)
            VALUES ('$tanggal', '$penanggung_jawab', '$instalasi', '$kategori_aset', '$nama_aset', '$jumlah', '$kondisi', '$catatan')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil disimpan');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// Ambil data aset
$sql_select = "SELECT * FROM aset ORDER BY id DESC";
// Ambil data aset
$sql_select = "SELECT * FROM aset ORDER BY id DESC";
$result = $conn->query($sql_select);
if (!$result) {
    die("Query error: " . $conn->error);
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
</head>
<body>
    <header class="bg-primary text-white py-3">
    <div class="container text-center">
        <h1>Input Data Monitoring Aset</h1>
        
    </div>
</header>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Form Monitoring Aset</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="penanggung_jawab" class="form-label">Penanggung Jawab / PIC</label>
                    <input type="text" id="penanggung_jawab" name="penanggung_jawab" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="instalasi" class="form-label">Instalasi</label>
                    <select id="instalasi" name="instalasi" class="form-select" required>
                        <option value="Bengkel">Bengkel</option>
                        <option value="Gudang">Gudang</option>
                        <option value="Pengamatan Laut">Pengamatan Laut</option>
                        <option value="Kapal Negara">Kapal Negara</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kategori_aset" class="form-label">Kategori Aset</label>
                    <select id="kategori_aset" name="kategori_aset" class="form-select" required>
                        <option value="Alat Berat">Alat Berat</option>
                        <option value="Alat Bantu">Alat Bantu</option>
                          <option value="Elektronik">Elektronik</option>
                        <option value="Atk">Atk</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nama_aset" class="form-label">Nama Aset</label>
                    <input type="text" id="nama_aset" name="nama_aset" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="form-select" required>
                        <option value="Baik">Baik</option>
                        <option value="Butuh Perawatan">Butuh Perawatan</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Habis">Habis</option>
                        </select>
                </div>
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <input type="text" id="catatan" name="catatan" class="form-control" required />
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Input Monitoring</button>
                    <a href="keluar.php" class="btn btn-secondary">Logout</a>
                </div>
            </form>
        </div>
    </div>
    <!-- Tabel Data Monitoring Aset -->
    <div class="card mt-5">
        <div class="card-header bg-secondary text-white">
            <h4 class="text-center">Data Monitoring Aset</h4>
        </div>
        <div class="card-body table-responsive">
            <table id="asetTable" class="table table-bordered table-striped align-middle" style="width:100%">
                <thead class="table-dark text-center">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
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
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<footer class="bg-primary text-white text-center py-3 mt-5">
    <p>Sistem Monitoring Aset Instalasi</p>
</footer>
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
</body>
</html>
<?php
$conn->close();
?>
