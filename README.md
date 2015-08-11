bm-FollowFriday (blogmotion)
===
###LICENCE###
Creative Commons Attribution-NoDerivatives 4.0 (International)

http://creativecommons.org/licenses/by-nd/4.0/

###Description###
Script PHP pour lister les X derniers abonnements (following) d'un compte @twitter

###Configuration###

- include/config.php
	- $twname : pseudo (@xxxxx) du compte twitter
	- $sitename : nom du site/blog (affich� dans la page)
	- $sitehome : URL du site (avec http://)
	- $cachefile : chemin et nom du fichier de cache
	- $heurecache : validit� du cache (en heure)
	- $count : nombre de personnes (following) � afficher

##Configuration minimale###
Le fichier de cache doit pouvoir �tre �crit, v�rifiez les permissions apache / nginx / lighttpd si n�cessaire.
Il est recommand� de laisser le fichier de cache � la racine (au m�me endroit que index.php). Vous pouvez en prot�ger l'acc�s mais cela n'a rien de n�cessaire.


###English version ###

###[EN] Description ###
PHP script to list the last X subscriptions (Following) an accounttwitter

###[EN] Configuration###

- include/config.php
	- $twname : username (@xxxxx) of twitter account
	- $sitename : name of the site/blog (displayed in the page)
	- $sitehome : Site URL (including http://)
	- $cachefile : path and name of the cache file
	- $heurecache : cache duration (in hours)
	- $count : number of following people to display

###[EN] Requirements ###
The cache file must be writable, check the permissions apache / nginx / lighttpd if necessary.
It is recommended to leave the cache file to the root (the same location as index.php). You can protect access if you want, but it is not required.