$(function(){
				
	/*
	 * 商品关注
	 */
	//分类筛选
	$(".discount-type li").click(function(){
		if($(this).hasClass('cur')){
			$(this).removeClass('cur');
		}else{
			$(this).addClass('cur');
		}
	})
	
	//点击分类，显示对应内容
	$(".choose-type").click(function(){
		if($(this).hasClass('cur')){
			$(".classification").show();
			$(".model-fliter").show();
		}else{
			$(this).removeClass('cur');
			$(".classification").hide();
			$(".model-fliter").hide();
		}
	})
	
	//点击蒙层，关闭
	$(".model-fliter").click(function(){
		$(this).hide();
		$(".classification").hide();
		$(".choose-type").removeClass('cur');
	})
	
	//商品编辑
	$("#edit-btn").toggle(function(){
		$("#edit-btn").text("完成");
		$(".follow-list-zp .fav-select").show();
		$(".fav-fixbar").show();
	},function(){
		$(".edit-btn").text("编辑");
		$(".follow-list-zp .fav-select").hide();
		$(".fav-fixbar").hide();
	})
	
	//商品全选与全不选
	$("#selectAllBtn").click(function(){
		$(".follow-list-zp .fav-select input").attr("checked",this.checked);
	});
	
	//为下面所有checkbox绑定点击事件
	$(".follow-list-zp .fav-select input").click(function(){
		//判断全选是否要被选中
		$("#selectAllBtn").attr("checked",$(".follow-list-zp .fav-select input").length == $(".follow-list-zp .fav-select input").filter(":checked").length);
	});
	
	//商品取消关注
	$("#multiCancle,#multiCancle-shop").click(function(){
		$(".model").css("z-index","102");
		$(".model").show();
		$(".dialog-unfollow").show();
	})
	
	//确定取消关注
	$(".confirm-btn").click(function(event){
		$(".model").hide();
		$(".dialog-unfollow").hide();
		
	})
	
	//弹窗再想想
	$(".cancel-btn").click(function(){
		$(".model").hide();
		$(".dialog-unfollow").hide();
		
	})


	/*
	 * 关注店铺
	 */
	$("#shoplist-edit").toggle(function(){
		$("#shoplist-edit").text("完成");
		$(".shop-list-zp .fav-select").show();
		$(".fav-fixbar").show();
	},function(){
		$("#shoplist-edit").text("编辑");
		$(".shop-list-zp .fav-select").hide();
		$(".fav-fixbar").hide();
	})
	
	//店铺全选与全不选
	$("#selectAllBtn-shop").click(function(){
		$(".shop-list-zp .fav-select input").attr("checked",this.checked);
	});
	
	//为下面所有checkbox绑定点击事件
	$(".shop-list-zp .fav-select input").click(function(){
		//判断全选是否要被选中
		$("#selectAllBtn-shop").attr("checked",$(".shop-list-zp .fav-select input").length == $(".shop-list-zp .fav-select input").filter(":checked").length);
	});

	
})