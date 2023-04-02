<div class="subscribe-box" style="margin-bottom: 20px;">
	<h3 class="subscribe-title"><?php echo $heading_title; ?></h3>
	<div class="subscribe-form">
		<form class="form-inline" action="" method="post">
		  <div class="form-group required">
		    <label class="sr-only" for="input-name"><?php echo $name;?></label>
			  <input type="text" class="form-control" name="txtname" id="txtname" placeholder="<?php echo $name;?>">
			  <label class="sr-only" for="exampleInputAmount">Email:</label>
		      <input type="email" class="form-control" name="txtemail" id="txtemail" placeholder="Email">
			  <label class="sr-only" for="input-town"><?php echo $town;?></label>
			  <input type="text" class="form-control" name="txttown" id="txttown" placeholder="<?php echo $town;?>">
		  </div>
		  <button type="submit" class="btn btn-primary" onclick="return subscribe1(event);"><?php echo $but_newsletter;?></button>
		</form>      
	</div>
</div>
<script>
	function subscribe1(event){
		event.preventDefault();
		$.ajax({
			url: 'index.php?route=extension/module/newsletters/setSubscribe',
			type: 'post',
			data: {
				email: $('#txtemail').val(),
				name: $('#txtname').val(),
				town: $('#txttown').val()
			},
			dataType: 'json',									
			success: function(json) { 
				alert(json.message);
				$('#txtemail').val('');
				$('#txtname').val('');
				$('#txttown').val('');
			}						
		});
	}
</script>