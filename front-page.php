<?php get_header() ?>


<?php
$args = array(
  'post_type' => 'slide',
  'posts_per_page' => -1,
  'orderby' => 'date',
  'order' => 'ASC'
);
$slides = new WP_Query($args);


$show_services_block = get_theme_mod('show_services_block', true);
$services_block_count = get_theme_mod('services_block_count', 1);
$border_radius = get_theme_mod('services_border_radius', '10px');
$bg_color = get_theme_mod('services_bg_color', '#ffffff');
$shadow_x = get_theme_mod('services_shadow_x', '0px');
$shadow_y = get_theme_mod('services_shadow_y', '4px');
$shadow_blur = get_theme_mod('services_shadow_blur', '10px');
$shadow_spread = get_theme_mod('services_shadow_spread', '0px');
$shadow_color = get_theme_mod('services_shadow_color', 'rgba(0,0,0,0.3)');

if ($show_services_block):
  ?>
  <section
    style="box-shadow: <?php echo esc_attr(get_theme_mod('services_shadow_x', '0px')); ?> <?php echo esc_attr(get_theme_mod('services_shadow_y', '4px')); ?> <?php echo esc_attr(get_theme_mod('services_shadow_blur', '10px')); ?> <?php echo esc_attr(get_theme_mod('services_shadow_spread', '0px')); ?> <?php echo esc_attr(get_theme_mod('services_shadow_color', 'rgba(0,0,0,0.3)')); ?>;"
    class="h-auto custom-slider mb-25 mt-7 relative w-full overflow-hidden services-block <?php echo esc_attr(get_theme_mod('services_radius_class', 'rounded-xl')); ?> ">
    <div class="slider-track flex transition-transform duration-500">
      <?php while ($slides->have_posts()):
        $slides->the_post(); ?>
        <?php
        $desktop_img = get_post_meta(get_the_ID(), '_slide_desktop', true);
        $mobile_img = get_post_meta(get_the_ID(), '_slide_mobile', true);
        ?>
        <div class="w-full flex-shrink-0 relative">
          <picture>
            <?php if ($mobile_img): ?>
              <source srcset="<?php echo esc_url($mobile_img); ?>" media="(max-width: 768px)">
            <?php endif; ?>
            <?php if ($desktop_img): ?>
              <img src="<?php echo esc_url($desktop_img); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto">
            <?php else: ?>
              <?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
            <?php endif; ?>
          </picture>

          <div class="absolute bottom-3 left-3 bg-black/50 text-white text-sm px-3 py-1 rounded">
            <?php the_title(); ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- دکمه‌ها -->
    <button
      class="slider-prev absolute top-1/2 -translate-y-1/2 left-3 hover:bg-white text-gray-700 rounded-full p-2 z-30">
      ›
    </button>
    <button
      class="slider-next absolute top-1/2 -translate-y-1/2 right-3 hover:bg-white text-gray-700 rounded-full p-2 z-30">
      ‹
    </button>
  </section>

  <script>
    document.querySelectorAll(".custom-slider").forEach(sliderContainer => {
      const slider = sliderContainer.querySelector(".slider-track");
      const slides = slider.children.length;
      let index = 0;

      function showSlide(i) {
        index = (i + slides) % slides;
        slider.style.transform = `translateX(-${index * 100}%)`;
      }

      sliderContainer.querySelector(".slider-next").onclick = () => showSlide(index + 1);
      sliderContainer.querySelector(".slider-prev").onclick = () => showSlide(index - 1);

      setInterval(() => showSlide(index + 1), 4000);
    });
  </script>


  <?php
  // فراخوانی گردونه دسته‌بندی
  get_template_part('woocommerce/parts/category-carousel');
  ?>


  <?php
  // نمایش یا مخفی کردن بنرها با توجه به تنظیمات سفارشی‌سازی
  if (get_theme_mod('show_banners', true)) {
    get_template_part('template-parts/banner-front-page-t');
  }
  ?>



  <!-- محصولات -->
  <?php
  $args = array(
    'status' => 'publish',
    'limit' => 10, // چند محصول
    'orderby' => 'date',
    'order' => 'DESC',
  );

  $products = wc_get_products($args);
  ?>

  <div class="relative overflow-visible mt-10   ">
    <!-- هدر محصول -->
    <div class="flex items-center justify-center mb-4 mt-8">
      <h2 class="text-3xl text-gray-700 font-bold">محصولات لوپ مارکت</h2>
    </div>
    <button id="nextBtn"
      class="absolute  right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow p-2 hover:bg-gray-200">
      ‹
    </button>

    <div id="slider"
      class="flex mx-5 max-w-6xl mx-auto gap-7 overflow-x-auto overflow-y-visible scroll-smooth snap-x snap-mandatory rounded-xl  pb-10 pt-6 "
      style="scrollbar-width: none;">
      <?php foreach ($products as $product):
        setup_postdata($GLOBALS['post'] =& $product->get_id()); ?>
        <?php wc_get_template_part('content', 'product'); ?>
      <?php endforeach; ?>
    </div>

    <button id="prevBtn"
      class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow p-2 hover:bg-gray-200">
      ›
    </button>
  </div>
  <script>
    const slider = document.getElementById("slider");
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");

    function step() {
      const first = slider.querySelector(".slide");
      if (!first) return 300;
      const rect = first.getBoundingClientRect();
      const gap = parseFloat(getComputedStyle(slider).gap) || 0;
      return rect.width + gap;
    }

    nextBtn.addEventListener("click", () => {
      slider.scrollBy({ left: step(), behavior: "smooth" });
    });
    prevBtn.addEventListener("click", () => {
      slider.scrollBy({ left: -step(), behavior: "smooth" });
    });
  </script>



  <!-- باکس پیشنهاد شگفت انگیز -->
  <?php
  get_template_part('woocommerce/amazingoffer-box');
  ?>



  <div class="relative overflow-visible   ">
    <button id="nextBtn"
      class="absolute  right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow p-2 hover:bg-gray-200">
      ‹
    </button>

    <div id="slider"
      class="flex mx-5 max-w-6xl mx-auto gap-7 overflow-x-auto overflow-y-visible scroll-smooth snap-x snap-mandatory rounded-xl  pb-10 pt-6 "
      style="scrollbar-width: none;">
      <?php foreach ($products as $product):
        setup_postdata($GLOBALS['post'] =& $product->get_id()); ?>
        <?php wc_get_template_part('content', 'product'); ?>
      <?php endforeach; ?>
    </div>

    <button id="prevBtn"
      class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full shadow p-2 hover:bg-gray-200">
      ›
    </button>
  </div>

  <?php
  //فراخوانی بنر
  get_template_part('template-parts/banner-front-page-b');
  ?>




  <script>
    const slider = document.getElementById("slider");
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");

    function step() {
      const first = slider.querySelector(".slide");
      if (!first) return 300;
      const rect = first.getBoundingClientRect();
      const gap = parseFloat(getComputedStyle(slider).gap) || 0;
      return rect.width + gap;
    }

    nextBtn.addEventListener("click", () => {
      slider.scrollBy({ left: step(), behavior: "smooth" });
    });
    prevBtn.addEventListener("click", () => {
      slider.scrollBy({ left: -step(), behavior: "smooth" });
    });
  </script>
  <main id="main" class="site-main ">
    <?php
    if (have_posts()) {
      while (have_posts()) {
        the_post();
        //the_title('<h2>', '</h2>');
        //the_content();
        //the_post_thumbnail();
      }
    } else {
      echo '<p>No content found.</p>';
    }
    ?>
  </main>

  <?php
endif;
wp_reset_postdata();
?>
<?php get_footer() ?>