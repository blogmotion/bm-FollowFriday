bm-FollowFriday (blogmotion)
===
### ℹ️ LICENCE ℹ️
Creative Commons Attribution-NoDerivatives 4.0 (International)

http://creativecommons.org/licenses/by-nd/4.0/

> english version below

### Description
Script PHP pour lister les X derniers abonnements (following) d'un compte @twitter

### Configuration

- include/config.php
	- $twname : pseudo (@xxxxx) du compte twitter
	- $sitename : nom du site/blog (affiché dans la page)
	- $sitehome : URL du site (avec http://)
	- $cachefile : chemin et nom du fichier de cache
	- $heurecache : validité du cache (en heure)
	- $count : nombre de personnes (following) à afficher

### 🚦 Configuration minimale
Le fichier de cache doit pouvoir être écrit, vérifiez les permissions apache / nginx / lighttpd si nécessaire.
Il est recommandé de laisser le fichier de cache à la racine (au même endroit que index.php). Vous pouvez en protéger l'accès mais cela n'a rien de nécessaire.


### English version

### [EN] Description
PHP script to list the last X subscriptions (Following) an accounttwitter

### [EN] Configuration

- include/config.php
	- $twname : username (@xxxxx) of twitter account
	- $sitename : name of the site/blog (displayed in the page)
	- $sitehome : Site URL (including http://)
	- $cachefile : path and name of the cache file
	- $heurecache : cache duration (in hours)
	- $count : number of following people to display

### [EN] 🚦 Requirements
The cache file must be writable, check the permissions apache / nginx / lighttpd if necessary.
It is recommended to leave the cache file to the root (the same location as index.php). You can protect access if you want, but it is not required.