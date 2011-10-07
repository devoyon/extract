<?php

class Dao {

	public function openMantisConnection($MANTIS_DB_SERVER,$MANTIS_DB_USER,$MANTIS_DB_PASS,$MANTIS_DB_DATABASE) {
		//Mantis Database Connection
		mysql_connect($MANTIS_DB_SERVER,$MANTIS_DB_USER,$MANTIS_DB_PASS);
		@mysql_select_db($MANTIS_DB_DATABASE) or die( "Unable to select database");
		//only after conection
		mysql_query("SET NAMES 'utf8'");
	
	}
	
	public function CloseMysqlConnection() {
	mysql_close();
	}


	public function GetAllTickets() {
		
			$sql = "SELECT 
					h.id, 
					h.user_id, 
				   	u.username,
				    	p.id as Project_id,
					p.name as Project_name, b.summary,
					h.bug_id, 
					c.value as billable,
					(h.new_value-h.old_value) as total_value, 
					h.date_modified, 
					date FROM (SELECT *, 
					DATE_FORMAT(FROM_UNIXTIME(date_modified),'%Y-%m-%d') 
					as date FROM 
					mantis_bug_history_table
					WHERE 
					field_name LIKE '%Temps cons%' 
					ORDER BY bug_id ASC, 
					date_modified DESC) 
					as h 
				INNER JOIN 
					mantis_user_table u
				ON
				        h.user_id = u.id 
				INNER JOIN
				 	mantis_bug_table b
				ON
				        h.bug_id = b.id
				INNER JOIN
				        mantis_project_table p
				ON
				        b.project_id = p.id
				INNER JOIN 
						mantis_custom_field_string_table c 
				ON
						h.bug_id = c.bug_id 
				AND
					c.field_id='6' 
				GROUP BY 
					h.bug_id, date 
				ORDER BY 
					date ASC, p.id ASC";

		//echo "SQL = $sql<br>"; //debug
		//Get values
		$result=mysql_query($sql);
									
		//return result to main program
		return $result;

			}

	public function GetAllTicketsInformation($datasok) {
		$sql ="SELECT * FROM mantis_bug_table WHERE id=$datasok[bug_id] order by last_updated";
		//echo "SQL = $sql<br>"; //debug
		//Get values
		$result=mysql_query($sql);
									
		//return result to main program
		return $result;
	}

	public function GetProjectName($data3) {
		$sql ="SELECT * FROM mantis_project_table WHERE id='$data3[project_id]'";
		//echo "SQL = $sql<br>"; //debug
		//Get values
		$result=mysql_query($sql);
									
		//return result to main program
		return $result;
	}

	public function GetCategoryName($data3) {
		$sql ="SELECT * FROM mantis_category_table WHERE id='$data3[category_id]'";
		//echo "SQL = $sql<br>"; //debug
		//Get values
		$result=mysql_query($sql);
									
		//return result to main program
		return $result;
	}

	public function GetUserName($data3) {
		$sql ="SELECT * FROM mantis_user_table WHERE id='$data3[handler_id]'";
		//echo "SQL = $sql<br>"; //debug
		//Get values
		$result=mysql_query($sql);
									
		//return result to main program
		return $result;
	}

	public function GetBillable($TICKET) {
		$sql ="SELECT * FROM mantis_bug_history_table WHERE bug_id=$TICKET and field_name like '%[CIL] Imputation Projet%' order by date_modified DESC";
		//echo "SQL = $sql<br>"; //debug
		//Get values
		$result=mysql_query($sql);
									
		//return result to main program
		return $result;
	}




}	



?>
