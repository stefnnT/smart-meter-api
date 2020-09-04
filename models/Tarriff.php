<?php

    class Tarriff  {
        // DB stuff
        private $conn;
        // private $table = 'device_status';

        // Post Properties
        public $stakeholder_id;
        public $r1;
        public $r2s;
        public $r2t;
        public $r3;
        public $r4;
        public $c1s;
        public $c1t;
        public $c2;
        public $c3;
        public $d1;
        public $d2;
        public $d3;
        public $a1;
        public $a2;
        public $a3;
        public $s1;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        //Add Tarriff details
        public function add_tarriff() {
            //query
            $query = 'INSERT INTO tarriffs SET 
                        stakeholder_id = (SELECT stakeholder_id FROM stakeholders WHERE role_id = 1) ,
                        r1 = :r1, 
                        r2s = :r2s, 
                        r2t = :r2t, 
                        r3 = :r3, 
                        r4 = :r4, 
                        c1s = :c1s, 
                        c1t = :c1t, 
                        c2 = :c2, 
                        c3 = :c3, 
                        d1 = :d1, 
                        d2 = :d2, 
                        d3 = :d3, 
                        a1 = :a1, 
                        a2 = :a2, 
                        a3 = :a3,
                        s1 = :s1,
                        created_at = CURRENT_TIMESTAMP ';
            
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->r1 = htmlspecialchars(strip_tags($this->r1));
            $this->r2s = htmlspecialchars(strip_tags($this->r2s));
            $this->r2t = htmlspecialchars(strip_tags($this->r2t));
            $this->r3 = htmlspecialchars(strip_tags($this->r3));
            $this->r4 = htmlspecialchars(strip_tags($this->r4));
            $this->c1s = htmlspecialchars(strip_tags($this->c1s));
            $this->c1t = htmlspecialchars(strip_tags($this->c1t));
            $this->c2 = htmlspecialchars(strip_tags($this->c2));
            $this->c3 = htmlspecialchars(strip_tags($this->c3));
            $this->d1 = htmlspecialchars(strip_tags($this->d1));
            $this->d2 = htmlspecialchars(strip_tags($this->d2));
            $this->d3 = htmlspecialchars(strip_tags($this->d3));
            $this->a1 = htmlspecialchars(strip_tags($this->a1));
            $this->a2 = htmlspecialchars(strip_tags($this->a2));
            $this->a3 = htmlspecialchars(strip_tags($this->a3));
            $this->s1 = htmlspecialchars(strip_tags($this->s1));
            
            //Bind named parameters
            $stmt->bindParam(':r1', $this->r1);
            $stmt->bindParam(':r2s', $this->r2s);
            $stmt->bindParam(':r2t', $this->r2t);
            $stmt->bindParam(':r3', $this->r3);
            $stmt->bindParam(':r4', $this->r4);
            $stmt->bindParam(':c1s', $this->c1s);
            $stmt->bindParam(':c1t', $this->c1t);
            $stmt->bindParam(':c2', $this->c2);
            $stmt->bindParam(':c3', $this->c3);
            $stmt->bindParam(':d1', $this->d1);
            $stmt->bindParam(':d2', $this->d2);
            $stmt->bindParam(':d3', $this->d3);
            $stmt->bindParam(':a1', $this->a1);
            $stmt->bindParam(':a2', $this->a2);
            $stmt->bindParam(':a3', $this->a3);
            $stmt->bindParam(':s1', $this->s1);


            //Execute query
            if ($stmt->execute()) {
                return true;
            }
            
            //Error message
            printf("Error: ", $stmt->error);
            return false;
        }

        // Update tarriff details

    }