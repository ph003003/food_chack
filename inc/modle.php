<?php
//上傳圖片並自動產生縮圖(指定上傳路徑)-----------------------------------------------------------------
function file_upload($desired_dir,$image_name)
{
	//初始設定
	/*
	define('KB', 1024);
	define('MB', 1048576);
	define('GB', 1073741824);
	define('TB', 1099511627776);
	預設最小單位為MB(轉換1MB=1024KB)
	=>
	*/
	
	if(!defined('MB')) define('MB', 1024);
	if(!defined('GB')) define('GB', 1048576);
	if(!defined('TB')) define('TB', 1073741824);
	
	//預設錯誤訊息
	$phpFileUploadErrors = array(
	    0 => 'There is no error, the file uploaded with success',
	    1 => 'The uploaded file exceeds the upload_max_filesize directive in System',
	    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
	    3 => 'The uploaded file was only partially uploaded',
	    4 => 'No file was uploaded',
	    6 => 'Missing a temporary folder',
	    7 => 'Failed to write file to disk.',
	    8 => 'A PHP extension stopped the file upload.',
	);
	
	$allowMaxSize	= 2*MB;									//允許上傳大小(2MB)
	$limitedext		= array("jpg");		//允許副檔名
	$count_img		= count($_FILES['files']['name']);		//上傳圖片數量
	$desired_dir1	= $desired_dir."full/";					//原圖資料夾
	$desired_dir2	= $desired_dir."thumb/";				//縮圖資料夾
	
	if($_FILES['files']['name'][0]!='')
	{
		for($i=0; $i<$count_img; $i++)
		{
			$filename		= $_FILES['files']['name'][$i];										//取得原始檔名
			
			if ($_FILES["files"]["error"][$i] == UPLOAD_ERR_OK)
			{
				$file_tmp	= $_FILES['files']['tmp_name'][$i];									//取得暫存檔名
				$file_size	= number_format(($_FILES['files']['size'][$i]/1024), 1, '.', '');	//取得檔案大小(byte=>KB)
				$file_ext	= trim(substr($filename, -4), '.');									//取得副檔名 
																								//or => $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
				$err		= 0;																//錯誤數量					
				
				//檢查副檔名
				if (!in_array(strtolower($file_ext),$limitedext))
				{
		        	$errors[]=$filename.'(格式錯誤，格式限定副檔名 .jpg)';
		        	$err++;
				}
				
				//檢查檔案大小
				if($file_size > $allowMaxSize || $file_size == 0)
				{
					$errors[]=$filename.'['.$file_size/1024 .']超過2Mb限制!';
					$err++;
				}
				
				//如果初步檢查通過
				if(empty($err)==true)
				{					
					//檢查目標及縮圖資料夾=>若不存在就建立對應資料夾
				    if(is_dir($desired_dir)==false){mkdir("$desired_dir", 0700);}
				    if(is_dir($desired_dir1)==false){mkdir("$desired_dir1", 0700);}
				    if(is_dir($desired_dir2)==false){mkdir("$desired_dir2", 0700);}
				    
				    //一律變更檔名
					$target_file = $image_name.".".$file_ext;
					
					//建立上傳標的(路徑+檔名)
					$target_path = $desired_dir1.$target_file ;
					$target_path2 = $desired_dir2.$target_file ;
				    			
					//判斷副檔名,建立對應的圖像(圖像來源為原始圖片)=>(imagecreatefromjpeg, imagecreatefrompng, imagecreatefromgif)
					if(strtolower($file_ext) == 'jpg'){$file_ext='jpeg';}//(jpg use jpeg header & function)
					$function_name = 'imagecreatefrom'.$file_ext;
					$image = $function_name($file_tmp);
					
					// 取得來源圖片長寬
					$image_w = imagesx($image);
					$image_h = imagesy($image);

					// 假設要長寬不超過200(依循此原則等比例重設長寬)
					if($image_w > $image_h)
					{
						$thumb_w = 200;
						$thumb_h = intval($image_h / $image_w * 200);
					}
					else
					{
						$thumb_h = 200;
						$thumb_w = intval($image_w / $image_h * 200);
					}

					// 建立縮圖=>建立一個自訂寬度、高度的黑色畫布
					$thumb = imagecreatetruecolor($thumb_w, $thumb_h);

					//開始縮圖=>將圖像複製到另一個圖像(原始圖像 to 新的圖像)
					//(新目標圖像,原始圖像,目標X起點,目標Y起點,來源X起點,來源Y起點,目標圖像寬度,目標圖像高度,原始圖像寬度,原始圖像高度)
					imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_w, $thumb_h, $image_w, $image_h);
					
					// 儲存縮圖到指定 thumb 目錄　=>(imagejpeg,imagepng,imagegif)
					$function_name2 = 'image'.$file_ext;
					$function_name2($thumb, $target_path2);

					// 儲存原圖到指定 images 目錄
					move_uploaded_file($file_tmp, $target_path);
					
					//釋放資源
					imagedestroy($thumb);
					imagedestroy($image);
					
					// 將成功上傳的圖檔名寫入陣列
					$file_name[]=$target_file;
				}
			}
			else
			{
				$errors[] = 'Error:'.$filename.' ('.$phpFileUploadErrors[$_FILES['files']["error"][$i]]."!)";
			}
		}
		
		if(empty($errors)==true)
		{
			//全部上傳成功(沒有失敗)
			$result		= array("y",$file_name);
		}
		elseif(isset($file_name))
		{
			//部分上傳成功，部分失敗
			$error_msg	= implode(",",$errors);
			$result		= array("yn",$file_name,$error_msg);
		}
		else
		{
			//全部上傳失敗
			$error_msg	= implode(",",$errors);
			$result		= array("n",$error_msg);
		}
	}
	else
	{
		$result= array("n",$phpFileUploadErrors[4]);
	}
	
	return $result;
}

function food_order($search)
{	
	conn();
	global $conn;
	global $list_mem,$count_mem,$start,$limit;
	
	$sql = "SELECT * FROM food_order where `order_sn`>0 ";
	$mod			= ary($search,0);
	if($mod=="food_order")  		$sql .= " and order_status ='進行中'";
	else if($mod=="order_record")	$sql .= " and order_status !='進行中'";
		
	$sql .= "ORDER BY `order_time` DESC";
	$res = $conn->query($sql);	
	$list_mem = $res->fetchAll();
	$count_mem = count($list_mem);
	cls_conn();
}

function food_order_detailed($search)
{
		global $conn;
		global $list_mem,$count_mem,$start,$limit;
		
		$sql = "SELECT * FROM food_order_detailed where `order_sn`>0";
		

		$mod			= ary($search,0);
		$detaile_buyer	= ary($search,1);
		$order_sn		= ary($search,2);
		
		
		if($order_sn)
		{
			$sql .= " and order_sn ='".$order_sn."'";
		}	
		
		if($detaile_buyer)
		{
			$sql .= " and detaile_buyer ='".$detaile_buyer."'";
		}
		
		conn();							
		$res = $conn->query($sql);	
		$list_mem = $res->fetchAll();
		$count_mem = count($list_mem);
		cls_conn();
}



function f_member($sn,$search,$get_page)
{
	global $conn;
	if($sn)
	{
		global $f_name,$f_offday,$f_delivery,$f_meum,$f_phon,$f_kind;
		
		$sql = "SELECT f_name,f_offday,f_delivery,f_meum,f_phon,f_kind FROM food where f_sn='".$sn."' limit 1";
		conn();
		$res = $conn->query($sql);									
		if($res)
		{
			list($f_name,$f_offday,$f_delivery,$f_meum,$f_phon,$f_kind)=$res->fetch();
		}
		cls_conn();
	}
	else
	{
		global $list_mem,$count_mem,$start,$limit;
		
		$f_name	= ary($search,0);
		$f_phon	= ary($search,1);

		$sql = "SELECT * FROM food where f_sn>0";
		
		
		if($f_name)
		{
			$sql .= " and f_name like '%".$f_name."%'";
		}
		if($f_phon)
		{
			$sql .= " and f_phon like '%".$f_phon."%'";
		}
		else{
		$sql .= " order by f_sn desc";
		}
		
		conn();							
		$res = $conn->query($sql);	
		$list_mem = $res->fetchAll();
		$count_mem = count($list_mem);
		cls_conn();
	}
}
function f_meum_edit($sn)
{
	global $conn;

	if($sn)
	{
		global $meum_sn,$meals,$price,$restaurant;
		$sql = "SELECT meum_sn,meals,price,restaurant FROM food_meum where meum_sn='".$sn."' ";
		conn();
		$res = $conn->query($sql);			
		if($res)
		{
			list($meum_sn,$meals,$price,$restaurant)=$res->fetch();
		}	
		conn();							
		$res = $conn->query($sql);	
		$list_mem = $res->fetchAll();
		$count_mem = count($list_mem);
		cls_conn();
	}	
}
//餐點菜單資料表
function f_meum($sn,$search,$get_page)
{
	global $conn;
	
	if($sn)
	{
		global $meals,$price,$restaurant;
		$sql = "SELECT meum_sn,meals,price,restaurant FROM food_meum where f_sn='".$sn."' ";
		conn();
		$res = $conn->query($sql);			
		if($res)
		{
			list($meum_sn,$meals,$price,$restaurant)=$res->fetch();
		}		
	}	
	else{
	$sql = "SELECT * FROM food_meum where meum_sn>0";
	}
	
		global $list_mem,$count_mem,$start,$limit;
		$meals	= ary($search,0);
		$price	= ary($search,1);
	
		if($meals)
		{
			$sql .= " and meals like '%".$meals."%'";
		}
		if($price)
		{
			$sql .= " and price like '%".$price."%'";
		}
		
		
		else{
		$sql .= " order by meum_sn desc";
		}
		$get_page='n';		
		if($get_page!='n')
		{
			conn();
			$res = $conn->query($sql);	
			$list = $res->fetchAll();
			$count = count($list);
			cls_conn();
			
			$search=implode(",",$search);
			
			pagination($_SERVER['PHP_SELF'],$count,$get_page,$search);
		
			if($start || $limit)
			{
				 $sql .= " LIMIT ".$start.",".$limit;
			}
		}
		
		conn();							
		$res = $conn->query($sql);	
		$list_mem = $res->fetchAll();
		$count_mem = count($list_mem);
		cls_conn();
	
}

//分頁管理
function pagination($target,$count,$get_page,$search)
{
	global $pagination,$start,$limit;
		
	/* Setup vars for query */
	$adjacents=3;
	$page = 0;
	$status = 0;
	$targetpage = $target;					//your file name  (the name of this file)
	$limit = 10;								//how many items to show per page
	if((isset($get_page)) and ($get_page<>0) )
	{
		$page = $get_page;
		$start = ($page - 1) * $limit;		//first item to display on this page
	}
	else
	{
		$page = 0;
		$start = 0;							//if no page var is given, set start to 0
	}
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;				//if no page var is given, default to 1.
	$prev = $page - 1;						//previous page is page - 1
	$next = $page + 1;						//next page is page + 1
	$lastpage = ceil($count/$limit);  		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;					//last page minus 1

	/* 
	Now we apply our rules and draw the pagination object. 
	We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	
	$pagination = "";
	if($lastpage > 1)
	{ 
		$pagination .= "<ul class='pagination pagination-lg'>";
		$pagination .= "<li><a href='#'>共".$count."筆</a></li>";
		
		//previous button
		if ($page > 1) 
		{
			$pagination.= "<li><a href='$targetpage?page=$prev&search=$search'><i class='fa fa-angle-left'></i></a></li>";
		}		
		else
		{
			$pagination.= "<li><a href='#'><i class='fa fa-angle-left'></i></a></li>";
		}
		
		//pages 
		if ($lastpage < 7 + ($adjacents * 2))
		{ 
			//not enough pages to bother breaking it up
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
				{
					$pagination.= "<li class='active'><a href='#'>$counter</a></li>";
				}				
				else
				{
					$pagination.= "<li><a href='$targetpage?page=$counter&search=$search'>$counter</a></li>";
				}				      
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2)) 
		{
			//enough pages to hide some			
			if($page < 1 + ($adjacents * 2))    
			{
				//close to beginning; only hide later pages
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
					{
						$pagination.= "<li class='active'><a href='#'>$counter</a></li>";
					}					
					else
					{
						$pagination.= "<li><a href='$targetpage?page=$counter&search=$search'>$counter</a></li>";  
					}
				}
				$pagination.= "<li><a href=\"#\">...</a></li>";
				$pagination.= "<li><a href='$targetpage?page=$lpm1&search=$search'>$lpm1</a></li>"; 
				$pagination.= "<li><a href='$targetpage?page=$lastpage&search=$search'>$lastpage</a></li>";
			}			
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				//in middle; hide some front and some back
				$pagination.= "<li><a href=\"$targetpage?page=1\">1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=2\">2</a></li>";
				$pagination.= "<li><a href=\"#\">...</a>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
					{
						$pagination.= "<li><a class=\"active\" href=\"#\">$counter</a></li>";
					}					
					else
					{
						$pagination.= "<li><a href=\"$targetpage?page=$counter&search=$search\">$counter</a></li>";  
					}
				}
				$pagination.= "<li><a href=\"#\">...</a>";
				$pagination.= "<li><a href=\"$targetpage?page=$lpm1&search=$search\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=$lastpage&search=$search\">$lastpage</a></li>";   
			}			
			else
			{
				//close to end; only hide early pages
				$pagination.= "<li><a href=\"$targetpage?page=1&search=$search\">1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=2&search=$search\">2</a></li>";
				$pagination.= "<li><a href=\"#\">...</a></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					{
						$pagination.= "<li><a class=\"active\" href=\"#\">$counter</a></li>";
					}
					else
					{
						$pagination.= "<li><a href=\"$targetpage?page=$counter&search=$search\">$counter</a></li>";    
					}
				}
			}
		}

		//next button
		if ($page < $counter - 1) 
		{
			$pagination.= "<li><a href='$targetpage?page=$next&search=$search'><i class='fa fa-angle-right'></i></a></li>";
		}		
		else
		{
			$pagination.= "<li><a href='#'><i class='fa fa-angle-right'></i></a></li>";
		}		  
		$pagination.= "</ul>\n";
	}
	elseif($lastpage==1)
	{
		$pagination .= "<ul class='pagination pagination-lg'>";
		$pagination .= "<li><a href='#'>共".$count."筆</a></li>";
		$pagination .= "</ul>\n"; 
	}
}
?>