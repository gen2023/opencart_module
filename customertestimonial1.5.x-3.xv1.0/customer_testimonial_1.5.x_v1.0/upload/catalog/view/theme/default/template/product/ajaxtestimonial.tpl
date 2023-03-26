<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div id="content">
	<?php echo $content_top; ?>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<h1><?php echo $heading_title; ?></h1>
	
	<div class="testimonials-page">
		<?php if ($ajaxtestimonials) { ?>
			
			<div class="top-button">
				<a href="#popup-testimonials" class="popup-testimonials button"><?php echo $write; ?></a>
				
				<div class="sort">
					<b><?php echo $text_sort; ?></b>
					<select onchange="location = this.value;" name="sorting" style="min-width:200px;">
						<?php foreach ($sorts as $sorts) { ?>
							<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
								<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>	
				</div>
			</div>
			<div class="clear"></div>
			
			<div class="items">
				<?php foreach ($ajaxtestimonials as $ajaxtestimonial) { ?>
					<div class="item">
						<div class="name"><i><?php echo $ajaxtestimonial['date_added']; ?></i><span><?php echo $ajaxtestimonial['name']; ?></span></div>
						<?php if ($ajaxtestimonial['rating']) { ?>
							<div class="rating">
								<?php for ($i = 1; $i <= 5; $i++) { ?>
									<?php if ($ajaxtestimonial['rating'] < $i) { ?>
										<img src="<?php echo $template_path.'/image/ajaxtestimonial-small-0.png'; ?>" alt="">
										<?php } else { ?>
										<img src="<?php echo $template_path.'/image/ajaxtestimonial-small-1.png'; ?>" alt="">
									<?php } ?>
								<?php } ?>	
							</div>
						<?php } ?>
						
						<div class="desc"><?php echo $ajaxtestimonial['description']; ?></div>
						
						<div class="bottom">
							<a class="reply" href="#"><?php echo $text_reply; ?></a>
							<div class="like <?php if ($ajaxtestimonial['voted'] == false){ echo 'disabled'; } ?>">
								<div class="like-wrap">
									<span class="stat"><span class="score_likes"><?php echo $ajaxtestimonial['likes']; ?></span></span>
									<a href="#" class="voted like-button" data-id="<?php echo $ajaxtestimonial['id']; ?>" data-vote="likes"></a>
								</div>
								<div class="like-wrap">
									<span class="stat"><span class="score_unlikes"><?php echo $ajaxtestimonial['unlikes']; ?></span></span>
									<a href="#" class="voted unlike-button" data-id="<?php echo $ajaxtestimonial['id']; ?>" data-vote="unlikes"></a>
								</div>
							</div>
						</div>
						
						<?php
						    $reply_class = 'disable';
							$reply = $ajaxtestimonial['reply'];
							if ($reply){
								$reply_class = '';
							}
						?>	
						<div class="reply-wrap <?php echo $reply_class; ?>">
							<h2><?php echo $text_replies; ?></h2>	
							
							<?php
								if ($reply) {
									$i=0;
									while ($i < sizeof($reply)) {
									?>
									<div class="reply-wrap-item">
										<div class="name"><i><?php echo $reply[$i]['date_added']; ?></i><span><?php echo $reply[$i]['name']; ?></span></div>
										<div class="desc"><?php echo $reply[$i]['description']; ?></div>
									</div>
									<?php
										$i++;
									}
								}
							?>
						</div>
						
						<div class="reply-form" id="reply-form-<?php echo $ajaxtestimonial['id']; ?>" style="display:none">
							<h2><?php echo $text_newreply; ?></h2>
							<form action="index.php?route=product/ajaxtestimonial/reply" method="POST">
								
								<b><?php echo $entry_name; ?> <span class="req">*</span></b><br />
								<input class="input reply_name" type="text" name="reply_name">
								<br />
								<br />
								
								<b><?php echo $entry_reply; ?> <span class="req">*</span></b><br />
								<textarea class="input reply_description" cols="40" rows="10" style="width:50%" name="reply_description[<?php echo $lang_id; ?>][reply_description]" id="reply_description<?php echo $lang_id; ?>"></textarea>
								<br />
								<br />
								<input type="hidden" class="reply_id" name="reply_id" value="<?php echo $ajaxtestimonial['id']; ?>">
								
								<?php if ($need_captcha && $captcha_reply) { ?>
									<b><?php echo $entry_captcha; ?></b> <span class="req">*</span><br />
									<input class="input" id="captcha-reply" type="text" name="captcha" /><br />
									<img style="margin-top:6px" src="index.php?route=product/ajaxtestimonial/captcha" /><br />
									<br />
									<br />
								<?php } else { ?>
									<input id="captcha-reply" type="hidden" name="captcha" value="" />
								<?php } ?>
								
								<div class="buttons">
									<div class="right"><input type="submit" value="<?php echo $text_reply; ?>" class="submit button"></div>
								</div>
								
								<div class="complete"></div>
							</form>
						</div>
					</div>
					
					<?php } ?>
					
				</div>
				
				<div style="clear:both"></div>
				<div class="pagination"><?php echo $pagination; ?></div>
				
				<?php }else{ ?>
				
				<p><?php echo $text_empty; ?></p>
				
			<?php } ?>
			
			<div class="bottom-button">
				<a href="#popup-testimonials" class="popup-testimonials button" style="float:left"><?php echo $write; ?></a>
				<?php if ($single_review == false) { ?>
					<?php if ($loadmore_button == true) { ?>
						<a href="#" class="button button-more" style="float:right" onclick="getPageNext(); return false;"><?php echo $load_more; ?></a>
					<?php } ?>
					<?php } else { ?>
					<a href="<?php echo $showall_url; ?>" style="float:right" class="button"><?php echo $showall; ?></a>
				<?php } ?>
			</div>
			<div style="clear:both"></div>
			<input type="hidden" id="page_limit" value="<?php echo $page_limit; ?>">
			<input type="hidden" id="page_total" value="<?php echo $page_total; ?>">
			<input type="hidden" id="page_current" value="<?php echo $page_current; ?>">
			<input type="hidden" id="loadmore_button" value="<?php echo $loadmore_button; ?>">
			
		</div>
		
		<?php echo $content_bottom; ?>
	</div>
	
	<div style="display:none">
		<div class="popup-addtestimonials" id="popup-testimonials">
			<div class="complete"></div>
			
			<h2><?php echo $text_leave; ?></h2>
			<br />
			
			<form action="index.php?route=product/ajaxtestimonial_form" method="POST">
				<b><?php echo $entry_name; ?> <span class="req">*</span></b><br />
				<input class="input form-name" type="text" name="name">
				<br />
				<br />
				
				<b><?php echo $entry_telephone; ?></b><br />
				<input class="input form-phone" type="tel" pattern="^[0-9]+$" name="phone">
				<br />
				<br />
				
				<b><?php echo $entry_email; ?> <span class="req">*</span></b><br />
				<input class="input form-email" type="email" name="email">
				<br />
				<br />
				
				<div class="rating">
					<b><?php echo $entry_rating; ?></b> <span class="req">*</span><br />
					<div class="rating-wrap">
						<input <?php if ($default_rating == '1') echo 'checked'; ?> class="star-1" id="star-1" type="radio" name="rating" value="1"><label class="star-1" for="star-1">1</label>
						<input <?php if ($default_rating == '2') echo 'checked'; ?> class="star-2" id="star-2" type="radio" name="rating" value="2"><label class="star-2" for="star-2">2</label>
						<input <?php if ($default_rating == '3') echo 'checked'; ?> class="star-3" id="star-3" type="radio" name="rating" value="3"><label class="star-3" for="star-3">3</label>
						<input <?php if ($default_rating == '4') echo 'checked'; ?> class="star-4" id="star-4" type="radio" name="rating" value="4"><label class="star-4" for="star-4">4</label>
						<input <?php if ($default_rating == '5') echo 'checked'; ?> class="star-5" id="star-5" type="radio" name="rating" value="5"><label class="star-5" for="star-5">5</label>
						<span></span>
					</div>	
				</div>
				<br />
				<br />
				
				<b><?php echo $entry_message; ?> <span class="req">*</span></b><br />
				<textarea class="input textarea input-description form-description" cols="40" rows="10" style="width:99%" name="description[<?php echo $lang_id; ?>][description]" id="description<?php echo $lang_id; ?>"></textarea>
				<br />
				<br />
				
				<?php if ($need_captcha == true) { ?>
					<b><?php echo $entry_captcha; ?></b> <span class="req">*</span><br />
					<input class="input" id="captcha" type="text" name="captcha" value="<?php echo $captcha; ?>" /><br />
					<img style="margin-top:6px" src="index.php?route=product/ajaxtestimonial/captcha" /><br />
					<br />
					<br />
				<?php } ?>
				
				<div class="buttons">
					<div class="right"><input type="submit" value="<?php echo $text_button; ?>" class="submit button"></div>
				</div>
				
			</form>
			
		</div>
	</div>
	
<?php echo $footer; ?>