
<?php
// Database configuration constants
class DatabaseConfig {
    const HOST = 'localhost';
    const PORT = 3306;
    const DATABASE = 'leavedb';
    const USERNAME = 'root';
    const PASSWORD = '';
    const CHARSET = 'utf8mb4';
    
    // Connection options
    const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];
    
    public static function getDSN() {
        return sprintf(
            "mysql:host=%s;port=%d;dbname=%s;charset=%s",
            self::HOST,
            self::PORT,
            self::DATABASE,
            self::CHARSET
        );
    }
    
    public static function getConnection() {
        try {
            return new PDO(
                self::getDSN(),
                self::USERNAME,
                self::PASSWORD,
                self::OPTIONS
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
}
?>
