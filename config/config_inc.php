<?php
	
	//database parameter
	//----------------------------
	$g_hostname = 'localhost';
	$g_db_type = 'mysql';
	$g_database_name = 'mantisDB';
	$g_db_username = 'mantis_User';
	$g_db_password = 'mantis_Password';

	// Mantis Root URL (to see tickets when we click on them)
	$MANTIS_URL="http://<ROOT_URL>/mantis";

	// Custom_field name configured in mantis-db
	$CUSTOM_FIELD_TIME_CONSUMED="Time consumed";
	
	//Final imputation TEXT
	// this last parameter tell me if the time consumed will be billed to the entity or to the customer
	$TC_FINAL="Imputed to my project";
	// Projects ID on which time is attached to my projects.
	$TC_FINAL_PROJET=array('19','94');

	$VERSION="Mantis-pilot - Extract V2.2";
	$URL="http://www.mantis-pilot.org";
?>
