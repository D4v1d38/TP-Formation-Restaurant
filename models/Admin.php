<?php
    
    class Admin{
        
        // PROPRIETES
        private $database;
        private $bdd;
        
        // CONSTRUCTEUR
        
        public function __construct(){
            
            $this->database = new Database();
            $this->bdd = $this->database->getConnectBdd();
            
        }
        
        // METHODES
        
        public function getAdminByEmail($email){
            $query = $this->bdd->prepare('SELECT id_admin,pseudo,email,password 
                                          FROM admin 
                                          WHERE email= ?');
                                          
           $query->execute([$email]);
           
           $admin=$query->fetch();
           
           return $admin;
        }
        
    }
    
    
?>