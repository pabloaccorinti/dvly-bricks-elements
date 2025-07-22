<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class Brxe_Dvly_Featured_Product_Categories extends \Bricks\Element
{
    public $category = 'dvly-elements';
    public $name = 'dvly-featured-product-categories';
    public $icon = 'ti-layout-grid2';

    public function get_label()
    {
        return esc_html__('Featured Product Categories', 'bricks');
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
        $default_categories = $this->get_default_parent_categories();

        $this->controls['icon_features_alignment'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Alignment', 'bricks'),
            'type' => 'text-align',
            'css' => [
                [
                    'property' => 'text-align',
                    'selector' => '.brxe-dvly-featured-product-categories-container',
                ],
            ],
        ];

        // Content Controls
        $this->controls['section_title'] = [
            'tab' => 'content',
            'group' => 'content',
            'label' => esc_html__('Section Title', 'bricks'),
            'type' => 'text',
            'default' => esc_html__('Featured Categories', 'bricks'),
        ];

        $this->controls['section_subtitle'] = [
            'tab' => 'content',
            'group' => 'content',
            'label' => esc_html__('Section Subtitle', 'bricks'),
            'type' => 'textarea',
            'default' => esc_html__('Explore our top product categories.', 'bricks'),
        ];

        $this->controls['selected_categories'] = [
            'tab' => 'content',
            'group' => 'content',
            'label' => esc_html__('Select Categories', 'bricks'),
            'type' => 'select',
            'multiple' => true,
            'options' => $this->get_product_categories(),
            'default' => $default_categories,
            'description' => esc_html__('Select one or more product categories to display in this section.', 'bricks'),
        ];

        // Style Controls
        $this->controls['title_typography'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Title Typography', 'bricks'),
            'type' => 'typography',
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.brxe-dvly-featured-product-categories-title',
                ],
            ],
        ];

        $this->controls['subtitle_typography'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Subtitle Typography', 'bricks'),
            'type' => 'typography',
            'css' => [
                [
                    'property' => 'typography',
                    'selector' => '.brxe-dvly-featured-product-categories-subtitle',
                ],
            ],
        ];

        $this->controls['category_items_height'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Items height', 'bricks'),
            'type' => 'number',
            'unit' => 'px',
            'inline' => true,
            'css' => [
                [
                    'property' => 'grid-auto-rows',
                    'selector' => '.brxe-dvly-featured-product-categories-grid',
                ],
            ],
        ];

        $this->controls['full_width'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Full Width', 'bricks'),
            'type' => 'checkbox',
            'default' => false,
            'css' => [
                [
                    'property' => 'width',
                    'value' => '100%',
                    'selector' => '.brxe-dvly-featured-product-categories-container',
                    'condition' => ['full_width' => true],
                ],
                [
                    'property' => 'max-width',
                    'value' => '100%',
                    'selector' => '.brxe-dvly-featured-product-categories-container',
                    'condition' => ['full_width' => true],
                ],
            ],
        ];

        // Updated Gap Control (Using Bricks example)

        $this->controls['columns'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Number of Columns', 'bricks'),
            'type' => 'number',
            'min' => 1,
            'max' => 5,
            'step' => 1,
            'default' => 3,
            'inline' => true,
            'live' => true,
        ];

        $this->controls['gap_between_items'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Gap Between Items', 'bricks'),
            'type' => 'number',
            'unit' => 'px',
            'default' => '16',
            'inline' => true,
            'live' => true,
        ];

        $this->controls['overlay_color'] = [
            'tab' => 'content',
            'group' => 'appearance',
            'label' => esc_html__('Category overlay color', 'bricks'),
            'type' => 'color',
            'inline' => true,
            'css' => [
                [
                    'property' => 'background',
                    'selector' => '.brxe-dvly-featured-product-category-overlay',
                ]
            ]
        ];
    }

    private function get_product_categories()
    {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $options[$category->term_id] = $category->name;
            }
        }
        return $options;
    }

    private function get_default_parent_categories($limit = 3)
    {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => 0,
            'orderby' => 'name',
            'order' => 'ASC',
            'number' => $limit,
        ]);

        return (!is_wp_error($categories) && !empty($categories))
            ? wp_list_pluck($categories, 'term_id')
            : [];
    }

    public function render()
    {
        $settings = $this->settings ?? [];
        $section_title = !empty($settings['section_title']) ? esc_html($settings['section_title']) : '';
        $section_subtitle = !empty($settings['section_subtitle']) ? esc_html($settings['section_subtitle']) : '';
        $selected_categories = $settings['selected_categories'] ?? [];

        $columns = isset($settings['columns']) && is_numeric($settings['columns']) && $settings['columns'] > 0
            ? 'grid-template-columns: repeat(' . (int) $settings['columns'] . ', 1fr);'
            : '';

        $gap_value = $settings['gap_between_items'] ?? null;
        $grid_gap = is_numeric($gap_value) ? $gap_value . 'px' : 'var(--gap-s)';

        $args = ['taxonomy' => 'product_cat', 'hide_empty' => true];
        if (!empty($selected_categories)) {
            $args['include'] = $selected_categories;
        } else {
            $args['parent'] = 0;
        }

        $product_categories = get_terms($args);

        echo '<section ' . $this->render_attributes('_root') . '>';
        echo '<div class="brxe-dvly-featured-product-categories-container brxe-container">';

        if ($section_title || $section_subtitle) {
            echo '<div class="brxe-dvly-featured-product-categories-header">';
            if ($section_title) {
                echo '<h2 class="brxe-dvly-featured-product-categories-title">' . $section_title . '</h2>';
            }
            if ($section_subtitle) {
                echo '<p class="brxe-dvly-featured-product-categories-subtitle">' . $section_subtitle . '</p>';
            }
            echo '</div>';
        }

        $grid_style = $columns . ' gap: ' . $grid_gap . ';';
        if (!empty($settings['full_width'])) {
            $grid_style .= ' padding-left: ' . $grid_gap . '; padding-right: ' . $grid_gap . ';';
        }

        echo '<div class="brxe-dvly-featured-product-categories-grid" style="' . esc_attr($grid_style) . '">';

        if (!empty($product_categories) && !is_wp_error($product_categories)) {
            foreach ($product_categories as $category) {
                $category_name = esc_html($category->name);
                $category_link = get_term_link($category);
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $category_image = wp_get_attachment_url($thumbnail_id);

                echo '<div class="brxe-dvly-featured-product-category-item">';
                echo '<a href="' . esc_url($category_link) . '" class="brxe-dvly-featured-product-category-link">';
                if ($category_image) {
                    echo '<img src="' . esc_url($category_image) . '" alt="' . esc_attr($category_name) . '" class="brxe-dvly-featured-product-category-image">';
                }
                echo '<div class="brxe-dvly-featured-product-category-overlay"></div>';
                echo '<h4 class="brxe-dvly-featured-product-category-title">' . $category_name . '</h4>';
                echo '</a>';
                echo '</div>';
            }
        }

        echo '</div></div></section>';
    }
}
