<?php

require 'models/User.php';

class UserController{
    
    private $user;
    
    public function __construct(){
        
        $this->user = new User();
    }
    
    public function createAccount(){
        
        $template = "user/create_account";
        
        if(!empty($_POST)){
            $email = $_POST['email'];
            $user  = $this->user->getUserByEmail($email);
            
            if($user){
                $message = "Erreur : L'adresse mail existe déjà !";
            }
            else{
                $prenom     = htmlspecialchars($_POST['prenom']);
                $nom        = htmlspecialchars($_POST['nom']);
                $email      = htmlspecialchars($_POST['email']);
                $adresse    = htmlspecialchars($_POST['adresse']);
                $codepostal = htmlspecialchars($_POST['codePostal']);
                $ville      = htmlspecialchars($_POST['ville']);
                $phone      = htmlspecialchars($_POST['phone']);
                $password   = htmlspecialchars($_POST['password']);
                $password   = password_hash($password,PASSWORD_DEFAULT);
                $naissance  = htmlspecialchars($_POST['birthdate']);
                
                //appel la methode addUSer
                $test = $this->user->addUser($prenom,$nom,$email,$adresse,$codepostal,$ville,$phone,$password,$naissance);
                if($test){
                    $message = "Le Compte a bien été créée";
                    $template = "user/connect";
                }
                else{
                    $message = "Une erreur est survenue lors de la création du compte";
                }
            }
        }

        require "www/layout.phtml";
    }
    
    
    public function connexion(){
        
        $template = "user/connect";

        
        if(!empty($_POST['email']) && isset($_POST['email']) && !empty($_POST['password']) && isset($_POST['password'])){
            
            $email      = htmlspecialchars($_POST['email']);
            $password   = htmlspecialchars($_POST['password']);
            
            $user  = $this->user->getUserByEmail($email);
            
            if(!$user){
                $message = "Erreur : L'adresse mail n'existe pas !";
            }
            else{

                if(password_verify($password, $user['password'])){
                    
                    $_SESSION['user']       = htmlspecialchars($user['mail']);
                    $_SESSION['firstname']  = htmlspecialchars($user['prenom']);
                    $_SESSION['name']       = htmlspecialchars($user['nom']);
                    $_SESSION['idUser']     = htmlspecialchars($user['id_user']);
                    
                    header('location:index.php');
                    
                }else{
                    $message= "Le mot de passe est incorect";
                }
            }
        }

        require "www/layout.phtml";
    }
    
    public function is_connect(){
    
        if(isset($_SESSION['user'])){
            return true;
            
        }else{
            return false;
        }
    }
    
    public function deconnect(){
        $_SESSION = array();
        session_destroy();

        header('location:index.php');
    }
    
}