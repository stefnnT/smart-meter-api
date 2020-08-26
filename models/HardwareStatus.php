<?php

    class HardwareStatus  {
        // DB stuff
        private $conn;
        private $table = 'device_status';

        // Post Properties
        public $id;
        public $mains_state;
        public $device_state;
        public $potential_loss;
        public $bypass_state;
        public $voltage;
        public $frequency;
        public $current_consumption;
        public $kwh;
        public $temperature;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        //Update Device Status
        public function update_status() {
            //query
            $query = 'INSERT INTO '.$this->table .' SET 
                        mains_state = :mains_state, 
                        device_state = :device_state, 
                        potential_loss = :potential_loss,
                        voltage = :voltage,
                        frequency = :frequency,
                        current_consumption = :current_consumption,
                        kwh = :kwh,
                        temperature = :temperature ';
            
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->mains_state = htmlspecialchars(strip_tags($this->mains_state));
            $this->device_state = htmlspecialchars(strip_tags($this->device_state));
            $this->potential_loss = htmlspecialchars(strip_tags($this->potential_loss));
            $this->bypass_state = htmlspecialchars(strip_tags($this->bypass_state));
            $this->voltage = htmlspecialchars(strip_tags($this->voltage));
            $this->frequency = htmlspecialchars(strip_tags($this->frequency));
            $this->current_consumption = htmlspecialchars(strip_tags($this->current_consumption));
            $this->kwh = htmlspecialchars(strip_tags($this->kwh));
            $this->temperature = htmlspecialchars(strip_tags($this->temperature));

            //Bind named parameters
            $stmt->bindParam(':mains_state', $this->mains_state);
            $stmt->bindParam(':device_state', $this->device_state);
            $stmt->bindParam(':potential_loss', $this->potential_loss);
            $stmt->bindParam(':bypass_state', $this->bypass_state);
            $stmt->bindParam(':voltage', $this->voltage);
            $stmt->bindParam(':frequency', $this->frequency);
            $stmt->bindParam(':current_consumption', $this->current_consumption);
            $stmt->bindParam(':kwh', $this->kwh);
            $stmt->bindParam(':temperature', $this->temperature);

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