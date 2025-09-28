<?php

session_start();
require_once __DIR__ . '/../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../index.php'); exit; }

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header('Location: ../index.php?gagal_masuk=empty'); exit;
}

$sql  = "SELECT id, nama_admin, username, password, foto FROM tbl_admin WHERE username = ? LIMIT 1";
$stmt = $koneksi->prepare($sql);
if (!$stmt) { header('Location: ../index.php?gagal_masuk=db'); exit; }
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    header('Location: ../index.php?gagal_masuk=invalid'); exit;
}

$row = $res->fetch_assoc();
$stored = $row['password'] ?? '';

$isMd5 = (strlen($stored) === 32 && ctype_xdigit($stored));

$valid = $isMd5 ? (md5($password) === strtolower($stored)) : ($password === $stored);

if (!$valid) {
    header('Location: ../index.php?gagal_masuk=invalid'); exit;
}

$_SESSION['status']     = 'online';
$_SESSION['id']         = (int)$row['id'];
$_SESSION['username']   = $row['username'];
$_SESSION['nama_admin'] = $row['nama_admin'];
$_SESSION['foto']       = $row['foto'];
$_SESSION['role']       = 'admin';

if ($upd = $koneksi->prepare("UPDATE tbl_admin SET status='online' WHERE id=?")) {
    $upd->bind_param('i', $_SESSION['id']);
    $upd->execute();
    $upd->close();
}

header('Location: ../dashboard.php?masuk=1');
exit;
