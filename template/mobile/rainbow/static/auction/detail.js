$(function(){

	document.getElementById("myprice").onclick=function(){
		$.ajax({
			type: "POST",
			url: "{:U('Mobile/Auction/offerPrice')}",//+tab,
			data: cart2_form.serialize(),// 你的formid
			dataType: "json",
			success: function (data) {
				layer.closeAll();
				if (data.status != 1) {
					showErrorMsg(data.msg);  //执行有误
					// 登录超时
					if (data.status == -100){
						location.href = "{:U('Mobile/User/login')}";
					}
					ajax_return_status = 1; // 上一次ajax 已经返回, 可以进行下一次 ajax请求
					return false;
				}

				showErrorMsg('订单提交成功，跳转支付页面!');
				location.href = "/index.php?m=Mobile&c=Cart&a=cart4&order_sn=" + data.result;
			}
		});
		// $('#mask').show();
		
	};
	
	document.getElementById("tobuy").onclick=function(){
		
		$('#mask').hide();
		$('#mask2').show();
		$("#mask3").hide()
	
	};
	document.getElementById("tobuy1").onclick=function(){
		
		
		$("#mask3").hide()
	
	};

		
})

            
	   

     
     
     



