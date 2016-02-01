<!DOCTYPE html>
<html>


	<head>
	
		<!--Titre de la page-->
		<title> Equipier </title>
		
		<!--On inclut les metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>
		
	</head>
	
	
	<body>	
	
		<?php 
		
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, chef d'equipie ou inscrit) vers leurs page d'accueil respectives*/
			include("redirection_equipier.php");
			
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut  d'equipier*/
			include("banniere_equipier.include.php"); 
			
			/*On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle*/
			include("enjeux_site.include.php"); 
			
			//on recupere le nom de l'utilisateur sur la page
			$nom = $_SESSION['nom'];
			
			//on prepare la requete permettant de recuperer l'identifiant de l'equipe de l'utilisateur sur la page
			$sql5 = "SELECT * FROM inscrit WHERE nom_inscrit LIKE '$nom'";
			//on execute la requete
			$result5 = $con->query($sql5) or die("la requete de recuperation de l'ID a echouée !");
			//on recupere les informations renvoyées par la requete dans un tableau associatif
			$row5 = $result5->fetch_assoc();
			//on recupere l'identifiant de l'equipe
			$inscritIDequipe =  $row5["id_equipe_inscrit"];
		
			//on prepare la requete permettant de recuperer les differentes informations de l'equipe à partir de l'identifiant recuperé precedemment
			$sql6 = "SELECT * FROM equipe WHERE id_equipe = '$inscritIDequipe'";
			//on execute la requete
			$result6 = $con->query($sql6);
			//on recupere les informations renvoyées par la requete dans un tableau associatif
			$row6 = $result6->fetch_assoc();
			//on recupere le nombre d'equipiers et le nom de l'equipe
			$nomEquipe =  $row6["nom_equipe"];
			$nbEquipier = $row6['nombre_equipier']-1;
		
		
		?>
		
		
		<!--Ajout du script permettant la deconnexion-->
		<?php include("deconnexion.include.php");?>

		
		<div class = "formulaire">
			
			<!--Message de bienvenue-->
			<p class="label_titre_page"> BIENVENUE ! </p>
			
			<!-- Image de decoration-->
			<p class="centre"><img src="imgsource/equipier.png" class="moyenneImage" alt="decoration"></p>
			
			<!--Message de bienvenue et d'information sur l'etat de l'equipe-->
			<div class="description"> Bravo <?php echo htmlentities($nom); ?> ! Vous avez ete accepté(e) au sein des <?php echo htmlentities($nomEquipe); ?> !<br> <br>
				L' equipe dans laquelle vous etes possede actuellement <?php echo htmlentities($nbEquipier); ?> equipier(s) .<br> <br>
				Que voulez-vous faire maintenant ? 
			</div> <br> <br>
			
			<!--Bouton renvoyant vers la page permettant d'afficher les reunions à venir -->
			<a href="reunions.php" class="boutons">MES REUNIONS</a> <br>
			
			<!--Bouton renvoyant vers la page permettant de telecharger un compte-rendu pour une reunion-->
			<a href="telecharger.php" class="boutons"> TELECHARGER UN COMPTE-RENDU </a> <br>
			
			<!--Bouton renvoyant vers la page permettant d'afficher les informations sur les equipiers-->
			<a href="equipiers.php" class="boutons">MES EQUIPIERS</a> <br>
			
			<!--formulaire permettant la deconnexion-->
			<form name="deconnexion" method="post" action="chef_equipe.php" ><br> <br> 
			
				<!--Bouton de deconnexion-->
				<input type="submit" name="DECONNEXION" value="DECONNEXION" id="deconnexion">
				
			</form>
		
		</div>
		
	</body>
	
</html>