<?php
// config.php — HARAP GANTI BASE_URL sebelum deploy
define('BOT_TOKEN', '8320580407:AAG0kZRP3MmJyt4vAoF7gPAKmWHTvjYqGs8');
define('BASE_URL', 'https://YOUR_DOMAIN_HERE'); // ganti dengan domainmu, mis. https://namadomain.com
define('UPLOAD_DIR', 'uploads'); // relatif ke project root
define('ALLOWED_EXT', ['jpg','jpeg','png','gif','webp']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB
