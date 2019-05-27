<?php
/*
Plugin Name: Restaurant
Description: Demo restaurant for block editor
Version: 1.0.0
Author: Zarko
Text Domain: restaurant
Domain Path: /languages
*/

// products post type
add_action('init', function() {

    register_post_type('product', [
        'label' => __('Products', 'restaurant'),
        'public' => true,
        'show_in_rest' => true,
        'template' => [
        ['core/paragraph', [
            'placeholder' => __('Product subtitle', 'restaurant')
        ]],
        ['core/columns', [], [
            ['core/column', [], [
            ['core/image', []]
            ]],
            ['core/column', [], [
            ['core/paragraph', [
                'placeholder' => 'Product description'
            ]]
            ]]
        ]],
        ['core/gallery']
        ]
    ]);

});

// including JS for the block and registering server side
add_action('init', function() {

    wp_register_script('restaurant', plugins_url('build/index.js', __FILE__), ['wp-blocks', 'wp-element', 'wp-components']);
    
    register_block_type('restaurant/related-products', [
        'attributes' => [
            'perPage' => [
                'type' => 'number',
                'default' => 8
            ],
            'algorithm' => [
                'type' => 'string',
                'default' => 'keyword'
            ]
        ],
        'render_callback' => function($attributes, $content) {
            
            global $post;
            ob_start();

            $args = [
                'post_type' => 'product',
                'posts_per_page' => $attributes['perPage'],
            ];

            $args['s'] = $attributes['algorithm'] == 'keyword' ? $post->post_title : '';

            $products = new WP_Query($args);

            if($products->have_posts()) {
                while($products->have_posts()) {
                    $products->the_post();
                    echo '<h4>' . get_the_title() . '</h4>';
                }
            } else {
                _e('No related products found.', 'restaurant');
            }

            $content = ob_get_clean();
            return $content;
        },
        'editor_script' => 'restaurant',
    ]);

});