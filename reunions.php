<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Reunions </title>
		
		<!--On inclut les metadonnées communes à tous le site-->
		<?php include("entete.include.php");?>
		
	</head>
	
	<body>
	
		<?php
		//Script de redirection des utilisateurs indesirables (visiteurs, inscrits)
			
			//On precise les parametres permettant de se connecter à la base de données et on se connecte à celle-ci
			include("paramBDD.include.php");
			
			//On stocke les données de l'eventuel utilisateur qui s'est connecté dans un tableau associatif. On commence une session
			session_start();
			
			//si un utilisateur s'est connecté
			if(isset($_SESSION['nom']))
			{	
				//on recupere son nom (comme le nom est unique, cela permettra d'identifier precisemment l'utilisateur)
				$nomUser=$_SESSION['nom'];	
				//On prepare une requete SQL permettant d'identifier le statut de l'utilisateur
				$strSQLUser = "SELECT * FROM inscrit WHERE nom_inscrit = '$nomUser' "; 
				//on execute la requete
				$queryUser = mysqli_query($con, $strSQLUser) or die("la recuperation du statut a echouée !");
				//on stocke le resultat de la requete dans un tableau associatif afin de recuperer l'attribut 'statut_inscrit'
				$rowUser = $queryUser->fetch_assoc();
				
				//Si l'utilisateur est deja inscrit et connecté
				if($rowUser['statut_inscrit'] == "INSCRIT")
				{
					//on le renvoie vers sa page d'accuei
					header('Location: inscrit.php');
				
				}
			}
			
			//Si l'utilisateur n'est pas inscrit
			else
			{
				//on le renvoie vers sa page d'accueil
				header("Location: index.php");
			}
			
			//si l'utilisateur est un chef d'equipe
			if(isset($_SESSION['nom']) && $rowUser['statut_inscrit'] == 'CHEF_EQUIPE')
			{
				//On inclut la banniere de la page d'accueil du chef d'equipe commune à tout les pages ou l'utilisateur est connecté et possede le statut de chef d'equipe
				include("banniere_chef.include.php");	
				
			}
			
			//si l'utilisateur est un equipier
			else
			{
				//On inclut la banniere de la page d'accueil de l'equipier commune à tout les pages ou l'utilisateur est connecté et possede le statut d'equipier
				include("banniere_equipier.include.php");
				
			}
		?>


		<!--On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle-->
		<?php include("enjeux_site.include.php"); ?>
	
		
		<div class ="listeEquipe">
		
			<!--Titre de la colonne permettant d'afficher les reunions à venir  -->
			<p class="label_titre_page"> MES REUNIONS </p>
			
			<!--ajout d'une image de decoration-->
			<p class="centre"><img src="imgsource/meeting.png"  class="moyenneImage"  alt="decoration"></p>
		
			<?php 
			//script d'affichage des reunions planifiées pour une date superieure à la date de consultaion de la page
				
				//on recupere l'identifiant de l'equipe de l'utilisateur
				$idEquipe=$rowUser['id_equipe_inscrit'];
				
				//on prepare la requete permettant de recuperer toutes les reunions dont la date est superieure à la date actuelle
				//la fonction current_date() en sql permet ceci
				$sql = "SELECT *, DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR')) FROM reunion WHERE id_reunion_equipe= '$idEquipe' && date_reunion >= CURRENT_DATE()  ORDER BY date_reunion DESC";
				
				//on execute la requete
				$result = mysqli_query($con, $sql) or die("erreur de recuperation des reunions !");
				
				//on compte le nombre de lignes retournées par la requete
				$nblignes = mysqli_num_rows($result);
				
				//on affiche les reunions en toute securité pour eviter l'injection de code
				echo "Voici la liste des reunions planifiées pour les prochains jours:<br> <br>";
				
				if ($nblignes > 0) {
					
					while($row = $result->fetch_assoc())
					{
	
						echo '<td>> Une reunion portant sur  '.htmlentities($row["motif_reunion"]).' aura lieu le  '.htmlentities($row["DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR'))"]).' à '.htmlentities($row['heure_reunion']).' heures '.htmlentities($row['minutes_reunion']).'. </td> <br><br>';
					
					}	
					
				}
				
				//si aucune reunion planifiée dans les prochains jours
				else 
				{
					//on informe l'utilisateur
					echo "<div class='message_erreur'> Pas de reunions planifiées pour les prochains jours ! </div><br>";
					
					//si l'utilisateur est un chef d'equipe
					if($rowUser['statut_inscrit'] == "CHEF_EQUIPE")
					{
						//on l'invite à planifier une reunion
						echo '<a href="planifier_reunion.php" class="boutons"> PLANIFIEZ-EN UNE ! </a>';
					}
				} 

			?>
			
		</div>
	
	</body>
	
</html>