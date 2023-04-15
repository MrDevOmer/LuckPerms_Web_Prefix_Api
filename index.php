<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luckperms";

// Oyuncu adını al
$player = $_GET['player'];

// MySQL veritabanı bağlantısı kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Prefix için izinleri al
$sql = "SELECT permission FROM luckperms_group_permissions WHERE name IN (SELECT primary_group FROM luckperms_players WHERE username = '$player') AND permission LIKE 'prefix.%'";
$result = $conn->query($sql);

// Prefixler için dizi oluştur
$prefixes = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $permission = $row["permission"];
        $prefix = substr($permission, 7);
        if (strpos($prefix, "1.") === 0) {
            $prefix = substr($prefix, 2);
        }
        array_push($prefixes, $prefix);
    }
}

// JSON yanıtı oluştur
$response = array('player' => $player, 'prefixes' => $prefixes);
echo json_encode($response);

// Bağlantıyı kapat
$conn->close();
?>
