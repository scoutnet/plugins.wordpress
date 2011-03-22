<?php
    /* @var $event SN_Model_Event */
?>
<div class="snk_event">
    <h3><span class="snk_start"><?php echo date('d. m. Y', $event->Start); ?></span> / <span class="snk_title"><?php echo $event->Title; ?></span></h3>
    <p class="snk_description"><?php echo $event->Description; ?></p>
    <p class="snk_author"><?php echo $event->Author->get_full_name(); ?></p>
</div>
