<?php

namespace App\Models;

use PDO;

class User extends \Core\Model {

    public static function getAll() {

        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT id, name FROM users');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>