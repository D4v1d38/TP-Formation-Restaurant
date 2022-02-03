<?php

class Meal{
    
    // PROPRIETES
    private $database;
    private $bdd;
    
    // CONSTRUCTOR
    
    public function __construct(){
        // creer une instance de la Database
        $this -> database = new Database();
        $this->bdd = $this -> database -> getConnectBdd(); 
    }
    
    // METHODES
    
    // permet de sélectionner tous les aliments de la base de données.
    
    public function getMeals(){
        //faire toutes les etapes pour une requete SELECT
        
        $query = $this->bdd->prepare('SELECT id_meal,name,description,prix,photo
                                      FROM meal');
        
        $query->execute();
        
        $listMeal = $query->fetchAll();
        
        return  $listMeal;
    }
    
    // selectionne un repas selon son ID
    public function getMealById($id_meal){
        
        
        $query = $this->bdd->prepare('SELECT id_meal, name, description, prix,photo
                                      FROM meal 
                                      WHERE  id_meal=?');
                                      
        $query->execute([$id_meal]);
        
        $oneMeal = $query->fetch();
        
        return  $oneMeal;
    }
    
    // Mise à jour du repas avec image
    
    public function upDateMealWithImage($menuName,$menuDescription,$menuPrice,$uniqueName,$idMeal){
        
        $query = $this->bdd->prepare('UPDATE meal 
                                      SET name =?,description=?,prix=?,photo =?
                                      WHERE id_meal=?');
                                      
        $query->execute([$menuName,$menuDescription,$menuPrice,$uniqueName,$idMeal]);
    }
    
    //Mise à jour du repas sans image

    public function upDateMealWithOutImage($idMeal,$menuName,$menuDescription,$menuPrice){
        
        $query = $this->bdd->prepare('UPDATE meal 
                                      SET name =?,description=?,prix=?
                                      WHERE id_meal=?');
                                      
        $query->execute([$menuName,$menuDescription,$menuPrice,$idMeal]);
    }
    
    // Suppression d'un menu
    
    public function deleteMealById($id_meal){
        
        $query = $this->bdd->prepare('DELETE FROM meal 
                                     WHERE id_meal = ?');
        $query->execute([$id_meal]);
        
        return true;
    }
    
    //Ajouter un menu

    public function addMenu($menuName,$menuDescription,$menuPrice,$uniqueName){
        
        $query = $this->bdd->prepare('INSERT INTO meal(name, description, prix, photo)
                                      VALUES (?,?,?,?)');
                                      
        $query->execute([$menuName,$menuDescription,$menuPrice,$uniqueName]);
        return true;
    }
    
    
}


