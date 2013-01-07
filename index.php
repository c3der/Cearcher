<?php
  session_start();
  require_once 'MasterController.php';
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Arbetsprov - Martin Cedeskog</title>
  <link rel="stylesheet" type="text/css" href="css/basic.css"/>
  <link rel="shortcut icon" href="pics/favicon.png" />
  <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body lang="sv">
	<div id="searchDiv">
		<h1 id="tec">Cearcher</h1>
				<input typ="text" name="inputText" id="inputText" />
				<input type="button" name="searchBtn" id="searchBtn" value="SÖK" />
			<div id="menu">
				<ul>
					<li><img src="pics/all_logo.png" id="allImg" /></li>
					<li><img src="pics/google_logo.png" id="googleImg" /></li>
					<li><img src="pics/bing_logo.png" id="bingImg" /></li>
					<li><img src="pics/yahoo_logo.png" id="yahooImg" /></li>
				</ul>
			</div>
		</div>
		<div id="searchResult">
			<!-- Here is where the results is supposed to be displayed -->
		</div>
	<script>
		$(document).ready(function(){  

			$("#searchBtn").click(function(){
		   
			    if( $("#inputText").val() == "" )
				{
					console.log("Du måste ange ett sökord.") 
				}
				
				else
				{
					sWord = $("#inputText").val();
					
					// IF no button has been clicked, Do regular serach
					$.fn.makeAjaxCall(sWord, "all");
					
					// Show ALL
					$("#allImg").click(function(){
						engineChoice = "all";
						$.fn.makeAjaxCall(sWord, "all");
					});
					
					// Show GOOGLE engine
					$("#googleImg").click(function(){
						engineChoice = "google";
						$.fn.makeAjaxCall(sWord, "google");
					});
					
					// Show YAHOO engine
					$("#yahooImg").click(function(){
						engineChoice = "yahoo";
						$.fn.makeAjaxCall(sWord, "yahoo");
					});
					
					// Show BING engine
					$("#bingImg").click(function(){
						engineChoice = "bing";
						$.fn.makeAjaxCall(sWord, "bing");
					});				
				}
			});
			
			// Function to make AJAX call
			$.fn.makeAjaxCall = function( sWord, engineChoice ) {
				$.ajax({
					    url: "MasterController.php",
					    type: "POST",
					    data: { quest: sWord, engine: engineChoice },
					    success: function(result){
					    	$("#searchResult").empty();
					    	$("#searchResult").append(result);
					    }
					});
			}
		});
	</script>
</body>
</html>