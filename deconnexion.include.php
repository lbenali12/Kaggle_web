
	<?php
	//script permettant la deconnexion	
	
		//Si l'utilisateur appuie sur le bouton de deconnexion
		if(isset($_POST['DECONNEXION']))
		{
			//On detruit la session qui a ete demarrée
			session_destroy();
			
			//on redirige l'utilisateur vers la page d'accueil
			header('Location: index.php');

		}
	?>
