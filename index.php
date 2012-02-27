<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新横浜駅イベント情報</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	var eventnum = 0;
	// アリーナのデータ取得
	$.getJSON("arena.php", null,　function(json){
		if (json.length==0){
			$("#arena-detail").remove();
			$("#arena-existence").text("ありません").css("color","blue");
			return
		}

		var nowdate = new Date();
		day = String(nowdate.getMonth()+1).length == 1 ? "0" + String(nowdate.getMonth()+1) : String(nowdate.getMonth()+1);
		var today = nowdate.getFullYear() +"/"+ day +"/"+ nowdate.getDate()+".php";
		$("#arena-existence").text("あります").css("color","red");
		$("#arena-detail-title").text(json.title);
		$("#arena-detail-time1").text(json.time1);
		$("#arena-detail-time2").text(json.time2);
		$("#arena-detail-url").attr("href","http://www.yokohama-arena.co.jp/event/"+today);
		$("#arena-detail").css("display","block");
		eventnum++;

	});

	// 日産スタジアムのデータ取得
	$.getJSON("stadium.php", null,　function(json){
		if (json.length==0){
			$("#stadium-detail").remove();
			$("#stadium-existence").text("ありません").css("color","blue");
			return
		}
		
		var nowdate = new Date();
		$("#stadium-existence").text("あります").css("color","red");
		if (!json.length) alert();
		
		for (var i = 0; i < json.length; i++){
			//"event"=>"event","open"=>"open","start"=>"start","end"=>"end","price"=>"price"
			$("#stadium-detail").append("<br/>イベント名："+json[i].event);
			$("#stadium-detail").append("<br/>開場："+json[i].open);
			$("#stadium-detail").append("<br/>開始："+json[i].start);
			$("#stadium-detail").append("<br/>終了："+json[i].end);
		}
		$("#stadium-detail").css("display","block");
	});
	
	$(function(){
		var nowdate = new Date();
		$("#today").text( (nowdate.getMonth()+1) +"/"+ nowdate.getDate());
	});
});
</script>
</head>
<body>

<h1>新横浜駅イベント情報</h1>
今日<b id="today"></b>の新横浜近辺のイベント情報です。

<h2>横浜アリーナ</h2>
<p>
今日の横浜アリーナのイベントは<b id="arena-existence"></b>。<br/>
</p>
<p>
<div id="arena-detail" style="display:none">
<div>イベントタイトル：<a id="arena-detail-url" href="#" target="_blank"><span id="arena-detail-title"></span></a>（<span id="arena-detail-time1"></span>　<span id="arena-detail-time2"></span>）</div>
</div>
</p>
<p>
<a href="http://www.yokohama-arena.co.jp/event/" target="_blank">今月の横浜アリーナ公式サイトイベント情報</a>
</p>

<h2>日産スタジアム</h2>
<p>
今日の日産スタジアムのイベントは<b id="stadium-existence"></b>。<br/>
</p>
<p>
<div id="stadium-detail" style="display:none"></div>
</p>
<p>
<a href="http://www.nissan-stadium.jp/calendar/" target="_blank">今月の日産スタジアム公式サイトイベント情報</a>
</p>

</body>
</html>