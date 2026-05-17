<?php
require 'c:\xampp\htdocs\danahibah\includes\config.php';
require 'c:\xampp\htdocs\danahibah\includes\db_connect.php';

mysqli_query($conn, "UPDATE roles SET slug='super_admin', label='Super Administrator' WHERE id=1");
mysqli_query($conn, "UPDATE roles SET slug='management', label='Management Administrator' WHERE id=2");
mysqli_query($conn, "UPDATE roles SET slug='branch_admin', label='Branch Administrator' WHERE id=3");
mysqli_query($conn, "INSERT IGNORE INTO roles (id, slug, label) VALUES (4, 'committee', 'Branch Committee Member')");
mysqli_query($conn, "UPDATE users SET role_id=1 WHERE username='admin'");

echo "Roles updated";
