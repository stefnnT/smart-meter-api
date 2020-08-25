<?php

    class Database {
        // mysql://o8akjzi4g2nclvan:bi7w4q2l5sd461af@d13xat1hwxt21t45.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/g2zmqwhw2f3rkq9j    
        // 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
        // DB Params

        // private $host = 'd13xat1hwxt21t45.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        // private $db_name = 'g2zmqwhw2f3rkq9j';
        // private $username = 'o8akjzi4g2nclvan';
        // private $password = 'bi7w4q2l5sd461af';

        private $host = 'localhost';
        private $db_name = 'powercube';
        private $username = 'root';
        private $password = '';

        private $conn;

        // DB Connect
        public function connect() {
            $this->conn = null;

            try { 
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch(PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }

            return $this->conn;
        }
    }