<?php
require 'config.php';
$dir = rtrim(UPLOAD_DIR, '/') . '/';
$files = [];
if (is_dir($dir)) {
    $items = scandir($dir);
    foreach ($items as $f) {
        if ($f === '.' || $f === '..') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ALLOWED_EXT)) {
            $files[] = $f;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gallery — Image Uploader</title>
  <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body{font-family:'Lexend Deca',system-ui,Arial;background:#f6f8fb;margin:0;padding:24px}
    .wrap{max-width:1100px;margin:0 auto}
    header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
    header h1{font-size:18px;margin:0}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px}
    .card{background:white;padding:10px;border-radius:12px;box-shadow:0 6px 20px rgba(12,34,56,0.06);text-align:center}
    .card img{max-width:100%;height:140px;object-fit:cover;border-radius:8px}
    .meta{margin-top:8px;font-size:13px;color:#374151}
    .btn{display:inline-block;margin-top:8px;padding:8px 10px;border-radius:8px;background:#2563eb;color:white;text-decoration:none;font-weight:600}
    .copy{background:#10b981;border-radius:8px;padding:6px 8px;color:white;text-decoration:none;margin-left:8px}
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <h1>Gallery</h1>
      <div>
        <a href="index.html" class="btn">Upload Baru</a>
      </div>
    </header>
    <div class="grid" id="grid">
      <?php foreach (array_reverse($files) as $f): 
        $url = rtrim(BASE_URL, '/') . '/' . trim(UPLOAD_DIR, '/') . '/' . $f;
        ?>
        <div class="card">
          <img src="<?php echo htmlspecialchars($url); ?>" alt="">
          <div class="meta"><?php echo htmlspecialchars($f); ?></div>
          <div style="margin-top:8px">
            <a class="btn" href="<?php echo htmlspecialchars($url); ?>" target="_blank">Buka</a>
            <button class="copy" data-url="<?php echo htmlspecialchars($url); ?>">Copy Link</button>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($files)): ?>
        <p>Tidak ada gambar. Upload dulu via web atau bot Telegram.</p>
      <?php endif; ?>
    </div>
  </div>
  <script>
    document.addEventListener('click', function(e){
      if(e.target.matches('.copy')) {
        const url = e.target.getAttribute('data-url');
        if(navigator.clipboard) {
          navigator.clipboard.writeText(url).then(()=> {
            e.target.textContent = 'Copied!';
            setTimeout(()=> e.target.textContent = 'Copy Link', 1200);
          });
        } else {
          prompt('Copy link berikut:', url);
        }
      }
    });
  </script>
</body>
</html>
