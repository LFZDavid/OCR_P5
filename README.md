# Blog en PHP

## Description
  Création d'un blog professionnel de développeur.
  L'application se décompose en 2 grands groupes de pages:
  * Les pages visibles par tous les visiteurs (Front)
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
    
  * Les pages permettant d'administrer le blog (BackOffice)
    * Liste de tous les articles _(ajouter/modifier/supprimer)_
    * Création/Modification d'article
    * Liste de tous les commentaires _(validation de commentaires)_
    
## Installation
  ### Base de données
    Importer le fichier database.sql pour installer la structure de la base de données sur votre serveur
  ### Installation des fichiers du site
    Faire un git clone du repository puis éditer le fichier config.php en renseignant les données corespondant à votre base de données
  ### Composer
    Après installation des fichiers :
     composer init
    
  
 
  


