<!DOCTYPE html>
<html>


	<head>
		<title> Chef d'equipe </title>
		<?php include("entete.include.php"); ?>
		
	</head>
	
	
	<body>	
		
		
		
		<?php 
			
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou inscrit) vers leurs page d'accueil respectives*/
			include("redirection_chef.php");
			
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut de chef d'equipe*/
			include("banniere_chef.include.php");

			/*On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle*/
			include("enjeux_site.include.php"); 
			
			//on recupere le nom de l'utilisateur sur la page
			$nom = $_SESSION['nom'];
			
			//on prepare la requete permettant de recuperer l'identifiant de l'equipe de l'utilisateur sur la page
			$sql5 = "SELECT * FROM inscrit WHERE nom_inscrit LIKE '$nom'";
			//on execute la requete
			$result5 = $con->query($sql5)or die("la requete de recuperation de l'ID d'equipe a echouée");
			//on recupere les informations renvoyées par la requete dans un tableau associatif
			$row5 = $result5->fetch_assoc();
			//on recupere l'identifiant de l'equipe
			$inscritIDequipe =  $row5["id_equipe_inscrit"];
			
			//on prepare la requete permettant de recuperer les differentes informations de l'equipe à partir de l'identifiant recuperé precedemment
			$sql6 = "SELECT * FROM equipe WHERE id_equipe = '$inscritIDequipe'";
			//on execute la requete
			$result6 = $con->query($sql6)or die("la requete de recuperation de l' equipe a echouée");
			//on recupere les informations renvoyées par la requete dans un tableau associatif
			$row6 = $result6->fetch_assoc();
			//on recupere le nombre d'equipiers
			$nbEquipier =  $row6["nombre_equipier"]-1;
			
			//on prepare la requete permettant de recuperer les differentes icandidatures faites à l'equipe du chef d'equipe
			$sql7 = "SELECT * FROM candidature WHERE id_equipe_candidature = '$inscritIDequipe'";
			//on execute la requete
			$result7 = $con->query($sql7) or die ("la requete conceranant les candidatures a echouée");
			//on compte le nombre de lignes renvoyé par la requete
			$nbCandidat =  mysqli_num_rows($result7);
			
		?>
		
		<!--Ajout du script permettant la deconnexion-->
		<?php include("deconnexion.include.php");?>

		
		<div class = "formulaire">
		
			<!--Message de bienvenue-->
			<p class="label_titre_page"> BIENVENUE ! </p>
			
			<!-- Image de decoration renvoyant vers le site TED referenceant les qualités d'un grand leader -->
			<p class="centre"> <a href="https://www.ted.com/talks/roselinde_torres_what_it_takes_to_be_a_great_leader?language=fr" target ="_blank"><img src="imgsource/chef.png" class="moyenneImage"  alt="decoration"></a></p>
			
			<!--Message de bienvenue et d'information sur l'etat de l'equipe-->
			<div class="description"> Bravo <?php echo htmlentities($nom); ?> ! Vous avez ete promu(e) chef d'equipe !<br> <br>
			Votre etes actuellement responsable de <?php echo htmlentities($nbEquipier); ?> equipier(s) et <?php echo htmlentities($nbCandidat); ?> candidat(s) sont(est) en attente d'une reponse de votre part pour leur admission dans votre equipe.<br> <br>
			Que voulez-vous faire maintenant ?
			</div> <br> <br>
			
			<!--Bouton renvoyant vers la page permettant d'ajouter des equipiers-->
			<a href="ajouter.php" class="boutons"> AJOUTER DES EQUIPIERS </a> <br><!-- Retour à la ligne-->
			
			<!--Bouton renvoyant vers la page permettant de planifier une reunion -->
			<a href="planifier_reunion.php" class="boutons"> PLANIFIER UNE REUNION </a> <br>
			
			<!--Bouton renvoyant vers la page permettant d'afficher les reunions à venir -->
			<a href="reunions.php" class="boutons">MES REUNIONS</a> <br>
			
			<!--Bouton renvoyant vers la page permettant de deposer un compte-rendu pour une reunion-->
			<a href="deposer.php" class="boutons"> DEPOSER UN COMPTE-RENDU </a> <br>
			
			<!--Bouton renvoyant vers la page permettant de telecharger un compte-rendu pour une reunion-->
			<a href="telecharger.php" class="boutons">TELECHARGER UN COMPTE-RENDU</a> <br>
			
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