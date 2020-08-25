<?php

    class Status  {
        // DB stuff
        private $conn;
        private $table = 'device_status';

        // Post Properties
        public $id;
        public $device_id;
        public $op_mode;
        public $battery;
        public $op_volt;
        public $op_current;
        public $op_power;
        public $ch_current;
        public $time;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        //Update Device Status
        public function update_status() {
            //query
            $query = 'INSERT INTO '.$this->table .' SET 
                        device_id = :device_id, 
                        op_mode = :op_mode,
                        battery = :battery,
                        op_volt = :op_volt,
                        op_current = :op_current,
                        op_power = :op_power,
                        ch_current = :ch_current ';
            
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->device_id = htmlspecialchars(strip_tags($this->device_id));
            $this->op_mode = htmlspecialchars(strip_tags($this->op_mode));
            $this->battery = htmlspecialchars(strip_tags($this->battery));
            $this->op_volt = htmlspecialchars(strip_tags($this->op_volt));
            $this->op_current = htmlspecialchars(strip_tags($this->op_current));
            $this->op_power = htmlspecialchars(strip_tags($this->op_power));
            $this->ch_current = htmlspecialchars(strip_tags($this->ch_current));

            //Bind named parameters
            $stmt->bindParam(':device_id', $this->device_id);
            $stmt->bindParam(':op_mode', $this->op_mode);
            $stmt->bindParam(':battery', $this->battery);
            $stmt->bindParam(':op_volt', $this->op_volt);
            $stmt->bindParam(':op_current', $this->op_current);
            $stmt->bindParam(':op_power', $this->op_power);
            $stmt->bindParam(':ch_current', $this->ch_current);

            //Execute query
            if ($stmt->execute()) {
                return true;
            }
            
            //Error message
            printf("Error: ", $stmt->error);
            return false;
        }

        public function last_sent_notification() {
            $query = 'SELECT UNIX_TIMESTAMP(last_sent) AS last_sent FROM notifications WHERE site_id = :device_id';

            $stmt = $this->conn->prepare($query);
            
            // Clean data
            $this->device_id = htmlspecialchars(strip_tags($this->device_id));
            
            // Bind named parameters
            $stmt->bindParam(':device_id', $this->device_id);

            //Execute query
            if ($stmt->execute()) {
                return $stmt;
            }
            
            //Error message
            printf("Error: ", $stmt->error);
            return false;
        }

        public function update_last_notification_time() {
            $query = 'UPDATE notifications SET battery = :battery, last_sent = CURRENT_TIMESTAMP WHERE site_id = :device_id';

            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->device_id = htmlspecialchars(strip_tags($this->device_id));
            $this->battery = htmlspecialchars(strip_tags($this->battery));

            // Bind named parameters
            $stmt->bindParam(':device_id', $this->device_id);
            $stmt->bindParam(':battery', $this->battery);

            // Execute query
            $stmt->execute();
            return;
        }
    }