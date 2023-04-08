<div class="<?php echo $css_class; ?>">
	<div class="opgtm-testimonial-inner <?php if (empty($author_image)) { echo 'opgtm-no-author-image'; } ?>">
		<?php if ( isset( $author_image ) && ($display_avatar)) { ?>
			<div class="opgtm-testimonial_avatar">			
					<div class="opgtm-avtar-image"><?php echo $author_image;?></div>			
			</div>
		<?php }?>
		<div class="opgtm-testimonial-author">
			<?php if($display_client && !empty($author)){ ?>
				<div class="opgtm-testimonial-client">
					<?php echo esc_html($author); ?>
				</div>
			<?php } ?>
			<?php if($display_job && !empty($job_title) || $display_company && !empty($company)) { ?>
			<div class="opgtm-testimonial-cdec">
				<?php 
					if($display_job && !empty($job_title)) {
						echo esc_html($job_title);
					}
					if($display_job && !empty($job_title) && $display_company && !empty($company)) { 
						echo " / ";
					 }
					if($display_company && !empty($company)) {
						echo '<a href="'.esc_url($url).'" > '.esc_html($company).' </a>';
					}
				?>
			</div>
			<?php } ?>
		</div>
		<div class="opgtm-testimonial-content">
			<h4><?php echo esc_html($testimonial_title); ?></h4>
			<div class="opgtm-testimonials-text">
				<p>
				<?php if($display_quotes) { ?> <em> <?php } ?>
					<?php  echo get_the_content(); ?> 
				<?php if($display_quotes) { ?> </em> <?php } ?>
				</p>
			</div>
		</div>
	</div>	
</div>