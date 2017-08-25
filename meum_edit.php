<?php
	session_start();
	require_once 'inc/root.php';	//$salt
	$wk		= post("wk")?post("wk"):get("wk");
	switch ($wk)
	{
		/**********************************************  上傳圖片  **********************************************/
		case 'upload_img':
	    	$errors	= '';
	    	$pimg	= '';
	    	$f_sn= post("f_sn");
	    	if(isset($_FILES['files']))
	    	{
				$url	= "images/prod/";		//圖片上傳目標
				$data	= file_upload($url,$f_sn);	//開始上傳圖片//上傳圖片並自動產生縮圖(指定上傳路徑)存於modle.php
				if($data[0]=='y')
				{
					//上傳成功
					$pimg_array	= $data[1];
					$pimg		= implode(",",$pimg_array);
				}
				elseif($data[0]=='yn')
				{
					//部分成功,部分失敗
					$pimg_array	= $data[1];
					$errors		= $data[2];
					$pimg		= implode(",",$pimg_array);
				}
				else  //上傳失敗
				{
					$errors=$data[1];
				}
			}
			
			if($errors)
			{
				echo "<p>".$errors."</p><hr/>";
			}
			if($pimg && count($pimg_array)>1)
			{
				//多筆
				foreach($pimg_array as $row)
				{
					echo "<p><a href='images/prod/full/".$row."' targer='_blank'><img src='images/prod/thumb/".$row."' /></a></p>";
				}
			}	
			elseif($pimg)
			{
				//單筆		
				if($pimg)
				{
					echo "上傳成功";
					echo "<p><a href='images/prod/full/".$pimg."' targer='_blank'><img src='images/prod/thumb/".$pimg."' /></a></p>";
				}
			}
			break;
	/********************************************** 菜品新增  **********************************************/
		case "meum_add":	
			$f_sn	= post("f_sn"); 	 			
			$meals	= post("meals");				
			$price	= post("price");   
			conn();
				$sql = "SELECT `f_name` FROM food WHERE f_sn = '".$f_sn."'";
				$res = $conn->query($sql);
				$restaurant = $res->fetchColumn();			
			
			if($meals!='' &&$price!='')
			{
			$sql = "INSERT INTO food_meum (`f_sn`,`meals`,`price`,`restaurant`) ";	
			$sql.= "VALUES(:f_sn,:meals,:price,:restaurant)";
			$res = $conn->prepare($sql);
			$res->bindParam(':f_sn',$f_sn,PDO::PARAM_STR,50);						
			$res->bindParam(':meals',$meals,PDO::PARAM_STR,18);	
			$res->bindParam(':price',$price,PDO::PARAM_STR,15);						
			$res->bindParam(':restaurant',$restaurant,PDO::PARAM_STR,15);					
			$res->execute();
			if($res)
			{
				$status = array(
					'type'	=> 'success',
					'url'	=> 'meum_index.php?mod=meum&sn='.$f_sn,
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
			}
			else
			{
				$status = array(
					'type'=>'false',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'必填欄位不得空白!'
				);
			}
					
			echo json_encode($status);
			break;			
		/**********************************************  菜品修改  **********************************************/
		case "meum_edit":
		$meals	= post("meals");				
		$price	= post("price");  
		$meum_sn= post("meum_sn"); 	
		$sn= post("sn");  
			if($meals!=''&$price!='')
			{
				conn();
				$sql = "UPDATE food_meum SET `price`=:price, `meals`=:meals WHERE `meum_sn`='".$meum_sn."'";
				$res = $conn->prepare($sql);				
				$res->bindParam(':price',$price,PDO::PARAM_STR,18);	
				$res->bindParam(':meals',$meals,PDO::PARAM_STR,15);						
				$res->execute();
				if($res)
				{
					$status = array(
						'type'	=> 'success',
						'url'	=> 'meum_index.php?mod=meum&sn='.$sn,
						'msg'	=> '菜品修改已更新'
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
			}
			else
			{
				$status = array(
					'type'=>'false',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'必填欄位不得空白!'
				);
			}
			
			echo json_encode($status);
			
			break;
		
	/**********************************************  菜品刪除  **********************************************/
		
		case "meum_del":
		
			$meum_sn	= get("meum_sn"); 	 			//序號
			$sn= post("sn");
			conn();
			$sql = "DELETE FROM `food_meum` WHERE `meum_sn`='".$meum_sn."'";				
			$res = $conn->exec($sql);
			if(file_exists("uploads/".$meum_sn.".jpg")){
             
			 
            unlink("uploads/".$meum_sn.".jpg");//將檔案刪除
        }
			if($res)
			{
				$status = array(
				'type'	=> 'success',
						'url'	=> 'meum_index.php?mod=meum&sn='.$sn,
						'msg'	=> '菜品刪除已更新'
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
				/********************************************** 店家新增  **********************************************/
		case "f_add":	
			$f_name	= post("f_name"); 	 			
			$f_offday	= post("f_offday");				
			$f_delivery	= post("f_delivery");    	 		
			$f_phon	= post("f_phon");	
			$f_kind	= post("f_kind");
			
			if($f_name!='')
			{
			conn();
			$sql = "INSERT INTO food (`f_name`,`f_offday`,`f_delivery`,`f_phon`,`f_kind`) ";	
			$sql.= "VALUES(:f_name,:f_offday,:f_delivery,:f_phon,:f_kind)";
			$res = $conn->prepare($sql);
			$res->bindParam(':f_name',$f_name,PDO::PARAM_STR,50);						
			$res->bindParam(':f_delivery',$f_delivery,PDO::PARAM_STR,18);	
			$res->bindParam(':f_offday',$f_offday,PDO::PARAM_STR,15);						
			$res->bindParam(':f_delivery',$f_delivery,PDO::PARAM_STR,15);	
			$res->bindParam(':f_phon',$f_phon,PDO::PARAM_STR,15);	
			$res->bindParam(':f_kind',$f_kind,PDO::PARAM_STR,15);	
						
			$res->execute();
			if($res)
			{
				$status = array(
					'type'	=> 'success',
					'url'	=> 'meum_index.php?mod=f_re',
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
			}
			else
			{
				$status = array(
					'type'=>'false',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'必填欄位不得空白!'
				);
			}
					
			echo json_encode($status);
	    	
			break;
			
		
		/**********************************************  修改  **********************************************/
		
		case "f_edit":
			

			$f_sn	= post("f_sn"); 	 
			$f_name	= post("f_name"); 	 			
			$f_offday	= post("f_offday");				
			$f_delivery	= post("f_delivery");    	 		
			$f_phon	= post("f_phon");	
			$f_kind	= post("f_kind");

			
			if($f_name!='')
			{
				conn();
				$sql = "UPDATE food SET `f_name`=:f_name,`f_offday`=:f_offday,`f_delivery`=:f_delivery,`f_phon`=:f_phon,`f_kind`=:f_kind WHERE `f_sn`='".$f_sn."'";
				$res = $conn->prepare($sql);				

				$res->bindParam(':f_name',$f_name,PDO::PARAM_STR,15);						
				$res->bindParam(':f_offday',$f_offday,PDO::PARAM_STR,15);	
				$res->bindParam(':f_delivery',$f_delivery,PDO::PARAM_STR,8);	
				$res->bindParam(':f_phon',$f_phon,PDO::PARAM_STR,8);
				$res->bindParam(':f_kind',$f_kind,PDO::PARAM_STR,8);						
				
							
				$res->execute();
				if($res)
				{
					$status = array(
						'type'	=> 'success',
						'url'	=> 'meum_index.php?mod=f_re',
						'msg'	=> '店家資料已更新'
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
			}
			else
			{
				$status = array(
					'type'=>'false',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'必填欄位不得空白!'
				);
			}
			
			echo json_encode($status);
			
			break;
		
	
			/**********************************************  店家刪除  **********************************************/
		
		case "f_del":
		
			$f_sn	= get("sn"); 	 			//序號
			
			conn();
			$sql = "DELETE FROM `food` WHERE `f_sn`='".$f_sn."'";				
			$res = $conn->exec($sql);
			 if(file_exists("uploads/".$f_sn.".jpg")){
             
            unlink("uploads/".$f_sn.".jpg");//將檔案刪除
        }
			if($res)
			{
				$status = array(
				'type'	=> 'success',
						'url'	=> 'meum_index.php?mod=f_re',
						'msg'	=> '店家資料已更新'
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
				
		/**********************************************  表單測試  **********************************************/	
		case "test":	
				/*	
				資料處理結果	'type'	=>	成功(會跳轉頁面) 	=>	success 
											失敗(停留原始頁面)	=>	紅 false / 無 dismissable / 藍 info / 黃 warning 
				欄位是否清空	'cls'	=>	y/n
				原始按鈕樣式	'btn'	=>	success/primary...
				顯示訊息		'msg'	=>	'xxxxxxx'
				*/			
						
				$status = array(
					'type'=>'success',
					'cls'=>'n',
					'btn'=>'success',
					'msg'=>'test successed'
				);		
				
			print_r($status);
			echo json_encode($status);
	    	
			break;	
	}
?>
