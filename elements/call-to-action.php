<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class Brxe_Dvly_Call_To_Action extends \Bricks\Element
{
    public $category = 'dvly-elements';
    public $name = 'dvly-call-to-action';
    public $icon = 'ti-bolt';

    public function get_label()
    {
        return esc_html__('Call to Action', 'bricks');
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
        $this->controls['textAlign'] = [ // Setting key
            'tab' => 'content',
            'label' => esc_html__('Text align', 'bricks'),
            'type' => 'text-align',
            'css' => [
                [
                    'property' => 'text-align',
                    'selector' => '.brxe-dvly-call-to-action-content',
                ],
            ],
            'group' => 'appearance',
        ];
        $this->controls['cta_padding'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Padding', 'bricks' ),
            'type' => 'dimensions',
            'responsive' => true,
            'group' => 'appearance',
            'css' => [
              [
                'property' => 'padding',
                'selector' => '.brxe-dvly-call-to-action-container',
              ]
            ],
          ];
        $this->controls['cta_above_title'] = [
            'label' => esc_html__('Above Title', 'bricks'),
            'type' => 'text',
            'group' => 'content',
            'default' => 'Welcome Message',
        ];
        $this->controls['above_title_typography'] = [
            'label' => esc_html__('Above Title Typography', 'bricks'),
            'type' => 'typography',
            'group' => 'appearance',
            'css' => [
                ['selector' => '.brxe-dvly-call-to-action-above-title'],
            ],
        ];
        $this->controls['cta_title'] = [
            'label' => esc_html__('Title', 'bricks'),
            'type' => 'text',
            'group' => 'content',
            'default' => 'Your Next Big Step Starts Here',
        ];
        $this->controls['title_typography'] = [
            'label' => esc_html__('Title Typography', 'bricks'),
            'type' => 'typography',
            'group' => 'appearance',
            'css' => [
                ['selector' => '.brxe-dvly-call-to-action-title'],
            ],
        ];
        $this->controls['cta_description'] = [
            'label' => esc_html__('Description', 'bricks'),
            'type' => 'textarea',
            'group' => 'content',
            'default' => 'Discover what you can achieve with our platform. Join us today and make an impact.',
        ];
        $this->controls['description_typography'] = [
            'label' => esc_html__('Description Typography', 'bricks'),
            'type' => 'typography',
            'group' => 'appearance',
            'css' => [
                ['selector' => '.brxe-dvly-call-to-action-description'],
            ],
        ];
        $this->controls['cta_button'] = [
            'label' => esc_html__('Buttons', 'bricks'),
            'type' => 'repeater',
            'max' => 1,
            'default' => [
                [
                    'cta_text' => 'Learn More',
                    'cta_link' => ['type' => 'external', 'url' => '#'],
                    'cta_style' => 'primary',
                    'cta_size' => 'md',
                ],
            ],
            'fields' => [
                'cta_text' => [
                    'label' => esc_html__('Button Text', 'bricks'),
                    'type' => 'text',
                ],
                'cta_link' => [
                    'label' => esc_html__('Button Link', 'bricks'),
                    'type' => 'link',
                ],
                'cta_style' => [
                    'label' => esc_html__('Button Style', 'bricks'),
                    'type' => 'select',
                    'options' => [
                        'primary' => esc_html__('Primary', 'bricks'),
                        'secondary' => esc_html__('Secondary', 'bricks'),
                        'light' => esc_html__('Light', 'bricks'),
                        'dark' => esc_html__('Dark', 'bricks'),
                    ],
                    'default' => 'primary',
                ],
                'cta_size' => [
                    'label' => esc_html__('Button Size', 'bricks'),
                    'type' => 'select',
                    'options' => [
                        'sm' => esc_html__('Small', 'bricks'),
                        'md' => esc_html__('Medium', 'bricks'),
                        'lg' => esc_html__('Large', 'bricks'),
                        'xl' => esc_html__('Extra Large', 'bricks'),
                    ],
                    'default' => 'md',
                ],
                'icon' => [
                    'label' => esc_html__('Icon', 'bricks'),
                    'type' => 'icon',
                ],
            ],
            'group' => 'content',
        ];
    }

    public function render()
    {
        $this->set_attribute('_root', 'class', 'brxe-dvly-call-to-action');
        $s = $this->settings;

        echo '<section ' . $this->render_attributes('_root') . '>';
        echo '<div class="brxe-container brxe-dvly-call-to-action-container">';

        echo '<div class="brxe-dvly-call-to-action-content">';

        if (!empty($s['cta_above_title'])) {
            echo '<h6 class="brxe-dvly-call-to-action-above-title">' . esc_html($s['cta_above_title']) . '</h6>';
        }
        if (!empty($s['cta_title'])) {
            echo '<h2 class="brxe-dvly-call-to-action-title">' . esc_html($s['cta_title']) . '</h2>';
        }
        if (!empty($s['cta_description'])) {
            echo '<p class="brxe-dvly-call-to-action-description">' . esc_html($s['cta_description']) . '</p>';
        }

        if (!empty($s['cta_button']) && is_array($s['cta_button'])) {
            echo '<div class="brxe-dvly-call-to-action-buttons">';
            foreach ($s['cta_button'] as $index => $btn) {
                if (!empty($btn['cta_text']) && !empty($btn['cta_link'])) {
                    $classes = ['brxe-button', 'bricks-button'];
                    if (!empty($btn['cta_style'])) {
                        $classes[] = 'bricks-button-' . esc_attr($btn['cta_style']);
                    }
                    if (!empty($btn['cta_size'])) {
                        $classes[] = esc_attr($btn['cta_size']);
                    }

                    // Generate unique ID per button
                    $attr_id = 'cta_button_' . $index;
                    $this->set_link_attributes($attr_id, $btn['cta_link']);

                    echo '<a ' . $this->render_attributes($attr_id) . ' class="' . esc_attr(implode(' ', $classes)) . '">';
                    echo esc_html($btn['cta_text']);
                    if (!empty($btn['icon']['icon'])) {
                        echo '<i class="' . esc_attr($btn['icon']['icon']) . '"></i>';
                    }                    
                    echo '</a>';

                }
            }
            echo '</div>';
        }

        echo '</div>'; // .brxe-dvly-call-to-action-content
        echo '</div>'; // .brxe-container
        echo '</section>'; // .brxe-dvly-call-to-action
    }
}
