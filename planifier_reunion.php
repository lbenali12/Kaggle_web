<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Planifier </title>
		
		<!--On inclut es metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>

	</head>
	
	
	<body>
	
		<?php
		//script de traitement des planifications de la reunion
			
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou inscrit) vers leurs page d'accueil respectives*/
			include("redirection_chef.php");
			
			//on recupere le nom de l'utilisateur sur la page
			$nom = $_SESSION['nom'];
			
			//on prepare la requete permettant de recuperer l'identifiant de l'equipe de l'utilisateur sur la page
			$sql5 = "SELECT * FROM inscrit WHERE nom_inscrit LIKE '$nom'";
			//on execute la requete
			$result5 = $con->query($sql5) or die("la recuperation de l'ID de l'equipe a echouée !");
			//on recupere les informations renvoyées par la requete dans un tableau associatif
			$row5 = $result5->fetch_assoc();
			//on recupere l'identifiant de l'equipe
			$inscritIDequipe =  $row5["id_equipe_inscrit"];
		
			//lorsque l'utilisateur valide sa planification
			if(isset($_POST['PLANIFIER']))
			{
				//on securise les saisies pour les stocker dans la BDD
				$motif = mysqli_real_escape_string($con, $_POST['motif']);
				$date = mysqli_real_escape_string($con, $_POST['date_reunion']);
				$heure = mysqli_real_escape_string($con, $_POST['heure_reunion']);
				$minutes = mysqli_real_escape_string($con, $_POST['minutes_reunion']);
			
				//on instancie des variables pour les eventuelles erreurs
				$erreur_date=' ';
				$erreur_heure=' ';
				$erreur_saisie=' ';
				
				//lorsque tous les champs sont saisis
				if($motif && $date && $heure && $minutes)
				{
					//si la saisie de l'heure et des minutes ne comprend pas d'anomalies
					if($heure>24 || $heure<0 ||$minutes>59 || $minutes<0 )
					{
						//on affiche une erreur et on invite à resaisir
						$erreur_heure = "<div class='message_erreur'> Erreur ! L'heure saisie est invalide !<br> Note: 1 jour = 24 heures ET 1 heure = 60 minutes ! </div> <br>";
					}
					
					//si la saisie est correcte
					else
					{
						//on prepare la requete permettant de stocker la reunion planifiée dans la BDD
						$strSQL = " INSERT INTO reunion VALUES (NULL, '$motif', '$date', '$heure', '$minutes', '$inscritIDequipe', 0)";
						//on execute la requete
						$query = mysqli_query($con, $strSQL) or die("Erreur de synthaxe dans la requete SQL d'insertion de reunion !");
						//on execute a requete
						header('Location: chef_equipe.php');

					}
					
				}
				
				//si au moins un champs n'est pas saisi
				else
				{
					//on affiche une erreur et on invite à resaisir
					$erreur_saisie = '<div class="message_erreur">Erreur ! Saisissez tous les champs ! </div><br>';
				}

			
			}
			
		?>
		
		<!--On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut de chef d'equipe-->
		<?php include("banniere_chef.include.php"); ?>	
		
		<!--On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou inscrit) vers leurs page d'accueil respectives-->
		<?php include("enjeux_site.include.php"); ?>
		
		<!--Formulaire permettant la saisie des informations relatives à la reunion planifiée-->
		<div class="formulaire">
			
			<!--Titre du formulaire-->
			<p class="label_titre_page"> PLANIFIER UNE REUNION </p>
			
			<!-- Image de decoration-->
			<p class="centre"><img src="imgsource/schedule.png" class="moyenneImage"  alt="decoration"></p>
			
			<!--Affchage de la date d'aujourd'hui-->
			<?php 
				$dateToday= date("d.m.y");
				echo '<div class=message_confirmation>Aujourd\'hui, nous sommes le '.htmlentities($dateToday).'. </div><br><br>'; 
			?>
			
			<form name="planifier" method="post" action="planifier_reunion.php" >
			
				<?php 
				//affichage d'une eventule erreur de saisie relative a un champs manquant
					if(isset($erreur_saisie))
					{
						echo $erreur_saisie; 
					}
				?>
			
			
				<!--champs de saisie du motif de la reunion-->
				<label for="motif" class="label_saisie">Motif de la reunion</label> <br>
				<input type="text" id="motif" name="motif" placeholder="Preparation de la competition" maxlength="255" class="saisie" ><br>
			
				<?php 
				//affichage d'une eventule erreur de saisie relative a la date
					if(isset($erreur_date))
					{
						echo $erreur_date; 
					}
				
				?>
				
				<!--champs de saisie de la date de la reunion-->
				<label for="date_reunion" class="label_saisie">Date de la reunion</label><br>
				<input type="date" id="date_reunion" name="date_reunion"  class="saisie" ><br>
			
				<?php
				//affichage d'une eventule erreur de saisie relative a l'heure de la reunion
					if(isset($erreur_heure))
					{
						echo $erreur_heure; 
					}
				?>
				
				<!--champs de saisie pour l'heure de la reunion-->
				<label for="heure_reunion" class="label_saisie">Heure de la reunion</label><br>
				<input type="number" id="heure_reunion" name="heure_reunion" placeholder="HH"  class="saisie_heure"> : <input type="number" id="minutes_reunion" name="minutes_reunion" placeholder="MM"  class="saisie_heure" ><br>
				
				<!--Bouton de validation du formulaire-->
				<input type="submit" value="PLANIFIER" name ="PLANIFIER" class="boutons"><br>
				
				<!--Bouton de reinitialisation du formulaire-->
				<input type="reset" value="EFFACER" class="boutons">
			
			</form>
		
		</div>
		
	</body>
	
</html>