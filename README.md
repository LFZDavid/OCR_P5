# Blog en PHP

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/af39394975f64c9ca4918bec967b91ba)](https://app.codacy.com/gh/LFZDavid/OCR_P5?utm_source=github.com&utm_medium=referral&utm_content=LFZDavid/OCR_P5&utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/be4ed9cb75cdabe8dac1/maintainability)](https://codeclimate.com/github/LFZDavid/OCR_P5/maintainability)

## Description
  Création d'un blog professionnel de développeur.
  L'application se décompose en 2 grands groupes de pages:
  ### Les pages visibles par tous les visiteurs (Front)

  * Accueil
    * Présentation
    * lien de téléchargement du CV
    * Liens vers les différents réseau sociaux
    * Formulaire de contact
  * Liste de tous les articles _(actifs)_
  * Page d'article avec la liste des commentaires _(validés)_ 
    * Contenu complet de l'article _(Titre, chapô, contenu, auteur, date de mise à jour)_
    * Liste des commentaires publié et validés
    * Un formulaire de soumission de commentaire pour les utilisateurs connectés.
  * Page/Formulaire d'inscription/connexion pour les utilisateur
    
  ### Les pages permettant d'administrer le blog (BackOffice)
  * Liste de tous les articles _(ajouter/modifier/supprimer)_
    * Création/Modification d'article
    * Liste de tous les commentaires _(validation de commentaires)_
  --- 
## Installation
  ### __Fichiers du site__
  1. Récupérer les fichiers en téléchargeant le .zip du repository ou avec la commande `git clone ` 
  2. _(facultatif)_ Editer le fichier config.php en renseignant les données corespondant à votre base de données
    
  ### __Composer__
  Après installation des fichiers, initialiser Composer avec la commande : `composer init`
  ### __Version PHP__
  \>= 7.3
  ### __Base de données__
   * Option 1 :
      * Dans le menu "importer", selectionner le fichier `app/database/blog_p5.sql`
  * Option 2 :
      1. Créer une base de donnée vierge nommé `"blog_p5"` (_ou le nom choisit dans le fichier `config.php`_ )
      2. Selectionner l'encodage `utf-8`
      3. Executer la commande : `php app/cmd/install_db.php` pour installer les données.
  
  
 
  


