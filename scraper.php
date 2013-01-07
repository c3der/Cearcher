<?php

require_once("Model.php");

class Scraper {
		
	public function DoScrape( $url, $pattern )
	{
		$scrapedData = array();
		
		$ch = curl_init( $url );

		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$scrapedData = curl_exec($ch);

		curl_close($ch);

		$doc = new DOMDocument();
		@$doc->loadHTML( $scrapedData );

		preg_match_all( $pattern, $scrapedData, $matches );
		
		return $matches;
	}
}