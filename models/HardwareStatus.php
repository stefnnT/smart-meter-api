<?php

    class HardwareStatus  {
        // DB stuff
        private $conn;
        private $table = 'device_status';

        // Post Properties
        public $id;
        public $meter_number;
        public $mains_in;
        public $mains_out;
        public $device_state;
        public $potential_loss;
        public $bypass_state;
        public $voltage;
        public $frequency;
        public $current_consumption;
        public $kwh;
        public $temperature;
        public $temp_everything;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }
        
        //Update Device Status
        public function update_status() {
            //query
            $query = 'INSERT INTO '.$this->table .' SET 
                        meter_id = :meter_id,
                        mains_in = :mains_in, 
                        mains_out = :mains_out, 
                        device_state = :device_state, 
                        potential_loss = :potential_loss,
                        bypass_state = :bypass_state,
                        voltage = :voltage,
                        frequency = :frequency,
                        current_consumption = :current_consumption,
                        kwh = :kwh,
                        temp_everything = :temp_everything,
                        temperature = :temperature ';
            
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->meter_id = htmlspecialchars(strip_tags($this->meter_number));
            $this->mains_in = htmlspecialchars(strip_tags($this->mains_in));
            $this->mains_out = htmlspecialchars(strip_tags($this->mains_out));
            $this->device_state = htmlspecialchars(strip_tags($this->device_state));
            $this->potential_loss = htmlspecialchars(strip_tags($this->potential_loss));
            $this->bypass_state = htmlspecialchars(strip_tags($this->bypass_state));
            $this->voltage = htmlspecialchars(strip_tags($this->voltage));
            $this->frequency = htmlspecialchars(strip_tags($this->frequency));
            $this->current_consumption = htmlspecialchars(strip_tags($this->current_consumption));
            $this->kwh = htmlspecialchars(strip_tags($this->kwh));
            $this->temperature = htmlspecialchars(strip_tags($this->temperature));
            $this->temp_everything = htmlspecialchars(strip_tags($this->temp_everything));
            
            //Bind named parameters
            $stmt->bindParam(':meter_id', $this->meter_id);
            $stmt->bindParam(':mains_in', $this->mains_in);
            $stmt->bindParam(':mains_out', $this->mains_out);
            $stmt->bindParam(':device_state', $this->device_state);
            $stmt->bindParam(':potential_loss', $this->potential_loss);
            $stmt->bindParam(':bypass_state', $this->bypass_state);
            $stmt->bindParam(':voltage', $this->voltage);
            $stmt->bindParam(':frequency', $this->frequency);
            $stmt->bindParam(':current_consumption', $this->current_consumption);
            $stmt->bindParam(':kwh', $this->kwh);
            $stmt->bindParam(':temperature', $this->temperature);
            $stmt->bindParam(':temp_everything', $this->temp_everything);

            //Execute query
            if ($stmt->execute()) {
                return true;
            }
            
            //Error message
            printf("Error: ", $stmt->error);
            return false;
        }

        public function get_hardware_state() {
            $query = 'SELECT * FROM meter_state WHERE meter_number = :meter_number LIMIT 1';

            $stmt = $this->conn->prepare($query);
            
            // Clean data
            $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
            
            // Bind named parameters
            $stmt->bindParam(':meter_number', $this->meter_number);

            //Execute query
            if ($stmt->execute()) {
                return $stmt;
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