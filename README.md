# Depot du DAL PHP de PHAM ALEXANDRE
## A Propos
Le dépot  contient les  fichier pour installer  un DAL  qui permet de  mettre en place un service web , service web qui  permet de trier un tableau  (tri à bulle ou tri rapide) donné par un utilisateur dans l'url du service web et qui renvoie le tableau trié à l'utilisateur
## Syntaxe de la requete 
Arguments : 



 **méthode de tri  disponibles**



• `BubbleSort`


• `QuickSort`



**le tableau à trier :**
Exemple : [544,567,144,17]


La requete à envoyer  au service se décompose en  http://[***adresse du service***]?type= [***Le Type de Tri***] &t= [***Le Tableau à trier entouré de crochets***] 



exemple : http://localhost/Api/?type=QuickSort&t=[1,14,709,2152,48,1471,14]



## Installation 
Vous devez deplacer le contenu du dossier **"src"** dans le réperoire suivant **C:\wamp64\www** et dans le contenu  du dossier "assets" vous devez importer la base de donnée fournie dans votre PHPmyAdmin ou créer une nouvelle avec les meme caractèristiques  et vous devez aussi créer un dossier nommé **"Credentials"**  dans le répertoire suivant : **C:\wamp64** et placer le fichier **db.json** (présent dans le dossier **"assets"** ) dedans.


