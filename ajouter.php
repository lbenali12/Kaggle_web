<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Ajouter </title>
		
		<!--On inclut es metadonnées communes à tous le site-->
		<?php include("entete.include.php");?>
		

	</head>
	
	
	<body>
	
		<?php
		//Script de recuperation des candidatures de l'equipe
		
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut de chef d'equipe*/
			include("banniere_chef.include.php");

			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou inscrit) vers leurs page d'accueil respectives*/
			include("redirection_chef.php");
			
			//on recupere le nom de l'utilisateur sur la page
			$nomInscrit=$_SESSION['nom'];
			
			//on prepare la requete permettant de recuperer l'identifiant de l'equipe de l'utilisateur sur la page
			$sql5 = "SELECT * FROM inscrit WHERE nom_inscrit LIKE '$nomInscrit'";
			//on execute la requete
			$result5 = $con->query($sql5) or die("la recuperation de l'id a echouée !");
			//on recupere les informations renvoyées par la requete dans un tableau associatif
			$row5 = $result5->fetch_assoc();
			//on recupere l'identifiant de l'equipe
			$inscritIDequipe =  $row5["id_equipe_inscrit"];
			
			//on prepare la requete permettant de recuperer les candidatures qui ont ete enregistrées pour l'equipe de l'utilisateur sur la page
			$sql6 = "SELECT * FROM candidature WHERE id_equipe_candidature = '$inscritIDequipe'";
			//on execute la requete
			$result6 = $con->query($sql6) or die("la recuperation des candidsatures a echouée !");
			
		?>

		<!--Titre du formulaire-->
		<p class="label_titre_page"> AJOUTER DES EQUIPIERS </p>
		
		
		<!-- Image de decoration-->
		<p class="centre"><img src="imgsource/adduser.png" class="tresPetiteImage"  alt="decoration"></p>
		

		<?php
		//Script d'ajout et de suppression des candidatures recuperées

			//lorsque l'utilisateur selectionne des candidatures et clique sur ajouter
			if(isset($_POST['AJOUTER']) && !empty($_POST['listeCandidat']))
			{
				//on recupere son nom
				$nomInscrit = $_SESSION['nom'];
				
				//variable permettant de stocker le(s) choix de l'utilisateur
				$choix ='';
				
				//On instancie une variable permettant l'affichage d'une eventuelle erreur 
				$erreur ='';
				
				//on parcoure la liste des candidatures qui sont affichées
				for ($i=0;$i<count($_POST['listeCandidat']);$i++)
				{
					//on stocke le choix qui correpond à l'identifiant de la candidature selectionnée si une candidature est cochée
					$choix = $_POST['listeCandidat'][$i];
					
					//on prepare la requete permettant de modifier le statut du candidat qui a candidaté
					$sql3 = "UPDATE inscrit SET statut_inscrit = 'EQUIPIER', id_equipe_inscrit = '$inscritIDequipe' WHERE id_inscrit = '$choix'";
					//on execute la requete
					$result3 = $con->query($sql3) or die("le changement du statut a echoué !");
					
					//on prepare la requete permettant de recuperer le nombre d'equipier de l'equipe du chef qui traite les candidatures
					$sql1 = "SELECT * FROM equipe WHERE id_equipe = '$inscritIDequipe'  ";
					//on execute la requete
					$result1 = $con->query($sql1) or die("la mise a jour du nombre d'equipier a echouée !");
					//on recupere les informations renvoyées par la requete dans un tableau associatif
					$row1 = $result1->fetch_assoc();
					//on met a jour le nombre d'equipiers en incrementant celui-ci
					$equipierMAJ =  $row1["nombre_equipier"]+1;
					
					//on prepare la requete permettant de stocker le nombre d'equipiers mis a jour precedemment
					$sql10 = "UPDATE equipe SET nombre_equipier = '$equipierMAJ' WHERE id_equipe = '$inscritIDequipe'  ";
					//on execute la requete
					$result10 = $con->query($sql10) or die("l'insertion de la mise a jour du nombre d'equipiers a echouée !");
					
					//on prepare la requete permettant de supprimmer les candidatures du candidat qui a ete selectionné pour devenir equipier
					$sql9 = "DELETE FROM candidature WHERE id_inscrit_candidature = '$choix'  ";
					//on execute la requete
					$result9 = $con->query($sql9);
					
					//on renvoie le chef d'equipe à sa page d'accueil
					header('Location: chef_equipe.php');
				}
	
			}
			
			
			//lorsque l'utilisateur selectionne des candidatures et clique sur supprimer
			if(isset($_POST['SUPPRIMER']) && !empty($_POST['listeCandidat']))
			{
				
				$nomInscrit = $_SESSION['nom'];
				$choix ='';

				for ($i=0;$i<count($_POST['listeCandidat']);$i++)
				{
					$choix = $_POST['listeCandidat'][$i];
					$sqlDEL = "DELETE FROM candidature WHERE id_inscrit_candidature = '$choix' && id_equipe_candidature =  '$inscritIDequipe'";
					$resultDEL = $con->query($sqlDEL);
					header('Location: ajouter.php');
					
				}
			}
			
			//lorsque l'utilisateur ne selectionne rien et clique sur supprimer ou ajouter
			if((isset($_POST['SUPPRIMER']) && empty($_POST['listeCandidat'])) || (isset($_POST['AJOUTER']) && empty($_POST['listeCandidat'])))
			{
				//on affiche un message d'erreur et on invite a ressasir
				$erreur = "<div class='message_erreur'>  Choisissez une candidature pour la supprimer ou un candidat à ajouter ! </div> <br>";
			}
	
			
			

		?>

		<!--Formulaire affichant la lite des candidatures presentes dans la BDD et permettant d'ajouter un candidat ou supprimer une candidature qui a ete faite-->
		<form name="postuler" method="post" action="ajouter.php" >
			
			<!--Affichage de la liste des candidats et de leurs informations-->
			<div class ="listeCandidat">
				<?php 
				//script d'affichage de la liste des candidatures
					
					//Texte invitant l'utilisateur de choisir les candidatures qui l'interesse
					echo "Quel candidat(s) accepter ? <br><br>";
					//explication de l'action des boutons
					echo "Cliquez sur 'AJOUTER' pour ajouter les candidats selectionnés  <br>";
					echo "Cliquez sur 'SUPPRIMER' pour supprimer les candidatures selectionnées<br> <br><br>";
					
					//si des candidatures existent pour l'equipe en question
					if ($result6->num_rows > 0) 
					{
						//on boucle en stockant les infos de chaque equipe dans un tableau associatif
						while($row6 = $result6->fetch_assoc())
						{	
							//on recupere l'identifiant de l'inscrit qui a candidaté
							$candidatureIDinscrit =  $row6["id_inscrit_candidature"];
							//on prepare la requete permettant de recuperer les informations de l'inscrit qui a candidaté
							$sql7 = "SELECT * FROM inscrit WHERE id_inscrit = '$candidatureIDinscrit' ORDER BY nom_inscrit ASC";
							//on execute la requete
							$result7 = $con->query($sql7) or die("la recuperation des infos de l'inscrit a echouée !");
							//on stocke les informations de l'inscrit dans un tableau associatif
							$row7 = $result7->fetch_assoc();
							
							//on affiche les informations tout en nous protegant contre l'injection de code
							echo '<td><input type="checkbox" name="listeCandidat[]" value='.htmlentities($row6["id_inscrit_candidature"]).'>Mon nom est '.htmlentities($row7["nom_inscrit"]).':</td> <br><br>';
							echo '<textarea id="motivation" cols="70" rows="7" class="motivations"> Je postule car :'.htmlentities($row6["motivations"]).' .Pour de plus amples informations, je suis joignable au '.htmlentities($row7['telephone_inscrit']).' </textarea> <br><br>';	
						}
					}
				
					//si aucune candidatures n'a ete effectué pour l'equipe du chef d'equipe
					else 
					{
						//on affiche un message l'incitant a revenir plus tard
						echo "<div class='message_erreur'>Pas de candidatures disponibles pour l'instant ! Revenez plus tard ! </div><br>";
	 
					}
					
					//si une eventuelle erreur est detectée
					if(isset($erreur))
					{
						//on affiche l'erreur
						echo $erreur;
					}
				?>
		
				<!--Boutons de validation du formulaire-->
				<input type='submit' value='AJOUTER' name ='AJOUTER' class='boutons'><br>
				<input type='submit' value='SUPPRIMER' name ='SUPPRIMER' class='boutons'><br>
			
			</div>
			
		</form>
	
	</body>
	
</html>