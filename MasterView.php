<?php

require_once 'scraper.php';

class MasterView
{	
	public function CreateView( $scraped )
	{
		$html = "";
		
		for($i = 0; $i < count( $scraped ); $i++)
		{
			$html .= "<div class='searchResult'><img class='searchLogo' src='pics/". $scraped[$i]['type'] ."_logo.png'/>";
		
			$html .= "<h1>" . $scraped[$i]['header'] . "</h1>";
		
			$html .= "<p>" . $scraped[$i]['content'] . "</p>";
		
			$html .= "</div>";
		}

		return $html;
	}
}