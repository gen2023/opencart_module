<?php if ($heading_title) {?>
  <div class="block_title">
	<?php echo $heading_title; ?>
  </div>	
<?php } ?>

<form action="" method="post">
<?php foreach ($voting_attributes as $key=> $voting_attribute) { ?>
  <div>
	<?php if($type_module==0){?>
    <input type="radio" name="voting<?php echo $module_id?>" value="<?php echo $key; ?>">
	<?}else{?>
	<input type="checkbox" name="voting<?php echo $module_id?>" value="<?php echo $key; ?>">
	<?php } ?>
    <label for="contactChoice1"><?php echo $voting_attribute[$lang_id]['text']; ?></label>
  </div>
  <?php } ?>
  <div>
    <button onClick="return setValueVoting(event);" type="submit"><?php echo $text_btn; ?></button>
  </div>
</form>

<script>
 function setValueVoting(event){
	event.preventDefault();
	let result=[];
	if (<?php echo $type_module?>==0){
	result[0]=$("input[type=radio][name=voting<?php echo $module_id?>]:checked").val();
	}else{
    $('[name="voting<?php echo $module_id?>"]:checked').each(function(){
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
				module_id: <?php echo $module_id?>
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
</script>