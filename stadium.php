<?PHP
date_default_timezone_set('Asia/Tokyo');
//require_once "simple_html_dom.php";
include_once("snoopy.class.php");
include_once("htmlsql.class.php");

function stadium($sD=-1){

	// デバッグ用
	// d=0 : イベントなし　d=1 ：イベントあり
	if ($sD!=null && ($sD=="0" || $sD=="1")){
		return $sD=="0" ? json_encode(array()):json_encode(array(array("day" => "day","event"=>"event","open"=>"open","start"=>"start","end"=>"end","price"=>"price")));
	}
	
	//初期設定
	$sURL  = "http://www.nissan-stadium.jp/calendar/";
	$wsql = new htmlsql();
	
	if (!$wsql->connect('url', $sURL)){}
	
	// カレンダー部分を取得
	if ($a = !$wsql->query('SELECT * FROM table WHERE $class == "txtbox01"')){
		print "Query error: " . $wsql->error; 
		exit;
	}
	if (!$wsql->connect('string',$wsql->results[0]["text"])){
		print 'Error while connecting: ' . $wsql->error;
		exit;
	}
	
	// 行データを取得
	if (!$wsql->query('SELECT * FROM tr')){
		print "Query error: " . $wsql->error; 
		exit;
	}
	
	$sToday    = date("j"); 
	$wsql2     = new htmlsql();
	$aData     = array();
	
	// 行データを取得し、テーブルを作成する
	// 本日のイベント取得部分と重複しているので改善するべき
	foreach($wsql->fetch_array() as $row){

		// 列を取り出す
		if (!$wsql2->connect('string',$row["text"])){
			print 'Error while connecting: ' . $wsql2->error;
			exit;
		}
		
		if (!$wsql2->query('SELECT * FROM td')){
			print "Query error: " . $wsql->error; 
			exit;
		}
		// 列の取得
		// 0:"day"  2:"event" 3:"open" 4:"start" 5:"end" 6:"price"
		$sDay   = str_replace("\n","",$wsql2->results[0]["text"]);
		$sEvent = $wsql2->results[2]["text"];
		$sEvent = strip_tags($sEvent,"<a>");
		$sEvent = str_replace("<a href='../tour/index.php'></a>","",$sEvent);
		$sEvent = str_replace("<a href='../track/index.php'></a>","",$sEvent);
		$sEvent = str_replace("\n","",$sEvent);
		$sOpen  =  $wsql2->results[3]["text"];
		$sStart =  $wsql2->results[4]["text"];
		$sEnd   =  $wsql2->results[5]["text"];
		$sPrice =  $wsql2->results[6]["text"];
		
		$aData[] = array("day" => $sDay,"event"=>$sEvent,"open"=>$sOpen,"start"=>$sStart,"end"=>$sEnd,"price"=>$sPrice);
	}

	// 本日のイベントの有無を判別し、ある場合はイベント情報を返す
	$aEvent = array();

	for ($i=0;$i<count($aData);$i++){
		if ($aData[$i]["day"] != $sToday) continue;
		
		if ($aData[$i]["event"]!="") 
			array_push($aEvent,$aData[$i]);
		
		// 同一開催日に複数のイベントが発生している場合
		// 該当行が日付部分が空白になっているため必要な処理
		for ($j=$i+1;true;$j++){
			if ($aData[$j]["day"] == "" && $aData[$j]["event"]!=""){
				$aData[$j]["day"] = $sToday;
				array_push($aEvent,$aData[$j]);
			}
			else{
				break;
			}
		}
		break;
	}
	
	return json_encode($aEvent);

}

echo stadium(@$_GET["d"]);

?>
