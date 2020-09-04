<?php

  class MeterDetails {
    // DB stuff
    private $conn;
    private $table = 'all_details';
    
    // For read_single function 
    public $site_name;
    public $device_id;
    
    
    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    public function read_all() {
        //query
        $query = 'SELECT * FROM '. $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();
        

        return $stmt;
    }
  }