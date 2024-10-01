<?php
header('Content-Type: application/json');

require_once "/var/www/lib/auth.php";

logout();

echo json_encode([
    "result" => false,
]);