<?php
header('Content-type: text/html; charset=utf-8');  

//######################################
// Includes
//######################################
include ('./config/config_inc.php');
include_once './dao/DAO.php';


//######################################
// Head of the array 
//######################################
echo ' <table border="1" style="font-size:12px;"><tr><th>Ingénieur</th><th>Ticket</th><th>Projet</th><th>Catégorie</th><th>Sujet</th><th>Imputation (dixièmes)</th><th>Imputé projet</th><th>mois</th><th>jour</th><th>Projet imputé</th><th>'.$TC_FINAL.'</th></tr>';

//######################################
// instance of Dao
//######################################
$aDao = new Dao();

//######################################
// open Database connection
//######################################
$aDao->openMantisConnection($g_hostname,$g_db_username,$g_db_password ,$g_database_name);
 
//######################################
// Get all tickets for the year where 
// time consumed has been updated
//######################################
$result =  $aDao->GetAllTickets();


// for each ticket updated 
//--------------------------------------------------------
while ($datasok=mysql_fetch_assoc($result)){
	
	//----------------------------------------
	// Get all Ticket informations
	//----------------------------------------"
	$result3 =  $aDao->GetAllTicketsInformation($datasok);
	$data3=mysql_fetch_assoc($result3);
	$PROJECT_ID=$data3['project_id'];	
	
	//----------------------------------------
	// Select Project Name
	//----------------------------------------"
	$result4 =  $aDao->GetProjectName($data3) ;
	$data4 = mysql_fetch_array($result4);
	$PROJECT_NAME=$data4['name'];
	
	//----------------------------------------
	// Select category
	//----------------------------------------
	$result5 =  $aDao->GetCategoryName($data3);
	$data5 = mysql_fetch_array($result5);
	$CATEGORY_NAME=$data5['name'];

	//----------------------------------------
	// Search name of user
	//----------------------------------------
	$result6 =  $aDao->GetUserName($data3);
	$data6 = mysql_fetch_array($result6);
	$USERNAME=$data6['username'];
	
	//----------------------------------------
	// Total imputation
	//----------------------------------------
	$UNITS=$datasok['total_value'];
	
	//----------------------------------------
	// Subject of ticket
	//----------------------------------------
	$SUBJECT_TICKET=$data3['summary'];


	//----------------------------------------
	//Search of billable or not.
	//----------------------------------------
	$TICKET=$datasok['bug_id'];
	$result7 =  $aDao->GetBillable($TICKET,$CUSTOM_FIELD_TIME_CONSUMED);
	$data7 = mysql_fetch_array($result7);
	$num7= mysql_num_rows($result7);
	
	if  ( $num7 > 0 ){
		$BILLABLE=$data7['new_value'];
	}
	else{
		$BILLABLE="OUI";
	}//if  ( $num9 > 0 ){

	
	//--------------------------------------------------------------
	// Modify final imputation depending the case
	//---------------------------------------------------------------
	
	if ( "$BILLABLE" == "OUI" | "$BILLABLE" == " --- " ){
		$PROJECT_IMPUT_NAME=$PROJECT_NAME;
		$FINAL_IMPUT="0";
	}
	else {
		$FINAL_IMPUT="1";
		// cut the line to get the project name
		$VALEUR = explode('(', $BILLABLE,2);
		// if no error
		if ( @$VALEUR[1] != ""){
			$VALEUR2 = explode(')',@$VALEUR[1]);
			//search Project name of witch we bill
			$result4 =  $aDao->GetProjectName($VALEUR2[0]) ;
			$row4 = mysql_fetch_array($result4);
			$PROJECT_IMPUT_NAME=$row4['name'];
			$PROJECT_ID=$VALEUR2[0];			
		}
		else{
			$PROJECT_IMPUT_NAME = "Error : $BILLABLE";			
		}


	}

	//----------------------------------------
	//Put in form date
	//----------------------------------------	
	list($YEAR, $MONTH, $DAY) = split('[/.-]', $datasok['date']); 	
	$DATE_TICKET="$YEAR-$MONTH";
	$DATE_TICKET_light="$DAY";

	//----------------------------------------
	// Calculate the final imputation
	//----------------------------------------
		$i = 0; 
		$max = count( $TC_FINAL_PROJET ); 
		echo "Ticket ==> $TICKET<br>";
		echo "TC_FINAL_VALUE = $TC_FINAL_VALUE<br>";
		while( $i < $max ) 
		{ 
			echo ''.$TC_FINAL_PROJET[ $i ].' == '.$PROJECT_ID.'<br>';
			if ( $TC_FINAL_PROJET[ $i ] == $PROJECT_ID ){
			$TC_FINAL_VALUE="YES";
			echo "EGAL ==>YES<br>";
			break;
			}
			else {
				echo "PAS EGAL !!<br>";
				if ($FINAL_IMPUT="0" ){
					$TC_FINAL_VALUE="YES";
				}
				else {
					$TC_FINAL_VALUE="NO";
				}
					
				
			
			}  			
			$i++; 
		} 
		echo "TC_FINAL_VALUE = 	$TC_FINAL_VALUE<br>";	
		echo "sortie de while<hr>";
		
	



	//----------------------------------------
	//chow the line
	//----------------------------------------
	echo '<tr><td>'.$USERNAME.'</td><td><a href="'.$MANTIS_URL.'/view.php?id='.$TICKET.'" target="blank">'.$TICKET.'</a></td><td>'.$PROJECT_NAME.'</td><td>'.$CATEGORY_NAME.'</td><td>'.$SUBJECT_TICKET.'</td><td>'.$UNITS.'</td><td>'.$BILLABLE.'</td><td>'.$DATE_TICKET.'</td><td>'.$DATE_TICKET_light.'</td><td>'.$PROJECT_IMPUT_NAME.'</td><td>'.$TC_FINAL_VALUE.'</td></tr>';


}


echo '</table><br><br>';

echo "$VERSION<br>";
echo "<i>$URL</i>"; 

mysql_close();


	


?>
