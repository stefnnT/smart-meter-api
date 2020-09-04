<?php

  class User  {
    // DB stuff
    private $conn;
    // private $table = 'device_status';

    // Post Properties
    public $meter_number;
    public $state;

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


  }