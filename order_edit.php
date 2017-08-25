<?php
	session_start();
	require_once 'inc/root.php';	//$salt
	$wk		= post("wk")?post("wk"):get("wk");
	switch ($wk)
	{
	/********************************************** 結帳  **********************************************/
		case "checkout":	
		conn();
			$sql = "UPDATE `food_order` SET `order_status` = '結帳' WHERE `food_order`.`order_sn` ='".$_SESSION['order_sn']."';";
			$res = $conn->prepare($sql);	
			$res->execute();
			if($res)
			{
				$status = array(
					'type'	=> 'success',
					'url'	=> 'index.php?mod=checkout&order_sn="'.$_SESSION['order_sn'].'"',
					'msg'	=> "結帳已完成"
				);
			}
			echo json_encode($status);
		break;
	/********************************************** order_detailed_add 新增 點菜  **********************************************/
		case "order_detailed_add":	
			$meum_sn= post("meum_sn"); 
			$portion= (int)post("portion"); 
			$meals	= post("meals"); 
			$price	= post("price"); 
			if($portion=="")$portion=(int)1;
			conn();
			if($price && $meals)
			{
			}
			else
			{
			$sql = "SELECT `meals` FROM food_meum WHERE meum_sn = '".$meum_sn."' ";
			$res = $conn->query($sql);
			$meals = $res->fetchColumn();	
			$sql = "SELECT `price` FROM food_meum WHERE meum_sn = '".$meum_sn."' ";
			$res = $conn->query($sql);
			$price = $res->fetchColumn();
			$price = $price * $portion;
			}
			$sql = "INSERT INTO `food_order_detailed`
			( `order_sn`, `detaile_buyer`, `meals`, `portion` , `price`)
			VALUES ( '".$_SESSION['order_sn']."' , ' ' , '".$meals."' , '".$portion."' , '".$price."' ); ";	
			$res = $conn->prepare($sql);	
			$res->execute();
			if($res)
			{
				$status = array(
					'type'	=> 'success',
					'url'	=> 'index.php?mod=food_order_detailed&order_sn='.$_SESSION['order_sn'].' ',
					'msg'	=> '已點餐'
				);
			}
			else
			{
				$status = array(
					'type'=>'false',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'系統暫停服務!'
				);					
			}
			cls_conn();
			echo json_encode($status);
		break;
		/**********************************************order_add 新增  **********************************************/
		case "order_add":	
			$f_sn= post("f_sn"); 
			$order_time=date("Y-m-d H:s:i");		
			conn();
			$sql = "SELECT `f_name` FROM food WHERE f_sn = '".$f_sn."' ";
			$res = $conn->query($sql);
			$restaurant = $res->fetchColumn();
			$sql = "INSERT INTO `food_order`
			(`order_sn`, `f_sn`, `order_time`, `order_duty`, `restaurant`) 
			VALUES (NULL, '".$f_sn."' , '".$order_time."', ' ' , '".$restaurant."' );";	
			$res = $conn->prepare($sql);	
			$res->execute();
			if($res)
			{
				$status = array(
					'type'	=> 'success',
					'url'	=> 'index.php?mod=food_order',
					'msg'	=> '已新增'
				);
			}
			else
			{
				$status = array(
					'type'=>'false',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'系統暫停服務!'
				);					
			}
			cls_conn();
			echo json_encode($status);
		break;
/********************************************** 刪除  **********************************************/
		case "order_del":
			$order_sn= get("order_sn");
			conn();
			$sql = "DELETE FROM `food_order` WHERE `order_sn`='".$order_sn."'";				
			$res = $conn->exec($sql);
			if($res)
			{
				$status = array(
				'type'	=> 'success',
						'url'	=> 'index.php',
						'msg'	=> '刪除已更新'
				);								
			}
			else
			{
				$status = array(
					'type'=>'false',
					'msg'=>'oops!系統暫停服務'
				);
			}
			cls_conn();	
			echo json_encode($status);
		break;
/********************************************** 刪除  **********************************************/
		case "order_detailed_del":	
			$detailed_sn= get("detailed_sn");	//序號
			conn();
			$sql = "DELETE FROM `food_order_detailed` WHERE `detailed_sn`='".$detailed_sn."'";				
			$res = $conn->exec($sql);
			if($res)
			{
				$status = array(
				'type'	=> 'success',

						'msg'	=> '刪除已更新'
				);								
			}
			else
			{
				$status = array(
					'type'=>'false',
					'msg'=>'oops!系統暫停服務'
				);
			}
			cls_conn();	
			echo json_encode($status);
		break;			
	}
?>
