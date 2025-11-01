<?php
// bot.php - Telegram webhook endpoint
require 'config.php';

function telegramRequest($method, $params = []) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/" . $method;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

function downloadFile($fileUrl, $saveTo) {
    $ch = curl_init($fileUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode === 200 && $data !== false) {
        return file_put_contents($saveTo, $data) !== false;
    }
    return false;
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$update = json_decode(file_get_contents('php://input'), true);
$chat_id = $update['message']['chat']['id'] ?? null;
$message = $update['message'] ?? [];

if (!$chat_id) {
    http_response_code(200);
    exit;
}

function replyWithInlineUrl($chat_id, $text, $url) {
    $reply_markup = json_encode([
        'inline_keyboard' => [
            [['text' => '📷 Buka Gambar', 'url' => $url]]
        ]
    ]);
    telegramRequest('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => $reply_markup
    ]);
}

// detect image
if (!empty($message['photo'])) {
    $photo = end($message['photo']);
    $file_id = $photo['file_id'];
} elseif (!empty($message['document']) && isset($message['document']['mime_type']) && strpos($message['document']['mime_type'], 'image/') === 0) {
    $file_id = $message['document']['file_id'];
} else {
    telegramRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Kirim gambar (photo atau file gambar). Bot akan menyimpan dan mengirimkan link."]);
    http_response_code(200);
    exit;
}

$getfile = telegramRequest('getFile', ['file_id' => $file_id]);
if (empty($getfile['ok'])) {
    telegramRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Gagal mengambil info file."]);
    http_response_code(200);
    exit;
}
$file_path = $getfile['result']['file_path'];
$file_url = "https://api.telegram.org/file/bot".BOT_TOKEN."/".$file_path;
$ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
if (!in_array($ext, ALLOWED_EXT)) {
    telegramRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Jenis file tidak diizinkan."]);
    http_response_code(200);
    exit;
}
$basename = uniqid('img_') . '.' . $ext;
$save_path = rtrim(UPLOAD_DIR, '/') . '/' . $basename;
$ok = downloadFile($file_url, $save_path);
if (!$ok) {
    telegramRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Gagal men-download file."]);
    http_response_code(200);
    exit;
}
if (filesize($save_path) > MAX_FILE_SIZE) {
    unlink($save_path);
    telegramRequest('sendMessage', ['chat_id' => $chat_id, 'text' => "Gagal: file terlalu besar (max " . (MAX_FILE_SIZE/1024/1024) . " MB)."]);
    http_response_code(200);
    exit;
}
$public_url = rtrim(BASE_URL, '/') . '/' . trim(UPLOAD_DIR, '/') . '/' . $basename;

// Try to send preview photo (using public URL) and inline button
telegramRequest('sendPhoto', [
    'chat_id' => $chat_id,
    'photo' => $public_url,
    'caption' => "✅ Gambar berhasil diupload!\nLink ada di tombol di bawah.",
]);
replyWithInlineUrl($chat_id, "<b>Link Gambar:</b>\n" . $public_url, $public_url);
http_response_code(200);
echo 'OK';
