<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Equipes </title>
		
		<!--On inclut es metadonnées communes à tous le site-->
		<?php include("entete.include.php");?>

	</head>
	
	
	<body>
	
		<?php 
		//Script de recuperation des infos de la personne sur la page et de redirection des personnes indesirables
			
			//On precise les parametres permettant de se connecter à la base de données et on se connecte à celle-ci
			include("paramBDD.include.php");
			
			//On stocke les données de l'eventuel utilisateur qui s'est connecté dans un tableau associatif. On commence une session
			session_start();
			
			//Si la personne sur la page est dja inscrite
			if(isset($_SESSION['nom']))
			{
				//On recupere son nom
				$nomUser=$_SESSION['nom'];
				//On prepare la requete permettant de savoir son statut
				$strSQLUser = "SELECT * FROM `inscrit` WHERE nom_inscrit = '$nomUser' "; 
				//on execute la requete
				$queryUser = mysqli_query($con, $strSQLUser) or die("la requete SQL de recuperation de l'utilisateur a echouée");
				//on recupere le nombre de lignes renvoyées par la requete
				$lignesUser = mysqli_num_rows($queryUser);
				//on stocke les informations renvoyées par la requete dans un tableau associatif
				$rowUser = $queryUser->fetch_assoc();
				//on recupere le statut de l'utilisateur qui est sur la page s'il est deja connecté
				
				//si l'utilisateur est un equipier
				if($rowUser['statut_inscrit'] == "EQUIPIER")
				{
					//on le renvoie vers sa page d'accueil
					header('Location: equipier.php');
				}
				
				//si l'utilisateur est un chef d'equipe
				else if($rowUser['statut_inscrit'] =="CHEF_EQUIPE") 
				{
					//on le renvoie vers sa page d'accueil
					header('Location: chef_equipe.php');
				}
			}
	
			//si l'utilisateur est un inscrit 
			if(isset($_SESSION['nom']) && $rowUser['statut_inscrit'] =="INSCRIT")
			{
				//On inclut la banniere de la page d'accueil de l'inscrit commune à tout les pages ou l'utilisateur est connecté et possede le statut d'inscrit
				include("banniere_inscrit.include.php");	
			}
			
			//si l'utilisateur n' est pas inscrit 
			else
			{
				//On inclut la banniere de la page d'accueil commune à tout les pages ou l'utilisateur n'est pas connecté 
				include("banniere.include.php");
			}
		?>
		
		
		<!--Titre de la colonne permettant d'afficher les equipes -->
		<p class="label_titre_page"> Liste des equipes </p>

		<!--On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle-->
		<?php include("enjeux_site.include.php"); ?>
		
		<!--Affichage de la liste des equipes-->
		<div class ="listeEquipe">
		
			<!--ajout d'une image de decoration representant une equipe -->
			<p class="centre"><img src="imgsource/team2.png"  class="moyenneImage"  alt="decoration"></p>
			
			<?php 
			//Script de recuperation de la liste des equipes et de leurs informations
				
				//On prepare la requete permettant de recuperer la liste des equipes par ordre alphabetique
				$sql = "SELECT * FROM equipe ORDER BY nom_equipe ASC";
				//On execute la requete
				$result = $con->query($sql) or die("la requete SQL de recuperation des equipes a echoué");
				//On affiche la liste des equipes
				echo "Voici la liste de nos equipes existantes:<br> <br>";
				//si le nombre d'equipe renvoyé est superieur à 0
				if ($result->num_rows > 0) 
				{
					//Tant que les informations de chaque equipe sont stockés dans un tableau associatif
					while($row = $result->fetch_assoc())
					{
						//on recupere l'identifiant de l'equipe
						$equipe = $row['id_equipe'];
						//On prepare la requete permettant de reuperer le nom du chef d'equipe
						$sql2 = "SELECT * FROM inscrit WHERE id_equipe_inscrit = '$equipe' && statut_inscrit ='CHEF_EQUIPE'";
						//on execute la requete
						$result2 = $con->query($sql2) or die("la requete permettant de recuperer le chef a echouée");
						//on stocke les informations renvoyées par la requete dans un tableau associatif 
						$row2 = $result2->fetch_assoc();
						//on recupere le nom du chef d'equipe
						$nomChef = htmlentities($row2['nom_inscrit']);
						
						//On effiche les informations de l'equipe
						echo '<td> > L\'equipe '.htmlentities($row["nom_equipe"]).' possedant '.htmlentities($row['nombre_equipier']).' joueur(s) et dirigée par '.$nomChef.'</td> <br><br>';
					
					}	
					
			    }
				//si le nombre d'equipe renvoyé egale 0
				else 
				{
					//on informe l'utilisateur qu'aucune equipe n'a ete cree pour linstant
					echo "<div class='message_erreur'> Pas d'equipe disponibles pour l'instant ! </div><br>";

				} 
				
				//Si l'utilisateur n'est pas encore inscrit
				if(!isset($_SESSION['nom']))
				{
					//On affiche un bouton l'invitant à s'inscrire
					echo '<br> <br> <a href="inscription.php" class="boutons"> REJOIGNEZ-NOUS ! </a>';
				}
			?>
			
		</div>
	
	</body>
	
</html>