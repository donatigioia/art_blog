<?php
$output = $skin = $show_icon = $icon_type = $icon_image = $icon = $icon_simpleline = $box_style = $box_dir = $animation_type = $animation_duration = $animation_delay = $el_class = '';
extract(shortcode_atts(array(
    'skin' => 'custom',
    'show_icon' => false,
    'icon_type' => 'fontawesome',
    'icon' => '',
    'icon_simpleline' => '',
    'icon_image' => '',
    'box_style' => '',
    'box_dir' => '',
    'animation_type' => '',
    'animation_duration' => 1000,
    'animation_delay' => 0,
    'el_class' => ''
), $atts));

$el_class = porto_shortcode_extract_class( $el_class );

switch ($icon_type) {
    case 'simpleline': $icon_class = $icon_simpleline; break;
    case 'image': $icon_class = 'icon-image'; break;
    default: $icon_class = $icon;
}
if (!$show_icon)
    $icon_class = '';

$output = '<div class="porto-feature-box wpb_content_element ' . $el_class .'"';
if ($animation_type) {
    $output .= ' data-appear-animation="'.$animation_type.'"';
    if ($animation_delay)
        $output .= ' data-appear-animation-delay="'.$animation_delay.'"';
    if ($animation_duration && $animation_duration != 1000)
        $output .= ' data-appear-animation-duration="'.$animation_duration.'"';
}
$output .= '>';

$output .= '<div class="feature-box' . ($skin != 'custom' ? ' feature-box-' . $skin : '') . ($box_style ? ' ' . $box_style : '') . ($box_dir ? ' ' . $box_dir : '') . '">';

if ($icon_class) {
    $output .= '<div class="feature-box-icon"><i class="' . $icon_class . '">';
    if ($icon_class == 'icon-image' && $icon_image) {
        $icon_image = preg_replace('/[^\d]/', '', $icon_image);
        $image_url = wp_get_attachment_url($icon_image);
        $image_url = str_replace(array('http:', 'https:'), '', $image_url);
        if ($image_url)
            $output .= '<img alt="" src="' . esc_url($image_url) . '">';
    }
    $output .= '</i></div>';
}
$output .= '<div class="feature-box-info' . ($icon_class ? '' : ' p-none') . '">' . do_shortcode($content) . '</div>';
$output .= '</div>';

$output .= '</div>';

echo $output;