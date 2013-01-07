// Tar reda på om data på sökordet redan existerar i DB
		if( $model->checkIfExist( $question, $engine ) == true )
		{
			$dates = $model->getDates( $question );
			
			if( $dates['oldTime'] < $dates['currentTime'])
			{
				// Raderar om cachen har gått ut
				$model->deleteByQuestion( $question );
				
				// lägger till den ny data
				$model->insertNewDataArray( $matches, $engine, $question );
				
				return $matches;
			}
			
			else
			{
				// Hämtar den cachade datan
				$matches = $model->findDublicates( $question );
				// $matches = $model->selectFromQuestion( $question, $engine );
				
				return $matches;
			}
		}
		
		else
		{
			// LÃ¤gger till i DB om det inte redan existerar dÃ¤r
			$model->insertNewDataArray( $matches, $engine, $question );
			
			// $matches = $model->selectFromQuestion( $question, $engine );
			$matches = $model->findDublicates( $question);
			
			return $matches;
		}
