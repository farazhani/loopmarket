<?php
/**
 * Template Name: درباره ما
 * Template Post Type: page
 */
get_header();
?>

<?php
$args = array(
  'post_type' => 'banner',
  'posts_per_page' => 1, // فقط 1 تا بنر
  'tax_query' => array(
    array(
      'taxonomy' => 'banner_category',
      'field' => 'slug',
      'terms' => 'about', // اسم دسته (نامک slug دسته)
    ),
  ),
);
$banners = new WP_Query($args);
?>

<div class="container mx-auto px-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

    <!-- ستون عکس (چپ) -->
    <div class="order-1 md:order-2">
      <?php
      if ($banners->have_posts()):
        while ($banners->have_posts()):
          $banners->the_post(); ?>
          <div class=" w-full">
            <a href="#">
              <?php the_post_thumbnail('full', array('class' => 'w-full my-3 h-auto rounded-xl')); ?>
            </a>
          </div>
        <?php endwhile;
        wp_reset_postdata();
      else:
        echo "<p>هیچ بنری پیدا نشد</p>";
      endif;
      ?>


    </div>

    <!-- ستون متن (راست) -->
    <div class="order-2 md:order-1 text-right">
      <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
      <div class="prose prose-lg text-gray-700 leading-relaxed text-justify">
        <?php
        while (have_posts()):
          the_post();
          the_content();
        endwhile;
        ?>
      </div>
    </div>

  </div>
</div>

<?php get_footer(); ?>