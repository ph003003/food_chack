
 
window.onload = function (){
    /*
    ** 抽奖概率为 总和 * 10 ; 总概率 360°
    ** 旋转最少  turn +  度数的圈数 ;
    **
    */
	var f_sn =  [$("#f_sn0").val(),$("#f_sn5").html() , $("#f_sn4").val() , $("#f_sn3").val() , $("#f_sn2").val() , $("#f_sn1").val()] ; //奖品提示
    var PrizeSon =  [$("#prize0").html(),$("#prize5").html() , $("#prize4").html() , $("#prize3").html() , $("#prize2").html() , $("#prize1").html()] ; //奖品提示
    var totalNum = 6 ; // 转盘 总数
    var trunNum = [ 1 , 2 , 3 , 4 , 5 , 6 ]; //概率奖品 编号
    var turntable = [] ; // 随机概率计算
    var isStatr = false ; //锁 专拍没有执行完的时候 不可以再次点击 ;
    var lenCloc = 0 ; //当前第几次计算叠加的度数
    var turn  = 3 ; //转盘旋转最低的圈数

    var brn = document.getElementById("button");
    var wheel = document.getElementById("wheel");

    /* 循环概率 */
    for (var i = 0; i < trunNum.length; i++) {
        for (var j = 0; j < trunNum[i]; j++) {
            turntable.push(i+1);
        }
    }

    /* 点击 开始  */
    brn.onclick = function(){
       
		if(!isStatr){
            isStatr = true;
            var random = Math.floor(Math.random()*turntable.length);
            //console.log(Math.floor(Math.random()*turntable.length)%6);
            operation(random);
        }else{
            return false;
        }
    }

    /*    开始 function  ran = 随机    */
     function operation( ran ) {
        lenCloc++;
        var Prize = turntable[ran]-1 , sun = turn*360 ;  //编号  // 度数  //  时间
        if(Prize>=totalNum){
            Prize = 0;
        }
        var soBuom =parseInt(Math.floor(Math.random()*60) - 30);



        /*    旋转度数 = 上次度数+ 最小圈数 * 360 + 当前数字 * 60 +随机角度  = 最终旋转度数     */
        wheel.style.transform = "rotate("+((lenCloc*sun+Prize*60)+soBuom)+"deg)";
        //wheel.style.webkitTransform = "rotate("+((lenCloc*sun+Prize*60)+soBuom)+"deg)";

        setTimeout(function () {

		alert("你選中了:「" + PrizeSon[Prize] + "」");
            isStatr = false;
		if(confirm("你要選這家餐廳嗎?")){
				
				$.ajax({
					type: "post",
					url: "order_edit.php?wk=order_add",
					dataType: "json",
					data: {
						f_sn: f_sn[Prize]
					},
					success: function(data) //success 表示成功後 data回送數據	
					{
						if(data.url)
							{

								var bk=data.url;
								if(bk.match('.php?')==null)
								{
									bk+=".php";
								}
								if(data.msg)
								{
									alert(data.msg);
								}
								location.href=bk;
							}
						
						},
						
					error:function(xhr, ajaxOptions, thrownError)
					{ 
						   alert(xhr.status); 
						   alert(thrownError); 
					}	 
				});
				
		}
		else
		{
		alert("回去重選");
		history.go(0);
		}
	
			

        }, 3500);
    }
}

