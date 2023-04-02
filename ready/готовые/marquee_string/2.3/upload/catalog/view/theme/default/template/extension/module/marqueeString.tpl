<div class='marquee' style='overflow:hidden; font-size:<?php echo $fontSize; ?>px; color:<?php echo $textColor; ?>; background:<?php echo $textBg; ?>; height:<?php echo $height; ?>px;<?php echo $css; ?>'><?php echo $string; ?></div>


<script type="text/javascript"><!--
$(function() {
    $('.marquee').marquee({
      duration: <?php echo $duration; ?>,
      direction: '<?php echo $direction; ?>',
      delayBeforeStart: <?php echo $delayBeforeStart; ?>,
      gap: <?php echo $gap; ?>,
      startVisible: <?php echo $startVisible; ?>,
      pauseOnHover: <?php echo $pauseOnHover; ?>,
      pauseOnCycle: <?php echo $pauseOnCycle; ?>,
      speed: <?php echo $speed; ?>,
      duplicated: <?php echo $duplicated; ?>,
      amount: <?php echo $amount; ?>
    });
  });
--></script> 