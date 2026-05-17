<?php get_header(); ?>

<div class="section">
  <div class="container">

    <div class="section-header" style="text-align:left;margin-bottom:2rem;">
      <?php the_archive_title('<h1 class="section-title">', '</h1>'); ?>
      <?php the_archive_description('<p class="section-description" style="max-width:none;margin:0;">', '</p>'); ?>
    </div>

    <div class="card-grid">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php
          $type = get_post_type();
          if ($type === 'nc_event') get_template_part('template-parts/content', 'event');
          elseif ($type === 'nc_service') get_template_part('template-parts/content', 'service');
          else get_template_part('template-parts/content', 'post');
          ?>
        <?php endwhile; ?>
      <?php else : ?>
        <div style="grid-column:1/-1;text-align:center;padding:3rem 0;">
          <p style="color:var(--color-text-muted);"><?php esc_html_e('Nothing found here.', 'neighborhood-connect'); ?></p>
        </div>
      <?php endif; ?>
    </div>

    <nav class="pagination"><?php echo paginate_links(['prev_text' => '<i class="fa-solid fa-chevron-left"></i>', 'next_text' => '<i class="fa-solid fa-chevron-right"></i>']); ?></nav>

  </div>
</div>

<?php get_footer(); ?>
