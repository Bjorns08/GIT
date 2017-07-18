
<?php   
    $url = filter_input(INPUT_POST, 'url');
    parsing_main ($url);
    
 Function parsing_main($url)
{
    $Liste_url = array($url);     
        
    if (strpos($url,'drone-fpv-racer') > 0)
    {
        $Prix = parsing_DFR($Liste_url);
    }
    elseif (strpos($url,'fpv4drone') > 0)
    {   
       $Prix = parsing_FPV4($Liste_url);
    }
    elseif (strpos($url,'made4race') > 0)
    {
      $Prix = parsing_MD4R($Liste_url);  
    }
    elseif (strpos($url,'breizh-racer') > 0)
    {   
      $Prix = parsing_Breizh_Racer($Liste_url);
    }

    //echo $Prix[0]."<br />".$Prix[1];
	return $Prix;
}

    

Function parsing_DFR($Liste_url)
{
    //echo 'Drone FPV Racer'."<br />";
    $queryXPath1 = '//span[@class="list_price"]'; //DFR
    $queryXPath2 = '//span[@id="availability_value"]'; //DFR
    $Prix = array();
    $Prix = Prix_Dispo ($Liste_url, $queryXPath1, $queryXPath2);
    //Rempalcer le . par , 
    $Prix[0] = str_replace('.', ',', $Prix[0]);
    //OutOf
	//echo $Prix[1];
    if (strpos ($Prix[1], "indisponible")> 0)
    {
        $Prix[1] = '0';
    }
    else
    {
         $Prix[1] = '1';
    }
    
    // echo $Prix[0]."<br />".$Prix[1]; 
    Return $Prix;
}
Function parsing_FPV4($Liste_url)
{
    //echo 'FPV4DRONE'."<br />";
    $queryXPath1 = '//span[@id="our_price_display"]';//FPV4 & MADE4
    $queryXPath2 = '//p[@id="availability_statut"]';//FPV4 & MADE4
    $Prix = array();
    $Prix = Prix_Dispo ($Liste_url, $queryXPath1, $queryXPath2);
    // Suppression du ? en fin de chaine 
    $Prix[0] = str_replace('€', '', $Prix[0]);
    //si 0 pas en stock si 1 en stock 
    if (strpos ($Prix[1], "plus en stock")> 0)
    {
        $Prix[1] = '0';
    }
    else
    {
         $Prix[1] = '1';
    }
     // echo $Prix[0]."<br />".$Prix[1];
    Return $Prix;
}

Function parsing_MD4R($Liste_url)
{
    echo 'MADE4RACE'."<br />";
    $queryXPath1 = '//span[@id="our_price_display"]';//FPV4 & MADE4
    $queryXPath2 = '//p[@id="availability_statut"]';//FPV4 & MADE4
    $Prix = array();
    $Prix = Prix_Dispo ($Liste_url, $queryXPath1, $queryXPath2);
    
      // Suppression du ? en fin de chaine 
    $Prix[0] = str_replace('€', '', $Prix[0]);
     if (strpos ($Prix[1], "plus en stock")> 0)
    {
        $Prix[1] = '0';
    }
    else
    {
         $Prix[1] = '1';
    }
    
     // echo $Prix[0]."<br />".$Prix[1];
    Return $Prix;
}
Function parsing_Breizh_Racer($Liste_url)
{
    echo 'Breizh Racer'."<br />";
    $queryXPath1 = '//span[@class="PBSalesPrice"]';//breiz
    $queryXPath2 = '//span[starts-with(@class, "PBShortTxt PBM")]';//breiz
    $Prix = array();
    $Prix = Prix_Dispo ($Liste_url, $queryXPath1, $queryXPath2);
   // Suppression du ? en fin de chaine 
    $Prix[0] = str_replace('€', '', $Prix[0]);
    //Rupture
    if (strpos ($Prix[1], "Rupture")> 0)
    {
        $Prix[1] = '0';
    }
    else
    {
         $Prix[1] = '1';
    }
    // echo $Prix[0]."<br />".$Prix[1];
    Return $Prix;
}
// Function de récupération 
Function Prix_Dispo ($Liste_url, $queryXPath1, $queryXPath2)
{
    foreach ($Liste_url as $url) 
    {
            $dom = new DOMDocument();
            @$dom->loadHTMLFile($url);
            $x = new DOMXPath($dom);

            //prix
            $nodeList = $x->query($queryXPath1);
            $PRIX=array();
            foreach ($nodeList as $node)
            {
               // array_push($PRIX, utf8_decode(urldecode($node->nodeValue.'</br></br>')));
                array_push($PRIX, $node->nodeValue);
                break;
            }

            //dispo
            $nodeList = $x->query($queryXPath2);
            foreach ($nodeList as $node)
            {
              // array_push($PRIX, utf8_decode(urldecode($node->nodeValue.'</br></br>')));
               array_push($PRIX, $node->nodeValue);
               break;
            }
    }		
    return $PRIX;
}


?>