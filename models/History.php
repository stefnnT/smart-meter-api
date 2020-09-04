<?php

    class History  {
      // DB stuff
      private $conn;

      public $meter_number;

      // Constructor with DB
      public function __construct($db) {
        $this->conn = $db;
      }

      public function today_usage() {
        // query
        $query = "SELECT sum(kwh_used) as kwh_used FROM device_status WHERE meter_id = :meter_number AND time_stamp > DATE_SUB(NOW(), INTERVAL 1 DAY) ";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
            
        // Bind named parameters
        $stmt->bindParam(':meter_number', $this->meter_number);

        if ($stmt->execute()) {
          return number_format($stmt->fetch()->kwh_used, 2);
        } 

      }

      public function week_usage() {
        // query
        $query = "SELECT sum(kwh_used) as kwh_used FROM device_status WHERE  meter_id = :meter_number AND time_stamp > DATE_SUB(NOW(), INTERVAL 1 WEEK) ";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
            
        // Bind named parameters
        $stmt->bindParam(':meter_number', $this->meter_number);

        if ($stmt->execute()) {
          return number_format($stmt->fetch()->kwh_used, 2);
        } 

      }

      public function month_usage() {
        // query
        $query = "SELECT sum(kwh_used) as kwh_used FROM device_status WHERE  meter_id = :meter_number AND time_stamp > DATE_SUB(NOW(), INTERVAL 1 MONTH) ";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
            
        // Bind named parameters
        $stmt->bindParam(':meter_number', $this->meter_number);

        if ($stmt->execute()) {
          return number_format($stmt->fetch()->kwh_used, 2);
        } 

      }

    }
