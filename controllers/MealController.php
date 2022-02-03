<?php

// on inclus le model
require 'models/Meal.php';

class MealController{
    
    private $meal;
     
    
    public function __construct(){
        $this->meal = new Meal();
        $this->connect = new AdminController();
        
    }
    
    public function listMeal(){
        
        $meals = $this->meal->getMeals();
        
        $template = "home";
        
        require "www/layout.phtml";
    }
    
    
    // METHODES ADMIN
    public function editProduct(){
        if($this->connect->isAdmin() == true){
            
            if(!empty($_POST)){
    
                //traitement des données recus par le formulaire
    
                $idMeal             = htmlspecialchars($_POST['id_menu']);
                $menuName           = htmlspecialchars($_POST['menu_name']);
                $menuDescription    = htmlspecialchars($_POST['menu_description']);
                $menuPrice          = htmlspecialchars($_POST['menu_price']);
                      
                        
                //traitement des images
                if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){
                            
                    $tmpName    = $_FILES['photo']['tmp_name'];
                    $name       = strtolower($_FILES['photo']['name']);
                    $size       = $_FILES['photo']['size'];
                    $error      = $_FILES['photo']['error'];
                            
                    // définition des extension acceptées
                    $extensionsValid = ['jpeg','png','jpg'];
                            
                    //recuperation de l'extension du fichier
                    
                    $filesDecomposition = explode('.',$name);
                    $filetitle          = $filesDecomposition[0];
                    $filesExtension     = strtolower(end($filesDecomposition));
                    $filesMax           = 300000;
                            
                            
                    if(in_array($filesExtension,$extensionsValid) && $size <= $filesMax && $error == 0){
                           
                        //on definit un nom unique avec le timestamp
                        $uniqueName = $filetitle."_".time().".".$filesExtension;

                        if(isset($uniqueName)){

                            move_uploaded_file($tmpName,'www/images/'.$uniqueName);
                            //Appel au modele de mise a jour avec image
                            $meal = $this->meal->upDateMealWithImage($menuName,$menuDescription,$menuPrice,$uniqueName,$idMeal);
                        }
                    }
                }else{
                    //Appel au modele de mise a jour sans image
                    $meal = $this->meal->upDateMealWithOutImage($idMeal,$menuName,$menuDescription,$menuPrice);
                }

                if($meal == true){
                    $message = "Modification du repas effectuée avec succès !";
                    header("location:index.php?action=admin&message=".$message."&class=success");
                }else{
                    $message = "Une erreur est survenue lors de la modification";
                    header('location:index.php?action=admin&message='.$message."&class=error");
                }
                
            }else{
                        
                if(array_key_exists('id_meal',$_GET)){
                    
                    $meal = $this->meal->getMealById($_GET['id_meal']); 
                }else{
                
                    $meals = $this->meal->getMeals();
                }   
            }
        
            $template = "admin/adminEdit";
            
            require "www/layout.phtml";
        }else{
            header("location:index.php");
            exit();
            
        }
    }    
    
    public function deleteProduct(){
        if($this->connect->isAdmin() == true){
            // appeler fonction du model pour requette de suppression
            if(array_key_exists('id_meal',$_GET)){
                
                $id_meal = htmlspecialchars($_GET['id_meal']);
                    
                $delete = $this->meal->deleteMealById($id_meal);
                
                if($delete == true){
                    $message = "Produit supprimé avec succès !";
                    header("location:index.php?action=admin&message=".$message."&class=success");
                    exit();
                }else{
                    $message = "Une erreur est survenue";
                    header('location:index.php?action=admin&message='.$message."&class=error"); 
                }
                
            }
        }else{
                header("location:index.php");
                exit();
        }
    }
    
    
    public function addMeal(){
        
        
        if($this->connect->isAdmin() == true){
            
            if(!empty($_POST)){
                $add=false;
                $addDefault=false;
                //traitement des données recus par le formulaire
    
                $menuName           = htmlspecialchars($_POST['menu_name']);
                $menuDescription    = htmlspecialchars($_POST['menu_description']);
                $menuPrice          = htmlspecialchars($_POST['menu_price']);
                
                //traitement des images
                if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){
                            
                    $tmpName    = $_FILES['photo']['tmp_name'];
                    $name       = strtolower($_FILES['photo']['name']);
                    $size       = $_FILES['photo']['size'];
                    $error      = $_FILES['photo']['error'];
                            
                    // définition des extension acceptées
                    $extensionsValid = ['jpeg','png','jpg'];
                            
                    //recuperation de l'extension du fichier
                    
                    $filesDecomposition = explode('.',$name);
                    $filetitle          = $filesDecomposition[0];
                    $filesExtension     = strtolower(end($filesDecomposition));
                    $filesMax           = 300000;
                            
                    if(in_array($filesExtension,$extensionsValid) && $size <= $filesMax && $error == 0){
                           
                        //on definit un nom unique avec le timestamp
                        $uniqueName = $filetitle."_".time().".".$filesExtension;

                        if(isset($uniqueName)){
                            move_uploaded_file($tmpName,'www/images/'.$uniqueName);
                            //Appel au modele de mise a jour avec image
                            $add = $this->meal->addMenu($menuName,$menuDescription,$menuPrice,$uniqueName);
                        }
                    }
                }else{
                    $defaultImage = "default.jpg";
                    $addDefault = $this->meal->addMenu($menuName,$menuDescription,$menuPrice,$defaultImage);
                }
                
                if($add==true){
                    $message = "article ajouté avec succes!";
                    header("location:index.php?action=admin&message=".$message."&class=success");
                }else if($addDefault==true){
                    $message = "article ajouté avec succes avec l'image par defaut!";
                    header("location:index.php?action=admin&message=".$message."&class=info");
                }else{
                    $message = "une erreur est survenue !";
                    header("location:index.php?action=admin&message=".$message."class=error");
                }
            }else{
                
                $template = "admin/addMeal"; 
                require "www/layout.phtml";
            }      
                        
        }else{
                header("location:index.php");
                exit();
        } 
    }           
}


