<?php

//on inclus le model

require 'models/booking.php';


class BookingController{
    
    private $booking;
    private $connect;
    
    
    public function __construct(){
        $this->booking = new Booking();  //instance du model Booking
        $this->connect = new UserController();
        $this->adminConnect = new AdminController();
        
    }

    public function booking(){
        
        if($this->connect->is_Connect() == true){
            
            $template = "booking/booking";
    
                if(isset($_POST['id_user'],$_POST['number-person'],$_POST['date'],$_POST['hour'])){
                    
                    if(!empty($_POST['id_user']) && !empty($_POST['number-person']) && !empty($_POST['date']) && !empty($_POST['hour'])){
                        
                        $idUser     = htmlspecialchars($_POST['id_user']); 
                        $nbCouverts = htmlspecialchars($_POST['number-person']);
                        $dateTime   = htmlspecialchars($_POST['date'])." ".htmlspecialchars($_POST['hour']);
                        
                        //on range les données dans la variable $reservation pour pouvoir afficher des messages d'erreurs
                        $reservation = $this->booking->insertResa($idUser,$dateTime,$nbCouverts);
                        
                        if($reservation){
                            $message = " Votre réservation a été enregistrée pour " .$nbCouverts." personne(s) le ". $dateTime ;
                        }
                        else{
                            
                            $message = " Une erreur est survenue , vore réservation n'a pas pu etre prise en compte";
                        }  
                    }else{
                        $message = " Merci de remplir tous les champs ";
                    }
                    
                }

                 require "www/layout.phtml";
        }else{
            header("location:index.php");
            exit();
        }
    }

    public function editBooking(){
        if($this->adminConnect->isAdmin() == true){

            if(!empty($_POST)){
                
                $date = htmlspecialchars($_POST['date']);
                $time = htmlspecialchars($_POST['hour']);
                $nbCouvert = htmlspecialchars($_POST['number-person']);
                $idReservation = htmlspecialchars($_POST['id_reservation']);

                $fullDate = $date." ".$time;

                $reservation = $this->booking->upDateBookingById($nbCouvert,$idReservation,$fullDate);
                
                if($reservation == true){
                    $message = "Modification de la reservation reussie";
                    header("location:index.php?action=admin&message=".$message."&class=success");
                }else{
                    $message = "Une erreur est survenue lors de la mise a jour de la reservation";
                    header("location:index.php?action=admin&message=".$message."&class=error");                    
                }

            }else{
                if(array_key_exists('id_reservation',$_GET)){
                
                    $idResa = htmlspecialchars($_GET['id_reservation']);
    
                    $oneResa = $this->booking->getBookingById($idResa);
    
                }else{
                    $listeresa= $this->booking->getBookingList();
                }
            }
            $template="admin/bookingEdit";
           
            require "www/layout.phtml";

        }else{
            header('locaion:index.php');
        }
    }

    public function deleteBooking(){
        if($this->adminConnect->isAdmin()){
            if(array_key_exists('id_reservation',$_GET)){

                $idResa = htmlspecialchars($_GET['id_reservation']);
                $oneResa = $this->booking->deleteBooking($idResa);
                
                if($oneResa == true){
                    $message = "Réservation supprimée avec succès";
                    header('location:index.php?action=admin&message='.$message."&class=success");
                }else{
                    $message = "Une erreure est survenue lors de la suppression";
                    header('location:index.php?action=admin&message='.$message."&class=error"); 
                }
            }
        }else{
            header('location:index.php');
        }
    }
}