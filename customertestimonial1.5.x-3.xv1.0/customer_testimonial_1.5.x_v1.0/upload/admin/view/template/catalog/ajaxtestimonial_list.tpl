<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src='view/image/information.png'><?php echo $heading_title; ?></h1>
			
			<?php if ($ajaxtestimonial_total!=-1) { ?>
				<div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
			<?php } ?>
		</div>
		
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							
							<td class="center">#</td>
							
							<td class="left" style="width:75%"><?php if ($sort == 'td.description') { ?>
								<a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_description; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_description; ?>"><?php echo $column_description; ?></a>
							<?php } ?></td>
							
							<td class="center" style="min-width:100px"><?php if ($sort == 't.name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							<td class="center"><?php if ($sort == 't.date_added') { ?>
								<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
							<?php } ?></td>
							<td class="center"><?php if ($sort == 't.status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
							<?php } ?></td>
							<td class="center"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($ajaxtestimonials) { ?>
							<?php foreach ($ajaxtestimonials as $ajaxtestimonial) { ?>
								<tr>
									<td class="center"><?php if ($ajaxtestimonial['selected']) { ?>
										<input onclick="$(this).parent().next().next('.tm-parent').find('.tm-child input[name*=\'selected\']').attr('checked', this.checked);" value="<?php echo $ajaxtestimonial['ajaxtestimonial_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input onclick="$(this).parent().next().next('.tm-parent').find('.tm-child input[name*=\'selected\']').attr('disabled', this.checked).attr('checked', this.checked);" type="checkbox" name="selected[]" value="<?php echo $ajaxtestimonial['ajaxtestimonial_id']; ?>" />
									<?php } ?></td>
									<td class="center" style="width:30px"><?php echo $ajaxtestimonial['ajaxtestimonial_id']; ?></td>
									<td class="left tm-parent">
										<p><?php echo $ajaxtestimonial['description']; ?></p>
										
										<?php
											$reply = $ajaxtestimonial['reply'];
											if ($reply){
											?>
											<table class="list tm-child" style="margin-top:20px;max-width:1200px">
												<thead>
													<tr>
														<td class="center"></td>
														<td class="left"><?php echo $column_description; ?></td>	
														<td class="center"><?php echo $column_name; ?></td>							
														<td class="center"><?php echo $column_date_added; ?></td>		
														<td class="center"><?php echo $column_status; ?></td>
														<td class="center"><?php echo $column_action; ?></td>
													</tr>
												</thead>
												<tbody>
													<?php 
														$i=0;
														while ($i < sizeof($reply)) { ;
														?>														
														<tr>
															<td class="center"><?php if ($ajaxtestimonial['selected']) { ?>
																<input type="checkbox" name="selected[]" value="<?php echo $reply[$i]['id']; ?>" checked="checked" />
																<?php } else { ?>
																<input type="checkbox" name="selected[]" value="<?php echo $reply[$i]['id']; ?>" />
															<?php } ?></td>
															<td class="left" style="width:80%"><?php echo $reply[$i]['description']; ?></td>
															<td class="center"><?php echo $reply[$i]['name']; ?></td>
															<td class="center"><?php echo $reply[$i]['date_added']; ?></td>
															<td class="center"><?php echo $reply[$i]['status']; ?></td>	
															<td class="center"><?php foreach ($reply[$i]['action'] as $action) { ?>
																<a href="<?php echo $action['href']; ?>">[<?php echo $action['text']; ?>]</a> 
															<?php } ?></td>
														</tr>
														<?php
															$i++;
														}
													?>
												</tbody>
											</table>
										<?php } ?>	
										
										<td class="center">			
											<?php if ($ajaxtestimonial['name']!="") echo $ajaxtestimonial['name']; ?>	
										</td>
										
										<td class="center"><?php echo $ajaxtestimonial['date_added']; ?></td>
										<td class="center"><?php echo $ajaxtestimonial['status']; ?></td>
										<td class="center"><?php foreach ($ajaxtestimonial['action'] as $action) { ?>
											<a href="<?php echo $action['href']; ?>">[<?php echo $action['text']; ?>]</a> 
										<?php } ?></td>
									</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</form>
				<div class="pagination"><?php echo $pagination; ?></div>
			</div>
		</div>
		
		<?php if ($ajaxtestimonial_total!=-1) { ?>
			<div class="box"><div class="heading"><div class="buttons"><a href="<?php echo $module_settings_path; ?>" class="button"><span><?php echo $text_module_settings; ?></span></a></div></div></div>
		<?php } ?>
		
	<?php echo $footer; ?>											