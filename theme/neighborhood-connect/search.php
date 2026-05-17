<?php get_header(); ?>

<div class="section">
  <div class="container">

    <div class="section-header" style="text-align:left;">
      <h1 class="section-title">
        <?php printf(esc_html__('Search results for: %s', 'neighborhood-connect'), '<em>' . esc_html(get_search_query()) . '</em>'); ?>
      </h1>
      <?php get_search_form(); ?>
    </div>

    <div class="card-grid">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/content', 'post'); ?>
        <?php endwhile; ?>
      <?php else : ?>
        <div style="grid-column:1/-1;text-align:center;padding:4rem 0;">
          <i class="fa-solid fa-magnifying-glass" style="font-size:3rem;color:var(--color-text-muted);display:block;margin-bottom:1rem;"></i>
          <h2><?php esc_html_e('No results found', 'neighborhood-connect'); ?></h2>
          <p style="color:var(--color-text-muted);margin-bottom:1.5rem;"><?php esc_html_e('Try different keywords or browse by category.', 'neighborhood-connect'); ?></p>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php esc_html_e('Go Home', 'neighborhood-connect'); ?></a>
        </div>
      <?php endif; ?>
    </div>

    <?php if (have_posts()) : ?>
      <nav class="pagination"><?php echo paginate_links(['prev_text' => '&larr;', 'next_text' => '&rarr;']); ?></nav>
    <?php endif; ?>

  </div>
</div>

<?php get_footer(); ?>
