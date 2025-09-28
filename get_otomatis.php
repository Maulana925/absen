<?php

date_default_timezone_set("Asia/Makassar");

require_once __DIR__ . '/db/koneksi.php';
if (!isset($koneksi) || !$koneksi instanceof mysqli) {
    http_response_code(500);
    echo "Koneksi database tidak tersedia.";
    exit;
}

header('Content-Type: text/plain'); 

$tanggal = date("Y-m-d");
$waktu   = date("H:i:s");

$sqlCekHariIni = "SELECT 1 FROM view_absen WHERE tanggal = CURDATE() LIMIT 1";
$hasil = mysqli_query($koneksi, $sqlCekHariIni);

if ($hasil === false) {
    http_response_code(500);
    echo "DB Error (cek hari ini): " . mysqli_error($koneksi);
    exit;
}

$adaAbsenHariIni = mysqli_num_rows($hasil) > 0;

echo "$tanggal / $waktu\n";

if (!$adaAbsenHariIni) {
    $sqlDelete = "DELETE FROM tbl_absen WHERE tanggal < CURDATE()";
    if (!mysqli_query($koneksi, $sqlDelete)) {
        echo "Peringatan: gagal hapus data absen lama: " . mysqli_error($koneksi) . "\n";
    }
    $sqlReset = "UPDATE tbl_karyawan 
                 SET status_absen = NULL 
                 WHERE status_absen IN ('hadir','sakit','izin','alfa')";
    if (!mysqli_query($koneksi, $sqlReset)) {
        echo "Peringatan: gagal reset status_absen: " . mysqli_error($koneksi) . "\n";
    }
}

