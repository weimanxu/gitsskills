$(function(){
	$('.wallet-tab-head a').bind('click', function(){
		if($(this).parent().hasClass('active')){
			return ;
		}
		
		var index = $(this).parent().index();
		$('.wallet-tab-content table').hide();
		$('.wallet-tab-content table').eq(index).show();
		
		$('.wallet-tab-head .active').removeClass('active');
		$('.wallet-tab-head li:eq(' + index + ')').addClass('active');
		
		$('.wallet-tab-content .tab-item.active').removeClass('active');
		$('.wallet-tab-content .tab-item:eq(' + index + ')').addClass('active');
	});
	
	
});