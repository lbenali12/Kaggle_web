<?php
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
		
		//on execute la requete
		$queryUser = mysqli_query($con, $strSQLUser)or die("la recuperation du statut a echouée !");
		
		//on stocke le resultat de la requete dans un tableau associatif afin de recuperer l'attribut 'statut_inscrit'
		$rowUser = $queryUser->fetch_assoc();
		
		//Si l'utilisateur est deja equipier et connecté
		if($rowUser['statut_inscrit'] == "EQUIPIER")
		{
			//on le renvoie vers sa page d'accuei
			header('Location: equipier.php');
			
		}
		
		//Si l'utilisateur est deja inscrit et connecté		
		else if($rowUser['statut_inscrit'] =="INSCRIT") 
		{
			//on le renvoie vers sa page d'accuei
			header('Location: inscrit.php');
		}
	}

	
	//Si l'utilisateur est un simple visiteur
	else
	{
		//on le renvoie vers sa page d'accueil
		header("Location:index.php");
	}
	
?>