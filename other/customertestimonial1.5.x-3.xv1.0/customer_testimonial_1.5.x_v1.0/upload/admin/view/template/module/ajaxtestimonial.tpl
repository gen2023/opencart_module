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
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				
				<table>
					
					<tr>
						<td width="10"></td>
						<td><?php echo $entry_default_rating; ?></td>
						<td><span><?php echo $entry_bad; ?></span>&nbsp;
							<input type="radio" name="ajaxtestimonial_default_rating" value="1" style="margin: 0;" <?php if ( $ajaxtestimonial_default_rating == 1 ) echo 'checked="checked"';?> />
							&nbsp;
							<input type="radio" name="ajaxtestimonial_default_rating" value="2" style="margin: 0;" <?php if ( $ajaxtestimonial_default_rating == 2 ) echo 'checked="checked"';?> />
							&nbsp;
							<input type="radio" name="ajaxtestimonial_default_rating" value="3" style="margin: 0;" <?php if ( $ajaxtestimonial_default_rating == 3 ) echo 'checked="checked"';?> />
							&nbsp;
							<input type="radio" name="ajaxtestimonial_default_rating" value="4" style="margin: 0;" <?php if ( $ajaxtestimonial_default_rating == 4 ) echo 'checked="checked"';?> />
							&nbsp;
							<input type="radio" name="ajaxtestimonial_default_rating" value="5" style="margin: 0;" <?php if ( $ajaxtestimonial_default_rating == 5 ) echo 'checked="checked"';?> />
							&nbsp; <span><?php echo $entry_good; ?></span>
						</td>
					</tr>
					
					<tr>
						<td width="10"></td>
						<td><?php echo $entry_admin_approved; ?></td>
						<td>
							<?php if (isset($ajaxtestimonial_admin_approved)) { ?>
								<input type="checkbox" name="ajaxtestimonial_admin_approved" value="0" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="ajaxtestimonial_admin_approved" value="0" />
							<?php } ?>
						</td>
					</tr>
					
					<tr>
						<td width="10"></td>
						<td><?php echo $entry_send_to_admin; ?></td>
						<td>
							<?php if (isset($ajaxtestimonial_send_to_admin)) { ?>
								<input type="checkbox" name="ajaxtestimonial_send_to_admin" value="0" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="ajaxtestimonial_send_to_admin" value="0" />
							<?php } ?>
						</td>
					</tr>
					
					<tr>
						<td width="10"></td>
						<td><?php echo $entry_admin_loadmore; ?></td>
						<td>
							<?php if (isset($ajaxtestimonial_admin_loadmore)) { ?>
								<input type="checkbox" name="ajaxtestimonial_admin_loadmore" value="0" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="ajaxtestimonial_admin_loadmore" value="0" />
							<?php } ?>
						</td>
					</tr>
					
					
					<tr>
						<td width="10"></td>
						<td><?php echo $entry_all_page_limit; ?></td>
						<td><input type="text" name="ajaxtestimonial_all_page_limit" value="<?php echo $ajaxtestimonial_all_page_limit; ?>" size="3" />
						</td>
					</tr>
					
					<tr>
						<td width="10"></td>
						<td><?php echo $entry_all_page_date_format; ?></td>
						<td><input type="text" name="ajaxtestimonial_all_page_date_format" value="<?php echo $ajaxtestimonial_all_page_date_format; ?>" size="10" />
						</td>
					</tr>
					
				</table>
				<br>
				
				
				<table id="module" class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $entry_title; ?></td>
							<td class="left"><?php echo $entry_limit; ?></td>
							<td class="left"><?php echo $entry_random; ?></td>
							<td class="left"><?php echo $entry_character_limit; ?></td>
							<td class="left"><?php echo $entry_layout; ?></td>
							<td class="left"><?php echo $entry_position; ?></td>
							<td class="left"><?php echo $entry_status; ?></td>
							<td class="right"><?php echo $entry_sort_order; ?></td>
							<td></td>
						</tr>
					</thead>
					<?php $module_row = 0; ?>
					<?php foreach ($modules as $module) { ?>
						<tbody id="module-row<?php echo $module_row; ?>">		
							<tr>
								<td colspan="9" id="language-<?php echo $module_row; ?>" class="htabs" style="border-right:0">
									<?php foreach ($languages as $language) { ?>
										<a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
									<?php } ?>
								</td>
							</tr>							
							<tr>
								<?php foreach ($languages as $language) { ?>
									<td class="left" id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
										<input type="text" name="ajaxtestimonial_module[<?php echo $module_row; ?>][ajaxtestimonial_title][<?php echo $language['language_id']; ?>]" id="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"  value="<?php echo isset($module['ajaxtestimonial_title'][$language['language_id']]) ? $module['ajaxtestimonial_title'][$language['language_id']] : ''; ?>" size="30" />
									</td>
								<?php } ?>
								
								<td class="left"><input type="text" name="ajaxtestimonial_module[<?php echo $module_row; ?>][ajaxtestimonial_limit]" value="<?php echo $module['ajaxtestimonial_limit']; ?>" size="2" /></td>
								
								<?php if (isset($module['ajaxtestimonial_random'])) { ?>
									<td  class="left"><input type="checkbox" name="ajaxtestimonial_module[<?php echo $module_row; ?>][ajaxtestimonial_random]" value="0" checked="checked"  /></td>
									<?php } else { ?>
									<td  class="left"><input type="checkbox" name="ajaxtestimonial_module[<?php echo $module_row; ?>][ajaxtestimonial_random]" value="0"   /></td>
								<?php } ?>
								
								<td class="left"><input type="text" name="ajaxtestimonial_module[<?php echo $module_row; ?>][ajaxtestimonial_character_limit]" value="<?php if (isset($module['ajaxtestimonial_character_limit'])) echo $module['ajaxtestimonial_character_limit']; else echo ""; ?>" size="2" /></td>
								
								<td class="left"><select name="ajaxtestimonial_module[<?php echo $module_row; ?>][layout_id]">
									<?php foreach ($layouts as $layout) { ?>
										<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
											<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select></td>
								
								<td class="left"><select name="ajaxtestimonial_module[<?php echo $module_row; ?>][position]">
									<?php if ($module['position'] == 'content_top') { ?>
										<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
										<?php } else { ?>
										<option value="content_top"><?php echo $text_content_top; ?></option>
									<?php } ?>
									<?php if ($module['position'] == 'content_bottom') { ?>
										<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
										<?php } else { ?>
										<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
									<?php } ?>
									<?php if ($module['position'] == 'column_left') { ?>
										<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
										<?php } else { ?>
										<option value="column_left"><?php echo $text_column_left; ?></option>
									<?php } ?>
									<?php if ($module['position'] == 'column_right') { ?>
										<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
										<?php } else { ?>
										<option value="column_right"><?php echo $text_column_right; ?></option>
									<?php } ?>
								</select></td>
								
								<td class="left"><select name="ajaxtestimonial_module[<?php echo $module_row; ?>][status]">
									<?php if ($module['status']) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select></td>
								
								<td class="right"><input type="text" name="ajaxtestimonial_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
								
								<td class="center"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
								
							</tr>
							
						</tbody>
						<?php $module_row++; ?>
					<?php } ?>
					<tfoot class="tfoot">
						<tr>
							<td colspan="8"></td>
							<td class="center"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	var module_row = <?php echo $module_row; ?>;
	
	function addModule() {		
		html  = '<tbody id="module-row' + module_row + '">';		
		html += '<tr>';
		html += '<td colspan="9" id="language-' + module_row + '" class="htabs" style="border-right:0">';
		<?php foreach ($languages as $language) { ?>
			html += '    <a href="#tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
		<?php } ?>
		html += '</td>';
		html += '</tr>';			
		html += '<tr>';
		html += '<?php foreach ($languages as $language) { ?><td class="left" id="tab-language-' + module_row + '-<?php echo $language['language_id']; ?>"><input type="text" name="ajaxtestimonial_module[' + module_row + '][ajaxtestimonial_title][<?php echo $language['language_id']; ?>]" value="Testimonials" size="30" id="description-' + module_row + '-<?php echo $language['language_id']; ?>"/><?php } ?>';		
		html += '<td class="left"><input type="text" name="ajaxtestimonial_module[' + module_row + '][ajaxtestimonial_limit]" value="10" size="2" /></td>';		
		html += '<td class="left"><input type="checkbox" name="ajaxtestimonial_module[' + module_row + '][ajaxtestimonial_random]" value="0" /></td>';		
		html += '<td class="left"><input type="text" name="ajaxtestimonial_module[' + module_row + '][ajaxtestimonial_character_limit]" value="100" size="2" /></td>';		
		html += '    <td class="left"><select name="ajaxtestimonial_module[' + module_row + '][layout_id]">';
		<?php foreach ($layouts as $layout) { ?>
			html += ' <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="left"><select name="ajaxtestimonial_module[' + module_row + '][position]">';
		html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
		html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
		html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
		html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
		html += '    </select></td>';
		html += '    <td class="left"><select name="ajaxtestimonial_module[' + module_row + '][status]">';
		html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
		html += '      <option value="0"><?php echo $text_disabled; ?></option>';
		html += '    </select></td>';
		html += '    <td class="right"><input type="text" name="ajaxtestimonial_module[' + module_row + '][sort_order]" value="0" size="3" /></td>';
		html += '    <td class="center"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '</tr>';
		html += '</tbody>';
		
		$('#module tfoot').before(html);
		
		$('#language-' + module_row + ' a').tabs();

		module_row++;
	}
//--></script> 

<script type="text/javascript"><!--
	<?php $module_row = 0; ?>
	<?php foreach ($modules as $module) { ?>
		$('#language-<?php echo $module_row; ?> a').tabs();
		<?php $module_row++; ?>
	<?php } ?> 
//--></script> 

<style>
	.htabs a{
	margin-top:20px;
	}
	.list td {
	border-bottom:0;
	border-top: 1px solid #DDDDDD;
	}
	.list td:last-child {
	border-bottom: 1px solid #DDDDDD;
	}
	.list .tfoot td{
	border-bottom: 1px solid #DDDDDD;
	}
</style>

<?php echo $footer; ?>	