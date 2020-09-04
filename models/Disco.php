<?php

  class Disco  {
    // DB stuff
    private $conn;
    // private $table = 'device_status';

    // Post Properties
    public $id;
    public $first_name;
    public $last_name;
    public $address;
    public $phone;
    public $meter_number;
    public $tarriff_code;

    public $stakeholder_id;
    public $role;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // create staekholders' accounts
    public function create_account() {
        //query
        $query = 'INSERT INTO subscribers SET 
                    stakeholder_id = (SELECT stakeholder_id FROM stakeholders
                      WHERE stakeholder_id = :stakeholder_id),
                    first_name = :first_name,
                    last_name = :last_name, 
                    address = :address, 
                    phone = :phone,
                    meter_number = :meter_number, 
                    tarriff_code = :tarriff_code,
                    units_left = "100.00"';
        
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->stakeholder_id = htmlspecialchars(strip_tags('23')); //this should be gotten from session
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
        $this->tarriff_code = htmlspecialchars(strip_tags($this->tarriff_code));
        
        //Bind named parameters
        $stmt->bindParam(':stakeholder_id', $this->stakeholder_id);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':meter_number', $this->meter_number);
        $stmt->bindParam(':tarriff_code', $this->tarriff_code);

        //Execute query
        if ($stmt->execute()) {
            return true;
        }
        
        //Error message
        printf("Error: ", $stmt->error);
        return false;
    }

    // create stakeholders' login details
    public function add_authentication() {
      //query
      $query = 'INSERT INTO user_authentication SET 
                  email = :meter_number,
                  password = :password, 
                  stakeholder_id = (SELECT stakeholder_id FROM stakeholders WHERE stakeholder_id = :stakeholder_id ) ';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->meter_number = htmlspecialchars(strip_tags($this->meter_number)); 
      $this->password = htmlspecialchars(strip_tags($this->password));
      $this->stakeholder_id = htmlspecialchars(strip_tags('23')); //this should be bound to session id  
      
      //Bind named parameters
      $stmt->bindParam(':stakeholder_id', $this->stakeholder_id); 
      $stmt->bindParam(':meter_number', $this->meter_number); 
      $stmt->bindParam(':password', $this->last_name);
      
      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    }

    public function add_harware_state_control() {
      $query = 'INSERT INTO meter_state SET 
                   meter_number = :meter_number,
                   state = 5';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
      
      //Bind named parameters
      $stmt->bindParam(':meter_number', $this->meter_number);
      
      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    }

    public function add_meter_recharge() {
      $query = 'INSERT INTO meter_recharge SET 
                   meter_number = :meter_number,
                   units = 0,
                   loaded = "no"';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->meter_number = htmlspecialchars(strip_tags($this->meter_number));
      
      //Bind named parameters
      $stmt->bindParam(':meter_number', $this->meter_number);
      
      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    }



// not yet done
    // create new stakeholder roles 
    public function edit_billing() {
      //query
      $query = 'INSERT INTO roles SET 
                  role = :role';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->role = htmlspecialchars(strip_tags($this->role));
      
      //Bind named parameters
      $stmt->bindParam(':role', $this->role);
      
      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    }


    //GET FUNCTIONS
    public function get_all_subscribers() {
      //query
      $query = 'SELECT * FROM  subscribers';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();
      

      return $stmt;
    }

    public function get_one_subscriber() {
      //query
      $query = 'SELECT * FROM subscribers WHERE meter_number = :meter_number LIMIT 1';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      
      $stmt->bindParam(':meter_number', $this->meter_number);

      // Execute query
      $stmt->execute();      

      return $stmt;
    }


    public function get_tarriff_price($tarriff_code, $disco_id) {
      //query
      $query = 'SELECT '.$tarriff_code.' FROM  tarriffs WHERE stakeholder_id = :stakeholder_id';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->stakeholder_id = htmlspecialchars(strip_tags($disco_id));

      // Bind paramenter
      $stmt->bindParam(':stakeholder_id', $this->stakeholder_id); 
      
      // Execute query
      $stmt->execute();

      return $stmt->fetch()->{$tarriff_code};
    }

    public function get_last_status($meter_number) {
      //query
      $query = 'SELECT meter_id, mains_in, mains_out, device_state, 
                  potential_loss, bypass_state, format(voltage, 1) as voltage,
                  format(frequency, 1) as frequency,
                  format(current_consumption, 1) as current_consumption,
                  format(kwh, 2) as kwh, format(kwh_used, 3) as kwh_used,
                  format(temperature, 1) as temperature, time_stamp
                  FROM  device_status WHERE meter_id = :meter_number ORDER BY time_stamp DESC LIMIT 1';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      // $this->meter_number = htmlspecialchars(strip_tags($meter_number));

      // Bind paramenter
      $stmt->bindParam(':meter_number', $meter_number); 
      
      // Execute query
      $stmt->execute();

      // echo $stmt->rowCount();
      if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch();
        return array(
          "mainsIn" => $result->mains_in,
          "mainsOut" => $result->mains_out,
          "potentialLoss" => $result->potential_loss,
          "tamperState" => $result->bypass_state,
          "voltage" => $result->voltage,
          "frequency" => $result->frequency,
          "temperature" => $result->temperature,
          "currentConsumption" => $result->current_consumption,
          "unitLeft" => $result->kwh,
          "lastUpdated" => strtotime($result->time_stamp)
        );
      }

      return null;
    }







    // set default tarriffs for discos
    public function create_tarriff() {   
      //query
      $query = 'INSERT INTO tarriffs (
                stakeholder_id, r1, r2s, r2t, 
                r3, r4, c1s, c1t, c2, c3, d1, 
                d2, d3, a1, a2, a3, s1, created_at)
                SELECT (SELECT stakeholder_id FROM stakeholders
                  WHERE email = :email), r1, r2s, r2t, 
                  r3, r4, c1s, c1t, c2, c3, d1, 
                  d2, d3, a1, a2, a3, s1, CURRENT_TIMESTAMP
                  FROM tarriffs WHERE stakeholder_id = (
                    SELECT Stakeholder_id FROM stakeholders WHERE role_id = 1 ) ';
      
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->email = htmlspecialchars(strip_tags("email2@gmail.com")); //this should take in sesssion email
      
      //Bind named parameters
      $stmt->bindParam(':email', $this->email);
      
      //Execute query
      if ($stmt->execute()) {
        return true;
      }
      
      //Error message
      printf("Error: ", $stmt->error);
      return false;
    
    }

    public function tofloat($num) {
      $dotPos = strrpos($num, '.');
      $commaPos = strrpos($num, ',');
      $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
          ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
    
      if (!$sep) {
          return floatval(preg_replace("/[^0-9]/", "", $num));
      }
  
      return floatval(
          preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
          preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
      );
    }





    // Retrieve all stakeholders
    public function read_all() {
      //query
      $query = 'SELECT * FROM '. $this->table;

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();
      

      return $stmt;
    }

    // Retrieve one stakeholder
    public function read_one() {
      //query
      $query = 'SELECT * FROM '. $this->table;

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();
      

      return $stmt;
    }


  }