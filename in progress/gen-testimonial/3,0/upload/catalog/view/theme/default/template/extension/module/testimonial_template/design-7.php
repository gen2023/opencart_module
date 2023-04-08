<div class="<?php echo $css_class; ?>">
	<div class="opgtm-testimonial-left">
		<?php if ( isset( $author_image ) && ($display_avatar)) { ?>
			<div class="opgtm-avtar-image"><?php echo '<a href="'.esc_url($url).'" > '.$author_image.' </a>'; ?></div>
		<?php }?>
	</div>
	<div class="opgtm-testimonial-content">
    <div class="opgtm-testimonials-text">
			<?php if($display_quotes) { ?> <em> <?php } ?>
				<?php  echo get_the_content(); ?> 
			<?php if($display_quotes) { ?> </em> <?php } ?>
		</div>
        <h4><?php echo '<a href="'.esc_url($url).'" > '.esc_html($testimonial_title).' </a>'; ?></h4>		
	</div>
	<?php if($display_company && !empty($company)) { ?>
		<div class="opgtm-testimonial-job">
			<?php 
			if($display_company && !empty($company)) {
				echo '<a href="'.esc_url($url).'" > '.esc_html($company).' </a>';
			} ?>
		</div>
	<?php } ?>
</div>