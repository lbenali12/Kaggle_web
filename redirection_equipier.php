<?php

include("paramBDD.include.php");
session_start();
if(isset($_SESSION['nom'])){
			$nomUser=$_SESSION['nom'];	
			$strSQLUser = "SELECT * FROM `inscrit` WHERE nom_inscrit = '$nomUser' "; 
					$queryUser = mysqli_query($con, $strSQLUser);
					$lignesUser = mysqli_num_rows($queryUser);
					$rowUser = $queryUser->fetch_assoc();
						if($rowUser['statut_inscrit'] == "CHEF_EQUIPE"){
							
							header('Location: chef_equipe.php');
							}
									
						else if($rowUser['statut_inscrit'] =="INSCRIT") {
							header('Location: inscrit.php');
									}
}

else{
	header("Location:index.php");
}
									
?>