$(function(){
	$('.project-tab-head a').bind('click', function(){
		if($(this).parent().hasClass('active')){
			return ;
		}
		var index = $(this).parent().index();
		
		$('.project-tab-head .active').removeClass('active');
		$('.project-tab-head li:eq(' + index + ')').addClass('active');
		
		$('.project-tab-content .tab-item.active').removeClass('active');
		$('.project-tab-content .tab-item:eq(' + index + ')').addClass('active');
	});
    $('.pList a').bind('click', function(){
        if($(this).parent().hasClass('active')){
            return ;
        }
        var index = $(this).parent().index();

        $('.project-tab-head .active').removeClass('active');
        $('.project-tab-head li:eq(' + index + ')').addClass('active');

        $('.project-tab-content .tab-item.active').removeClass('active');
        $('.project-tab-content .tab-item:eq(' + index + ')').addClass('active');
    });
	
});