<?php

class Database
{
    private $host = 'localhost'; // Replace with your database host
    private $user = 'root'; // Replace with your database username
    private $pass = ''; // Replace with your database password
    private $dbname = 'website'; // Replace with your database name


    private $dbh;
    private $error;
    private $stmt;

    public function __construct()
    {
        // Set DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        // Set PDO options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        try {
            // Create a new PDO instance
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function update($table, $data, $where)
    {
        $set = '';
        foreach ($data as $column => $value) {
            $set .= "$column = :$column, ";
        }
        $set = rtrim($set, ', ');
    
        // Build the WHERE part of the SQL query
        $whereClause = '';
        foreach ($where as $column => $value) {
            $whereClause .= "$column = :$column AND ";
        }
        $whereClause = rtrim($whereClause, 'AND ');
    
        // Construct the full SQL query
        $query = "UPDATE $table SET $set WHERE $whereClause";
    
        // Prepare the query
        $this->query($query);
    
        // Bind values for the SET part
        foreach ($data as $column => $value) {
            $this->bind(":$column", $value);
        }
    
        // Bind values for the WHERE part
        foreach ($where as $column => $value) {
            $this->bind(":$column", $value);
        }
    
        // Execute the query
        return $this->execute();
    }
    
    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
?>