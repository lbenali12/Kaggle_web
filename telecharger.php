<!DOCTYPE html>
<html>
	
	<head>
	
	
		<title> Telecharger </title>
		
		
		<?php include("entete.include.php");?>
		

	</head>
	
	
	<body>
	
 
		<?php 


			//script de redirection des utilisateurs indesirables
			include("paramBDD.include.php");
			session_start();
			if(isset($_SESSION['nom'])){			
		$nomUser=$_SESSION['nom'];	
		$strSQLUser = "SELECT * FROM `inscrit` WHERE nom_inscrit = '$nomUser' "; 
		$queryUser = mysqli_query($con, $strSQLUser);
		$rowUser = $queryUser->fetch_assoc();
			if($rowUser['statut_inscrit'] == "INSCRIT"){
				header('Location: inscrit.php');
							}
						}
			else{
				header("Location: index.php");
				}
	
			if(isset($_SESSION['nom']) && $rowUser['statut_inscrit'] == 'CHEF_EQUIPE'){
			include("banniere_chef.include.php");	
			}
			
			else{
				include("banniere_equipier.include.php");
				}

		
		
		
		
		$inscritIDequipe =  $rowUser["id_equipe_inscrit"];
		
		//on recupere les reunions ayant un compte-rendu qui a ete depose		
		$sql6 = "SELECT *, DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR')) FROM reunion WHERE id_reunion_equipe = '$inscritIDequipe' && cr_depose = 1 ORDER BY date_reunion, heure_reunion, minutes_reunion ASC";
		$result6 = $con->query($sql6);
	?>


		<p class="label_titre_page"> VOS REUNIONS </p>
		<p class="centre"><img src="imgsource/report.png" class="tresPetiteImage" alt="decoration"></p>
		
			
			
		<?php
			
				


			if(isset($_POST['TELECHARGER'])){
				
				
				
				$nomInscrit = $_SESSION['nom'];
				$choix ='';
				$erreur='';
				$depot='';
				

				//lorsqu'une reunion est selectionnée		
				if(isset($_POST['listeReunion'])){		
				for ($i=0;$i<count($_POST['listeReunion']);$i++)
					{ 
						//On recupere les attributs de la reunion selectionnée
						$choix = $_POST['listeReunion'][$i];			
						$sql3 = "SELECT * FROM reunion  WHERE id_reunion_equipe = '$inscritIDequipe' && id_reunion='$choix'";
						$result3 = $con->query($sql3);
						$rowCR = $result3->fetch_assoc();
						
						$dateCR = $rowCR['date_reunion'];
						$motifCR = $rowCR['motif_reunion'];
						$idCR = $rowCR['id_reunion'];
						
						$salt='7G0yFBt6LwHa4P0jfwAj';
						$random=md5('IZJ8Pz5ZYcMTWMTqZGKB'.sha1($salt));
							
						
						//on instancie le chemin menant au compte-rendu
						$idCR = $rowCR['id_reunion'];
						$directoryCR = $random.'/'.md5($inscritIDequipe).'_'.sha1($idCR).'.pdf';
						
						
						
						$nom_CR='cr_reunion_'.$dateCR.'_'.$motifCR.'.pdf';
						
						
							//ces header permettent de mettre en place le telechargement du compte-rendu correspondant à la reunion selectionnée
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$nom_CR);
							
							
						
							//On va chercher le compte-rendu de la reunion selectionnée
							readfile($directoryCR);
							exit;
						
						
					
					
					}
				}
else{
	$erreur = '<div class="message_erreur2"> Erreur lors du transfert du compte-rendu ! Choisissez la reunion pour laquelle vous voulez le compte-rendu !<br> <br></div>';
}				
						
			}
				
			
			
			
			
			
			
?>

		<!--formulaire permettant le telechargement du compte-rendu-->
		<form name="postuler" method="post" action="telecharger.php" enctype="multipart/form-data" >
		<div class ="listeCandidat">
			<?php 
			
			
			
			echo "Selectionnez une reunion pour telecharger un compte-rendu ?<br> <br>";
		

			if ($result6->num_rows > 0) {
					
					
					while($row6 = $result6->fetch_assoc()){	
					$reunionID =  $row6["id_reunion"];
					$reunionMotif =  $row6["motif_reunion"];
					$reunionDate =  $row6["DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR'))"];
					$reunionheure =  $row6["heure_reunion"];
					$reunionMinutes =  $row6["minutes_reunion"];
	
					echo '<td><input type="radio" name="listeReunion[]" value='.htmlentities($row6["id_reunion"]).'>Ordre du jour de la reunion du '.htmlentities($row6["DATE_FORMAT(date_reunion, GET_FORMAT(DATE, 'EUR'))"]).' à '.htmlentities($reunionheure).' heures '.htmlentities($reunionMinutes).' : '.htmlentities($reunionMotif).'.</td> <br><br>';
					
					
					}

					
			}
			else {
     echo "<div class='message_erreur'>Pas de compte-rendus disponibles ! </div><br>";
	 
	 if($rowUser['statut_inscrit'] == 'CHEF_EQUIPE'){
	 echo "<a href='planifier_reunion.php' class='boutons'> PLANIFIEZ UNE REUNION ! </a> <br>";
	  echo "<a href='deposer.php' class='boutons'> DEPOSEZ UN COMPTE-RENDU ! </a> <br>";
	 }
} ?>
		
			
			<?php if(isset($_POST['TELECHARGER'])){
				echo $erreur;
				echo $depot;
			}?>
			
			
			<input type='submit' value='TELECHARGER' name ='TELECHARGER' class='boutons'><br>
		</div>
		</form>
		

		
	</body>
</html>