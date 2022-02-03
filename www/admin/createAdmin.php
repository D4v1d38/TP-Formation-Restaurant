
<?php

$bdd=new PDO('mysql:host=db.3wa.io;dbname=davidrotolo_irreductibles;charset=utf8',"davidrotolo","1dab2f9c1a3dc3f96a1229b7f7684115");

$email='david@mail.com';
$mdp=password_hash("david",PASSWORD_DEFAULT);
$prenom='david';
$nom="rotolo";
$id_fonction='2';


$query=$bdd->prepare("INSERT INTO admin( nom, prenom, email, id_fonction, password) VALUES (?,?,?,?)");
$query->execute([$nom,$prenom, $email,$id_fonction, $mdp]);