<?php

require_once("inc/Database.php");

class Model
{
	public function	selectFromQuestion( $question, $type )
	{
		$result = array();
		$db = new Database;
		
		if( $db->Connect() )
		{
			$stmt = $db->Prepare("SELECT header, content, type FROM t_results WHERE question = ? AND type = ?");
			$stmt->bind_param('ss', $question, $type );
			$stmt->execute();
			
			$stmt->bind_result( $header, $content, $type );
			
			$count = 0;
			while( $stmt->fetch() )
			{
				$result[$count]['header'] = $header;
				$result[$count]['content'] = $content;
				$result[$count]['type'] = $type;
				
				$count++;
			}
			
			$stmt->close();
			$db->Close();
			
			return $result;
		}
		
		else
		{
			echo("fail!");
		}
	}
	
	public function insertNewDataArray( $inputArray, $engine, $question )
	{
		$db = new Database;
		
		for($i = 0; $i <= 19; $i++)
		{
			$header = strip_tags( $inputArray[0][$i] );
			$i++;
			$content = strip_tags( $inputArray[0][$i] );
			$type = $engine;
			
			$db->Connect();
			$stmt = $db->Prepare( "INSERT INTO t_results( header, content, type, question ) VALUES( ?,?,?,? )" );
			$stmt->bind_param("ssss", $header, $content, $type, $question );
			$stmt->execute();
			$stmt->close();
			$db->Close();
		}
	}
	
	public function getDates( $question )
	{
		$db = new Database();
		$dates = array();
		
		$db->Connect();
		$stmt = $db->Prepare('SELECT DATE_ADD(date,INTERVAL 6 HOUR) AS OldDate, NOW() AS NewDate FROM `t_results` WHERE question = ? LIMIT 1');

		$stmt->bind_param('s', $question );
		$stmt->execute();
		
		$stmt->bind_result( $dates['oldTime'], $dates['currentTime'] );
		$stmt->fetch();
		$stmt->close();
		$db->Close();
		
		return $dates;
	}
	
	public function checkIfExist( $question, $type )
	{
		$db = new Database();
		
		$db->Connect();
		$stmt =  $db->Prepare( 'SELECT header FROM t_results WHERE question = ? AND type = ?' );
		$stmt->bind_param( 'ss', $question, $type );
		$stmt->execute();
		$stmt->bind_result( $headers );
		$stmt->fetch();
		
		$stmt->close();
		$db->Close();
		
		if( count($headers) > 0 )
		{
			return true;
		}
		
		else
		{
			return false;
		}
	}
	
	public function deleteByQuestion( $question )
	{
		$db = new Database();
		
		$db->Connect();
		$stmt = $db->Prepare('DELETE FROM t_results WHERE question = ?');
		$stmt->bind_param('s', $question );
		$stmt->execute();
		$stmt->close();
		$db->Close();
	}

	public function findDublicates( $question )
	{
		$db = new Database();
		
		$db->Connect();
		$stmt = $db->Prepare('SELECT t_results.header, t_results.content, t_results.type
							  FROM t_results
							    INNER JOIN (SELECT header, COUNT(*) AS CountOf
						                FROM t_results
						                GROUP BY t_results.header
						                HAVING COUNT(*)>=1
						            ) dt ON t_results.header=dt.header
						            WHERE question = ?
						            ORDER BY CountOf DESC, content ASC');
		$stmt->bind_param('s', $question );
		$stmt->execute();
			
		$stmt->bind_result( $header, $content, $type );
			
		$count = 0;
		while( $stmt->fetch() )
		{
			$result[$count]['header'] = $header;
			$result[$count]['content'] = $content;
			$result[$count]['type'] = $type;
			
			$count++;
		}
		
		$stmt->close();
		$db->Close();
		
		return $result;
	}
}