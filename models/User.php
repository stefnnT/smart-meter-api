<?php

  class User  {
    // DB stuff
    private $conn;
    // private $table = 'device_status';

    // Post Properties
    public $meter_number;
    public $amount;
    public $state;
    public $tarriff;
    public $units;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }
    
    
    // update state
    public function update_hardware_state() {
      $query = 'UPDATE meter_state SET 
                  state = :state,
                  last_updated = CURRENT_TIMESTAMP
                  WHERE
                  meter_number = :meter_number';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
      $this->state = htmlspecialchars(strip_tags($this->state));
      
      //Bind named parameters
      $stmt->bindParam(':meter_number', $this->meter_number);
      $stmt->bindParam(':state', $this->state);
      
      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    }

    public function calc_units($tarriff, $amount, $stakeholder_id) {
      $query = 'SELECT * FROM tarriffs WHERE stakeholder_id = :stakeholder_id LIMIT 1';

      $stmt = $this->conn->prepare($query);
      
      // Clean data
      // $tarriff = htmlspecialchars(strip_tags($tarriff));
      $stakeholder_id = htmlspecialchars(strip_tags($stakeholder_id));
      
      // Bind named parameters
      // $stmt->bindParam(':tarriff', $tarriff);
      $stmt->bindParam(':stakeholder_id', $stakeholder_id);

      //Execute query
      if ($stmt->execute()) {
          if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            return number_format($amount/$result->{$this->tarriff}, 2);
          }
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    }

    public function recharge_status() {
      $query = 'INSERT INTO meter_recharge SET
                  meter_number = :meter_number,
                  units = :units,
                  loaded = "no"';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
      $this->units = htmlspecialchars(strip_tags($this->calc_units($this->tarriff, $this->amount, $this->stakeholder_id)));
      
      
      //Bind named parameters
      $stmt->bindParam(':meter_number', $this->meter_number);
      $stmt->bindParam(':units', $this->units);
      

      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
      
    }


  }