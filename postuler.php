<!DOCTYPE html>
<html>
	
	<head>
		
		<!--Titre de la page-->
		<title> Postuler </title>
		
		<!--On inclut les metadonnées communes à tous le site-->
		<?php include("entete.include.php");?>

	</head>
	
	
	<body>
		<?php 
		
			/*On ajoute la banniere commmune a toute les pages du site ou l'utilisateur est connecté et possede le statut d'inscrit*/
			include("banniere_inscrit.include.php"); 
			
			/*On ajoute un script permettant de rediriger les utilisateurs indesirables (visiteurs, equipier ou chef d'equipe) vers leurs page d'accueil respectives*/
			include("redirection_inscrit.php");
			
		?>
		
		<!--Affichage du titre de la page-->
		<p class="label_titre_page"> POSTULER </p>
		
		<!-- Image de decoration -->
		<p class="centre"><img src="imgsource/apply.png" class="tresPetiteImage"  alt="decoration"></p>
		
		<?php

			//On precise les parametres permettant de se connecter à la base de données et on se connecte à celle-ci
			include("paramBDD.include.php");
			
			//On instancie une variable permettant l'affichage d'une eventuelle erreur 
			$erreur='';
			
			//Lorsque l'utilisateur valide le formulaire
			if(isset($_POST['POSTULER']) && !empty($_POST['listeEquipe']))
			{
				//on recupere son nom
				$nomInscrit = $_SESSION['nom'];
				//on recupere ses motivations tout en securisant la saisie contre les injections sql
				$motivations = mysqli_real_escape_string($con, $_POST["motivations"]);
				
				//une fois que les motivations ont ete saisies
				if($motivations)
				{
					
					//On prepare une requete permettant de recuperer l'identifiant de la personne qui est sur la page
					$sql5 = "SELECT id_inscrit FROM inscrit WHERE nom_inscrit LIKE '$nomInscrit'";
					//On execute la requete 
					$result5 = $con->query($sql5) or die ("Erreur dans la requete SQL recuperant l'identifiant de l'inscrit !");
					//on stocke les valeurs retournées par la requete dans un tableau associatif
					$row5 = $result5->fetch_assoc();
					//On recupere l'identifiant
					$inscritID =  $row5["id_inscrit"];
					
					//on recupere les motivations saisies tout en nous protegeant contre les injections SQL
					$motivation = mysqli_real_escape_string($con, $_POST["motivations"]);
					
					//variable permettant de stocker le(s) choix de l'utilisateur
					$choix ='';
					
					//on parcoure la liste des equipes qui sont affichées
					for ($i=0;$i<count($_POST['listeEquipe']);$i++)
					{
						//on stocke le choix qui correpond à l'identifiant de l'equipe selectionnée si une equipe est coché
						$choix = $_POST['listeEquipe'][$i];
						
						//on prepare la requete permettant de retourner les informations de l'equipe selectionnée qui sera l'equipe our laquelle l'utilisateur a choisi de postuler
						$sql3 = "SELECT * FROM equipe WHERE nom_equipe LIKE '$choix'";
						//on execute la requete
						$result3 = $con->query($sql3) or die("la requete de recuperation des equipes a echoué");
						//on recupere et on stocke les informations renvoyées par la requete dans un tableau associatif
						$row2 = $result3->fetch_assoc();
						//on recupere l'identifiant et le nom de l'equipe selectionnée
						$eqpid =  $row2["id_equipe"];
						$eqpnom = $row2["nom_equipe"];
						
						//on prepare la requete permettant de verifier si le candidat n'a pas deja candidaté à l'equipe selectionné
						$sql6 = "SELECT * FROM candidature WHERE id_inscrit_candidature = '$inscritID' && id_equipe_candidature = '$eqpid' ";
						//on execute la requete
						$result6 = $con->query($sql6) or die("la requete de verification des candidatures a echouée");
						
						//Si la requete verifiant si le candidat n'a pas deja candidaté à l'equipe selectionné renvoie 1 lignes cad s'il a deja candidaté pour l'equipe selectionnée
						$nbLignes = mysqli_num_rows($result6);
						if($nbLignes > 0)
						{
							//on ne fait rien pour eviter l'encombrement de la BDD
							
						}
						
						//Sinon
						else
						{
							
							//on prepare la requete permettant l'insertion de la candidature dans la BDD
							$sql4 = "INSERT INTO candidature VALUES (NULL, '$motivations', '$inscritID' , '$eqpid')";
							//on execute la requete
							$result4 = $con->query($sql4) or die("la requete d'insertion de la candidature a echouée !");
							

						}

	
					}
					
					//A la fin de l'envoi des candidatures, on redirige l'utilisateur vers sa page d'accueil
					header('Location: inscrit.php');

				}
				
				//Si rien a ete saisi dans le champs des motivations
				else 
				{
					//on affiche un message d'erreur et on invite a ressasir
					$erreur = "<div class = 'message_erreur'> Saisissez vos motivations avant de postuler ! </div>";
				}

			}
			
			//Si aucune equipe n'a ete selectionnée
			else
			{
				//on affiche un message d'erreur et on invite a ressasir
				$erreur = "<div class='message_erreur'>  Choisissez une equipe pour postuler ! </div>";
			}

		?>
		
		<!--Formulaire affichant la lite des equipes presentes dans la BDD et permettant de postuler à ces equipes-->
		<form name="postuler" method="post" action="postuler.php" >
			
			<!--Affichage de la liste des equipes selon une colonne à sur la partie droite de l'ecran-->
			<div class ="listeBDD">
			
				<?php 
				//script de recuperation des equipes et de leurs informations
					
					//on prepare la requete permettant de recuperer la liste des equipes
					$sql = "SELECT nom_equipe FROM equipe ORDER BY nom_equipe ASC";
					//on execute la requete
					$result = $con->query($sql) or die("la requete de recuperation des equipes a echoué");
					
					//Texte invitant l'utilisateur de choisir les equipes auxquels il veut postuler
					echo "Dans quelle(s) equipe(s) postuler ?<br> <br>";
					
					//si la requete de recuperation des equipes renvoie plus de 0 lignes
					if ($result->num_rows > 0) 
					{
						//on stocke, pour chaque equipe, ses informations dans un tableau associatif 
						while($row = $result->fetch_assoc())
						{
							//affichage du nom de l'equipe à coté d'une 'checkbox' que l'utilisateur pourra selectionner. On protege l'affichage de ces informations à l'aide de la fonction 'htmlentities'
							echo '<td><input type="checkbox" name="listeEquipe[]" value='.htmlentities($row["nom_equipe"]).'> '.htmlentities($row["nom_equipe"]).'</td> <br>';
						}
						
						//bouton ouvrant une nouvelle fenetre afin d'afficher l'integralité des informations conceranant une equipe
						echo '<a href="afficher_equipe.php" class="boutons" target="_blank"> DETAILS DES EQUIPES  </a>';
		
					}
					
					//si la requete de recuperation des equipes renvoie 0 ligne
					else 
					{
						//on informe l'utilisateur qu'aucune equipe n'a encore ete créée et on lui demande de rezvenir plus tard ou de creer une equipe
						echo "<div class='message_erreur'>Pas d'equipe disponibles pour l'instant ! Revenez plus tard ! </div><br>";
						echo "<a href='creer_equipe.php' class='boutons'> CREEZ-EN UNE ! </a>";
					} 
				?>
				
			</div>
			
			<!--Formulaire permettant de saisir les motivations de l'utilisateur-->
			<!--Les deux formulaires ont ete separés car ils ne possedent pas les memes propriétés CSS-->
			<div class="formulaire2">
				
				<!--Champ de saisie des motivations de l'utilisateur-->
				<label for="motivations">Vos motivations</label> <br> <br>
				<textarea id="motivations"  name="motivations" rows="10" cols="30" placeholder="Inserez ici une phrase en 255 caracteres maximum et sans caracteres speciaux mettant en valeur vos motivations !" class="motivations" maxlength="255"></textarea><br>
				
				<?php 
					//affichage d'une eventuelle erreur
					if(isset($_POST['POSTULER']))
					{
						echo $erreur;
					}
				?>
				
				<!--Bouton de validation du formulaire-->
				<input type='submit' value='POSTULER' name ='POSTULER' class='boutons'><br>
			</div>
		
		</form>

	</body>
	
</html>