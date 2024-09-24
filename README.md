# Projet Web - Les 100 pages (annuaire)



## Lien du dépôt

https://gitlabinfo.iutmontp.univ-montp2.fr/guilhote/projet-annuaire

## Fonctionnement

### Les différentes pages 

- La page d'accueil : liste des profils visibles 

- La page de profil : affiche les informations d'un utilisateur choisi, accessible depuis la liste des utilisateurs ou avec la route /profil/code unique 

- La page de profil en json : uniquement accessible depuis la route /profil/code unique/json

- Les pages de connexion et d'inscription : accessible depuis le menu du site lorsque l'utilisateur n'est pas connecté

- La page de modification : accessible uniquement aux utilisateurs depuis leur propre page de profil, permet de modifier toutes les données de l'utilisateur

### La commande de création d'utilisateurs

- La commande basique : \
\
`php bin/console app:ajout-utilisateur --login=$valeur --email=$valeur --password=$valeur --visible=$choix --admin=$choix --codeUnique=$valeur`\
\
Il suffit de remplacer les différentes données (notées $valeur) par les valeurs souhaitées 
Pour --visible et --admin il faut remplacer $choix par true si l'utilisateur a un profil visible et a le rôle admin, dans le cas contraire il faut mettre no (le choix par défaut est no pour tout autre valeur que yes ou y)

- La commande intéractive : php bin/console app:ajout-utilisateur
Les différentes données seront demandées dans l'ordre suivant : login - email - password - visible - admin - code unique

- La commande hybride : On peut rentrer les paramètres de notre choix, ceux restant seront demandés intéractivement 

### Le rôle admin 

- Le rôle admin ne peut être donné qu'avec la commande de création d'utilisateur

- Il permet de supprimer le profil de n'importe quel utilisateur non admin 

- Il permet de voir les profils non visibles dans la liste des profils dans l'accueil

## Investissement

- Raphaël : liste des profils / requête ajax / commande intéractive pour créer un utilisateur / rôle admin / regex / connexion / code unique / suppression de profil
- Noé : css / regex / connexion / inscription / mode maintenance / code unique 
- Enzo : Initialisation du projet / vueProfil et vueProfilJSON / ligne de commande pour créer un utilisateur / README
