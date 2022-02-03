/*==========LES VARIABLES==========*/
let quantite;
let idMeal;
let commande =[];
let totalPrice=0;
let panier=0 ;


/*==============ETAPE 2 =================*/

/*============== APPEL AJAX =============*/
/*=======================================*/

// ETAPE 2A fonction load details 
// qui recupere id du repas 
// garce a $.get() on va le transmettre a index.php pour lancer la fonction du controller en transmettant 
//dans le 2ieme argument les parametres action et l'id recuperé
//puis on appel affdetails pour affichage du resultats (3ieme argument de $.get).


function loadDetails(){
    
    idMeal = $('#meal-select').val(); //on recupere l'id du repas dans le select
    console.log(idMeal); // on verifie qu'on recupere bien l'id du repas
    
    //appel ajax 
    //créer le paramètre action et un 2ieme paramètre pour envoyé l'id du repas
    // si la requete a reussi on appel la fonction affDetails
     $.get("index.php","action=ajax&id_meal="+idMeal,affDetails);
     

}

function addPanier(){

    $.get("index.php","action=panier&id_meal="+idMeal,ajaxPanier);

}

function passerCommande(){

    //js --> json  
    commande = JSON.stringify(commande);
    
    console.log($.get("index.php","action=passerCmd&commande="+commande+"&montantTotal="+panier,validOrder));
}

/*==============ETAPE 3 =================*/
/*======= FONCTION CALLBACK =============*/
/*=======================================*/

// la fonction callBack va afficher les détails du repas dans la div 

    function affDetails(plat){
    
    $("#target").html(plat);
}

// on lance la fonction ajaxPanier
    
    function ajaxPanier(meal){

        meal=JSON.parse(meal); 

        quantite = $("#quantity").val(); //provient du formulaire
        commande.push([quantite,meal]); // on push la quantité et le retour de la requete
        saveStorage();  //on appel la fonction saveStorage pour enregistrer dans le localstorage

    }
    
    function validOrder(reponse){
        console.log(reponse);
        
        $("#panier").empty();
        
        $("#panier").html(reponse);
        window.localStorage.removeItem('panier'); //on supprime juste l'enregistrement avec l'étiquette panier
    }


/*======= LOCAL STORAGE =============*/
/*=======================================*/

function saveStorage (){
    // on transforme en simple chaine de caractère = json
    // on transforme le tableau commande au format JSON
    commande = JSON.stringify(commande);
    
    //enregistrementn on choisit le nom de la clé et l'element a stocker
    // ici la clé est panier et contiend notre tableau commande
    
    window.localStorage.setItem('panier',commande);
    afficher(); //on appel la fonction afficher pour afficher les données dans le tableau recap sur la pageweb
}

function loadStorage(){
    //permet de recuperer les data du locastorage on recupere l'element avec la clé panier
    commande = window.localStorage.getItem('panier');
    
    //si le tableau n'a pas de data on passe un tableau vide sinon on convertit de JSON vers format exploitable
    if(commande == null){
        commande=[];
    }else{
        commande = JSON.parse(commande);
        console.log(commande);
    }
}

function afficher(){
    
    loadStorage();  //pour afficher les data il faut le recuperer du localstorage
    $("#cases").empty(); //on efface le container pour eviter les repetition a chaque ajout
    
    panier = 0;
    
    for(const uneCommande of commande){
        //calcul du prix total par ligne
        totalPrice = uneCommande[1].prix*uneCommande[0];
        
        //on cumule le total par ligne
        panier += totalPrice;

        //on affiche dans le tableau order.phtml 
        $("#cases").append("<tr><td>"+uneCommande[0]+"</td><td>"+uneCommande[1].name+"</td><td>"+uneCommande[1].prix+"€</td><td>"+totalPrice+"€</td></tr>");
        
    }
        $("#montantTotalPanier").empty();
        $("#montantTotalPanier").append("<th>Montant Total :</th><td>"+panier+" €</td>");
  
}


/*==============ETAPE 1 =================*/
/*======= ECOUTEURS D'EVENEMENTs =============*/
/*=======================================*/



$(function(){
    
    //Ecouteur d'evenement sur le select et on lance la fonction loadDetails
    $('#meal-select').on('change',loadDetails);
    
    
    //Ecouteur d'evenement sur le bouton ajouter et on lance la fonction addPanier()
    $('#add-menu').on('click', addPanier);
    
    $('#valider').on('click', passerCommande)
    
    //au chargement on va cherche le panier dans le localstorage et on affiche
    //cela permet si l'utilisateur quitte le site et reviens plus tard de reetrouver sa commande
    
    loadDetails();
    afficher();
});


