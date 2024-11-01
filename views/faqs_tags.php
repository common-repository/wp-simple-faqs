<?php
/*
 * WpSimpleFaqs Tags Cloud View
 */
?>
<a class="wpsimplefaqstag" href="#wpsimplefaqs_<?php echo $cat->term_id; ?>" style="background-color: <?php echo (!empty($headingColor)) ? $headingColor : ''; ?>; color: <?php echo (!empty($headingTxtColor)) ? $headingTxtColor : ''; ?>;"><?php echo $cat->name; ?></a>

