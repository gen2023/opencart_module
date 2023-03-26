// Load more
var arrow_enabled = 0; 
var container = ''; 
var page = 1;
var wh = 0;
var load = false;
var ct = 0;
var pages = [];
var filter_ajax = false;
var tmp_data_container = '';
var container_first_div = '';

function getContainer() {
    container = '.testimonials-page .items';
	return container;
}

function checkData() {
    if (container_first_div == $(container).find('div').eq(0).html()) {
        setTimeout('checkData()',100);    
		} else {
		$(container).prepend(tmp_data_container);
		$('#ajaxblock').remove();
		$(container).css('height','auto');
		load = false; 
	}
}

function getPageNext() {
	
    container = getContainer();
    
    if (load) return;
	
    if (!filter_ajax) {
        if (page>pages.length) {$('.button-more').hide(); return;}
        page++;
		} else {
        if ($('.pagination b').next().length==0) {$('.button-more').hide(); return;}
	}
    load = true;
	
    if (filter_ajax) {
        h = parseFloat($(container).css('height'));
        $(container).css('height',h+'px');
        tmp_data_container = $(container).html();
        container_first_div = $(container).find('div').eq(0).html();
	} 
    w = parseFloat($(container).css('width'));
    $(container).append('<div id="ajaxblock" style="width:'+w+'px;height:30px;margin-top:20px;text-align:center;border:none !important;"><img src="/catalog/view/theme/default/image/loading.gif" /></div>');
    
	
	if (!filter_ajax) {
		$.ajax({
			url:pages[page-2], 
			type:"GET", 
			data:'',
			success:function (data) {
				
				$data =$(data);
				$('#ajaxblock').remove();
				if ($data) {         
					$(container).append($data.find(container).html());
					
					// Current page
					var curr = parseInt($('#page_current').val());
					$('#page_current').val(curr+1);
				}
				
				load = false;
				
				// Hide load more
				var page_current = $('#page_current').val();
				var page_total = $('#page_total').val();
				if (page_current == page_total){
					$('.button-more').hide();
				}
				
			}});
			} else {
			$('.pagination b').next().click();
			setTimeout('checkData()',100);
			
	}
}

$(document).ready(function() {
	
	// Popup
	$(document).ready(function() {
		$('.popup-testimonials').colorbox({
			inline:true,
			width: 640,
			height: 720
		});
	});
	
	// Load more
	function load_more() {
		
		container = getContainer();
		
		if ($(container).length>0) {
			ct = parseFloat($(container).offset().top);
			filter_ajax = (typeof doFiltergs == 'function') || (typeof doFilter == 'function');
			
			
			$('.pagination a').each(function(){
				href = $(this).attr('href');
				if (jQuery.inArray(href,pages)==-1) {
					pages.push(href);
				}
			});
			$('.pagination').hide();
		}
		
		page_current = $('#page_current').val();
		page_total = $('#page_total').val();
		if (page_current == page_total){
			$('.button-more').hide();
		}
	}
	
	if ($('#loadmore_button').val() == '1') {
		load_more();
		}else{
		$('.button-more').hide();
	}
	
	// Testimonials add
	$('body').on('submit', '.popup-addtestimonials form', function(event) {
		var form = $(this);
		
		var captcha = '';
		if ($("#captcha").length > 0) {
			captcha = '&captcha=' + encodeURIComponent((form).find('input[name=\'captcha\']').val());
		}
		
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			dataType: 'json',
			data: 'name=' + encodeURIComponent(form.find('input[name="name"]').val()) + '&description=' + encodeURIComponent(form.find('.input-description').val()) + '&phone=' + encodeURIComponent(form.find('input[name="phone"]').val()) + '&rating=' + encodeURIComponent(form.find('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&email=' + encodeURIComponent(form.find('input[name="email"]').val()) + '&title=' + encodeURIComponent(form.find('input[name="title"]').val()) + '&city=' + encodeURIComponent(form.find('input[name="city"]').val()) + captcha,
			beforeSend: function() {
				form.parent().find('.complete').html('<div align="center"><img src="/catalog/view/theme/default/image/loading.gif" alt="" /></div>');
				form.find('.input').removeClass('border-red');
			},
			success: function(data) {
				if (data['error']) {
					var error = data['error'];
					var val = '';
					$(error).each(function(){
						val += '<li>'+this+'</li>';
					});		
					form.parent().find('.complete').html('<ul class="warning">' + val + '</ul>');
					
					var input = data['input'];
					$(input).each(function(){
						form.find('.input.form-'+this).addClass('border-red');	
					});
				}
				
				if (data['success']) {
					form.parent().find('.complete').html('<ul class="success">' + data['success'] + '</ul>');
					form.parent().find('.buttons').fadeOut();
					form.parent().find('.input').val('');
					form.parent().find('input[name=\'rating\']:checked').attr('checked', '');
					form.parent().find('.input').removeClass('border-red');
				}
			}
		});
		
		return false;
	});
	
	
	// Reply open
	$('body').on('click', '.testimonials-page .reply', function(event) {
		event.preventDefault();
		var $this = $(this).parent().parent();
		$(this).css('visibility', 'hidden');
		$this.find('.reply-form').fadeIn();
		$('html,body').animate({scrollTop: $this.find('.reply-form').offset().top - 30});	
	});
	
	// Reply add
	$('body').on('submit', '.reply-form form', function(event) {
		var form = $(this);
		
		var captcha = '';
		if ($("#captcha-reply").length > 0) {
			captcha = '&captcha=' + encodeURIComponent((form).find('input[name=\'captcha\']').val());
		}
		
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			dataType: 'json',
			data: 'reply_name=' + encodeURIComponent(form.find('.reply_name').val()) + '&reply_description=' + encodeURIComponent(form.find('.reply_description').val()) + '&reply_id=' + encodeURIComponent(form.find('.reply_id').val()) + captcha,
			beforeSend: function() {
				form.find('.complete').html('<div align="center"><img src="/catalog/view/theme/default/image/loading.gif" alt="" /></div>');
			},
			success: function(data) {
				if (data['error']) {
					var error = data['error'];
					var val = '';
					$(error).each(function(){
						val += '<li>'+this+'</li>';
					});		
					form.find('.complete').html('<ul class="warning">' + val + '</ul>');
					
					var input = data['input'];
					$(input).each(function(){
						form.find('.input.'+this).addClass('border-red');
					});
				}
				if (data['success']) {
					console.log(data['success']);
					
					form.find('.complete').html('<div class="success">' + data['success'] + '</div>');
					form.find('.buttons').fadeOut();
					form.find('.input').val('');
					form.find('.input').removeClass('border-red');
					
					if (data['approved_off'] == 'true'){
						var content = '<div class="reply-wrap-item"><div class="name"><i>'+data['date']+'</i><span>'+data['name']+'</span></div><div class="desc">'+data['description']+'</div></div>';
						
						var wrap = form.parents('.item').find('.reply-wrap h2');
						
						$(wrap).parent().removeClass('disable');
						$(content).addClass('refresh').insertAfter(wrap).show('slow');
						
						// Scroll
						$('html,body').animate({scrollTop: $(wrap).parent().offset().top - 30});	
						
						setTimeout(function(){
							$('.reply-wrap-item').removeClass('refresh');
						}, 3000);	
					}
					
				}
			}
		});
		
		return false;
	});
	
	
	// Vote buttons
	$('body').on('click', '.testimonials-page .voted', function(event) {
		event.preventDefault();
		var wrap = $(this).parents('.like');
		var form = $(this).parents('.item');
		
		if ($(wrap).hasClass('disabled')){
			return false;
		}
		
		$.ajax({
			url: 'index.php?route=product/ajaxtestimonial/vote',
			type: 'POST',
			dataType: 'json',
			data: 'id=' + encodeURIComponent($(this).data('id')) + '&vote=' + encodeURIComponent($(this).data('vote')),
			beforeSend: function() {
				form.find('.like').find('.like-wrap').css('visibility', 'hidden');
				$('<div class="loading" align="center"><img src="/catalog/view/theme/default/image/loading.gif" alt="" /></div>').appendTo(form.find('.like'));
			},
			success: function(data) {
				if (data['vote']) {
					if (data['vote'] == 'likes') {
						var likes = parseInt(wrap.find('.score_likes').text(),10) + 1;	
						wrap.find('.score_likes').text(likes);	
						
						}else{
						var unlikes = parseInt(wrap.find('.score_unlikes').text(),10) + 1;
						wrap.find('.score_unlikes').text(unlikes);	
						
					}
					wrap.addClass('disabled');	
					form.find('.like .loading').remove();
					form.find('.like').find('.like-wrap').css('visibility', 'visible');
					
				}
			}
		});
		
		return false;
	});
	
});					