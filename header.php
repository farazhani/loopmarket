<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">

<head>
  <script src="https://unpkg.com/alpinejs" defer></script>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <style>
    :root {
      --primary-color:
        <?php echo get_theme_mod('primary_color', '#1bb8d0'); ?>
      ;
      --secondary-color-1:
        <?php echo get_theme_mod('secondary_color1', '#50de49'); ?>
      ;

      /* نسخه شفاف‌تر سایه */
      --secondary-color-1-shadow:
        <?php
        $color = get_theme_mod('secondary_color1', '#50de49');
        // اگه HEX بود به RGBA تبدیلش کن
        if (preg_match('/^#([a-f0-9]{6})$/i', $color, $matches)) {
          $hex = $matches[1];
          $r = hexdec(substr($hex, 0, 2));
          $g = hexdec(substr($hex, 2, 2));
          $b = hexdec(substr($hex, 4, 2));
          echo "rgba($r, $g, $b, 0.5)"; // اینجا 0.4 یعنی ۴۰٪ opacity
        } else {
          echo $color; // fallback
        }
        ?>
      ;

      --secondary-color-2:
        <?php echo get_theme_mod('secondary_color2', '#ffaa00'); ?>
      ;

      --amazing-box-color1:
        <?php echo get_theme_mod('amazing_box_color1', '#0094b9'); ?>
      ;
      --amazing-box-color2:
        <?php echo get_theme_mod('amazing_box_color2', '#9eddec'); ?>
      ;

    }
  </style>


  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

  <?php wp_head(); ?>
</head>

<body dir="rtl" <?php body_class("bg-gray-100 max-w-screen-xl mx-auto sm:px-4"); ?>>
  <header x-data="{ open: false }"
    class="sticky left-0 right-0 lg:top-8 top-0 z-50 shadow-md bg-white  py-6 mb-4 my-6 rounded-xl">

    <div class="container flex justify-center md:gap-30 gap-20 max-w-screen-lg mx-auto flex items-center px-4">


      <!-- دکمه همبرگری (فقط موبایل) -->
      <button class="md:hidden cursor-pointer" @click="open = !open" :aria-expanded="open" aria-controls="mobile-menu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="w-7 h-7">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>

      <!-- لوگو -->
      <div class="shrink-0 w-24 md:w-32 md:justify-betwean">
        <?php if (function_exists("the_custom_logo"))
          the_custom_logo(); ?>
      </div>

      <!-- منو دسکتاپ -->
      <nav class="hidden md:flex flex-1 justify-center">
        <?php wp_nav_menu([
          "theme_location" => 'Header',
          "menu_class" => "main-nav flex items-center gap-6",
          "container" => false
        ]); ?>
      </nav>

      <!-- آیکون‌ها + دکمه همبرگر -->

      <div class="flex gap-3">

        <a href="<?php echo site_url('/?page_id=99'); ?>" class="relative inline-flex items-center">
          <!-- لاگین -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="#47505d"
            stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.33 0-6 2.67-6 6h12c0-3.33-2.67-6-6-6z" />
          </svg>
        </a>

        <!-- سبد خرید -->
        <?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
        <a href="<?php echo wc_get_cart_url(); ?>" class="relative inline-flex items-center ">
          <!-- SVG سبد خرید -->
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M2 3L2.26491 3.0883C3.58495 3.52832 4.24497 3.74832 4.62248 4.2721C5 4.79587 5 5.49159 5 6.88304V9.5C5 12.3284 5 13.7426 5.87868 14.6213C6.75736 15.5 8.17157 15.5 11 15.5H19"
              stroke="#47505D" stroke-width="1.5" stroke-linecap="round" />
            <path
              d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z"
              stroke="#47505D" stroke-width="1.5" />
            <path
              d="M16.5 18C17.3284 18 18 18.6715 18 19.5C18 20.3284 17.3284 21 16.5 21C15.6716 21 15 20.3284 15 19.5C15 18.6715 15.6716 18 16.5 18Z"
              stroke="#47505D" stroke-width="1.5" />
            <path
              d="M5 6H16.4504C18.5054 6 19.5328 6 19.9775 6.67426C20.4221 7.34853 20.0173 8.29294 19.2078 10.1818L18.7792 11.1818C18.4013 12.0636 18.2123 12.5045 17.8366 12.7523C17.4609 13 16.9812 13 16.0218 13H5"
              stroke="#47505D" stroke-width="1.5" />
          </svg>
          <?php if ($count > 0): ?>
            <span
              class="absolute -top-2 -right-2 inline-flex items-center justify-center p-[3px] text-xs text-white bg-red-600 rounded-full">
              <?php echo esc_html($count); ?>
            </span>
          <?php endif; ?>
        </a>
      </div>
    </div>

    <!-- منوی موبایل -->
    <div x-show="open" x-cloak @click.self="open = false" x-transition.opacity
      class="fixed inset-0 bg-black/50 z-50 md:hidden">
      <!-- پنل کشویی -->
      <div id="mobile-menu" x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute right-0 top-0 h-full w-64 bg-white shadow-lg p-6 flex flex-col gap-6"
        @keydown.escape.window="open = false">
        <!-- دکمه بستن -->
        <button class="self-end mb-4 cursor-pointer" @click="open = false" aria-label="بستن منو">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- منوی وردپرس -->
        <?php wp_nav_menu([
          "theme_location" => 'Header',
          "menu_class" => "wp-nav-menu flex flex-col gap-4",
          "container" => false
        ]); ?>
      </div>
    </div>
  </header>