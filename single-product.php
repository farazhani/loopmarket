<?php get_header(); ?>
<?php
defined('ABSPATH') || exit;

global $product; // شیء محصول جاری
?>

<?php
// گرفتن محصولات مرتبط بر اساس دسته
$terms = wp_get_post_terms(get_the_ID(), 'product_cat');
if (!empty($terms)) {
  $related = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 3,
    'post__not_in' => [get_the_ID()],
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => wp_list_pluck($terms, 'term_id'),
      ]
    ]
  ]);

  if ($related->have_posts()) {
    echo '<ul class="space-y-3 text-gray-600 mb-8">';
    while ($related->have_posts()) {
      $related->the_post();
      echo '<li class="flex items-center">';
      if (has_post_thumbnail()) {
        the_post_thumbnail('thumbnail', ['class' => 'w-10 h-auto mx-2 hover:underline']);
      }
      echo '<p>' . esc_html(get_the_title()) . '</p></li><hr>';
    }
    echo '</ul>';
    wp_reset_postdata();
  }
}
?>

<div class="bg-white shadow-md rounded-xl mx-auto p-6 grid md:grid-cols-2 gap-8">

  <!-- ستون تصویر -->
  <div>
    <?php if (has_post_thumbnail()) {
      the_post_thumbnail('medium', ['class' => 'rounded-xl shadow-md w-auto m-auto']);
    } ?>
  </div>

  <!-- ستون متن -->
  <div class="flex flex-col justify-center">

    <!-- مسیر -->
    <p class="text-gray-500 text-sm mb-4">
      <?php woocommerce_breadcrumb(); ?>
    </p>

    <!-- عنوان -->
    <h1 class="text-2xl font-bold text-gray-800 mb-3">
      <?php the_title(); ?>
    </h1>

    <!-- قیمت -->
    <p class="text-pink-500 text-xl font-extrabold mb-4">
      <?php echo wp_kses_post($product->get_price_html()); ?>
    </p>

    <!-- فرم افزودن به سبد خرید -->
    <form class=" items-center gap-4 mb-6"
      action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
      method="post" enctype="multipart/form-data">

      <!-- بخش تعداد -->
      <div class="flex items-center border w-27 mb-4 rounded-lg text-gray-500 border border-gray-500 overflow-hidden">
        <button type="button" class="px-3 py-1 hover:bg-gray-100" onclick="changeQty(1)">+</button>
        <input type="number" id="quantity" name="quantity" value="1" min="1"
          class="w-12 text-center border-none focus:ring-0" />
        <button type="button" class="px-3 py-1 hover:bg-gray-100" onclick="changeQty(-1)">-</button>
      </div>

      <!-- دکمه افزودن به سبد خرید -->
      <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow">
        افزودن به سبد خرید
      </button>

    </form>

    <script>
      function changeQty(num) {
        let inputEl = document.getElementById("quantity");
        let current = parseInt(inputEl.value) || 1;
        let updated = current + num;
        if (updated < 1) updated = 1;
        inputEl.value = updated;
      }
    </script>


    <!-- توضیحات -->
    <div class="prose max-w-none text-gray-700">
      <?php the_content(); ?>
    </div>

  </div>
</div>

<?php get_footer(); ?>