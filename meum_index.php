<?php
session_start();
require_once("inc/root.php");
$mod	= get('mod');
include_once 'alert.php' 
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
		<?php
 if($mod=='f_re')
	        	{
				$sn	= get('sn');
					
						//-------------------------------------------------------------------------------------------------------------	//
					//	店家列表(預設)																								//
					//-------------------------------------------------------------------------------------------------------------	//
					
					$get_page	= get('page');
					
					$f_name		= post('f_name');								
					$f_phon		= post('f_phon');								
					$status		= post('status') ? post('status') : 'y';	//狀態
					$status		= get('status') ? get('status'):$status;
					$search		= get('search');
					
					if($search)
					{
						$array	= explode(",",$search);
						$f_name	= $array[0];
						$f_phon	= $array[1];
						$status	= $array[2];
					}
					$search=array($f_name,$f_phon,$status);
					
					f_member($sn,$search,$get_page);	//=>$list_mem,$count_mem
		?>
					<!-- Search -->
					<div class="row" style="margin-top: 15px;">						
						<form action="meum_index.php?mod=f_re" method="POST">
	                        <div class="col-md-4">
	                            <div class="form-group">
	                            	<input type="text" class="form-control" name="f_name" placeholder="店家名稱" value="<?=$f_name;?>"/>
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="form-group">
	                            	<input type="text" class="form-control" name="f_phon" placeholder="店家電話" value="<?=$f_phon;?>"/>
	                            </div>
	                        </div>
							<div class="col-md-4">
	                           <div class="widget search">
	                           		<div class="input-group">
	                                    <span class="input-group-btn">
	                                        <button class="btn btn-default" type="submit">
	                                       		<i class="fa fa-search"></i>搜尋
	                                        </button>
	                                    </span>
									</div>
								</div>
							</div>
					</form>
				</div>
	                <!-- Default -->
					<div class="row">
						<div style="float: right;padding: 15px;">
		        			<button class="btn btn-primary tf-btn" onclick="javascript:location.href='meum_index.php?mod=f_add'">+ 新增店家</button>
		        		</div>		
	                    <div class="col-md-12">
	                        <table>
	                    		<thead>
	                    			<tr>
	                    				<th>序號</th>
	                    				<th>店家名稱</th>
	                    				<th>公休</th>
	                    				<th>外送方式</th>
										<th>菜單編輯</th>
										<th>菜單圖片</br>(點擊可看大圖)</th>
										<th>電話</th>
	                    				<th>種類</th>
										
	                    				<th align="center">管理</th>
	                    			</tr>
	                    		</thead>
	                    		<tbody>
		<?php
	                    		foreach($list_mem as $row)
	                    		{
	                    			
		?>
	                    			<tr>
	                    				<td><?=$row['f_sn'];?></td>
	                    				<td><?=$row['f_name'];?></td>
	                    				<td><?=$row['f_offday'];?><br/></td>
	                    				<td><?=$row['f_delivery'];?><br/></td>
										<td><?php echo "<a href='meum_index.php?mod=meum&sn=".$row['f_sn']."'>編輯餐廳菜單</a>";?></td>	
									 <td>
		<?php
									echo "<p><a href='images/prod/full/".$row['f_sn'].".jpg' targer='_blank'><img src='images/prod/thumb/".$row['f_sn'].".jpg' /></a></p>";
									echo '<form method="post" action="meum_edit.php" enctype="multipart/form-data">
										<input type="file" name="files[]" multiple />
										<input type="hidden" name="wk" value="upload_img" />
										<input type="hidden" name="f_sn" value='.$row['f_sn'].' />
										<input type="submit" value="upload"/>
										</form>'
		?>
										</td>	
										<td><?=$row['f_phon'];?><br/></td>
										<td><?=$row['f_kind'];?><br/></td>
	                    				<td align="center">
	    <?php
										echo "<a href='meum_index.php?mod=f_edit&sn=".$row['f_sn']."'>修改</a>|";
										echo "<span class='go' link='meum_edit.php?wk=f_del&sn=".$row['f_sn']."' ct='del' bk='meum_index.php?mod=f_re''>刪除</span>";											
											
	    ?>
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
				}
	else if($mod=='f_add')
	        	{ 
	        		//-------------------------------------------------------------------------------------------------------------	//
					//	店家新增																										//
					//-------------------------------------------------------------------------------------------------------------	//
		?>
					<form id="myform" class="form" name="f_add" action="meum_edit.php" ct="add" method="post">
							<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_name' placeholder="店名*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
							<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_offday' placeholder="公休*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
							<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_delivery' placeholder="外送方式*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
								<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_kind' placeholder="種類*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
							
							<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_phon' placeholder="電話*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
					   <div class="col-md-6">     
							<button type="button" class="btn btn-danger" onclick="history.go(-1);">返回</button>
							<button type="submit" id="submit" class="btn btn-primary">確定新增</button>
						</div>
	                </form>
		<?php
				}	
				elseif($mod=='f_edit')
	        	{
	        //-------------------------------------------------------------------------------------------------------------	//
			//	店家修改																									//
			//-------------------------------------------------------------------------------------------------------------	//
					$sn=get('sn');
					f_member($sn,'','');	//$mid,$mno,$mnm,$mail,$tel,$fax,$vat,$addr,$mail,$reg,$avbl;
		?>
					<form id="myform" class="form" name="f_edit" action="meum_edit.php" ct="edit" method="post">
	                 
	                      
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_name' value="<?=$f_name;?>" placeholder="店名*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
								
	                      <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_offday' value="<?=$f_offday;?>" placeholder="公休*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
							
						 <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_delivery' value="<?=$f_delivery;?>" placeholder="外送方式*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>				
								
						 <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_phon' value="<?=$f_phon;?>" placeholder="電話*" required>
	                                <p class="help-block text-danger"></p>
	                            </div> 
								
								<div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='f_kind' value="<?=$f_kind;?>" placeholder="種類*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	
	                    <input type="hidden" name="f_sn" value="<?=$sn;?>" />
	                    <button type="button" class="btn btn-danger" onclick="history.go(-1);">返回</button>
	                    <button type="submit" id="submit" class="btn btn-primary">確定更新</button>
	                </form>
		<?php
				}
		elseif($mod=='meum')
	        	{
	        //-------------------------------------------------------------------------------------------------------------	//
			//	菜單編輯列表																								//
			//-------------------------------------------------------------------------------------------------------------	//
					$sn=get('sn');	
					$get_page	= get('page');

					$price		= post('price');								
					$meals		= post('meals');								
					$status		= post('status') ? post('status') : 'y';	//狀態
					$status		= get('status') ? get('status'):$status;
					$search		= get('search');
					if($search)
					{
						$array	= explode(",",$search);
						$meals	= $array[0];
						$price	= $array[1];
						$status	= $array[2];
					}
					$search=array($price,$meals,$status);
					f_meum($sn,$search,$get_page);	//=>$list_mem,$count_mem
		?>
					<!-- Search -->
					<div class="row" style="margin-top: 15px;">						
						<form action="meum_index.php?mod=meum" method="POST">
	                        <div class="col-md-4">
	                      <div class="form-group">
	                            	<input type="text" class="form-control" name="meals" placeholder="菜品名稱" value="<?=$meals;?>"/>
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="form-group">
	                            	<input type="text" class="form-control" name="price" placeholder="價錢" value="<?=$price;?>"/>
	                            </div>
	                        </div>
	                           <div class="widget search">
	                           		<div class="input-group">
										<div class="form-group">
		                                  
		                                </div>
	                                    <span class="input-group-btn">
	                                        <button class="btn btn-default" type="submit">
	                                       		<i class="fa fa-search"></i>
	                                        </button>
	                                    </span>
	                                </div>
	                			</div>
	                        </div>
	                    </form>
	                </div>
	                <!-- Default -->
					<div class="row">
						<div style="float: right;padding: 15px;">
		        			<button class="btn btn-primary tf-btn" onclick="javascript:location.href='meum_index.php?mod=meum_add&sn=<?=$sn?>'">+ 新增菜單餐點</button>
		        		</div>
	                    <div class="col-md-12">
	                        <table>
	                    		<thead>
	                    			<tr>
	                    				<th>菜品名稱</th>
	                    				<th>價錢</th>
										<th>所屬餐廳</th>
	                    				<th align="center">管理</th>
	                    			</tr>
	                    		</thead>
	                    		<tbody>
		<?php
	                    		foreach($list_mem as $row)
	                    		{	
		?>
	                    			<tr>
	                    				<td><?=$row['meals'];?></td>
	                    				<td><?=$row['price'];?></td>
	                    				<td><?=$row['restaurant'];?><br/></td>    
	                    				<td align="center">
	                    					<?php
											echo "<a href='meum_index.php?mod=meum_edit&meum_sn=".$row['meum_sn']."&sn=".$sn."'>修改菜品資訊</a>|";
											echo "<span class='go' link='meum_edit.php?wk=meum_del&meum_sn=".$row['meum_sn']."&sn=".$sn."' ct='del' bk='meum_index.php?mod=meum&sn=".$sn."''>刪除菜品資訊</span>";											
											
	                    					?>
	                    				</td>
	                    			</tr>
		<?php
	                    		}
	    ?>
	                    		</tbody>
	                    	</table>
	                    	<!--<?=$pagination;?> !-->
							<button type="button" class="btn btn-block btn-lg btn-success" onclick="javascript:location.href='index.php'">回到首頁</button>
	                    </div>
	                </div>

		<?php
	
				}
		elseif($mod=='meum_add')
	        	{
				$sn=get('sn');
			
					//-------------------------------------------------------------------------------------------------------------	//
					//	菜單餐點新增																										//
					//-------------------------------------------------------------------------------------------------------------	//
		?>
					<h3>菜單餐點新增</h3>	
					<form id="myform" class="form" name="meum_add" action="meum_edit.php" ct="add" method="post">		
				          <input type="hidden" name="f_sn" value="<?=$sn;?>" />
							<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='meals' placeholder="菜品名稱*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
							<div class="col-md-12">
	                            <div class="form-group">
	                                <input type="number" autocomplete="off" class="form-control" name='price' placeholder="價錢*" required>
	                                <p class="help-block text-danger"></p>
	                            </div>
	                        </div>
						<div class="col-md-12">   
							<button type="button" class="btn btn-danger" onclick="history.go(-1);">返回</button>
							<button type="submit" id="submit" class="btn btn-primary">確定新增</button> 
						</div>
	                </form>
					<?php
				}
				elseif($mod=='meum_edit')
	        	{
	        //-------------------------------------------------------------------------------------------------------------	//
			//	菜單修改																									//
			//-------------------------------------------------------------------------------------------------------------	//
					$sn=get('$sn');
					$meum_sn=get('meum_sn');
					
					f_meum_edit($meum_sn);
		?>
					<form id="myform" class="form" name="meum_edit" action="meum_edit.php" ct="edit" method="post">
	                            <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='meals' value="<?=$meals;?>" placeholder="菜品名稱*" required />
	                                <p class="help-block text-danger"></p>
	                            </div>
								        <div class="form-group">
	                                <input type="text" autocomplete="off" class="form-control" name='price' value="<?=$price;?>" placeholder="價錢*" required />
	                                <p class="help-block text-danger"></p>
	                            </div>			
	                    <input type="hidden" name="meum_sn" value="<?=$meum_sn;?>" /> 
						<input type="hidden" name="sn" value="<?=$sn;?>" />
	                    <button type="button" class="btn btn-danger" onclick="history.go(-1);">返回</button>
	                    <button type="submit" id="submit" class="btn btn-primary">確定更新</button>
	                </form>
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