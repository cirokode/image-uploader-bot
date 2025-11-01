<?php
require 'config.php';
// pastikan folder uploads ada
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo 'Terjadi kesalahan saat upload.';
        exit;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXT)) {
        echo 'Jenis file tidak diizinkan.';
        exit;
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        echo 'File terlalu besar. Max: ' . (MAX_FILE_SIZE/1024/1024) . ' MB';
        exit;
    }
    $basename = uniqid('img_') . '.' . $ext;
    $save_path = rtrim(UPLOAD_DIR, '/') . '/' . $basename;
    if (move_uploaded_file($file['tmp_name'], $save_path)) {
        $public_url = rtrim(BASE_URL, '/') . '/' . trim(UPLOAD_DIR, '/') . '/' . $basename;
        echo '<h3>✅ Gambar berhasil diupload!</h3>';
        echo '<p><a href="' . htmlspecialchars($public_url) . '" target="_blank">' . htmlspecialchars($public_url) . '</a></p>';
        echo '<p><a href="gallery.php">Kembali ke gallery</a></p>';
        echo '<p><img src="' . htmlspecialchars($public_url) . '" alt="preview" style="max-width:300px"></p>';
    } else {
        echo 'Gagal menyimpan file.';
    }
} else {
    echo 'Tidak ada file yang dikirim.';
}
