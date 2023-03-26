<?php echo $header; ?>
<div class="container" id="introPanel">
	<div class="row"><?php echo $column_left; ?>
    	<?php if ($column_left && $column_right) { ?>
    		<?php $class = 'col-sm-6'; ?>
    	<?php } elseif ($column_left || $column_right) { ?>
    		<?php $class = 'col-sm-9'; ?>
    	<?php } else { ?>
    		<?php $class = 'col-sm-12'; ?>
    	<?php }?>
    	<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    		<h2><?php echo $heading_title; ?></h2>

      		<div id="calendar"></div>

      			<div class="row">
        			<!--<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>-->
        			<!--<div class="col-sm-6 text-right"><?php echo $results; ?></div>-->
      			</div>
				
      		<?php  
			if (!$events) { ?>
      			<p><?php echo $text_empty; ?></p>
      		<?php }	?>
      		<?php echo $content_bottom; ?>
        </div>
   	 	<?php echo $column_right; ?>
    </div>
</div>
	<script type="text/javascript"><!--

  $(document).ready(function() {

    $('#calendar').fullCalendar({
		firstDay: 1,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		monthNames: ['<?php echo $January; ?>','<?php echo $February; ?>','<?php echo $March; ?>','<?php echo $April; ?>','<?php echo $May; ?>','<?php echo $June; ?>','<?php echo $July; ?>','<?php echo $August; ?>','<?php echo $September; ?>','<?php echo $October; ?>','<?php echo $November; ?>','<?php echo $December; ?>'],
        monthNamesShort: ['<?php echo $Jan; ?>','<?php echo $Feb; ?>','<?php echo $Mar; ?>','<?php echo $Apr; ?>','<?php echo $May1; ?>','<?php echo $Jun; ?>','<?php echo $Jul; ?>','<?php echo $Aug; ?>','<?php echo $Sept; ?>','<?php echo $Oct; ?>','<?php echo $Nov; ?>','<?php echo $Dec; ?>'],
        dayNames: ["<?php echo $sun; ?>","<?php echo $mon; ?>","<?php echo $tue; ?>","<?php echo $wed; ?>","<?php echo $thu; ?>","<?php echo $fri; ?>","<?php echo $sat; ?>"],
        dayNamesShort: ["<?php echo $sun1; ?>","<?php echo $mon1; ?>","<?php echo $tue1; ?>","<?php echo $wed1; ?>","<?php echo $thu1; ?>","<?php echo $fri1; ?>","<?php echo $sat1; ?>"],
        buttonText: {
            prevYear: "&nbsp;&lt;&lt;&nbsp;",
			nextYear: "&nbsp;&gt;&gt;&nbsp;",
			today: "<?php echo $tooday; ?>",
			month: "<?php echo $month; ?>",
			week: "<?php echo $week; ?>",
			day: "<?php echo $day; ?>"
		},
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		events: [
		<?php foreach ($events as $event) {
				
				$new_from=explode('-',date("Y-m-d", strtotime($event['date_from']))); 
							
				$new_to=explode('-',date("Y-m-d", strtotime($event['date_to']))); 
				?>
			{
			title: '<?php print_r( $event["mindescription"]); ?>',
			//url: '<?php print_r( $event["href"]); ?>',
			url: 'index.php?route=extension/module/event_detail&event_id=<?php print_r($event["event_id"]); ?>',
			start: '<?php echo $new_from[0] . "-" . $new_from[1] . "-".$new_from[2]; ?>',
			end: '<?php echo $new_to[0] . "-" . $new_to[1] ."-" . $new_to[2]; ?>T20:00:00'
			},
		<?php } ?>{}] 
    });

  });

//--></script>
<?php echo $footer; ?>