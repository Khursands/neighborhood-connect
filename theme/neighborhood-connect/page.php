<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="section">
  <div class="container" style="max-width:800px;">
    <h1 class="post-title" style="margin-bottom:1.5rem;"><?php the_title(); ?></h1>
    <div class="post-content"><?php the_content(); ?></div>
    <?php if (comments_open()) : ?>
      <?php comments_template(); ?>
    <?php endif; ?>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
