<?php
###########################################
# auteur : Mr Xhark (blogmotion.fr)
# source 		: http://blogmotion.fr
# date 			: 04/08/2015
# modification 	: --/--/----
  $version = "1.5";
# licence type	: Creative Commons Attribution-NoDerivatives 4.0 (International)
# licence info	: http://creativecommons.org/licenses/by-nd/4.0/
# thank you 	: to @kidaas for his help on twitter 1.1 API
###########################################

# Paramètres de connexion
include('include/config.inc.php');

### < fonctions, variables ###
setlocale( LC_TIME, 'fr_FR.utf8' );

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
	<link rel="shortcut icon" type="image/ico" href="<?= $sitehome; ?>/favicon.ico" />
</head>

<!-- 
	███████╗███████╗    ██████╗ ██╗   ██╗    ██╗  ██╗██╗  ██╗ █████╗ ██████╗ ██╗  ██╗
	██╔════╝██╔════╝    ██╔══██╗╚██╗ ██╔╝    ╚██╗██╔╝██║  ██║██╔══██╗██╔══██╗██║ ██╔╝
	█████╗  █████╗      ██████╔╝ ╚████╔╝      ╚███╔╝ ███████║███████║██████╔╝█████╔╝ 
	██╔══╝  ██╔══╝      ██╔══██╗  ╚██╔╝       ██╔██╗ ██╔══██║██╔══██║██╔══██╗██╔═██╗ 
	██║     ██║         ██████╔╝   ██║       ██╔╝ ██╗██║  ██║██║  ██║██║  ██║██║  ██╗
	╚═╝     ╚═╝         ╚═════╝    ╚═╝       ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝
                                                                                 
	Fork me on GitHub https://github.com/blogmotion/bm-FollowFriday/

-->
<body>
<?php
$boutonTwitter = '<a href="https://twitter.com/'.$twname.'" class="twitter-follow-button" data-show-count="false" data-lang="fr">Suivre @'.$twname.'</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

echo '<div id="message">Vous devriez ' . $boutonTwitter . ' </div>';
echo '<p id="right">Je lis leurs tweets :</p>';

ob_start();

$mustRefresh = true;
if(file_exists($cachefile)) {
   $expirationTime = (3600*$heureCache);
   $diff = time() - filemtime($cachefile);
   # cache encore valide
   if($diff < $expirationTime) $mustRefresh = false;
}


# besoin de refresh ou de forcer le cache
if($mustRefresh || isset($_GET['force']) ) { ?>
<section>
	<?php
		require_once ('include/twitteroauth/twitteroauth.php'); # Bibliothèque oAuth
		
		# Création de l'objet
		$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
		$connection->host = "https://api.twitter.com/1.1/";
	
		// requête
		$content = $connection->get('friends/list', array('count' => $count, 'screen_name' => $pseudo));
		
		// Test si $content n'est pas vide et ne contient pas d'erreur
		if(!empty($content) && empty($content->errors[0]->code)){ 
			
			foreach ($content->users as $key => $user){	
				if(!empty($user)){
								echo '
<div class="friends">
	<a class="popup" href="https://twitter.com/'. e_($user->screen_name) .'" target="_Blank" rel="nofollow">
	<img src="'.$user->profile_image_url.'" width="48" height="48" alt="image" />
	<span>
		<div class="name">'. e_($user->name) .'</div>
		<div class="pseudescr">
			<div class="pseudo">@'.  e_($user->screen_name) .'</div>
			<div class="description">'. e_($user->description) .'</div>
		</div>
	</span>
	</a>
</div>';								
				} // if(!empty($user))
			}  // foreach $content
		
		} else {
				$erreur = true;
				echo  '<p id="erreur"><strong>...Oops!</strong><br /><br />Twitter limitant le nombre de requêtes via son API, cette page de #FF sera disponible d\'ici 15 min maximum. Revenez après le café ;-)<br /><br />';
				echo 'Code d\'erreur : '.$content->errors[0]->code.'</p>';
				break;
		}
	?>
</section>
	<?php
	if(!isset($erreur)) {
		$retour = ob_get_contents();
		unlink($cachefile);
		file_put_contents($cachefile, serialize($retour));
		ob_end_clean();
		echo '<!-- Generation du bm-cache : OK '.date('m-d-Y H:i:s').' -->';
	}
} // if($mustRefresh)

// affiche le contenu du cache (si erreur le message d'erreur est à l'intérieur)
echo "\n";


if(!isset($erreur)) {
	echo unserialize(file_get_contents($cachefile));
	echo "\n<!-- xhark-cache-system du : " . date ("m-d-Y H:i:s", filemtime($cachefile)) . " -->\n";
}
ob_end_flush();
?>
<footer>
	<img src="https://chart.googleapis.com/chart?chs=150x150&amp;cht=qr&amp;chl=http://<?= $_SERVER["SERVER_NAME"]; ?>&amp;choe=UTF-8&amp;chld=%7C0" width="150" height="150" alt="Flashez-moi avec votre smartphone ou votre tablette!"/><br />
	Licence <a href="http://creativecommons.org/licenses/by-nd/4.0/" title="Licence CC Attribution-NoDerivatives 4.0" rel="nofollow"> Creative Commons 4.0</a><br />
	Vous pouvez <a href="https://github.com/blogmotion/bm-FollowFriday/" title="sources on github" rel="nofollow">me forker</a> !<br />
	<?php echo date('Y'); ?> - <a href="<?= $sitehome; ?>" title="Retour sur <?= $sitename; ?>">Mr Xhark</a> v<?= $version; ?><br /><br /><br />
	<?php if(isset($ffshort)) echo '<span id="ff">' . $ffshort . '</span>'; ?>
</footer>
</body>
</html>