<?php

class User{
    
    // PROPRIETES
    private $database; //instanciation de la connexion a la bdd
    private $bdd; //on range les infos dans $bdd
    

   // CONSTRUCTEUR
    public function __construct(){
        $this->database = new Database();   
        $this->bdd = $this->database->getConnectBdd();
    }
    
    // METHODES
    public function addUser($prenom,$nom,$email,$adresse,$codepostal,$ville,$phone,$password,$naissance){
        $query = $this->bdd->prepare('INSERT INTO `user`(`prenom`, `nom`, `email`, `adresse`, `code_postal`, `ville`, `tel`, `password`, `naissance`) 
                                      VALUES (?,?,?,?,?,?,?,?,?)');
                                      
       $test = $query->execute([$prenom,$nom,$email,$adresse,$codepostal,$ville,$phone,$password,$naissance]);
       return $test; //en rangeant dans $test cela nous permet de faire des test if $test= true la requete est OK si false = pb de requete
        
    }
   
    public function getUserByEmail($email){
        $query = $this->bdd->prepare("SELECT id_user, nom,prenom,email,adresse,code_postal,ville,tel,password,naissance 
                                      FROM user
                                      WHERE email= ?");
        $query->execute([$email]);
        
        $user = $query->fetch();
        
        return $user;
    }
    
    public function getUserByID($id){
        $query = $this->bdd->prepare("SELECT id_user, nom,prenom,email,adresse,code_postal,ville,tel,password,naissance 
                                      FROM user
                                      WHERE id_user= ?");
        $query->execute([$id]);
        
        $user = $query->fetch();
        
        return $user;
    }
    
    
}