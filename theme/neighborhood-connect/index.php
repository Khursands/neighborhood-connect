<?php get_header(); ?>

<div class="section">
  <div class="container">

    <div class="section-header" style="text-align:left;">
      <h1 class="section-title">
        <?php
        if (is_home()) esc_html_e('Community News & Updates', 'neighborhood-connect');
        elseif (is_category()) single_cat_title();
        elseif (is_tag()) printf(esc_html__('Tag: %s', 'neighborhood-connect'), single_tag_title('', false));
        elseif (is_author()) printf(esc_html__('Author: %s', 'neighborhood-connect'), get_the_author());
        elseif (is_date()) esc_html_e('Archive', 'neighborhood-connect');
        else esc_html_e('Posts', 'neighborhood-connect');
        ?>
      </h1>
    </div>

    <div class="content-layout">
      <div class="main-content">
        <?php if (have_posts()) : ?>
          <div class="card-grid">
            <?php while (have_posts()) : the_post(); ?>
              <?php get_template_part('template-parts/content', 'post'); ?>
            <?php endwhile; ?>
          </div>

          <nav class="pagination" aria-label="<?php esc_attr_e('Posts pagination', 'neighborhood-connect'); ?>">
            <?php
            echo paginate_links([
              'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
              'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
              'type'      => 'list',
              'mid_size'  => 2,
              'before_page_number' => '<span class="page-link">',
              'after_page_number'  => '</span>',
            ]);
            ?>
          </nav>

        <?php else : ?>
          <div style="text-align:center;padding:4rem 0;">
            <i class="fa-solid fa-inbox" style="font-size:3rem;color:var(--color-text-muted);display:block;margin-bottom:1rem;"></i>
            <h2 style="margin-bottom:.5rem;"><?php esc_html_e('Nothing here yet', 'neighborhood-connect'); ?></h2>
            <p style="color:var(--color-text-muted);"><?php esc_html_e('No posts found. Check back soon!', 'neighborhood-connect'); ?></p>
          </div>
        <?php endif; ?>
      </div>

      <aside class="sidebar" aria-label="<?php esc_attr_e('Sidebar', 'neighborhood-connect'); ?>">
        <?php dynamic_sidebar('sidebar-blog'); ?>

        <!-- Quick Links Widget -->
        <div class="widget">
          <h3 class="widget-title"><?php esc_html_e('Explore', 'neighborhood-connect'); ?></h3>
          <ul style="display:flex;flex-direction:column;gap:.5rem;">
            <li><a href="<?php echo esc_url(home_url('/events/')); ?>" style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--color-text-muted);"><i class="fa-solid fa-calendar-days" style="color:var(--color-primary);width:16px;"></i> <?php esc_html_e('Events', 'neighborhood-connect'); ?></a></li>
            <li><a href="<?php echo esc_url(home_url('/services/')); ?>" style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--color-text-muted);"><i class="fa-solid fa-briefcase" style="color:var(--color-primary);width:16px;"></i> <?php esc_html_e('Services', 'neighborhood-connect'); ?></a></li>
            <li><a href="<?php echo esc_url(home_url('/issues/')); ?>" style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--color-text-muted);"><i class="fa-solid fa-triangle-exclamation" style="color:var(--color-primary);width:16px;"></i> <?php esc_html_e('Issues', 'neighborhood-connect'); ?></a></li>
          </ul>
        </div>
      </aside>
    </div>

  </div>
</div>

<?php get_footer(); ?>
