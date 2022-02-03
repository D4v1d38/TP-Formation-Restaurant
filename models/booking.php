<?php

class Booking{
    
    // PROPRIETES
    private $database;
    private $bdd;
    
    public function __construct(){
        $this->database = new Database();
        $this->bdd = $this->database -> getConnectBdd();
    }
    
    // METHODES
    public function insertResa($idUser,$dateTime,$nbCouverts){
        
        $query = $this->bdd->prepare('INSERT INTO reservation( id_user, date, nb_couverts) 
                                    VALUES (?,?,?)');
                                        
        $reservation = $query->execute([$idUser, $dateTime, $nbCouverts]);
        
        return $reservation;
        
    }

    // METHODES ADMIN

    public function getBookingList(){
        $query = $this->bdd->prepare('SELECT id_reservation, date, nb_couverts, prenom, nom, email,  tel
                                      FROM reservation
                                      INNER JOIN user ON user.id_user = reservation.id_user
                                      WHERE date >= NOW()
                                      ORDER BY date ASC');
        
        $query->execute();
        $listReservation = $query->fetchAll();

        return $listReservation;

    }

    public function getBookingById($idBooking){
        $query = $this->bdd->prepare('SELECT id_reservation, DATE_FORMAT(date,"%d")AS day,DATE_FORMAT(date,"%m")AS month,DATE_FORMAT(date,"%Y")AS year, DATE_FORMAT(date,"%H")AS hour, DATE_FORMAT(date,"%i")AS minutes,nb_couverts, prenom, nom, email,  tel,adresse,code_postal,ville
                                      FROM reservation
                                      INNER JOIN user ON user.id_user = reservation.id_user
                                      WHERE id_reservation = ?
                                    ');
        
        $query->execute([$idBooking]);
        
        $oneReservation = $query->fetch();

        return $oneReservation;

    }

    public function upDateBookingById($nbCouvert,$idReservation,$fullDate){

        $query = $this->bdd->prepare('UPDATE reservation 
                                      SET nb_couverts=?,
                                          date=?
                                      WHERE id_reservation=?
                                    ');
        
        $test = $query->execute([$nbCouvert,$fullDate,$idReservation]);

        return $test;

    }

    public function deleteBooking($idBooking){

        $query = $this->bdd->prepare('DELETE FROM reservation 
                                      WHERE id_reservation=?');
        
        $test = $query->execute([$idBooking]);

        return $test;

    }
    

    
}