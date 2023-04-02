<div id="voting<?php echo $module_id?>" class="voting" style="<?php echo $displayNone;?>">
<?php if ($heading_title) {?>
  <div class="block_title">
	<?php echo $heading_title; ?>
  </div>	
<?php } ?>
<div class="contentVoting">
<form action="" method="post">
<?php foreach ($voting_attributes as $key=> $voting_attribute) { ?>
  <div>
	<?php if($type_module==0){?>
    <input type="radio" name="voting<?php echo $module_id?>" value="<?php echo $key; ?>">
	<?}else{?>
	<input type="checkbox" name="voting<?php echo $module_id?>" value="<?php echo $key; ?>">
	<?php } ?>
    <label for="voting<?php echo $module_id?>"><?php echo $voting_attribute[$lang_id]['text']; ?></label>
  </div>
  <?php } ?>
    <button onClick="return setValueVoting(event);" type="submit"><?php echo $text_btn; ?></button>
	<div id="error<?php echo $module_id;?>" class="text-danger" style="display:none;"></div>
</form>
</div></div>
<div id="resultVoting<?php echo $module_id;?>" style="display:none;">
	<div id="contentResultVoting<?php echo $module_id;?>" style="display:none;" class="voting">
		<?php if ($heading_title) {?>
		  <div class="block_title">
			<?php echo $heading_title; ?>
		  </div>	
		<?php } ?>
		<div class="contentVoting">
		<?php foreach ($voting_attributes as $key=> $voting_attribute) { ?>
		  <div>
			<label><?php echo $voting_attribute[$lang_id]['text']; ?></label>
			<div id="votingValue<?php echo $key;?>" class="persentVoting" style="background: linear-gradient(to right, green <?php echo $voting_attribute['persent']; ?>%, transparent <?php echo $voting_attribute['persent']; ?>%);"></div>
		  </div>
		<?php } ?>
	</div></div>
</div>
<script>
 function setValueVoting(event){
	event.preventDefault();
	let result=[];
	let viewResult=<?php echo $viewResult; ?>;
	const form=document.querySelector("#voting<?php echo $module_id;?>");
	const resultVoting=document.querySelector("#resultVoting<?php echo $module_id;?>");
	const error=document.querySelector("#error<?php echo $module_id;?>");
	const contentResultVoting=document.querySelector("#contentResultVoting<?php echo $module_id;?>");
	
	if (<?php echo $type_module; ?>==0){
	result[0]=$("input[type=radio][name=voting<?php echo $module_id; ?>]:checked").val();
	}else{
    $('[name="voting<?php echo $module_id; ?>"]:checked').each(function(){
      result.push($(this).val());
    }); 
    
    //console.log(result.join(', '));
	}
	//console.log(result);
		$.ajax({
			url: 'index.php?route=extension/module/genVoting/setValueVoting',
			type: 'post',
			data: {
				checked: result,
				module_id: <?php echo $module_id; ?>
			},
			dataType: 'json',									
			success: function(json) { 
				if (viewResult==0){
					form.style.display='none';
					resultVoting.style.display='block';
					resultVoting.textContent=json.message;
				}else{
					form.style.display='none';
					resultVoting.style.display='block';
					contentResultVoting.style.display='block';
					getResultVoting();
				}
				
			},
			error: function(xhr, textStatus, errorThrown) {
				//console.log(xhr.responseText);
				//console.log(textStatus);
				
				error.style.display='block';
				error.textContent="<?php echo $error_voting; ?>";
				setTimeout(function() { 
					error.style.display='none';
				}, 10000);
			}			
		});	
 }
 function getResultVoting(){
	const contentResultVoting=document.querySelector("#contentResultVoting<?php echo $module_id;?>");
			$.ajax({
			url: 'index.php?route=extension/module/genVoting/getResultVoting',
			type: 'post',
			data: {
				module_id: <?php echo $module_id; ?>
			},
			dataType: 'json',									
			success: function(json) { 
				console.log(json.result);
				for(i=0;i<json.result.length;i++){
					const element=document.querySelector("#votingValue"+i);
					element.style.background='linear-gradient(to right, green '+ json.result[i] +'%, transparent '+ json.result[i] +'%)';
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				error.style.display='block';
				error.textContent="<?php echo $error_voting; ?>";
				setTimeout(function() { 
					error.style.display='none';
				}, 10000);
			}			
		});	
 }
</script>