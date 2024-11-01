<?php
/*
 * WpSimpleFaws Simple Faqs listing view
 */
?>

<details class="wpsimplefaq">
    <summary style="background-color: <?php echo (!empty($headingColor)) ? $headingColor : ''; ?>; color: <?php echo (!empty($headingTxtColor)) ? $headingTxtColor : ''; ?>;"><?php the_title(); ?></summary>
    <div class="wpsimplefaqcontent">
        <?php the_content(); ?>
    </div>
</details>