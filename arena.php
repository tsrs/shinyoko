<?php
date_default_timezone_set('Asia/Tokyo');

function arena($sD){
	if ($sD!=null && ($sD=="0" || $sD=="1")){
		return $sD=="0" ? json_encode(array()):json_encode(array("title"=>"タイトル","date"=>"日付","time1"=>"時間1","time2"=>"時間2"));
	}
	
	$sURL  = "http://www.yokohama-arena.co.jp/event/";
	$sHTML = file_get_contents($sURL);

	$dom = new DOMDocument();
	@$dom->loadHTML($sHTML);
	$oXML = simplexml_import_dom($dom);

	$oTable  = $oXML->xpath('//table[@class="event-table"]');

	foreach($oTable as $o){
		foreach ($o->tr[2]->td[0]->table[0] as $o2){
			$sDate  = strval($o2->td[0]);
			if ($sDate === date("m/d")){
				return (json_encode(array("title"=>strval($o->tr[0]->td[0]),
							  "date" =>$sDate,
							  "time1"=>strval($o2->td[3]),
							  "time2"=>strval($o2->td[4]))
							  )
						);
			}
		}
	}
	return json_encode(array());
}

echo arena(@$_GET["d"]);