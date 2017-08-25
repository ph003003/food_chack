<?php
	session_start();
	require_once("inc/root.php");
	$mod	= get('mod');
?>
  <!DOCTYPE html>
<html lang="en">
	<head>
<title>點餐系統</title>

<!DOCTYPE html>
<!-- 轉盤參考 url=(0059)http://www.5iweb.com.cn/resource/5iweb2017042404/index.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="css/Turntable.css" >
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.1.9/jquery.datetimepicker.min.css" />


</head>
  <?php
$sql = "SELECT * FROM food  ORDER BY rand() LIMIT 6 ";
conn();							
$res = $conn->query($sql);	
$list  = $res->fetchAll();
		foreach($list  as $row)	
		{				
			$f_name[]=$row['f_name'];
			$f_sn[]=$row['f_sn'];

		}	
?>
   <body>
	<input type="hidden" >
	<input type="hidden" id="f_sn0"  value=<?=$f_sn[0]?>>
	<input type="hidden" id="f_sn1"  value=<?=$f_sn[1]?>>
	<input type="hidden" id="f_sn2"  value=<?=$f_sn[2]?>>
	<input type="hidden" id="f_sn3"  value=<?=$f_sn[3]?>>
	<input type="hidden" id="f_sn4"  value=<?=$f_sn[4]?>>
	<input type="hidden" id="f_sn5"  value=<?=$f_sn[5]?>>


    <div class="wheel">	
			<ul id="wheel" class="wheel-list">
				<li style="transform: rotate(0deg);">
					<i style="transform: rotate(30deg) skewY(30deg);"></i>
					<div class="prize">
						<h3 id="prize0"><?=$f_name[0]?></h3>
						<p> </p>
					</div>
				</li>
				<li style="transform: rotate(60deg);">

                    <i style="transform: rotate(30deg) skewY(30deg);"></i>
                    <div class="prize">
						<h3 id="prize1"><?=$f_name[1]?></h3>
						<p>...</p>
					</div>
				</li>
				<li style="transform: rotate(120deg);">

                    <i style="transform: rotate(30deg) skewY(30deg);"></i>
                    <div class="prize">
						<h3 id="prize2"><?=$f_name[2]?></h3>
						<p></p>
					</div>
				</li>
				<li style="transform: rotate(180deg);">
					<i style="transform: rotate(30deg) skewY(30deg);"></i>
					<div class="prize">
						<h3 id="prize3"><?=$f_name[3]?></h3>
						<p></p>
					</div>
				</li>
				<li style="transform: rotate(240deg);">
                    <i style="transform: rotate(30deg) skewY(30deg);"></i>
					<div class="prize">
						<h3 id="prize4"><?=$f_name[4]?></h3>
						<p>  </p>
					</div>
				</li>
				<li style="transform: rotate(300deg);">
                    <i style="transform: rotate(30deg) skewY(30deg);"></i>
					<div class="prize">
						<h3 id="prize5"><?=$f_name[5]?></h3>
					
						
						<p> </p>
					</div>
				</li>
			</ul>
			<div id="pointer" class="wheel-pointer"><i></i></div>
			<div class="wheel-btn">
			
				
				
				<a id="button" href="Turntable.js::">
				
					<strong>午餐轉轉樂!</strong>
				</a>
			</div>
	</div>

	<div style="text-align:center;">
		<div style="margin:5 auto;">
			<button type="button" style="width:20rem ;height:4rem ;font-size:1rem ;"  onclick="history.go(0)">重抽餐廳</button>
			<button type="button" style="width:20rem ;height:4rem ;font-size:1rem ;"  onclick="history.go(-1)">返回</button>
		</div>
	</div>		


    	

</body></html>

<!-- Page JS  Scripts -->

<!-- Page JS (Customer) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.1.9/jquery.datetimepicker.min.js"></script>   
<script src="js/Turntable.js" charset="UTF-8"></script>

