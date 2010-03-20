<?php

class GridNavigator
{
	var $nav = array();
	
	function GridNavigator($query, $resultsPerPage = 10, $updateResults = true)
	{ 
		$input = array_merge($_GET,$_POST);	
		$this->nav = '';
		$result = mysql_query($query);
		(isset($_GET["start"])) ? $start = $_GET["start"] : $start = 0;
		if(isset($_GET["end"])) $resultsPerPage = $_GET["end"];
		$this->nav = array();
		$this->nav["navbar"] = '';
		$this->nav["updater"] = '';
		$this->nav["query"] = $query;//." LIMIT ".$start.",".$resultsPerPage;
		
		$qString = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&";
		$i = strrpos($qString,"/"); 
		if($i == null) ;
		else $pageName = substr($qString,$i+1);
		//if($qString == '' || substr($qString,0,5)=="start" || substr($qString,0,5=="order")) $pageName.="?";	else $pageName.="&";
		$pageName."<br/>";
		$pageName = str_replace("&start=".$input["start"],null,$pageName);
		$pageName = str_replace("&end=".$input["end"],null,$pageName);
		$pageName = str_replace("?start=".$input["start"],"?",$pageName);
		$pageName = str_replace("DESC",null,$pageName);
		
		$numResults = mysql_num_rows($result);
		
		if($numResults > 1)
		{
			
			$counter = 0;
			if($start-$resultsPerPage >= 0)
				$this->nav["navbar"].= " <a href='".$pageName."start=".
											($start-$resultsPerPage).
											"&end=".$resultsPerPage."'>Previous</a> ";

			while($counter * $resultsPerPage < $numResults)
			{
				$this->nav["navbar"].= " <a href='".$pageName."start=".
											$counter*$resultsPerPage.
											"&end=".$resultsPerPage.
											"'>".($counter+1)."</a> |";
				
				$counter++;
			}
			
			if($start+$resultsPerPage < $numResults)
				$this->nav["navbar"].= " <a href='".$pageName."start=".
											($start+$resultsPerPage).
											"&end=".$resultsPerPage."'>Next</a> ";
											
			if($updateResults)
				$this->nav["updater"].= "<form name='update' method=GET action=''>
													<input type='hidden' name='start' value='$start'/>
													<select name='end'>
														<option value='1'".(($_GET["end"]==1) ? "selected" : "").">1</option>
														<option value='5'".(($_GET["end"]==5) ? "selected" : "").">5</option>
														<option value='10'".(($_GET["end"]==10) ? "selected" : "").">10</option>
														<option value='25'".(($_GET["end"]==25) ? "selected" : "").">25</option>
													</select>
													<input class='button' type='submit' value='Update'/>
												</form>";
			
		}
		if($numResults <= $resultsPerPage)	$this->nav["navbar"] = null;
	}
	
	function getNav()
	{
		return $this->nav;
	}
}
				