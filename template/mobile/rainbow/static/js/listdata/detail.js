/*Initialize Swiper*/
var swiper = new Swiper('.swiper-container_lb', {
	/*方向*/
	direction: 'horizontal',
	/*轮播项-循环*/
	loop: true,
	//设置自动循环播放
	autoplay: true,
	/*循环，速度*/
	speed: 300,
	/*分页器*/
	pagination: {
//		el: '.swiper-pagination',
		el: 'swiper-pagination_lb',
	},
});