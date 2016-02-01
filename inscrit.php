<!DOCTYPE html>
<html>

	<head>
	
		<!--Titre de la page-->
		<title> Inscrit </title>
		
		<!--On inclut les metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>
		
	</head>
	
	
	<body>
		
		
	
		<?php 
		
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut d'inscrit*/
			include("banniere_inscrit.include.php");

			/*On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle*/
			include("enjeux_site.include.php"); 
			
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou chef d'equipe) vers leurs pages d'accueil respectives*/
			include("redirection_inscrit.php"); 
			
			//on recupere le nom de l'inscrit
			$nom = $_SESSION['nom'];
		?>
		
		<!--Ajout du script permettant la deconnexion-->
		<?php include("deconnexion.include.php");?>

		
		<div class = "formulaire">
			<!--Message de bienvenue-->
			<p class="label_titre_page"> BIENVENUE ! </p>
			
			<!-- Image de decoration felicitant l'inscrit -->
			<p class="centre"><img src="imgsource/felicitations.png" class="moyenneImage"  alt="decoration"></p>
			
			<!--Texte de bienvenue et invitant a faire un choix parmi les fonctionnalités disponibles pour l'inscrit-->
			<div class="description"> Felicitations <?php echo htmlentities($nom); ?> ! Vous etes inscrit(e) ! Que voulez-vous faire maintenant ? </div> <br> <br>
				
				<!--Bouton renvoyant vers la page permettant de postuler à une equipe-->
				<a href="postuler.php" class="boutons"> POSTULER </a> <br><!-- Retour à la ligne-->
				
				<!--Bouton renvoyant vers la page permettant d'afficher les equipes-->
				<a href="afficher_equipe.php" class="boutons"> LISTE DES EQUIPES </a> <br>
				
				<!--Bouton renvoyant vers la page permettant de creer une equipe-->
				<a href="creer_equipe.php" class="boutons"> CREER UNE EQUIPE </a> <br>
				
				<!--formulaire permettant la deconnexion-->
				<form name="deconnexion" method="post" action="inscrit.php" ><br> <br> 
				
					<!--Bouton de deconnexion-->
					<input type="submit" name="DECONNEXION" value="DECONNEXION" id="deconnexion">
					
				</form>
				
			</div>
			
		</div>
		
		

	</body>
	
</html>