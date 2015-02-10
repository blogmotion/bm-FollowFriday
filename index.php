<?php
###########################################
# auteur : Mr Xhark (blogmotion.fr)
# source 		: http://blogmotion.fr
# date 			: 22/10/2014
# modification 	: --/--/----
  $version = "1.4";
# licence type	: Creative Commons Attribution-NoDerivatives 4.0 (International)
# licence info	: http://creativecommons.org/licenses/by-nd/4.0/
# remerciement 	: @kidaas pour l'aide avec API twitter 1.1
###########################################

### </ fonctions, variables ###
setlocale( LC_TIME, 'fr_FR.utf8' );
$twname = 'xhark'; 					# twitter pseudo, sans '@'
$sitename = 'Blogmotion';			# nom du site/blog
$sitehome = 'http://blogmotion.fr';	# favicon et retour home
$cachefile = './cache-tw.html';		# fichier de cache
$heureCache = 12; 					# temps avant regénération du cache (en heure)

function e_($var,$avant='') { 
$var = trim($var);
if(!empty($var))
	return $avant . htmlspecialchars($var, ENT_COMPAT, 'UTF-8');
}
### fonctions, variables /> ###

# si nous sommes un vendredi
if( date('w')==5 ) {
	$ff 		= "#FF du Vendredi " . strftime( "%d %B %Y" , strtotime('now') ); 
	$ffshort 	= "#FF du Vendredi " . strftime( "%d %B" 	, strtotime('now') ); 
} else { $ff = "Les personnes que je suis sur Twitter"; } # sinon message classique

?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1"/>
	<?php echo "<title>" . $ff . " | @" . $twname . "</title>\n"; ?>
	<link rel="stylesheet" type="text/css" media="all" href="style.css" />
	<link rel="shortcut icon" type="image/ico" href="http://<?= $sitehome; ?>/favicon.ico" />
</head>

<body>
<?php
$boutonTwitter = '<a href="https://twitter.com/'.$twname.'" class="twitter-follow-button" data-show-count="false" data-lang="fr">Suivre @'.$twname.'</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

echo '<div id="message">Vous devriez ' . $boutonTwitter . ' </div>';
echo '<p id="right">Je suis leurs tweets depuis peu (following) :</p>';

ob_start();

if(file_exists($cachefile)) {
   $expirationTime = (3600*$heureCache);
   $dateCache = filemtime($cachefile);
   $now = time();
   $diff = $now - $dateCache;
}

if(!file_exists($cachefile) OR isset($_GET['force']) OR $diff > $expirationTime ) {
?>
	<section>
	<?php
		include('include/config.inc.php'); 						# Paramètres de connexion
		require_once ('include/twitteroauth/twitteroauth.php'); # Bibliothèque oAuth
		
		# Création de l'objet
		$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
		$connection->host = "https://api.twitter.com/1.1/";
		
		$i = 0;
		$cursor = -1; // -1 = Liste des derniers friends en date
		
		while($i < $max && $cursor != 0){
			// requête
			$query = 'https://api.twitter.com/1.1/friends/list.json?cursor='.$cursor.'&screen_name='.$twname;
			$content = $connection->get($query);
			
			// Test si $content n'est pas vide et ne contient pas d'erreur
			if(!empty($content) && empty($content->errors[0]->code)){ 
				// Récupère le curseur permettant d'atteindre les 20 prochains enregistrements
				$cursor = $content->next_cursor_str;
				foreach($content as $list){
					if(is_array($list)){ 
						foreach($list as $user){
							if(!empty($user)){
								echo '
<div class="friends">
	<a class="popup" href="https://twitter.com/'. e_($user->screen_name) .'" target="_Blank" rel="nofollow">
	<img src= "'.$user->profile_image_url.'" />
	<span>
		<p class="atname">@'.  e_($user->screen_name) .'</p>
		<p>'. e_($user->name) .'</p>
		<p>'. e_($user->description) .'</p>
	</span>
	</a>
</div>';		 
							} // if(!empty($user))
						} // foreach $list
					} //if(is_array)
				} // foreach $content
			}else {
				$erreur = true;
				echo  '<p id="erreur"><strong>...Oops!</strong><br /><br />Twitter limitant le nombre de requêtes via son API, cette page de #FF sera disponible d\'ici 15 min maximum. Revenez après le café ;-)<br /><br />';
				echo 'Code d\'erreur : '.$content->errors[0]->code.'</p>';
				break;
			}
			$i++;
		} // while
	?>
	</section>
	<?php
	if(!isset($erreur)) {
		$retour = ob_get_contents();
		unlink($cachefile);
		file_put_contents($cachefile, $retour);
		ob_end_clean();
		echo '<!-- Generation du bm-cache : OK '.date('m-d-Y H:i:s').' -->';
	}
} // if !file_exists

// affiche le contenu du cache (si erreur le message d'erreur est à l'intérieur)
echo "\n";
if(!isset($erreur)) { echo file_get_contents($cachefile); }
ob_end_flush();
?>
<footer>
	<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http://<?= $_SERVER["SERVER_NAME"]; ?>&choe=UTF-8&chld=|0" width="150" height="150" title="Flashez-moi avec votre smartphone ou votre tablette!"/><br />
	Licence <a href="http://creativecommons.org/licenses/by-nd/4.0/" title="Licence CC Attribution-NoDerivatives 4.0" rel="nofollow"> Creative Commons 4.0</a><br />
	<?php echo date('Y'); ?> - <a href="http://<?= $sitehome; ?>" title="Retour sur <?= $sitename; ?>">Mr Xhark</a> v<?= $version; ?><br /><br /><br />
	<?php if(isset($ffshort)) echo '<span id="ff">' . $ffshort . '</span>'; ?>
</footer>
</body>
</html>