<!DOCTYPE html>
<html>
	
	<head>
		
		<!--Titre de la page-->
		<title> Connexion </title>
		
		<!--On inclut es metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>
		
	</head>
	
	
	<body>
	
		<?php
		//Script de traitement des données du formulaire
		
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (inscrit, equipier ou chef d'equipe) vers leurs page d'accueil respectives*/
			include("redirection.php");
			
			//Lorsque l'utilisateur valide le formulaire
			if(isset($_POST['VALIDER']))
			{
				
				//On instancie une variable permettant l'affichage d'erreur liée l'inexistance d'un compte
				$erreur_compte='';
				
				//on recupere le nom saisi de maniere securisé contre les injections SQL
				$nom = mysqli_real_escape_string($con, $_POST['nom']);
				//on recupere le mot de passe saisi de maniere securisé contre les injections SQL
				$mot_de_passe = mysqli_real_escape_string($con,( $_POST['mot_de_passe']));
				
				//on prepare une requete SQL afin de verifier dans la base de données que la combinaison nom et mot de passe saisi existe bien dans la base de données
				$strSQL2 = "SELECT * FROM `inscrit` WHERE nom_inscrit = '$nom'"; 
				//On execute la requete
				$query2 = mysqli_query($con, $strSQL2) or die("la requete SQL de recuperation des informayions a echouée");
				//On recupere les informations renvoyées par la BDD dans un tableau associatif
				$row = $query2->fetch_assoc();
				//On recupere le mot de passe des informations du tableau associatif renvoyé par la BDD
				$mdpDB=$row['mdp_inscrit'];
				//On verifie que le mot de passe saisie correspond bien au mot de passe crypté de la BDD
				$verif =password_verify($mot_de_passe, $mdpDB);
				
				//Si le mot de passe saisi et le mot de passe de la BDD correspondent
				if($verif == 1 )
				{
					//Si l'utilisateur est un simple inscrit
					if($row['statut_inscrit'] == "INSCRIT")
					{
						//On le renvoie vers la page des inscrits
						$_SESSION['nom']= $nom;
						header('Location: inscrit.php');
					}
					
					//Si l'utilisateur est un equipier
					else if($row['statut_inscrit'] == "EQUIPIER")
					{
						//On le renvoie vers la page des equipiers
						$_SESSION['nom']= $nom;
						header('Location: equipier.php');
					}
					
					//Si l'utilisateur est un chef d'equipe					
					else
					{
						//On le renvoie vers la page des chefs d'equipes
						$_SESSION['nom']= $nom;
						header('Location: chef_equipe.php');
					}
					
				}
				
				//Si le mot de passe saisi et le mot de passe de la BDD ne correspondent pas
				else
				{
					//on affiche une erreur et on invite  resasir
					$erreur_compte = '<div class="message_erreur"> Erreur ! Cet utilisateur n\'existe pas !<br></div>';
	
				}
	
			}
			
		?>
		
		<!-- On inclut la banniere de la page d'accueil commune à tout les pages ou l'utilisateur n'est pas connecté -->
		<?php include("banniere.include.php"); ?>
		
		<!--On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle-->
		<?php include("enjeux_site.include.php"); ?>
		
		<!--formulaire permettant la saisides données -->
		<div class="formulaire">
		
			<!--Titre du formulaire -->
			<p class="label_titre_page"> CONNEXION </p>
			
			<!--ajout d'une image de decoration representant l'Oncle SAM  -->
			<p class="centre"><img src="imgsource/uncleSAM.jpg" class="petiteImage"  alt="decoration"></p>
			
			<!--On precise la methode d'envoi des données du formulaire ainsi que le script permettant de traiter le formulaire  -->
			<form name="connexion" method="post" action="connexion.php" >
				
				<!--Champs du formulaire permettant la saisie d'un nom contenant au maximum 100 caracteres (taille maximale accepte par la BDD pour l'attribut nom dans la table inscrit) -->
				<label for="nom" class="label_saisie">Nom d'utilisateur</label> <br>
				<input type="text" id="nom" name="nom" placeholder="Harry Cover" maxlength="100" class="saisie" ><br>
				
				<!--Champs du formulaire permettant la saisie d'un mot de passe  -->
				<label for="mot_de_passe" class="label_saisie">Mot de passe</label><br>
				<input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="xxxxxx" maxlength="50" class="saisie"><br>
				
				<!--Bouton de validation du formulaire -->
				<input type="submit" value="VALIDER" name ="VALIDER" class="boutons"><br>
				
				<!--Bouton de reinitialisation du formulaire -->
				<input type="reset" value="EFFACER" class="boutons"><br>
				
				
				<?php 
					//On affiche l'eventuelle erreur relative à un  compte inexistant
					if(isset($erreur_compte))
					{
						echo $erreur_compte; 
						echo '<br><a href="inscription.php" class="boutons"> INSCRIVEZ-VOUS ! </a> <br>';
					}
				?>
				
			</form>
			
		</div>
		
	</body>
	
</html>