<?php
/**
 * DO NOT USE THIS FILE IN PRODUCTION ENVIRONMENT
 */
header('Content-Type: application/json');

require_once "/var/www/lib/Database.php";

$db = new Database();

$tables = $db->fetchValues("SELECT table_name FROM information_schema.tables;");

echo json_encode([
    "result" => true,
    "pong" => true,
    "tables" => $tables
]);