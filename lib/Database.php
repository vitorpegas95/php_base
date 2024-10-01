<?php
date_default_timezone_set('Europe/Lisbon');

require_once __DIR__ . "/../config/dbConfig.php";
require_once "log.php";

class Database
{
    private $connection = null;

    public function __construct($useProxy = false)
    {
        $this->setupConnection();
    }

    public function close()
    {
        $this->connection = null;
    }

    private function setupConnection()
    {
        global $DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_PORT;
        $dsn = 'mysql:host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ];

        try {
            $this->connection = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD, $options);
        } catch (PDOException $e) {
            log::error($e->getMessage());
        }
    }

    public function directExec($query, $secondTry = false)
    {
        if (trim($query) == "") {
            return true;
        }
        try {
            if ($this->connection !== null) {
                $rtn = $this->connection->exec($query);
                if ($rtn) {
                    return $rtn;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (PDOException $e) {
            log::error($e->getMessage());
            return null;
        }
    }

    public function query($query, $params = [])
    {
        if (trim($query) == "") {
            return null;
        }
        if (!is_array($params)) {
            $params = [$params];
        }
        try {
            if ($this->connection !== null) {
                $stmt = $this->connection->prepare($query);
                $rtn = $stmt->execute($params);
                if ($rtn) {
                    return $stmt;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (PDOException $e) {
            log::error($e->getMessage());
            return null;
        }
    }

    public function fetch($query, $params = [])
    {
        $query = $this->query($query, $params);

        if ($query !== null) {
            return $query->fetch();
        } else {
            return null;
        }
    }

    public function fetchAll($query, $params = [])
    {
        $query = $this->query($query, $params);

        if ($query !== null) {
            return $query->fetchAll();
        } else {
            return null;
        }
    }

    public function fetchValue($query, $params = [])
    {
        $query = $this->query($query, $params);

        if ($query !== null) {
            return $query->fetchColumn();
        } else {
            return null;
        }
    }

    public function fetchValues($query, $params = [])
    {
        $query = $this->query($query, $params);

        if ($query !== null) {
            return $query->fetchAll(PDO::FETCH_COLUMN);
        } else {
            return null;
        }
    }
}