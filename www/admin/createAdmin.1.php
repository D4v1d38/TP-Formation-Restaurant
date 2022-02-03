
<?php

$bdd=new PDO('mysql:host=db.3wa.io;dbname=davidrotolo_restaurant;charset=utf8',"davidrotolo","1dab2f9c1a3dc3f96a1229b7f7684115");

$email='david@mail.com';
$mdp=password_hash("david",PASSWORD_DEFAULT);
$pseudo='david';


$query=$bdd->prepare("INSERT INTO admin(pseudo, email,password) VALUES (?,?,?)");
$query->execute([$pseudo, $email, $mdp]);