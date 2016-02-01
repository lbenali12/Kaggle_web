<!DOCTYPE html>
<html>
	
	<head>
	
		<!--Titre de la page-->
		<title> Deposer </title>
		
		<!--On inclut les metadonnées communes à tous le site-->
		<?php include("entete.include.php");?>
		
	</head>
	
	
	<body>
	
		<?php 
		//Script de recuperation des informations de la reunion pour laquelles le compte-rendu est deposé
			
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
			
			//on prepare la requete permettant de recuperer et afficher les reunions n'ayant pas encore de compte-rendus
			$sql6 = "SELECT *, DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR')) FROM reunion WHERE id_reunion_equipe = '$inscritIDequipe' && cr_depose = 0 ORDER BY date_reunion, heure_reunion, minutes_reunion DESC";
			//on execute la requete
			$result6 = $con->query($sql6) or die("la recuperation des reunions a echouée !");
			
		?>

		<!--Titre du formulaire de depot-->
		<p class="label_titre_page"> DEPOSER UN COMPTE-RENDU DE REUNION	</p>
		
		<!-- Image de decoration-->
		<p class="centre"><img src="imgsource/report.png" class="tresPetiteImage"  alt="decoration"></p>
		
	
		<?php
		//script permettant de traiter le depot
		

			//lorsque l'utilisateur valide son depot
			if(isset($_POST['DEPOSER']))
			{

				//on recupere le nom de l'utilisateur sur la page
				$nomInscrit = $_SESSION['nom'];
				
				//on instancie une variable de choix qui stockera les informations de la reunions pour laquelle la reunion se fera
				$choix ='';
				
				//on instancie une variable qui affichera les eventuelles erreurs liées au depot
				$erreur='';
				
				//on insatncie une variable qui informera l'utilisateur qu'il peut convertir ses documents a partir d'un site tiers 
				$convert='';
				
				//variable stockant l'extension valide du document à deposer
				$format_valide = array('pdf');
				
				//variable recuperant l'extension du document deposé
				$format_fichier = strtolower(  substr(  strrchr($_FILES['reunion_CR']['name'], '.')  ,1)  );
				
				//si le depot du compte-rendu renvoie une erreur
				if (($_FILES['reunion_CR']['error'] > 0)  )
				{
					
					//on affiche l'erreur en fonction de sa nature
					switch($_FILES['reunion_CR']['error'])
					{
						case 1: $erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! La taille du fichier excede la taille maximale autorisée !<br> <br></div>';
						case 2: $erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! La taille du fichier excede la taille maximale autorisée !<br> <br></div>';
						case 3: $erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! Le fichier n\'a ete que partiellement envoyé ! Merci de le deposer à nouveau !<br> <br></div>';
						case 4: $erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! La taille du fichier excede la taille maximale autorisée !<br> <br></div>';
						
					}
				
				}
				
				//si pas d'erreur renvoyé
				else
				{
					//on verifie l'extension du document deposé
					if(!in_array($format_fichier, array('pdf')))
					{
						//si l'extension est invalide, on envoie une erreur et on incite a recommencer le depot
						$erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! Seul les fichiers au format PDF sont acceptés !<br> </div>';	
						$convert = '<a href="http://www.conv2pdf.com/" class="boutons" target="_blank"> CONVERTISSEZ VOS DOCUMENTS ! </a> <br><br>';
					
					}
					
					//si l'extension est valide
					else
					{
						//lorsque l'utilisateur aura choisi une reunion
						if(isset($_POST['listeReunion']))
						{
							//on parcoure la liste des reunions affichés
							for ($i=0;$i<count($_POST['listeReunion']);$i++)
							{ 
								//on stocke le choix de l'utilisateur
								$choix = $_POST['listeReunion'][$i];
								//on prepare la requete permettantd de recuperer les informations de la reunions selectionnée
								$sql3 = "SELECT *, DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR')) FROM reunion  WHERE id_reunion_equipe = '$inscritIDequipe' && id_reunion='$choix'";
								//on execute la requete
								$result3 = $con->query($sql3) or die("la recuperation des infos de la reunion a echoué !");
								//on stocke les informations renvoyées par la requete dans un tableau associatif
								$rowCR = $result3->fetch_assoc();
						
								//on recupere les informations sur la reunion qu'on a besoin
								$dateCR = $rowCR["DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR'))"];
								$heureCR = $rowCR['heure_reunion'];
								$minutesCR = $rowCR['minutes_reunion'];
								$motifCR = $rowCR['motif_reunion'];
						
								//on instancie, on crypte et on concatene  deux chaine de caractere afin de proteger le dossier ou se fait le depot
								$salt='7G0yFBt6LwHa4P0jfwAj';
								$random=md5('IZJ8Pz5ZYcMTWMTqZGKB'.sha1($salt));
							
						
								//on recupere l'identifiant de la reunion
								$idCR = $rowCR['id_reunion'];
						
								//On stocke dans une variable le chemin menant au dossier ou le compte-rendu sera deposé
								$directoryCR = $random.'/'.md5($inscritIDequipe).'_'.sha1($idCR).'.pdf';
						
								//on stocke le compte-rendu deposé
								$upload = move_uploaded_file($_FILES['reunion_CR']['tmp_name'], $directoryCR);
						
								//on precise dans la BDD qu'un compte-rendu a ete deposé pour la reunion selectionnée pour plus tard differencier les reunions ayant un compte-rendu des reunions n'en ayant pas
								if($upload)
								{
							
									$sqlconf = "UPDATE reunion SET cr_depose = 1 WHERE id_reunion_equipe = '$inscritIDequipe' && id_reunion='$choix'";
									$resultconf = $con->query($sqlconf);
									header("Location: chef_equipe.php");
							
								}
						

							}
						}
						
						//si aucune reunion n'a ete selectionnée
						else
						{
							//on affiche une erreur et on invite a selectionner une renion
							$erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! Choisissez la reunion pour laquelle vous deposez le compte-rendu !<br> <br></div>';
						}				
						
					}
				}
			
			}
				
		?>

		<!--Formulaire permettant le depot-->
		<form name="postuler" method="post" action="deposer.php" enctype="multipart/form-data" >
			<div class ="listeCandidat">
		
				<?php 
				//Script affichant les reunions et leurs informations
					echo "Pour quel reunion voulez-vous deposer un compte-rendu ?<br> <br>";		
					
					//on affiche les reunions existantes pour l'equipe de l'utilisateur sur la page en securisant contre l'injection de code
					if ($result6->num_rows > 0) 
					{
						while($row6 = $result6->fetch_assoc())
						{	
							$reunionID =  $row6["id_reunion"];
							$reunionMotif =  $row6["motif_reunion"];
							$reunionDate =  $row6["date_reunion"];
							$reunionheure =  $row6["heure_reunion"];
							$reunionMinutes =  $row6["minutes_reunion"];
	
							echo '<td><input type="radio" name="listeReunion[]" value='.htmlentities($row6["id_reunion"]).'>Reunion du '.htmlentities($row6["DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR'))"]).' à '.htmlentities($reunionheure).' heures '.htmlentities($reunionMinutes).'</td> <br><br>';
					
					
					
						}

					
					}
					
					//si aucune reunion n'a ete planifiée
					else 
					{
						//on invite l'utilisateur à en planifier une
						echo "<div class='message_erreur'>Pas de reunions planifiées pour lesquelles vous pouvez deposer un compte-rendu ! </div><br>";
						echo "<a href='planifier_reunion.php' class='boutons'> PLANIFIEZ UNE REUNION ! </a> <br>";
						echo "<a href='telecharger.php' class='boutons'> TELECHARGEZ VOS COMPTE-RENDUS </a> <br> <br>";
	 
					} 
				?>
				
				<!--On precise la taille maximale que pourra avoir le fichier-->
				<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
			
				<?php 
				//on affiche les eventuelles erreurs
					if(isset($_POST['DEPOSER']))
					{
						echo $erreur;
						echo $convert;
					}
				?>
				
				<!--champs de depot du compte-rendu-->
				<label for="reunion_CR" class="label_saisie">Compte-rendu de reunion (Format PDF seulement. Taille maximale: 5 Mo) :</label><br>
				<input type="file" name="reunion_CR" id="reunion_CR" class="saisie"></input><br>
				
				<!--bouton de validation du depot compte-rendu-->
				<input type='submit' value='DEPOSER' name ='DEPOSER' class='boutons'><br>
				
			</div>
			
		</form>
	
	</body>
	
</html>