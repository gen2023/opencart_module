<style>
	#openModal<?php echo $module_id ?> .genpopup-content{
		border-radius:<?php echo $radius; ?>px;
		width:<?php echo $width; ?>px;
		height:<?php echo $height; ?>px;
		<?php echo $background; ?>
		
		}
</style>
<div id="openModal<?php echo $module_id; ?>" class="genpopup">
  <div class="genpopup-dialog">
    <div class="genpopup-content">
        <button class="genpopup-close">×</button>
        <?php echo $content; ?>
    </div>
  </div>
</div>

<script>
	if (<?php echo $modalMobile; ?>==1){
		openModal();
	} else {
		if ($(window).width() >768 ){
			openModal();
		}//else{console.log('mobile not');}
	}
	function openModal(){
		let count=<?php echo $viewed; ?>;
		const modal<?php echo $module_id; ?>=setInterval(()=>{
			$('#openModal<?php echo $module_id; ?>').css("display","block");
			if (<?php echo $closeModal; ?>==0){
				$('#openModal<?php echo $module_id; ?> .genpopup-close').css('display','none');
				setTimeout(()=>{$('#openModal<?php echo $module_id; ?>').css('display','none');},<?php echo $closeModalSecond; ?>);
				addViewModule(<?php echo $module_id; ?>);
				if(<?php echo $cookie;?>){
					setCookie(<?php echo $module_id; ?>,count);
				}
			}
			clearInterval(modal<?php echo $module_id; ?>);
		},<?php echo $viewSecond; ?>)
	}
	function setCookie(id,count){
		let value=[id,count];
		var date = new Date();
		date.setTime(date.getTime()+<?php echo $validity; ?>*24*60*60*1000);
		console.log(date);
		var expires = "; expires="+date.toGMTString();
		document.cookie = "genpopup_<?php echo $module_id; ?>="+value+expires;
	}
	
	function addViewModule(module_id){
		
		$.ajax({
			url: 'index.php?route=extension/module/genpopup/addView',
			type: 'post',
			data: {
				module_id: module_id
			},
			dataType: 'json',									
			success: function(json) { 
				console.log(json.message);
			},
			error: function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			}			
		});
	}

	$('#openModal<?php echo $module_id; ?> .genpopup-close').click(function(){
		$('#openModal<?php echo $module_id; ?>').css("display","none");
		
		addViewModule(<?php echo $module_id; ?>);
		if(<?php echo $cookie; ?>){
			setCookie(<?php echo $module_id; ?>,count);
		}
	});
</script>