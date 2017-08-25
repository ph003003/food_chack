<?php
session_start();
require_once("inc/root.php");
$mod	= get('mod');
?>
  <!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>點餐系統</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<script src="https://use.fontawesome.com/09faeb38dd.js"></script>
		<link rel="stylesheet" href="css/style.css" >
		<head/>
<style type="text/css">
	body{
		font-family:'微軟正黑體';
	}
	button{
		margin: 0.5rem 0rem;
	}
	div{
		text-align:center;line-height:100px,
	}
	input{
		text-align:center;line-height:100px,
	}
	input{
		text-align:center;line-height:100px,
	}
</style>
<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="jumbotron">
				<h2>
					歡迎使用! 吃喝轉轉樂
				</h2>
			</div>
		</div>
	</div>
</div>
		<?php
switch ($mod)
{		
	case "food_order":
	case "order_record":
					//-------------------------------------------------------------------------------------------------------------	//
					//	訂單列表(預設)																								//
					//-------------------------------------------------------------------------------------------------------------	//
					if($mod=='food_order')		echo '<div class="row clearfix"><h2>進行中的訂單</h2>';		
					if($mod=='order_record')	echo '<div class="row clearfix"><h2>歷史訂單</h2>';			
					$search=array($mod);
					food_order($search);	
		?>
	                <!-- Default -->
					<div class="row">
	                    <div class="col-md-12">
	                        <table>
	                    		<thead>
	                    			<tr>
	                    				<th>訂單序號</th>
	                    				<th>所選店家</th>
	                    				<th>開單時間</th>
										<th>訂單是否進行中</th>
	                    				<th align="center">管理</th>
	                    			</tr>
	                    		</thead>
	                    		<tbody>
	    <?php
	                    		foreach($list_mem as $row)
	                    		{
	    ?>
	                    			<tr>
	                    				<td><?=$row['order_sn'];?></td>
										<td><?=$row['restaurant'];?></td>
	                    				<td><?=$row['order_time'];?><br/></td>
										<td><?=$row['order_status'];?><br/></td>
							           	<td align="center">
	    <?php
										if($mod=='food_order')
											{
												echo "<a class='btn btn-primary'   href='index.php?mod=food_order_detailed&order_sn=".$row['order_sn']." '>點餐</a>|";								
												echo "<button  class='go' link='order_edit.php?wk=checkout&order_sn=".$row['order_sn']."' ct='up' bk='index.php?mod=checkout&order_sn=".$row['order_sn']."''>結帳</button>";
											}
										if($mod=='order_record')
											{
		?>
												<a href='index.php?mod=checkout&order_sn=<?=$row['order_sn']?>'>詳細  </a>
		<?php
											}	
					
		?>			
									<span class='go' link='order_edit.php?wk=order_del&order_sn=<?=$row['order_sn']?> ct='del' bk='index.php?mod=food_order'  '>刪除</span>
										</td>
									</tr>
		<?php
								}
		?>
	                    		</tbody>
	                    	</table>
							<button type="button" class="btn btn-block btn-lg btn-success" onclick="javascript:location.href='index.php'">回到首頁</button>
	                    </div>
	                </div>
		<?php
		break;	
	//-------------------------------------------------------------------------------------------------------------	//
		case "food_order_detailed":	//此為點選菜品
		case "checkout":			
		$detaile_buyer			= get('detaile_buyer');	
		$order_sn				= get('order_sn');	
		$_SESSION['order_sn']	= $order_sn	;	
		conn();	
		$sql = "SELECT`f_sn` FROM `food_order` WHERE order_sn ='".$order_sn."'";
		$res = $conn->query($sql);
		$f_sn= $res->fetchColumn();
		conn();
		$sql = "SELECT`f_sn` FROM `food_order` WHERE order_sn ='".$order_sn."'";
		$res = $conn->query($sql);
		$f_sn= $res->fetchColumn();
		$sql = "SELECT `f_name`,`f_offday`,`f_delivery`,`f_phon`,`f_kind`FROM `food` WHERE f_sn ='".$f_sn."'";
		$res = $conn->query($sql);
		list($f_name,$f_offday,$f_delivery,$f_phon,$f_kind)=$res->fetch();
		cls_conn();
		?>
		<div class="row">
			<div class="col-md-12">
			<h3>餐廳資訊</h3>
				<table>
					<thead>
						<tr>
							<th>店家名稱</th>
							<th>公休</th>
							<th>外送方式</th>
							<th>菜單圖片</br>(點擊可看大圖)</th>
							<th>電話</th>
							<th>種類</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?=$f_name ?></td>
							<td><?=$f_offday ?><br/></td>
							<td><?=$f_delivery ?><br/></td>
							 <td>
		<?php
							 echo "<p><a href='images/prod/full/".$f_sn.".jpg' targer='_blank'><img src='images/prod/thumb/".$f_sn.".jpg' /></a></p>";
		?>
							</td>	
							<td><?=$f_phon;?><br/></td>
							<td><?=$f_kind;?><br/></td>
						</tr>
					</tbody>
				</table>	
		<?php
		if($mod=='food_order_detailed')
		{
		?>
			
			<form id="myform" class="form" name="order_detailed_add" action="order_edit.php" ct="order_add" method="post"> 
				<div class="container">
					<div class="row clearfix"> 
							<h2>餐點選擇</h2>
							<div class="col-md-4 column">
							<select class="form-control" name="meum_sn"  id='meum_sn' >
		<?php
								$sql = "SELECT * FROM food_meum where f_sn='".$f_sn."'  ";				
								$res = $conn->query($sql);	
								$list_mem = $res->fetchAll();
								cls_conn();
								foreach($list_mem as $row)	
								{				
		?>
								<option value=<?=$row['meum_sn'];?> > <?=$row['meals'];?> 價格: <?=$row['price'];?> </option>
		<?php
								}
		?>　
							</select>	
						</div>
						<div class="col-md-4 column">
							<input type="number" class="form-control" id='portion' name="portion" placeholder="餐點數量" />
						</div>
						<div class="col-md-4 column">
							<button type="submit" id="submit" class="btn btn-block btn-primary">點餐</button>
						</div>
					</div>
				</div>
				<div class="container">
				<h3>自填點餐 </h3>
					<div class="row clearfix"> 
						<div class="col-md-4 column">
							<input type="text" class="form-control" id='meals' name="meals" placeholder="自填菜色" />	
						</div>
						<div class="col-md-4 column">
							<input type="number" class="form-control" id='price' name="price" placeholder="該菜價錢" />
						</div>
						<div class="col-md-4 column">
							<button type="submit" id="submit" class="btn btn-block btn-primary">自填點餐</button>
						</div>
					</div>
				</div>
			 </form>
	<?php
				$search=array($mod,$detaile_buyer,$order_sn);	
				food_order_detailed($search);	
			}	
/*********************************** ******************************/	/*********************************** ******************************/								
		else if($mod=='checkout')
		{	/**/
		?>

	　	     <table>
				<h2>結帳款項</h2>
					<tr>
		<?php
					$price=0;
					$order_sn				= get('order_sn');
					$_SESSION['order_sn']	= $order_sn	;	
					$sql = "SELECT * FROM food_order_detailed where	order_sn='".$order_sn."' ";
					conn();							
					$res = $conn->query($sql);	
					$list  = $res->fetchAll();
					foreach($list  as $row)	
					{				
						$downlist[]=$row['detaile_buyer'];
					}
					$downlist=array_unique($downlist);
					echo "<th>姓名</th><br>" ;
					foreach($downlist as $row)	
					{				
						echo "<th>".$row."</th><br>" ;
					}
		?>
					</tr>
				<tbody>
					<tr>
		<?php
				echo "<td>應收金額</td>";
					foreach($downlist as $row)	
					{				
						$sql = "SELECT * FROM food_order_detailed where  detaile_buyer='".$row."' and order_sn='".$order_sn."' ";
						conn();							
						$res = $conn->query($sql);	
						$list = $res->fetchAll();	
						foreach($list  as $row)	
							{			
								$price=$price+$row['price'];
							}
				echo "<td>".$price ."</td>";
				}	
		?>		
				</tr>
			</tbody>
		</table>
		<?php
/***********************************以上是結帳計算 ******************************/	/***********************************以上是結帳計算 ******************************/
		$order_sn				= get('order_sn');	
		$_SESSION['order_sn']	= $order_sn	;	
		$search=array($mod,"",$order_sn);	
		food_order_detailed($search);			
		}		
		?>
	
		<!-- Default -->
				<h3>點餐資訊</h3>
					<table>
						<thead>
							<tr>
								<th>所屬訂單</th>
								<th>點餐人姓名</th>
								<th>菜名</th>
								<th>數量</th>
								<th>價格</th>
								<th align="center">管理</th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($list_mem as $row)
						{	
		?>
							<tr>
								<td><?=$row['order_sn'];?></td>
								<td><?=$row['detaile_buyer'];?><br/></td>
								<td><?=$row['meals'];?><br/></td>
								<td><?=$row['portion'];?><br/></td>						
								<td><?=$row['price'];?><br/></td>						
								<td align="center">
		<?php
								if($mod=='food_order_detailed')
								{
									echo "<span class='go' link='order_edit.php?wk=order_detailed_del&detailed_sn=".$row['detailed_sn']."' ct='del'
									bk='index.php?mod=food_order_detailed&order_sn=".$_SESSION['order_sn']." '>刪除</span>";	
								}
								else
								{
									echo "<span class='go' link='order_edit.php?wk=order_detailed_del&detailed_sn=".$row['detailed_sn']."' ct='del'
									bk='index.php?mod=checkout&order_sn=".$_SESSION['order_sn']."'>刪除</span>";	
								}
		?>
								</td>
								
							</tr>
		<?php
						}
		?>
						</tbody>
					</table>
					<div class="col-md-6">
						<button type="button" class="btn btn-block btn-lg btn-success" onclick="history.go(-1);">返回上頁</button>
					</div>
					<div class="col-md-6">
						<button type="button" class="btn btn-block btn-lg btn-success" onclick="javascript:location.href='index.php'">回到首頁</button>
					</div>
				</div>
			</div>
		<?php
		break;	
	//-------------------------------------------------------------------------------------------------------------	//
		case "order_add":
		?>
	        <form id="myform" class="form" name="order_add" action="order_edit.php" ct="order_add" method="post">   
					<div class="container">
						<div class="row clearfix"> 		
							<h2>餐廳選擇</h2>
							<div class="col-md-6 column">
								<select class="form-control" name="f_sn"  id='f_sn' >
		<?php
									$sql = "SELECT * FROM food ";
									conn();							
									$res = $conn->query($sql);	
									$list_mem = $res->fetchAll();
									cls_conn();
									foreach($list_mem as $row)	
									{				
		?>
									<option value=<?=$row['f_sn'];?> > <?=$row['f_name'];?> </option>
		<?php
									}
		?>　
								</select>	
							</div>
							<div class="col-md-6 column">
							<button type="submit" id="submit" class="btn btn-primary">確定新增</button>
							</div>
						</div>
					</div>
				 </form>
		<?php
		break;	
	//-------------------------------------------------------------------------------------------------------------	//
		case "order_detailed_add":			
		?>	
				<form id="myform" class="form" name="order_detailed_add" action="order_edit.php" ct="order_add" method="post">   
						<div class="form-group">
						<select name="meum_sn"  id='meum_sn' >
		<?php
							$sql = "SELECT * FROM food_meum   ";
							conn();							
							$res = $conn->query($sql);	
							$list_mem = $res->fetchAll();
							cls_conn();
							foreach($list_mem as $row)	
							{				
		?>
							<option value=<?=$row['meum_sn'];?> > <?=$row['meals'];?> </option>
		<?php
							}
		?>　
						</select>		
						</div>
					<button type="submit" id="submit" class="btn btn-primary">確定新增</button>
				</form>
		<?php		
		break;	
	//-------------------------------------------------------------------------------------------------------------	//
		default:
		?>
			<!--//-------------------------------------------------------------------------------------------------------------	//
			//	(預設)		登入與註冊 選擇頁面																						//
			//-------------------------------------------------------------------------------------------------------------	// -->
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-md-12 column">
					<div class="row-fluid">
						<div class="col-md-4 column">
							<div class="hero-unit well">
								<h1>
									步驟一:選餐廳!
								</h1>
								<h3>
									請先選定餐廳，開啟新訂單。
								</h3>
								<p>
									<button type="button" class="btn btn-block btn-lg btn-primary" onclick="javascript:location.href='index.php?mod=order_add'">開新訂單(自己選)</button>
									<button type="button" class="btn btn-block btn-lg btn-primary" onclick="javascript:location.href='turntable.php'">開新訂單(轉轉樂)</button>
								</p>
							</div>
						</div>
						<div class="col-md-4 column">
							<div class="hero-unit well">
								<h1>
									步驟二:選餐點!
								</h1>
								<h3>
									請選擇訂單與餐點。
								</h3>
								<p>
									<button type="button" class="btn btn-block btn-lg btn-primary" onclick="javascript:location.href='index.php?mod=food_order'">最新開的訂單</button>
									<button type="button" class="btn btn-block btn-lg btn-primary" onclick="javascript:location.href='index.php?mod=food_order'">進行中的訂單</button>
								</p>
							</div>
						</div>
						<div class="col-md-4 column">
							<div class="hero-unit well">
								<h1>
									資料管理
								</h1>
								<h3>
									查看修改訂單、餐廳<span>、</span><span>菜品</span><span>。</span>
								</h3>
								<p>
									<button type="button" class="btn btn-warning btn-lg btn-block active" onclick="javascript:location.href='index.php?mod=order_record'">訂單紀錄</button>
									<button type="button" class="btn btn-warning btn-lg btn-block active" onclick="javascript:location.href='meum_index.php?mod=f_re'">編輯菜單</button>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
}
		?>
<!-- Page JS  Scripts -->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.scrollex.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/util.js"></script>
		<script src="js/main.js"></script>
	<!-- Page JS (Customer) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="js/check.js"></script>
		<script src="js/form.js"></script>
	</body>