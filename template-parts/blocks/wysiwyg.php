<?php

$wys_heading     = get_field('wys_heading');
$wys_description = get_field('wys_description');
echo '<!-- WYSIWYG -->';
echo '<section class="wysiwyg-component">';
echo '<div class="container">';
echo '<div class="wc-wrap">';
if ($wys_heading) {
    echo '<h2>' . esc_html($wys_heading) . '</h2>';
}
if ($wys_description) {
    echo '<div class="wc-decs bullet-styled">' . wp_kses_post($wys_description) . '</div>';
}
        echo '</div>';
echo '</div>';
echo '</section>';
