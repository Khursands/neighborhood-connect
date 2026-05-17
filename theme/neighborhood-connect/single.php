<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="post-hero">
  <div class="container">
    <div class="post-header">

      <div class="post-categories">
        <?php
        $cats = get_the_category();
        foreach ($cats as $cat) :
        ?>
          <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="badge badge-primary">
            <?php echo esc_html($cat->name); ?>
          </a>
        <?php endforeach; ?>
      </div>

      <h1 class="post-title"><?php the_title(); ?></h1>

      <div class="post-meta">
        <div class="post-author">
          <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => '']); ?>
          <span><?php esc_html_e('By', 'neighborhood-connect'); ?></span>
          <span class="post-author-name"><?php the_author(); ?></span>
        </div>
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
          <i class="fa-regular fa-calendar"></i>
          <?php echo esc_html(get_the_date()); ?>
        </time>
        <span><i class="fa-regular fa-clock"></i> <?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> min read</span>
        <?php if (comments_open()) : ?>
          <a href="#comments" style="color:var(--color-text-muted);">
            <i class="fa-regular fa-comment"></i>
            <?php echo esc_html(get_comments_number()); ?> <?php esc_html_e('comments', 'neighborhood-connect'); ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="section" style="padding-top:0;">
  <div class="container">
    <div class="content-layout">
      <div class="main-content">

        <?php if (has_post_thumbnail()) : ?>
          <div class="post-featured-image">
            <?php the_post_thumbnail('nc-hero', ['loading' => 'eager', 'alt' => get_the_title()]); ?>
          </div>
        <?php endif; ?>

        <div class="post-content">
          <?php the_content(); ?>
        </div>

        <!-- Tags -->
        <?php
        $tags = get_the_tags();
        if ($tags) :
        ?>
          <div class="service-tags" style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--color-border);">
            <?php foreach ($tags as $tag) : ?>
              <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag">#<?php echo esc_html($tag->name); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Author Box -->
        <div style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:1.5rem;margin-top:2.5rem;display:flex;gap:1rem;align-items:flex-start;">
          <?php echo get_avatar(get_the_author_meta('ID'), 64, '', '', ['style' => 'border-radius:50%;flex-shrink:0;']); ?>
          <div>
            <div style="font-weight:700;margin-bottom:.25rem;"><?php the_author(); ?></div>
            <p style="font-size:.875rem;color:var(--color-text-muted);"><?php the_author_meta('description'); ?></p>
          </div>
        </div>

        <!-- Post Navigation -->
        <nav style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:2.5rem;" aria-label="<?php esc_attr_e('Post navigation', 'neighborhood-connect'); ?>">
          <?php
          $prev = get_previous_post();
          $next = get_next_post();
          if ($prev) :
          ?>
            <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="widget" style="text-decoration:none;margin:0;">
              <div style="font-size:.75rem;color:var(--color-text-muted);margin-bottom:.25rem;"><i class="fa-solid fa-arrow-left"></i> <?php esc_html_e('Previous', 'neighborhood-connect'); ?></div>
              <div style="font-size:.875rem;font-weight:600;"><?php echo esc_html(get_the_title($prev)); ?></div>
            </a>
          <?php else : ?>
            <div></div>
          <?php endif; ?>

          <?php if ($next) : ?>
            <a href="<?php echo esc_url(get_permalink($next)); ?>" class="widget" style="text-decoration:none;margin:0;text-align:right;">
              <div style="font-size:.75rem;color:var(--color-text-muted);margin-bottom:.25rem;"><?php esc_html_e('Next', 'neighborhood-connect'); ?> <i class="fa-solid fa-arrow-right"></i></div>
              <div style="font-size:.875rem;font-weight:600;"><?php echo esc_html(get_the_title($next)); ?></div>
            </a>
          <?php endif; ?>
        </nav>

        <?php comments_template(); ?>

      </div>

      <aside class="sidebar">
        <?php dynamic_sidebar('sidebar-blog'); ?>
      </aside>
    </div>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
