 <?php
    
     require "models/Admin.php";
    
    class AdminController{
        // PROPRIETES
        private $administrator;
        
        // CONSTRUCTEUR
        public function __construct(){
            $this->administrator = new Admin();

        }
        
        // METHODES
        public function admin(){
            
            $template = "admin/homeAdmin";
            
            //on vérifie les données reçues par le formulaire de conenxion admin
            
            if(isset($_POST['email'], $_POST['password'])){
                if(!empty($_POST['email']) && !empty($_POST['password'])){
                
                    $email      = htmlspecialchars($_POST['email']);
                    $password   = htmlspecialchars($_POST['password']);
                    
                    $admin = $this->administrator->getAdminByEmail($email);
                    
                    if(!$admin){
                        $message = "Erreur : L'adresse mail n'existe pas !";
                    }
                    else{
                        if(password_verify($password, $admin['password'])){
                            
                            $_SESSION=[
                                'pseudoAdmin'   =>  htmlspecialchars($admin['pseudo']),
                                'emailAdmin'    =>  htmlspecialchars($admin['email']),
                                'idAdmin'       =>  htmlspecialchars($admin['id_admin'])
                                ];
                        }else{
                            $message= "Le mot de passe est incorect";
                        }
                    }
                }else{
                    $message= "Veuillez saisir tous les champs";
                } 
            }

            require 'www/layout.phtml';
            
        }
        
        //on test si l"admin est connecté
        public function isAdmin(){
            
            //controler i une session est ouverte
            if(isset($_SESSION['emailAdmin'])){
                return true;
            }else{
                return false;
            }
        }
        
        public function deconnect(){
            
           $_SESSION= array();
           session_destroy();
           
           header("location:index.php");
        }

    }

 ?>