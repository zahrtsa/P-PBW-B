<?php 
define ('NAMA_EVENT', 'Belajar PHP 2025');
define ('FILE_PENDAFTARAN', 'pendaftar.txt');

ob_start();
?>
<table>
    <thead>
    <tr>
        <th>Nama Lengkap</th>
        <th>Email</th>
        <th>Tanggal Lahir</th>
    </tr>
    </thead>
<tbody>
    <?php
    // Cek apakah file pendaftar.txt ada
    if (file_exists(FILE_PENDAFTARAN)) {
        // Membaca isi file baris per baris ke dalam array
        $pendaftar = file(FILE_PENDAFTARAN, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // Melakukan loop untuk setiap baris data
        foreach ($pendaftar as $data) {
        // Memecah baris data berdasarkan pemisah semicolon
            list($nama, $email, $tanggal_lahir) = explode(';', $data);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($nama) . "</td>";
            echo "<td>" . htmlspecialchars($email) . "</td>";
            echo "<td>" . htmlspecialchars($tanggal_lahir) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Belum ada pendaftar.</td></tr>";
    }
    ?>
</tbody>
</table>
<?php

$tabelData = ob_get_clean();
$status_message = '';
$error_messages = [];

function validateEmail($email){
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
}

function validateDate($date){
    $pattern = '/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(19[0-9]{2}|20[0-9]{2})$/';
    return preg_match($pattern, $date);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);

    if(empty($nama) || empty($email) || empty($tanggal_lahir)) {
        $error_messages[] = "Semua field wajib diisi.";
    }
    if (!validateEmail($email)) {
        $error_messages[] = "Format email tidak valid.";
    }
    if (!validateDate($tanggal_lahir)) {
        $error_messages[] = "Format tanggal lahir harus DD-MM-YYYY.";
    }

    if(empty($error_messages)) {
        $data_pendaftar = "{$nama};{$email};{$tanggal_lahir}\n";
        file_put_contents(FILE_PENDAFTARAN, $data_pendaftar, FILE_APPEND);

        $status_message = "Terimakasih, {$nama}! Pendaftaran Anda untuk event " .NAMA_EVENT. " berhasil";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Event Digital</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Form Pendaftaran Event "Belajar PHP 2025"</h1>
    <?php if (!empty($status_message)): ?>
        <p class="success"><?php echo $status_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($error_messages)): ?>
        <div class="error">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                <?php foreach ($error_messages as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <p>Silakan isi data diri Anda untuk mendaftar pada event kami.</p>
    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="email">Alamat Email:</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir (DD-MM-YYYY):</label>
            <input type="text" id="tanggal_lahir" name="tanggal_lahir"
            required>
        </div>
        <button type="submit">Daftar Sekarang</button>
    </form>
    <hr>
    <h2>Daftar Peserta yang Sudah Mendaftar</h2>
    <?php echo $tabelData ?>
</body>
</html>
