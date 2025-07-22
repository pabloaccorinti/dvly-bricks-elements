<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class Brxe_Dvly_Logo_Grid extends \Bricks\Element
{
    public $category = 'dvly-elements';
    public $name = 'dvly-logo-grid';
    public $icon = 'ti-gallery';

    public function get_label()
    {
        return esc_html__('Logo Grid', 'bricks');
    }

    public function set_control_groups()
    {
        $this->control_groups['content'] = [
            'title' => esc_html__('Content', 'bricks'),
            'tab' => 'content',
        ];
        $this->control_groups['appearance'] = [
            'title' => esc_html__('Appearance', 'bricks'),
            'tab' => 'content',
        ];
    }

    public function set_controls()
    {
        $this->controls['dvly_title'] = [
            'label' => esc_html__('Title', 'bricks'),
            'type' => 'text',
            'group' => 'content',
            'default' => 'Our Partners',
        ];

        $this->controls['dvly_subtitle'] = [
            'label' => esc_html__('Subtitle', 'bricks'),
            'type' => 'text',
            'group' => 'content',
            'default' => 'Trusted by companies worldwide',
        ];

        $this->controls['dvly_logos'] = [
            'label' => esc_html__('Logos', 'bricks'),
            'type' => 'repeater',
            'group' => 'content',
            'fields' => [
                'logo_image' => [
                    'label' => esc_html__('Image', 'bricks'),
                    'type' => 'image',
                ],
                'logo_link' => [
                    'label' => esc_html__('Link (optional)', 'bricks'),
                    'type' => 'link',
                ],
            ],
        ];

        $this->controls['dvly_title_typography'] = [
            'label' => esc_html__('Title Typography', 'bricks'),
            'type' => 'typography',
            'group' => 'appearance',
            'css' => [
                ['selector' => '{{SELECTOR}} .dvly-logo-grid-title'],
            ],
        ];

        $this->controls['dvly_subtitle_typography'] = [
            'label' => esc_html__('Subtitle Typography', 'bricks'),
            'type' => 'typography',
            'group' => 'appearance',
            'css' => [
                ['selector' => '{{SELECTOR}} .dvly-logo-grid-subtitle'],
            ],
        ];

    }

    public function render()
    {
        $s = $this->settings;

        echo '<section class="brxe-dvly-logo-grid">';
        echo '<div class="brxe-dvly-logo-grid-container brxe-container">';

        if (!empty($s['dvly_title'])) {
            echo '<h2 class="dvly-logo-grid-title">' . esc_html($s['dvly_title']) . '</h2>';
        }

        if (!empty($s['dvly_subtitle'])) {
            echo '<p class="dvly-logo-grid-subtitle">' . esc_html($s['dvly_subtitle']) . '</p>';
        }

        if (!empty($s['dvly_logos']) && is_array($s['dvly_logos'])) {
            echo '<div class="dvly-logo-grid-logos">';
            foreach ($s['dvly_logos'] as $index => $item) {
                $img_html = wp_get_attachment_image($item['logo_image']['id'] ?? 0, 'full', false, [
                    'class' => 'dvly-logo-image'
                ]);

                if (!$img_html)
                    continue;

                if (!empty($item['logo_link']['url'])) {
                    $link_id = 'dvly_logo_link_' . $index;
                    $this->set_link_attributes($link_id, $item['logo_link']);
                    echo '<a ' . $this->render_attributes($link_id) . ' class="dvly-logo-link">' . $img_html . '</a>';
                } else {
                    echo '<div class="dvly-logo-item">' . $img_html . '</div>';
                }
            }
            echo '</div>';
        }

        echo '</div>'; // .brxe-dvly-logo-grid-container
        echo '</section>'; // .brxe-dvly-logo-grid
    }
}
