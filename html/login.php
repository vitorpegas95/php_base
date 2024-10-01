<?php
header('Content-Type: application/json');

require_once "/var/www/lib/Database.php";
require_once "/var/www/lib/api.php";
require_once "/var/www/lib/auth.php";

$db = new Database();

$request = apiRequest();

if (isset($_SESSION["user"])) {
    exit(json_encode(["result" => true, "session" => $_SESSION["user"]]));
}

$username = strtolower(trim(str_replace(" ", "", getVar("username", $request, ""))));
$passwordHash = hash512(trim(getVar("password", $request, "")));

$existsUser = $db->fetchValues("SELECT id, username FROM users WHERE (LOWER(`username`)=? AND `password`=?) OR (LOWER(`email`)=? AND `password`=?)", [
    $username,
    $passwordHash,
    $username,
    $passwordHash
]);

if (!empty($existsUser)) {
    login($existsUser);
    exit(json_encode(["result" => true, "session" => $_SESSION["user"]]));
} else {
    echo json_encode([
        "result" => false,
    ]);
}

