<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Equipiers </title>
		
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
				$strSQLUser = "SELECT * FROM `inscrit` WHERE nom_inscrit = '$nomUser' "; 
				//On execute la requete
				$queryUser = mysqli_query($con, $strSQLUser) or die("la recuperation du statut a echouée !");
				//on stocke le resultat de la requete dans un tableau associatif afin de recuperer l'attribut 'statut_inscrit'
				$rowUser = $queryUser->fetch_assoc();
				
				//Si l'utilisateur est deja inscrit et connecté
				if($rowUser['statut_inscrit'] == "INSCRIT")
				{
					
					//on le renvoie vers sa page d'accueil
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
		
			<!--Titre de la colonne permettant d'afficher les equipiers  -->
			<p class="label_titre_page"> VOS CO-EQUIPIERS </p>
			
			<!--ajout d'une image de decoration-->
			<p class="centre"><img src="imgsource/equipiers.png"  class="moyenneImage" alt="decoration"></p>
		
			<?php 
			//script permettant d'afficher les equipiers
			
				//on recupere l'identifiant de l'utilisateur sur la page
				$idEquipe=$rowUser['id_equipe_inscrit'];
				//on prepare la requete permettant e recuperer les autres inscrits de la meme equipe
				$sql = "SELECT * FROM inscrit WHERE id_equipe_inscrit= '$idEquipe' ORDER BY  statut_inscrit, nom_inscrit ASC";
				//on execute la requete
				$result = mysqli_query($con, $sql) or die("erreur dans la recuperation des equipiers!");
				//on compte le nombre de ignes renvoyées par la requete
				$nblignes = mysqli_num_rows($result);
				
				//on affiche la liste des coequipiers ainsi que leurs informations de maniere a se proteger contre les injections de codes
				echo "Voici la liste de vos co-equipiers:<br> <br>";
				
				//Si il y'a plus de 2 equipiers (chef d'equipe + 1 equipiers)
				if ($nblignes >= 2) 
				{
					//on affiche les equipiers
					while($row = $result->fetch_assoc())
					{
						if($row['statut_inscrit']=='CHEF_EQUIPE')
						{
							
							echo '<td> > Le chef d\'equipe s\'appelle '.htmlentities($row["nom_inscrit"]).'. Il est joignable au  '.htmlentities($row["telephone_inscrit"]).'. </td> <br><br>';
							
						}
						
						else
						{
						
							echo '<td> > L\'equipier '.htmlentities($row["nom_inscrit"]).' est joignable au  '.htmlentities($row["telephone_inscrit"]).'. </td> <br><br>';
							
						}
					}
					echo '<td>Au total, l\'equipe dans laquelle vous etes possède '.htmlentities($nblignes).' equipier(s). </td> <br><br>';
					
				}
				
				//sinon
				else if ($nblignes < 2)
				{
					//on invite le chef d'equipe a ajouter des equipiers
					echo "<div class='message_erreur'> Pas d'equipiers, dans cette equipe hormis le chef d'equipe ! </div><br>";
					echo '<a href="ajouter.php" class="boutons"> AJOUTEZ-EN ! </a>';

				} 

			?>
			
		</div>
	
	</body>
	
</html>