<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connect.php';

$hash = password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 12]);
$sql = "UPDATE users SET password = ? WHERE username = 'admin'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $hash);
mysqli_stmt_execute($stmt);

echo "Password updated successfully.\n";
