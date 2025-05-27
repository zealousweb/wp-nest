<?php
$heading = get_field('heading');
$content = get_field('content');
?>
<div class="custom-block">
    <h2><?php echo esc_html($heading); ?></h2>
    <p><?php echo esc_html($content); ?></p>
</div>
