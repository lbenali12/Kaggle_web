<!DOCTYPE html>
<html>
	
	<head>
		
		<!--Titre de la page-->
		<title> Inscription </title>
		
		<!--On inclut es metadonnées communes à tous le site-->
		<?php include("entete.include.php"); ?>

	</head>
	
	
	<body>
	
	
		<?php
		//Script de traitement des données du formulaire
			
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (inscrit, equipier ou chef d'equipe) vers leurs page d'accueil respectives*/
			include("redirection.php");
			
			//Lorsque l'utilisateur valide le formulaire
			if(isset($_POST['VALIDER'])){	
				
				//on recupere le nom saisi de maniere securisé contre les injections SQL
				$nom = mysqli_real_escape_string($con, $_POST['nom']);
				//on recupere le numero de telephone saisi de maniere securisé contre les injections SQL
				$numero_telephone = mysqli_real_escape_string($con, $_POST['numero_telephone']);
				//on recupere le mot de passe saisi de maniere securisé contre les injections SQL et en le cryptant
				$mot_de_passe = password_hash(mysqli_real_escape_string($con, $_POST['mot_de_passe']), PASSWORD_BCRYPT );
				//on repete le mot de passe saisi de maniere securisé contre les injections SQL afin de verifier qu'il correspond bien au premier mot de passe saisi
				$repete_mot_de_passe = password_verify(mysqli_real_escape_string($con, $_POST['repete_mot_de_passe']), $mot_de_passe);
				
				//On instancie une variable permettant l'affichage d'erreur liée à la saisie du nom
				$erreur_nom=' ';
				//On instancie une variable permettant l'affichage d'erreur liée à la saisie du numero de telephone
				$erreur_num=' ';
				//On instancie une variable permettant l'affichage d'erreur liée à la saisie du mot de passe
				$erreur_mdp=' ';
				//On instancie une variable permettant l'affichage d'erreur liée à l'absence de saisi d'un champ du formulaire
				$erreur_saisie=' ';
						
						//Si tous les champs sont saisis
						if($nom && $numero_telephone && $mot_de_passe)
						{
							//Si le mot de passe repeté correspond au mot de passe saisi
							if($repete_mot_de_passe == '1')
							{
								//On instancie une variable imposant le format que le numero de telephone saisi doit avoir
								$motif ='`^0[1-68][0-9]{8}$`';
									
									//Si le numero de telephone saisi est incompatible avec le format instancié precedemment
									if(!preg_match($motif,$numero_telephone))
									{
										//On affiche une erreur et on invite à resaisir
										$erreur_num = "<div class='message_erreur'> Erreur ! Le numero de telephone n'est pas au bon format ! </div> <br>";
										
									}
						
									//Si le numero de telephone saisi est valide et correspond au format
									else
									{
										//on prepare une requete SQL afin de verifier dans la base de données que le nom saisi n'est pas deja pris par un utilisateur existant
										$strSQL2 = "SELECT * FROM `inscrit` WHERE nom_inscrit = '$nom'"; 
										//On execute la requete
										$query2 = mysqli_query($con, $strSQL2) or die("Erreur de synthaxe dans la requete SQL permettant de savoir si le nom saisi existe deja !");
										//On compte le nombre de ligne retournées par la requete
										$lignes = mysqli_num_rows($query2);
											//Si la requete verifiant si un nom d'utilisateur est deja pris ne retourne aucune ligne
											if($lignes == 0 )
											{	
												//On prepare la requete permettant d'ajouter l'utilisateur
												$strSQL = "INSERT INTO inscrit VALUES (NULL, '$mot_de_passe', '$nom', '$numero_telephone', 'INSCRIT', NULL);";
												//On execute la requete
												$query = mysqli_query($con, $strSQL) or die("Erreur de synthaxe dans la requete SQL permettant d'ajouter un utilisateur !");
												//On redirige l'utilisateur vers la page de connexion
												header('Location: connexion.php');
												
											}
											
											//Si la requete verifiant si un nom d'utilisateur est deja pris retourne au moins ligne 
											else
											{
												//On affiche une erreur et on invite à resaisir
												$erreur_nom = '<div class="message_erreur"> Erreur ! Ce nom d\'utilisateur est deja pris !<br> Choisissez-en un autre ! </div> <br>';
									
											}
				
					
									}
							}
							
							//Si le mot de passe repeté ne correspond pas au mot de passe saisi
							else
							{
								//On affiche une erreur et on invite à resaisir
								$erreur_mdp = '<div class="message_erreur"> Erreur ! Les deux mots de passe saisis <br> ne correspondent pas ! </div> <br>';
							}
				
				
						}
						
						//Si au moins un champ n'est pas saisi 
						else
						{
							//On affiche une erreur et on invite à resaisir
							$erreur_saisie = '<div class="message_erreur">Erreur ! Saisissez tous les champs ! </div><br>';
						}

					
			}
			
		?>
		
		<!-- On inclut la banniere de la page d'accueil commune à tout les pages ou l'utilisateur n'est pas connecté -->
		<?php include("banniere.include.php"); ?>
		
		<!--On ajoute un court texte decriptif des enjeux du site web et renvoyant vers le site officiel Kaggle ou une video explicative du concept Kaggle-->
		<?php include("enjeux_site.include.php"); ?>
		
		<!--formulaire permettant la saisides données pour l'inscription -->
		<div class="formulaire">
		
			<!--Titre du formulaire -->
			<p class="label_titre_page"> INSCRIPTION </p>
		
			<!--ajout d'une image de decoration representant l'Oncle SAM  -->
			<p class="centre"><img src="imgsource/uncleSAM.jpg" class="petiteImage"  alt="decoration"></p>
		
			<!--On precise la methode d'envoi des données du formulaire ainsi que le script permettant de traiter le formulaire  -->
			<form name="inscription" method="post" action="inscription.php" >
		
				<?php 
					//On affiche l'eventuelle erreur relative à un champs manquant lors de la validation du formulaire par l'utilisateur
					if(isset($erreur_saisie))
					{
						echo $erreur_saisie; 
					}
			
				?>
		
				<!--Champs du formulaire permettant la saisie d'un nom contenant au maximum 100 caracteres (taille maximale accepte par la BDD pour l'attribut nom dans la table inscrit) -->
				<label for="nom" class="label_saisie">Nom d'utilisateur</label> <br>
				<input type="text" id="nom" name="nom" placeholder="Harry Cover" maxlength="100" class="saisie" ><br>
		
				<?php 
					//On affiche l'eventuelle erreur relative à un numero de telephone invalide lors de la validation du formulaire par l'utilisateur
					if(isset($erreur_num))
					{
						echo $erreur_num; 
					}
			
				?>
		
				<!--Champs du formulaire permettant la saisie d'un numero de telephone-->
				<label for="numero_telephone" class="label_saisie">Numero de telephone</label><br>
				<input type="text" id="numero_telephone" name="numero_telephone" placeholder="0646118539" maxlength="10" class="saisie" ><br>
			
		
				<?php 
					//On affiche l'eventuelle erreur relative à un  mot de passe invalide lors de la validation du formulaire par l'utilisateur
					if(isset($erreur_mdp))
					{
						echo $erreur_mdp; 
					}
			
				?>
		
				<!--Champs du formulaire permettant la saisie d'un mot de passe  -->		
				<label for="mot_de_passe" class="label_saisie">Mot de passe</label><br>
				<input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="xxxxxx" maxlength="50" class="saisie"><br>
				<label for="repete_mot_de_passe" class="label_saisie">Confirmez votre mot de passe</label><br>
				<input type="password" id="repete_mot_de_passe" name="repete_mot_de_passe" placeholder="xxxxxx" maxlength="50" class="saisie" ><br>
		
				<!--Bouton de validation du formulaire -->
				<input type="submit" value="VALIDER" name ="VALIDER" class="boutons"><br>
		
				<!--Bouton de reinitialisation du formulaire -->
				<input type="reset" value="EFFACER" class="boutons">
		
		
			</form>
		
		</div>
		
	</body>
	
</html>