<?php
session_start();
session_destroy();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Redirect to index with a flag
header("Location: index.php?logged_out=1");
exit();
?>