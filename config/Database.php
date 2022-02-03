<?php

// la class Database permet de faire la connexion à la base de données

    class Database{
        
        private $bdd;
        
        public function __construct(){
            $this->bdd = new PDO('mysql:host=db.3wa.io;dbname=davidrotolo_restaurant;charset=utf8',"davidrotolo","1dab2f9c1a3dc3f96a1229b7f7684115");
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