<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Creation d'equipe </title>
		
		<!--On inclut les metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>

	</head>
	
	
	<body>
		<?php
		//Script permettant la creation d'une equipe
		
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou chef d'equipe) vers leurs page d'accueil respectives*/
			include("redirection_inscrit.php");
			
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut d'inscrit*/
			include("banniere_inscrit.php");
			
			//lorsque l'utilisateur valide sa saisie
			if(isset($_POST['VALIDER']))
			{
				//on recupere le nom de l'equipe  creer tout en nous protegeant contre les injections SQL
				$nomEquipe = mysqli_real_escape_string($con, $_POST['nom']);
				
				//Instanciation d'une variable d'erreur
				$erreur_nom=' ';
				
				//on recupere le nom de l'inscrit
				$nomInscrit=$_SESSION['nom'];
				
				//Si l'utlisateur a saisi un nom d'equipe
				if($nomEquipe)
				{
					//on prepare la requete verifiant si le nom saisi ne correspond pas au nom d'une equipe deja créée
					$strSQL4 = "SELECT * FROM equipe WHERE nom_equipe = '$nomEquipe'"; 
					//on execute la requete
					$query4 = mysqli_query($con, $strSQL4) or die("Erreur de synthaxe dans la requete SQL verifiant le nom !");
					//on compte le nombre de lignes renvoyées par la requete
					$lignes = mysqli_num_rows($query4);
					
					//si le nombre de ligne est de 0
					if($lignes == 0 )
					{
						//on prepare la requete permettant de recuperer l'identifiant de l'utilisateur qui vient de creer une equipe
						$sql5 = "SELECT id_inscrit FROM inscrit WHERE nom_inscrit LIKE '$nomInscrit'";
						//on execute la requete
						$result5 = $con->query($sql5)or die("la recuperation de l'identifiant de l'utilisateur a echoué !");
						//on stocke les informations recuperées dans un tableau associatif
						$row5 = $result5->fetch_assoc();
						//on recupere l'identifiant de l'utilisateur qui est stocké dans le tableau associatif
						$inscritID =  $row5["id_inscrit"];
						
						//on prepare la requete permettant de supprimer les differentes candidatures de l'utilisateur creant l'equipe afin de garder la BDD à jour
						$strSQL = "DELETE FROM candidature WHERE id_inscrit_candidature = '$inscritID'"; 
						//on execute la requete
						$query = mysqli_query($con, $strSQL) or die("Erreur de synthaxe dans la requete SQL supprimant les candidatures!");
						
						//on prepare la requete permettant d'inserer l'equipe créée
						$strSQL = "INSERT INTO equipe VALUES (NULL, '$nomEquipe', 1);";
						//on execute la requete
						$query = mysqli_query($con, $strSQL) or die("Erreur de synthaxe dans la requete SQL d'insertion de l'equipe !");
						
						//on prepare la requete permettant de recuperer l'identifiant de l'equipe qui vient d'etre créée
						$sql9 = "SELECT id_equipe FROM equipe WHERE nom_equipe LIKE '$nomEquipe'";
						//on execute la requete
						$result9 = $con->query($sql9)or die("la recuperation de l'ID de l'equipe cree a echouée !");
						//on stocke les informations de l'equipe recuperées dans un tableau associatif
						$row9 = $result9->fetch_assoc();
						//on recupere l'identifiant de l'equipe qui est stocké dans le tableau associatif
						$equipeID =  $row9["id_equipe"];
						
						//on prepare la requete permettant de modifier le statut de l'utilisateur qui vient de creer l'equipe et de lui assigner l'id de l'equipe cree comme clé etrangere
						$strSQL2 = "UPDATE inscrit SET statut_inscrit = 'CHEF_EQUIPE', id_equipe_inscrit = '$equipeID' WHERE id_inscrit = '$inscritID'";
						//on execute la requete
						$query2 = mysqli_query($con, $strSQL2) or die("le changement de statut a echoué !");
						
						//on redirige l'utilisateur vers la page d'accueil des chefs d'equipes
						header('Location:chef_equipe.php');
						
					}
					
					//si le nombre de lignes retournées est superieur à 0
					else
					{
						//on affiche une erreur et on invite à ressaisir
						$erreur_nom = '<div class="message_erreur"> Erreur ! Ce nom d\'equipe est deja pris !<br> Choisissez-en un autre ! </div> <br>';
					
					}	
				
				}
				
				//Si l'utlisateur n'a rien saisi
				else
				{
					//on affiche une erreur et on invite à ressaisir
					$erreur_nom = '<div class="message_erreur"> Erreur ! Saisissez le nom de l\'equipe ! </div> <br>';
					
				}
	
			}
			
		
		?>
		
		<!--On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle-->
		<?php include("enjeux_site.include.php"); ?>
		
		<!--Formulaire permettant la saisie d'un nom d'equipe-->
		<div class="formulaire">
			
			<!--Titre du formulaire -->
			<p class="label_titre_page"> CREATION D'EQUIPE </p>
			
			<!--ajout d'une image de decoration-->
			<p class="centre"><img src="imgsource/createteam.png" class="moyenneImage"  alt="decoration"></p>
			
			<form name="creerEquipe" method="post" action="creer_equipe.php" >
		
				<?php 
				//affichage d'une eventuel erreur conceranant le nom de l'equipe apres validation du formulaire
				if(isset($erreur_nom))
				{ 
			
				echo $erreur_nom; 
				
				}
				?>
				
				<!--Champs de saisie du nom de l'equipe-->
				<label for="nom" class="label_saisie">Nom de l'equipe</label> <br>
				<input type="text" id="nom" name="nom" placeholder="Lakers" maxlength="100" class="saisie" ><br>
				
				<!--Bouton de validation du formulaire -->
				<input type="submit" value="VALIDER" name ="VALIDER" class="boutons"><br>
				
				<!--Bouton de reinitialisation du formulaire -->
				<input type="reset" value="EFFACER" class="boutons">
				
			</form>
			
		</div>
		
	</body>
	
</html>