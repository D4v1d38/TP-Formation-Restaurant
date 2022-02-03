<?php

class Order{
    
    // PROPRIETES
    private $database;
    private $bdd;
    
    public function __construct(){
        $this->database = new Database();
        $this->bdd = $this->database -> getConnectBdd();
    }
    
    // METHODES
    public function addOrder($idUser,$montantPanier){
        
        $query = $this->bdd->prepare('INSERT INTO commandes( id_user, date, prix_total)
                                      VALUES (?,NOW(),?)');
                                        
        $order = $query->execute([$idUser, $montantPanier]);
        
        //récupérer la dernière clé primaire 
        $lastIdCmd = $this->bdd->lastInsertId();
        
        return  $lastIdCmd; //retourne la dernière clé primaire inserée 
        
    }

    public function addDetailsOrder($lastIdCmd,$commande){


        //requete pour inserer le detail de la commande en BDD
        $query = $this->bdd->prepare('INSERT INTO lignes_cmd(id_cmd, id_meal, quantite, prix_unit) 
                                      VALUES (?,?,?,?)');
                                      
        //Parcourir le tableau commande et inserer les infos par repas
        foreach($commande as $detailcommande){
            // $detailsOrder = $query->execute([$lastIdCmd,$detailcommande['id_meal'], $detailcommande['quantite'], $detailcommande['prix']]);
            $detailsOrder = $query->execute([$lastIdCmd,$detailcommande[1]->id_meal, $detailcommande[0], $detailcommande[1]->prix]);
        }
        

        return $detailsOrder;
        
    }
    
    public function OrdersList(){
        
        $query = $this->bdd->prepare('SELECT id_commande,user.id_user,date,prix_total,nom,prenom,email,tel
                                      FROM commandes 
                                      INNER JOIN user ON user.id_user = commandes.id_user');
                                      
        $query->execute();
        
        $listOrders = $query->fetchAll();
        
        return $listOrders;
        
    }
    
    public function OrderDetails($idCmd){
        
        $query = $this->bdd->prepare('SELECT id_commande, commandes.id_user, date, prix_total, quantite,prix_unit, nom, prenom, name, quantite*prix_unit AS soustotal
                                        FROM commandes 
                                        INNER JOIN lignes_cmd ON lignes_cmd.id_cmd = commandes.id_commande
                                        INNER JOIN user ON user.id_user = commandes.id_user
                                        INNER JOIN meal ON  lignes_cmd.id_meal =  meal.id_meal
                                        WHERE commandes.id_commande =?');
        $query->execute([$idCmd]);
        
        $detailOfOrder = $query->fetchAll();
        
        return $detailOfOrder;
    }
    
    public function deleteDetailOrder($idCmd){
        $query = $this->bdd->prepare('DELETE FROM commandes 
                                      WHERE id_commande =?');
                                      
        $test= $query->execute([$idCmd]);
        
      
        
        $query2 = $this->bdd->prepare('DELETE FROM lignes_cmd 
                                       WHERE id_cmd =? ');
        
        $test2= $query2->execute([$idCmd]);
        return $test2;

    }
    
}