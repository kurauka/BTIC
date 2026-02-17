<?php
$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$dbname = getenv('DB_NAME') ?: "bandari_tech_club";
$port = getenv('DB_PORT') ?: 3306;

try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Add SSL if needed (Aiven usually requires it)
    if (getenv('DB_SSL') === 'true') {
        $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $conn = new PDO($dsn, $username, $password, $options);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>