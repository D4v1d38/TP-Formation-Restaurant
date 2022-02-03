<?php
    require "models/Order.php";
   
    
// pas besoin de declarer la classe Meal car elle est deja declaré dans mealcontroller()
// l'instance etant faite avant ordercontroler donc pas utile de declarer ici.
// require "models/Meal.php";

class OrderController{

    
    private $meal;
    private $orderBdd;
    private $user;
    private $connect;
    private $order;
    private $AdminConnect;

    
    public function __construct(){
        
        $this->meal             = new Meal();
        $this->orderBdd         = new Order();
        $this->user             = new User();
        $this->connect          = new UserController(); //pour verifier que l'utilisateur est bien connecté
        $this->order            = new  Order();
        $this->adminConnect     = new AdminController();
    }
    
    public function order(){
        if( $this->connect ->is_Connect() == true){
        
            $mealsList =  $this->meal->getMeals();

            $template ="order/order";
        
            require 'www/layout.phtml';
            
        }else{
            header("location:index.php");
            exit();
        }
    }
    
    //cette méthode renvoie le détail d'un article sélectionné grâce à un id. 
    
    public function cmdAjax(){
        if( $this->connect ->is_Connect() == true){
            
            //récupérer l'id du repas 
            $id_meal = $_GET['id_meal'];
        
            //appeler la méthode getMealById 
            $oneMeal = $this->meal->getMealById($id_meal);

            //Appel au layout
    
            require "www/order/orderDetails.phtml";
        
        }else{
            header("location:index.php");
            exit();
        }
    }
    
    
    public function panierAjax(){
        if( $this->connect ->is_Connect() == true){
            
            //récupérer le paramètre id_meal
            $id_meal = $_GET["id_meal"];
            $meal = $this -> meal -> getMealById($id_meal);
            
            //echo json_encode($meal[$id_meal]);
            echo json_encode($meal);
            
        }else{
            header("location:index.php");
            exit();
        }

    }
    
    public function addOrderBdd(){
        if( $this->connect ->is_Connect() == true){
            
            $id_user = $_SESSION['idUser'];
            $commande = $_GET['commande'];
            $montantTotal = $_GET['montantTotal'];
        
        
            //json --> php
            // on recupere un fichier JSON on le convertie en PHP
            $commande = json_decode($commande);
            
            //on recupere l' ID de la derniere commande
            $lastIdCmd = $this->orderBdd->addOrder($id_user,$montantTotal);
            
            //appel à la methode
            $test= $this->orderBdd-> addDetailsOrder($lastIdCmd,$commande);
            
            // return $test;
            
            //on recuper un user par son id
            
            $userInfos=$this->user->getUserById( $id_user);
            
            //On appelle le layout recapCommande pour afficher la confirmation de la commande 
             require "www/order/recapCommande.phtml";
             
        }else{
            header("location:index.php");
            exit();
        }
    }
    
    
    // Methode Admin
    
    public function editOrder(){
        if($this->adminConnect->isadmin() == true){
            
            if(array_key_exists('id_com',$_GET)){
                
               $idCmd = htmlspecialchars($_GET['id_com']);
               
               $detailsOrder = $this->order->OrderDetails($idCmd);

            }else{
                $ordersList = $this->order->OrdersList();
            }
            
            $template = "www/admin/ordersEdit";

            require "www/layout.phtml";
            
        }else{
            header('location:index.php');
            exit();
        }
    }
    
    public function orderDeleteDetail(){
        if($this->adminConnect->isadmin() == true){
            
            if(array_key_exists('id_com',$_GET)){
                
                $idCmd = htmlspecialchars($_GET['id_com']);
                
                $delete = $this->order->deleteDetailOrder($idCmd);
                
                if($delete == true){
                    $message = "Réservation supprimée avec succès";
                    header('location:index.php?action=admin&message='.$message."&class=success");
                }else{
                    $message = "Une erreur est survenue";
                    header('location:index.php?action=admin&message='.$message."&class=error");
                }
            }
        }else{
            header('location:index.php');
        }
    }
}