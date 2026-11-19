<?php 
// Vercel runs this front controller from /api; keep that directory out of Laravel's base URL.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__ . '/../public/index.php'; 