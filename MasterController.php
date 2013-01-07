<?php

// BING HEADER -- #\<div class="sb_tlst"\>\<h3\>(.*?)\<\/h3\>\<\/div\>#s
// BING TEXT -- #\<div class="sa_mc"\>\<p\>(.*?)\<\/p\>\<\/div\>#is
// BING OLD -- #\<li class="sa_wr"\>(.*?)\<\/li\>#s


// GOOGLE OLD -- #\<li class="g"\>(.*?)\<\/li\>#s
// GOOGLE HEADER -- #\<h3 class="r"\>(.*?)\<\/h3\>#s
// GOOGLE TEXT -- #\<span class="st"\>(.*?)\<\/span\>#s


// YAHOO OLD -- #\<div class="res"\>(.*?)\<\/div\>\<div class="abstr"\>(.*?)\<\/div\>#s
// YAHOO HEADER -- #\<div class="res"\>(.*?)\<\/div\>#s
// YAHOO TEXT -- #\<div class="abstr"\>(.*?)\<\/div\>#s

require_once('MasterView.php');
require_once("Model.php");

if( isset( $_POST['quest'] ) ) 
{
	$inputText = urlencode( $_POST['quest'] );
	$engine = $_POST['engine'];

	$masterView = new MasterView;
	$model = new Model;
	$scraper = new Scraper;
	
	switch( $engine )
	{
		case google:
			$scraped = $model->selectFromQuestion( $inputText, 'google' );
			echo $masterView->CreateView( $scraped );
			break;
		
		case bing:
			$scraped = $model->selectFromQuestion( $inputText, 'bing' );
			echo $masterView->CreateView( $scraped );
			break;
		
		case yahoo:
			$scraped = $model->selectFromQuestion( $inputText, 'yahoo' );		
			echo $masterView->CreateView( $scraped );
			break;
			
		default:
		
			if( $model->checkIfExist( $inputText, 'google' ) == true )
			{				
				$dates = $model->getDates( $inputText );
				
				if( $dates['oldTime'] < $dates['currentTime'])
				{
					echo "2. Datumet har gått och så uppdaterar med ny data i databasen <br/>";
					
					// Raderar om cachen har gått ut
					//$model->deleteByQuestion( $inputText );
					
					// lägger till den ny data
					//$model->insertNewDataArray( $matches, $engine, $question );
				}
				
				else
				{
					// Hämtar den cachade datan
					$matches = $model->findDublicates( $inputText );
					
					echo $masterView->CreateView( $matches );
				}
			}
			
			else
			{
				// Lägger till i DB om det inte redan existerar.
				$googleArray = $scraper->DoScrape( 
					'http://www.google.se/search?q=' . $inputText . '&oe=utf8', 
					'#\<h3 class="r"\>(.*?)\<\/h3\>|\<span class="st"\>(.*?)\<\/span\>#is' 
				);
				
				$model->insertNewDataArray( $googleArray, 'google', $inputText );
				
				$bingArray = $scraper->DoScrape( 
					'http://www.bing.com/search?q=' . $inputText . '',
					'#\<div class="sb_tlst"\>\<h3\>(.*?)\<\/h3\>\<\/div\>|\<p\>(.*?)\<\/p\>#is'
				);
				
				$model->insertNewDataArray( $bingArray, 'bing', $inputText );
				
				$yahooArray = $scraper->DoScrape( 
					'http://se.search.yahoo.com/search?p=' . $inputText . '',
					'#\<div class="res"\>(.*?)\<\/div\>|\<div class="abstr"\>(.*?)\<\/div\>#is'
				);
				
				$model->insertNewDataArray( $yahooArray, 'yahoo', $inputText );
				
				$matches = $model->findDublicates( $inputText );
	
				echo $masterView->CreateView( $matches );
			}
	}
}