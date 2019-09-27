<?php

namespace ProjectSend\Classes;

use PDO;
use PDOException;

class Database
{
    protected $dbh;

    function __construct(PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Check if a table exists in the current database.
     *
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     * @throws PDOException
     */
    public function table_exists($table)
    {
        $table = str_replace(['%', '_'], ['', ''], $table);
        $stmt = $this->dbh->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        $stmt->fetchColumn();
        return $stmt->fetchColumn() !== false;
    }

}