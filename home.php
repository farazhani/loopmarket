<?php get_header(); ?>

<div class=" px-4 py-10 ">

  <h1 class="text-3xl font-bold text-center text-gray-800 mb-10"> بلاگ‌های لوپ مارکت</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (have_posts()):
      while (have_posts()):
        the_post(); ?>
        <div
          class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
          <?php if (has_post_thumbnail()): ?>
            <a href="<?php the_permalink(); ?>" class="hover:text-[var(--secondary-color-1)] transition">
              <img src="<?php the_post_thumbnail_url('medium') ?>" alt="<?php the_title(); ?>"
                class="w-full h-auto object-cover rounded-t-xl shadow-sm">
            </a>
          <?php endif; ?>
          <div class="p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
              <a href="<?php the_permalink(); ?>"
                class="hover:text-[var(--secondary-color-1)] transition"><?php the_title(); ?></a>
            </h2>
            <p class="text-gray-600 text-sm"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
          </div>
        </div>
      <?php endwhile; endif; ?>
  </div>
</div>
<?php get_footer(); ?>