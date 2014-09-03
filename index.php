<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title> Galerie </title>
  <link rel="stylesheet" href="css/basic.css" type="text/css" />
  <link rel="stylesheet" href="css/galleriffic-2.css" type="text/css" />
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.galleriffic.js"></script>
  <script type="text/javascript" src="js/jquery.opacityrollover.js"></script>
  <!-- We only want the thunbnails to display when javascript is disabled -->
  <script type="text/javascript">
    document.write('<style>.noscript { display: none; }</style>');
  </script>
  <?php include("auth.php") ?>
</head>

<body>

<?php
  echo '<div id="page">';
  if (isset($_GET['galerie'])){
    if (isset($_GET['token'])){
      if (is_file("galerie/".$_GET['galerie']."/pass.php") && !($_SESSION[$_GET['galerie']] == 'logedin')){
        include("galerie/".$_GET['galerie']."/pass.php");
	if ($_GET['token'] == $token){
	  $_SESSION[$_GET['galerie']] = 'logedin';
	  $_SESSION['finger'] = password_hash($_SERVER['HTTP_USER_AGENT'].substr($_SERVER["REMOTE_ADDR"],0,7), PASSWORD_BCRYPT);
	}
      }
    }
    if (is_file("galerie/".$_GET['galerie']."/pass.php") && !($_SESSION[$_GET['galerie']] == 'logedin')){
      echo "Bitte geben sie das Galerie-Passwort an</br>";
      echo '<form id="loginbox" action="login.php?galerie='.$_GET['galerie'].'" method="post">';
      echo '<p>Galerie-Passwort:<br><input name="pass" type="password" size="15" maxlength="20"></p>';
      echo '<input type="submit" value="Login"><br>';
      echo '<input type="hidden" name="token" value="'.csrf_token().'">';
      echo '</form></div>';
    }
    else {
      $bilder = glob("galerie/{$_GET['galerie']}/thumbs/*.jpg");
      natsort($bilder);
      echo '  <div id="container">
                <h1><a href="index.php">Galerie</a></h1>
  			    <h2>Meine Fotogallerie powered by Gallerific</h2>';
      echo '      <div id="gallery" class="content">
     			      <div id="controls" class="controls"></div>
	    		      <div class="slideshow-container">
		    		    <div id="loading" class="loader"></div>
			    	    <div id="slideshow" class="slideshow"></div>
			        </div>
			        <div id="caption" class="caption-container"></div>
			      </div>
			      <div id="thumbs" class="navigation">
			        <ul class="thumbs noscript">
			    ';
      foreach($bilder as $bild){
        $bildn = array_pop(explode("/", $bild));
        echo '<li>
                <a class="thumb" href="image.php?bild='.urlencode($bildn).'&galerie='.$_GET['galerie'].'" title="'.$bildn.'" >
                  <img src="galerie/'.$_GET['galerie'].'/thumbs/'.$bildn.'" alt="'.$bildn.'"/>
                </a>
                <div class="caption">
                  <div class="download">';
	if (is_dir("galerie/".$_GET['galerie']."/bilder/gross")){
		echo '<a href="image.php?bild='.urlencode($bildn).'&galerie='.$_GET['galerie'].'&gross=1" >Download Original</a>';
	} else {
		echo '<a href="image.php?bild='.urlencode($bildn).'&galerie='.$_GET['galerie'].'" >Download Original</a>'; 
	}
        echo '            </div>
                    <div class="image-title">'.$bildn.'</div>
				    <div class="image-desc">Description</div>
                </div>
                </li>';
      }
      echo '    </ul>
              </div>
            <div style="clear: both;"></div>
            </div>
            </div>';
    }
  }
  else {
    $verz = glob( "galerie/*", GLOB_ONLYDIR );
    echo '';
    foreach ( $verz as $dir ){
      $dirname = array_pop( explode( "/", $dir) );
      if (is_file("galerie/".$dirname."/.ready")){
	if (is_file("galerie/".$dirname."/pass.php")){
	  $bild = "galerie/schloss.png";
	}
      	else{      
	  $bilder = glob("galerie/{$dirname}/thumbs/*.jpg");
	  $bild = $bilder[rand(0, count($bilder))];
	}
        echo '<a href="'.$_SERVER['PHP_SELF'].'?galerie='.$dirname.'"><div class="gallcont"><div class="thumb"><img src="'.$bild.'"></div>';
        echo '<div class="galname">'.$dirname.'</div></div></a>';
      }
    }
    echo '<div class="clearb"></div></div>';
  }
?>
<div id="footer">Galerie @jochenwelzel.de</div>
<?php if (isset($_GET['galerie'])){ echo'<script type="text/javascript" src="js/galerie.js"></script>';} ?>
<!-- Piwik -->
  <script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(["trackPageView"]);
      _paq.push(["enableLinkTracking"]);
    
      (function() {
          var u=(("https:" == document.location.protocol) ? "https" : "http") + "://piwik.jochenwelzel.de/";
          _paq.push(["setTrackerUrl", u+"piwik.php"]);
          _paq.push(["setSiteId", "3"]);
          var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
          g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
        })();
      </script>
<!-- End Piwik Code -->
</body>
</html>
