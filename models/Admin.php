<?php

    class Admin  {
      // DB stuff
      private $conn;
      // private $table = 'device_status';

      // Post Properties
      public $id;
      public $first_name;
      public $last_name;
      public $company_name;
      public $email;
      public $address;
      public $phone;
      public $role_id;
      public $password;
      public $role;

      // Constructor with DB
      public function __construct($db) {
          $this->conn = $db;
      }
      
      // create staekholders' accounts
      public function create_account() {
          //query
          $query = 'INSERT INTO stakeholders SET 
                      first_name = :first_name,
                      last_name = :last_name, 
                      company_name = :company_name, 
                      email = :email, 
                      address = :address, 
                      phone = :phone,
                      role_id = :role_id';
          
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->first_name = htmlspecialchars(strip_tags($this->first_name));
          $this->last_name = htmlspecialchars(strip_tags($this->last_name));
          $this->company_name = htmlspecialchars(strip_tags($this->company_name));
          $this->email = htmlspecialchars(strip_tags($this->email));
          $this->address = htmlspecialchars(strip_tags($this->address));
          $this->phone = htmlspecialchars(strip_tags($this->phone));
          $this->role_id = htmlspecialchars(strip_tags($this->role_id));
          
          //Bind named parameters
          $stmt->bindParam(':first_name', $this->first_name);
          $stmt->bindParam(':last_name', $this->last_name);
          $stmt->bindParam(':company_name', $this->company_name);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':address', $this->address);
          $stmt->bindParam(':phone', $this->phone);
          $stmt->bindParam(':role_id', $this->role_id);

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
                    email = :email,
                    password = :password, 
                    stakeholder_id = (SELECT stakeholder_id FROM stakeholders WHERE email = :email) ';
        
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        
        //Bind named parameters
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        
        //Execute query
        if ($stmt->execute()) {
          return true;
        }
        
        //Error message
        printf("Error: ", $stmt->error);
        return false;
      }

      // create new stakeholder roles 
      public function add_role() {
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

      // set default tarriffs for discos
      public function create_tarriff() {    //this function is untested
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