<?php

// 1. Using hooks
// include('functions-woohooks.php');
// 2. Using template override
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close');
add_filter('woocommerce_enqueue_styles', '__return_false');




function mytheme_setup()
{
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');

  add_theme_support('custom-logo');

  add_theme_support('woocommerce');

  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');

  add_theme_support('woocommerce', array(
    'thumbnail_image_width' => 350,
    'single_image_width' => 500,
  ));

  register_nav_menus(["Header" => "Header Menu"]);
}
add_action('after_setup_theme', 'mytheme_setup');

add_action('customize_register', function ($wp_customize) {
  // Section
  $wp_customize->add_section('hodcode_social_links', [
    'title' => __('Social Media Links', 'hodcode'),
    'priority' => 30,
  ]);

  // Facebook
  $wp_customize->add_setting('hodcode_facebook', [
    'default' => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('hodcode_facebook', [
    'label' => __('Facebook URL', 'hodcode'),
    'section' => 'hodcode_social_links',
    'type' => 'url',
  ]);

  // Twitter
  $wp_customize->add_setting('hodcode_twitter', [
    'default' => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('hodcode_twitter', [
    'label' => __('Twitter URL', 'hodcode'),
    'section' => 'hodcode_social_links',
    'type' => 'url',
  ]);

  // LinkedIn
  $wp_customize->add_setting('hodcode_linkedin', [
    'default' => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'esc_url_raw',
  ]);
  $wp_customize->add_control('hodcode_linkedin', [
    'label' => __('LinkedIn URL', 'hodcode'),
    'section' => 'hodcode_social_links',
    'type' => 'url',
  ]);
});




function hodcode_enqueue_styles()
{
  wp_enqueue_style(
    'hodcode-style', // Handle name
    get_stylesheet_uri(), // This gets style.css in the root of the theme

  );
  wp_enqueue_style(
    'hodcode-webfont', // Handle name
    get_template_directory_uri() . "/assets/fontiran.css", // This gets style.css in the root of the theme

  );
  wp_enqueue_script(
    'tailwind', // Handle name
    "https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4", // This gets style.css in the root of the theme

  );
}
add_action('wp_enqueue_scripts', 'hodcode_enqueue_styles');


add_action('init', function () {

  // register_taxonomy('product_category', ['product'], [
  //   'hierarchical'      => true,
  //   'labels'            => [
  //     'name'          => ('Product Categories'),
  //     'singular_name' => 'Product Category'
  //   ],
  //   'rewrite'           => ['slug' => 'product-category'],
  //   'show_in_rest' => true,

  // ]);

  // register_post_type('product', [
  //   'public' => true,
  //   'label'  => 'Products',

  // //   'rewrite' => ['slug' => 'product'],
  // //   'taxonomies' => ['product_category'],

  //   'supports' => [
  //     'title',
  //     'editor',
  //     'thumbnail',
  //     'excerpt',
  //     'custom-fields',
  //   ],

  //   'show_in_rest' => true,
  // ]);
});

// hodcode_add_custom_field("price","product","Price (Final)");
// hodcode_add_custom_field("old_price","product","Price (Before)");

// add_action('pre_get_posts', function ($query) {
//   if ($query->is_home() && $query->is_main_query() && !is_admin()) {
//     $query->set('post_type', 'product');
//   }
// });

function toPersianNumerals($input)
{
  // English digits
  $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

  // Persian digits
  $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

  // Replace and return
  return str_replace($english, $persian, (string) $input);
}

function hodcode_add_custom_field($fieldName, $postType, $title)
{
  add_action('add_meta_boxes', function () use ($fieldName, $postType, $title) {
    add_meta_box(
      $fieldName . '_bx`ox',
      $title,
      function ($post) use ($fieldName) {
        $value = get_post_meta($post->ID, $fieldName, true);
        wp_nonce_field($fieldName . '_nonce', $fieldName . '_nonce_field');
        echo '<input type="text" style="width:100%"
         name="' . esc_attr($fieldName) . '" value="' . esc_attr($value) . '">';
      },
      $postType,
      'normal',
      'default'
    );
  });

  add_action('save_post', function ($post_id) use ($fieldName) {
    // checks
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return;
    if (!isset($_POST[$fieldName . '_nonce_field']))
      return;
    if (!wp_verify_nonce($_POST[$fieldName . '_nonce_field'], $fieldName . '_nonce'))
      return;
    if (!current_user_can('edit_post', $post_id))
      return;
    // save
    if (isset($_POST[$fieldName])) {
      $san = sanitize_text_field(wp_unslash($_POST[$fieldName]));
      update_post_meta($post_id, $fieldName, $san);
    } else {
      delete_post_meta($post_id, $fieldName);
    }
  });
}


// ثبت پست تایپ اسلاید
function create_slider_post_type()
{
  $labels = array(
    'name' => 'اسلایدها',
    'singular_name' => 'اسلاید',
    'add_new' => 'افزودن اسلاید',
    'add_new_item' => 'افزودن اسلاید جدید',
    'edit_item' => 'ویرایش اسلاید',
    'new_item' => 'اسلاید جدید',
    'view_item' => 'مشاهده اسلاید',
    'search_items' => 'جستجوی اسلاید',
    'not_found' => 'اسلایدی یافت نشد',
    'menu_name' => 'اسلایدها'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'menu_icon' => 'dashicons-images-alt2',
    'supports' => array('title', 'thumbnail'), // فقط عنوان + تصویر شاخص
    'has_archive' => false,
    'rewrite' => array('slug' => 'slides'),
  );

  register_post_type('slide', $args);
}
add_action('init', 'create_slider_post_type');




// افزودن متاباکس برای تصاویر موبایل و دسکتاپ
function slide_add_custom_meta_box()
{
  add_meta_box(
    'slide_images',
    'تصاویر اسلایدر (موبایل و دسکتاپ)',
    'slide_images_callback',
    'slide',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'slide_add_custom_meta_box');

function slide_images_callback($post)
{
  wp_nonce_field('save_slide_images', 'slide_images_nonce');

  $desktop_img = get_post_meta($post->ID, '_slide_desktop', true);
  $mobile_img = get_post_meta($post->ID, '_slide_mobile', true);
  ?>
  <p>
    <label for="slide_desktop">تصویر دسکتاپ:</label><br>
    <input type="text" id="slide_desktop" name="slide_desktop" value="<?php echo esc_attr($desktop_img); ?>"
      style="width:80%;" />
    <button class="button slide-upload" data-target="slide_desktop">انتخاب تصویر</button>
  </p>
  <p>
    <label for="slide_mobile">تصویر موبایل:</label><br>
    <input type="text" id="slide_mobile" name="slide_mobile" value="<?php echo esc_attr($mobile_img); ?>"
      style="width:80%;" />
    <button class="button slide-upload" data-target="slide_mobile">انتخاب تصویر</button>
  </p>

  <script>
    jQuery(document).ready(function ($) {
      var frame;
      $('.slide-upload').on('click', function (e) {
        e.preventDefault();
        var target = $(this).data('target');

        if (frame) {
          frame.open();
          return;
        }

        frame = wp.media({
          title: 'انتخاب تصویر',
          button: { text: 'استفاده از این تصویر' },
          multiple: false
        });

        frame.on('select', function () {
          var attachment = frame.state().get('selection').first().toJSON();
          $('#' + target).val(attachment.url);
        });

        frame.open();
      });
    });
  </script>
  <?php
}


// ذخیره متادیتا
function save_slide_images($post_id)
{
  if (!isset($_POST['slide_images_nonce']) || !wp_verify_nonce($_POST['slide_images_nonce'], 'save_slide_images')) {
    return;
  }

  if (array_key_exists('slide_desktop', $_POST)) {
    update_post_meta($post_id, '_slide_desktop', sanitize_text_field($_POST['slide_desktop']));
  }

  if (array_key_exists('slide_mobile', $_POST)) {
    update_post_meta($post_id, '_slide_mobile', sanitize_text_field($_POST['slide_mobile']));
  }
}
add_action('save_post', 'save_slide_images');






// ثبت پست تایپ بنر
function register_banners_cpt()
{
  $labels = array(
    'name' => 'بنرها',
    'singular_name' => 'بنر',
    'menu_name' => 'بنرها',
    'name_admin_bar' => 'بنر',
    'add_new' => 'افزودن بنر',
    'add_new_item' => 'بنر جدید',
    'new_item' => 'بنر جدید',
    'edit_item' => 'ویرایش بنر',
    'view_item' => 'نمایش بنر',
    'all_items' => 'همه بنرها',
    'search_items' => 'جستجوی بنر',
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'show_ui' => true,
    'menu_icon' => 'dashicons-images-alt2',
    'supports' => array('title', 'thumbnail'),
    'has_archive' => false,
    'rewrite' => array('slug' => 'banners'),
  );

  register_post_type('banner', $args);

  // ثبت دسته بندی برای بنرها
  register_taxonomy(
    'banner_category',
    'banner',
    array(
      'label' => 'دسته‌بندی بنرها',
      'hierarchical' => true,
      'rewrite' => array('slug' => 'banner-category'),
    )
  );
}
add_action('init', 'register_banners_cpt');



// // منو موبایل
// function theme_enqueue_alpine(){
//   wp_enqueue_script('alpinejs','https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js', array(), null, true);
// }
// add_action('wp_enqueue_scripts','theme_enqueue_alpine');



// سفارشی سازی رنگ سایت
function mytheme_customize_register($wp_customize)
{
  // رنگ اصلی
  $wp_customize->add_setting('primary_color', array(
    'default' => '#0073aa',
    'transport' => 'refresh',
  ));

  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color_control', array(
    'label' => __('Primary Color', 'mytheme'),
    'section' => 'colors',
    'settings' => 'primary_color',
  )));

  // رنگ فرعی 1
  $wp_customize->add_setting('secondary_color1', array(
    'default' => '#1bb8d0',
    'transport' => 'refresh',
  ));

  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color1_control', array(
    'label' => __('Secondary Color 1', 'mytheme'),
    'section' => 'colors',
    'settings' => 'secondary_color1',
  )));

  // رنگ فرعی 2
  $wp_customize->add_setting('secondary_color2', array(
    'default' => '#50de49',
    'transport' => 'refresh',
  ));

  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color2_control', array(
    'label' => __('Secondary Color 2', 'mytheme'),
    'section' => 'colors',
    'settings' => 'secondary_color2',
  )));


  // رنگ باکس شگفت انگیز 
// رنگ اول
  $wp_customize->add_setting('amazing_box_color1', array(
    'default' => '#0094b9',
    'transport' => 'refresh',
  ));

  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'amazing_box_color1_control', array(
    'label' => __('Amazing Box Color 1', 'mytheme'),
    'section' => 'colors',
    'settings' => 'amazing_box_color1',
  )));

  // رنگ دوم
  $wp_customize->add_setting('amazing_box_color2', array(
    'default' => '#9eddec',
    'transport' => 'refresh',
  ));

  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'amazing_box_color2_control', array(
    'label' => __('Amazing Box Color 2', 'mytheme'),
    'section' => 'colors',
    'settings' => 'amazing_box_color2',
  )));

}
add_action('customize_register', 'mytheme_customize_register');



?>


<?php
function mytheme_customize_register_blocks($wp_customize)
{
  $wp_customize->add_panel('blocks_panel', array(
    'title' => __('تنظیمات بلوک‌ها', 'mytheme'),
    'priority' => 30,
  ));

  $wp_customize->add_section('homepage_services', array(
    'title' => __('اسلایدر', 'mytheme'),
    'panel' => 'blocks_panel',
    'priority' => 10,
  ));

  $wp_customize->add_setting('show_services_block', array(
    'default' => true,
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('show_services_block', array(
    'label' => __('نمایش اسلایدر', 'mytheme'),
    'section' => 'homepage_services',
    'type' => 'checkbox',
  ));


  // 3. Border Radius
  $wp_customize->add_setting('services_radius_class', [
    'default' => 'rounded-xl',
    'transport' => 'postMessage',
  ]);
  $wp_customize->add_control('services_radius_class', [
    'label' => __('Border Radius اسلایدر', 'mytheme'),
    'section' => 'homepage_services',
    'type' => 'select',
    'choices' => [
      'rounded-none' => 'سایز 1',
      'rounded-sm' => 'سایز 2',
      'rounded-md' => 'سایز 3',
      'rounded-lg' => 'سایز 4',
      'rounded-xl' => 'سایز 5',
      'rounded-2xl' => 'سایز 6',
      'rounded-3xl' => 'سایز 7',
      'rounded-4xl' => 'سایز 8',
    ],
  ]);

  // 4. Shadow X
  $wp_customize->add_setting('services_shadow_x', array(
    'default' => '0px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('services_shadow_x', array(
    'label' => __('سایه X', 'mytheme'),
    'section' => 'homepage_services',
    'type' => 'text',
  ));

  // Shadow Y
  $wp_customize->add_setting('services_shadow_y', array(
    'default' => '4px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('services_shadow_y', array(
    'label' => __('سایه Y', 'mytheme'),
    'section' => 'homepage_services',
    'type' => 'text',
  ));

  // Shadow Blur
  $wp_customize->add_setting('services_shadow_blur', array(
    'default' => '10px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('services_shadow_blur', array(
    'label' => __('میزان بلور سایه', 'mytheme'),
    'section' => 'homepage_services',
    'type' => 'text',
  ));

  // Shadow Spread
  $wp_customize->add_setting('services_shadow_spread', array(
    'default' => '0px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('services_shadow_spread', array(
    'label' => __('میزان گسترش سایه', 'mytheme'),
    'section' => 'homepage_services',
    'type' => 'text',
  ));

  // Shadow Color
  $wp_customize->add_setting('services_shadow_color', array(
    'default' => 'rgba(0,0,0,0.3)',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_shadow_color', array(
    'label' => __('رنگ سایه', 'mytheme'),
    'section' => 'homepage_services',
  )));

  // 5. رنگ پس‌زمینه
  $wp_customize->add_setting('services_bg_color', array(
    'default' => '#ffffff',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_bg_color', array(
    'label' => __('رنگ پس‌زمینه', 'mytheme'),
    'section' => 'homepage_services',
  )));

}
add_action('customize_register', 'mytheme_customize_register_blocks');


function mytheme_customize_add_blocks2($wp_customize)
{
  $section_id = 'homepage_blocks2';

  $wp_customize->add_section($section_id, array(
    'title' => __('دسته بندی محصولات', 'mytheme'),
    'panel' => 'blocks_panel',
    'priority' => 20,
  ));

  // نمایش / مخفی کردن بلوک
  $wp_customize->add_setting('show_blocks2', array(
    'default' => true,
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('show_blocks2', array(
    'label' => __('نمایش دسته یندی ها', 'mytheme'),
    'section' => $section_id,
    'type' => 'checkbox',
  ));

  // Border Radius
  $wp_customize->add_setting('blocks2_radius_class', [
    'default' => 'rounded-lg',
    'transport' => 'postMessage',
  ]);
  $wp_customize->add_control('blocks2_radius_class', [
    'label' => __('Border Radius دسته بندی ها', 'mytheme'),
    'section' => $section_id,
    'type' => 'select',
    'choices' => [
      'rounded-none' => 'سایز 1',
      'rounded-sm' => 'سایز 2',
      'rounded-md' => 'سایز 3',
      'rounded-lg' => 'سایز 4',
      'rounded-xl' => 'سایز 5',
      'rounded-2xl' => 'سایز 6',
      'rounded-3xl' => 'سایز 7',
      'rounded-4xl' => 'سایز 8',
    ],
  ]);

  // Shadow X
  $wp_customize->add_setting('blocks2_shadow_x', array(
    'default' => '0px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('blocks2_shadow_x', array(
    'label' => __('سایه X', 'mytheme'),
    'section' => $section_id,
    'type' => 'text',
  ));

  // Shadow Y
  $wp_customize->add_setting('blocks2_shadow_y', array(
    'default' => '4px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('blocks2_shadow_y', array(
    'label' => __('سایه Y', 'mytheme'),
    'section' => $section_id,
    'type' => 'text',
  ));

  // Shadow Blur
  $wp_customize->add_setting('blocks2_shadow_blur', array(
    'default' => '10px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('blocks2_shadow_blur', array(
    'label' => __('میزان بلور سایه', 'mytheme'),
    'section' => $section_id,
    'type' => 'text',
  ));

  //  Shadow Spread
  $wp_customize->add_setting('blocks2_shadow_spread', array(
    'default' => '0px',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('blocks2_shadow_spread', array(
    'label' => __('میزان گسترش سایه', 'mytheme'),
    'section' => $section_id,
    'type' => 'text',
  ));

  // Shadow Color
  $wp_customize->add_setting('blocks2_shadow_color', array(
    'default' => 'rgba(0,0,0,0.3)',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'blocks2_shadow_color', array(
    'label' => __('رنگ سایه', 'mytheme'),
    'section' => $section_id,
  )));

  // رنگ پس‌زمینه
  $wp_customize->add_setting('blocks2_bg_color', array(
    'default' => '#f0f0f0',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'blocks2_bg_color', array(
    'label' => __('رنگ پس‌زمینه بلوک 2', 'mytheme'),
    'section' => $section_id,
  )));
}
// add_action('customize_register', 'mytheme_customize_add_blocks2');


add_action('customize_register', function ($wp_customize) {
  // اگر پنل وجود ندارد بساز
  if (!isset($wp_customize->blocks_panel)) {
    $wp_customize->add_panel('blocks_panel', array(
      'title' => __('تنظیمات بلوک‌ها', 'mytheme'),
      'priority' => 30,
    ));
  }

  mytheme_customize_add_blocks2($wp_customize);
});



// تنظیمات بنرها
function mytheme_customize_add_banners($wp_customize)
{
  $section_id = 'homepage_banners';

  $wp_customize->add_section($section_id, array(
    'title' => __('بنرها', 'mytheme'),
    'panel' => 'blocks_panel', // همون پنل اصلی بلوک‌ها
    'priority' => 30,
  ));

  // نمایش / مخفی کردن
  $wp_customize->add_setting('show_banners', array(
    'default' => true,
    'transport' => 'postMessage',
  ));

  $wp_customize->add_control('show_banners', array(
    'label' => __('نمایش بنرها', 'mytheme'),
    'section' => $section_id,
    'type' => 'checkbox',
  ));
}
add_action('customize_register', 'mytheme_customize_add_banners');




// فعال کردن Live Preview
function mytheme_customize_preview_js()
{
  wp_enqueue_script('mytheme-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview', 'jquery'), null, true);
}
add_action('customize_preview_init', 'mytheme_customize_preview_js');


// فعال کردن Live Preview
function mytheme_customize_preview_js2()
{
  wp_enqueue_script(
    'mytheme-customizer',
    get_template_directory_uri() . '/js/customizer.js',
    array('customize-preview', 'jquery'),
    null,
    true
  );
}
add_action('customize_preview_init', 'mytheme_customize_preview_js');

?>