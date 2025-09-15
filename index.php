  <?php get_header() ?>


<?php
$args = array(
  'post_type'      => 'slide',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'ASC'
);
$slides = new WP_Query($args);

if ($slides->have_posts()) :
?>









      <main id="main" class="site-main ">
      <?php
      if (have_posts()) {
        while (have_posts()) {
          the_post();
          the_title('<h2>', '</h2>');
          the_content();
          the_post_thumbnail();
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

