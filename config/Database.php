<?php

// la class Database permet de faire la connexion à la base de données

    class Database{
        
        private $bdd;
        
        public function __construct(){
            $this->bdd = new PDO('mysql:host=***host***;dbname=davidrotolo_restaurant;charset=utf8',"***user***","***password***");
        }
        
        public function getConnectBdd(){
            return $this->bdd;
        }
    }    
    
    
    //test de la connection a la bdd , 
    //faire une instance de Databse et appelme le getter pour la connection
    // $connect= new Database();
    // var_dump($connect->getConnectBdd());

?>
