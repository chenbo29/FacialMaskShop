/*创建头部*/
function lbHeadFun(){
	/*返回上一页*/
	if($('.returnBut_lb').attr('data-num') == 1 || $('.headWrap_lb').attr('data-num') == undefined ){
		window.history.back();
		console.log("返回上一页");
	}else {
		/*页面跳转*/
//		pageJump();
//		window.location.href = $('.headWrap_lb').attr('data-num');
	}
	return false;
}
/*Tab切换效果*/
function tanPageJump(_url,_num){
	/*页面跳转*/
	console.log(_url,_num);
	$('.headWrap_lb .headTab_lb .headTabTerm_lb').removeClass('activeTab_lb');
	/*当前*/
	$('.headWrap_lb .headTab_lb .headTabTerm_lb').eq(_num).addClass('activeTab_lb');
}
/*页面跳转*/
function pageJump(_url){
	/*页面跳转*/
	console.log(_url);
}

$(document).ready(function(){
	
})