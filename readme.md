# Association d'animaux 

## Sujet
Ce projet s'effectue par groupe de 4.

Vous devez réaliser un site pour une association d'animaux qui propose les services suivants:
Présentation des animaux à adopter, vente de produits pour ces animaux, blog et dons
 
Les utilisateurs peuvent donc se rendre sur ce site pour adopter un animal, acheter de la nourriture, des jouets ou des accessoires, faire un don à l'association et consulter les articles de blog.
 
La page d'accueil va présenter certains animaux à adopter, certains articles à acheter et va contenir un block proposant de faire un don.
 
Vous avez ensuite une page présentant tous les animaux à adopter et également les animaux adoptés le mois dernier. En cliquant sur un animal, on peut voir sa fiche (type, race, age, poids, ...)
 
Ce fonctionnement va se répéter avec la liste des articles à acheter. En cliquant sur un article on arrive sur la page de description et on peut ajouter l'article au panier avec une quantité.
 
L'utilisateur doit s'inscrire pour pouvoir passer commande, adopter un animal, faire un don.

## Organisation

### Fonctionnalités :
- Animaux
    - Affichage des animaux
    - Affichage des animaux adoptés le mois dernier
    - Adoption d'un animal **(CONNECTED)**
    - Ajout d'un animal **(ADMIN)**
    - Modification/Suppression d'un animal **(ADMIN)**
- Produits pour animaux
    - Affichage des produits
    - Ajout au panier **(CONNECTED)**
    - Ajout d'un produit **(ADMIN)**
    - Modification/Suppression d'un produit **(ADMIN)**
- Panier
    - Affichage du panier **(CONNECTED)**
    - Modifier les éléments du paniers **(CONNECTED)**
- Dons
    - Renseigner le montant **(CONNECTED)**
    - Afficher le total ce mois-ci
- Blog
    - Afficher les blog
    - Ajout article **(ADMIN)**
    - COMMANTAIRES **(CONNECTED)**
- Inscription/Connexion

### Base de données : 
- User
    - **Relation avec la table Animal**
    - **Relation avec la table Don**
    - **Relation avec la table Panier**
    - Identifiant
    - Password
    - CreatedAt
    - LastConnexion
    - IsAdmin
- Pet
    - **Relation avec la table User**
    - Name
    - Species (espèce)
    - Breed (race)
    - Age 
    - Weight
    - Sex
    - AdoptedAt *(nullable)*
    - ImageUrl *(nullable)*
- Product
    - Name
    - Description
    - Price
    - Image_url *(nullable)*
- Donation
    - **Relation avec la table User**
    - Amount
    - DonatedAt
- Article
    - Name
    - Description
    - Image_url *(nullable)*
    - InStock
- Cart
    - Product
    - User
- PurchasedHistory (Historique, si temps)
    - Cart
    - Product
    - Quantity
