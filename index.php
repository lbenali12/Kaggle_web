<!DOCTYPE html>
<html>


	<head>
		<!--Titre de la page-->
		<title> Accueil </title>
		
		<!--On inclut es metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>	
	</head>
	
	
	<body>	
		

		<?php 
			
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur n'est pas encore connecté*/
			include("banniere.include.php"); 

			/*On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle*/
			include("enjeux_site.include.php"); 
			
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (inscrit, equipier ou chef d'equipe) vers leurs page d'accueil respectives*/
			include("redirection.php");						
			
		?>
		
		
		<!-- Elements de redirection vers les page de connexion, d'insciption et de liste d'equipe de la page d'accueil qui seront affiché selon un alignement en colone à droite de la fenetre -->
		<div id="colonne_droite">
		<!-- Image de decoration sur la thematique du BIG DATA -->
		<p class="centre"><img src="imgsource/bigdata.jpg" alt="decoration" class="grandeImage" ></p>
		<!-- Bouton d'inscription -->
		<a href="inscription.php" class="boutons"> INSCRIPTION </a> <br><!-- Retour à la ligne-->
		<!-- Bouton de connexion -->
		<a href="connexion.php" class="boutons"> CONNEXION </a> <br>
		<!-- Bouton d'affichage de la liste des equipe -->
		<a href="afficher_equipe.php" class="boutons"> LISTE DES EQUIPES </a> <br>
		<!-- Logo de l'esigelec renvoyant vers le site officiel de l'esigelec -->
		<p class="centre"> <a href="http://www.esigelec.fr/"><img src="imgsource/esigelec.jpg" class="moyenneImage"  alt="decoration"> </a> </p>
		</div>

		
	</body>
	
	
	
</html>