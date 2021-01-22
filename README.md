# Blog en PHP

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
  ### Fichiers du site
  1. Faire un git clone du repository puis éditer le fichier config.php en renseignant les données corespondant à votre base de données
  2. Renseigner le fichier
    
  ### Composer
    Après installation des fichiers, lancer la commande : composer init
  ### Version PHP
    >= 7.3
  ### Base de données
   * Option 1 (phpmyadmin) :
      * Dans le menu "importer", selectionner le fichier "blog_p5.sql" (app/database/blog_p5.sql)
  
  * Option 2 :
      1. Créer une base de donnée vierge 
      * Attention: le nom de la base de données doit correspondre à celui indiqué dans le fichier config.php
      * Selectionner l'encodage "utf-8"
      1. Executer la commande : php app/cmd/install_db.php
  * Insérer des données de démonstration
    * lancer la commande : php app/cmd/insert_datas.php
  
 
  


