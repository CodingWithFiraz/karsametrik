<?php
require_once __DIR__ . '/config.php';

class DB {
    private static ?PDO $pdo = null;
    public static function pdo(): PDO {
        if (self::$pdo === null) {
            $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=".DB_CHARSET;

            // PHP 8.5+ memindahkan konstanta MySQL ke namespace Pdo\Mysql::
            $attrInitCmd  = PHP_VERSION_ID >= 80500 ? Pdo\Mysql::ATTR_INIT_COMMAND          : PDO::MYSQL_ATTR_INIT_COMMAND;
            $attrSslCa    = PHP_VERSION_ID >= 80500 ? Pdo\Mysql::ATTR_SSL_CA                : PDO::MYSQL_ATTR_SSL_CA;
         $attrSslVerif = PHP_VERSION_ID >= 80500 ? Pdo\Mysql::ATTR_SSL_VERIFY_SERVER_CERT : PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT;
          
$opts = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    $attrInitCmd                 => "SET time_zone='+07:00'",
    // SSL_CA dihapus — TiDB masih pakai SSL tapi tanpa verifikasi CA
    $attrSslVerif                => false,
];
            try { 
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $opts); 
            } catch (PDOException $e) {
                http_response_code(503);
                echo json_encode(['success'=>false, 'message'=>APP_DEBUG?'DB error: '.$e->getMessage():'Service unavailable.']);
                exit;
            }
        }
        return self::$pdo;
    }

    public static function fetchAll(string $sql, array $p=[]): array { $s=self::pdo()->prepare($sql); $s->execute($p); return $s->fetchAll(); }
    public static function fetchOne(string $sql, array $p=[]): ?array { $s=self::pdo()->prepare($sql); $s->execute($p); return $s->fetch()?:null; }
    public static function execute(string $sql, array $p=[]): int { $s=self::pdo()->prepare($sql); $s->execute($p); $id=(int)self::pdo()->lastInsertId(); return $id?:$s->rowCount(); }
}