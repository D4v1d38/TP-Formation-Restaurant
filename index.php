<?php
session_start();
//appel BDD
require "config/Database.php";

//appel des controllers
require "controllers/MealController.php";
require "controllers/UserController.php";
require "controllers/BookingController.php";
require "controllers/OrderController.php";
require "controllers/AdminController.php";

//instanciations des controllers
$userController     = new UserController();
$mealController     = new MealController();
$bookingController  = new BookingController();
$orderController    = new OrderController();
$adminController    = new AdminController();


//on controle le contenu du paramètre action envoyé par l'url
//SI action = creat_account on appel la methode createAccount()
//SINON on afffiche la liste de splats

if(array_key_exists('action',$_GET)){
    
    switch($_GET['action']){
        case "create_account" :
            $userController->createAccount();
        break;
        case "connexion" :
            $userController->connexion();
        break;
        case "deconnect" :
            $userController->deconnect();
        break;
        case "booking":
            $bookingController->booking();
        break;
        case "order" :
            $orderController->order();
        break;
        case "ajax" :
            $orderController->cmdAjax();   //appel à la méthode cmdAjax
        break;
        case "panier" :
             $orderController->panierAjax();
        break;
        case "passerCmd" :
            $orderController->addOrderBdd();
        break;
        case "admin":
            $adminController->admin();
        break;
        case "admin_deconnect":
            $adminController->deconnect();
        break;
        case "edit_product" :
            $mealController->editProduct(); //modification des menus
        break;
        case "delete_product" :
            $mealController->deleteProduct(); //suppression d'un menu
        break;
        case "add_article" :
            $mealController->addMeal();
        break;
        case "edit_booking" :
            $bookingController->editBooking();
        break;
        case "delete_booking" :
            $bookingController->deleteBooking();
        break;
        case  "orders_Edit" :
            $orderController->editOrder();
        break;
        case "delete_order" :
            $orderController->orderDeleteDetail();
            
    }
}
else{

    $mealController->listMeal();
}




