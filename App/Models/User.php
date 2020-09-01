<?php

namespace App\Models;

use PDO;

class User extends \Core\Model {
    
    public $errors = [];

    public function __construct($data = []) {
        foreach($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function create() {

       $this->validate();

        if (empty($this->errors)) {
            try {
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users`(`username`, `email`, `password`) VALUES (:username,:email,:password)";
                
                $db = static::getDB();
                $stmt = $db->prepare($sql);
                                                      
                $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
                $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
                                              
                return $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        return false;
    }

    protected function validate() {
        if ($this->username == '') {
            $this->errors[] = 'Name is required';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if ($this->emailExists($this->email)) {
            $this->errors[] = 'This email is already taken';
        }

        if ($this->password != $this->confirm_password) {
            $this->errors[] = 'Password do not match';
        }

        if (strlen($this->password) < 6) {
            $this->errors[] = 'Please enter at least 6 character for the password';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }
    }

    public static function emailExists($email) {
        return static::findByEmail($email) !== false;
    }

    public static function findByEmail($email) {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($email, $password) {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }
}

?>