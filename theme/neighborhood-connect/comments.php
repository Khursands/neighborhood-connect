<?php if (post_password_required()) return; ?>

<div id="comments" style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-border);">

  <?php if (have_comments()) : ?>
    <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:1.5rem;">
      <?php printf(esc_html(_n('%s Comment', '%s Comments', get_comments_number(), 'neighborhood-connect')), number_format_i18n(get_comments_number())); ?>
    </h2>

    <ol class="comment-list" style="list-style:none;display:flex;flex-direction:column;gap:1.5rem;">
      <?php
      wp_list_comments([
        'style'      => 'ol',
        'short_ping' => true,
        'avatar_size'=> 40,
        'callback'   => 'nc_comment',
      ]);
      ?>
    </ol>

    <?php the_comments_pagination(['prev_text' => '&larr;', 'next_text' => '&rarr;']); ?>

  <?php endif; ?>

  <?php if (!comments_open() && get_comments_number()) : ?>
    <p style="color:var(--color-text-muted);font-size:.875rem;"><?php esc_html_e('Comments are closed.', 'neighborhood-connect'); ?></p>
  <?php endif; ?>

  <?php
  comment_form([
    'title_reply'         => __('Leave a Comment', 'neighborhood-connect'),
    'title_reply_before'  => '<h3 style="font-size:1.125rem;font-weight:700;margin-bottom:1.5rem;">',
    'title_reply_after'   => '</h3>',
    'label_submit'        => __('Post Comment', 'neighborhood-connect'),
    'class_submit'        => 'btn btn-primary',
    'class_form'          => 'comment-form',
    'comment_field'       => '<div class="form-group"><label class="form-label" for="comment">' . __('Comment', 'neighborhood-connect') . '<span class="required"> *</span></label><textarea id="comment" name="comment" class="form-control" rows="5" required></textarea></div>',
    'fields'              => [
      'author' => '<div class="form-group"><label class="form-label" for="author">' . __('Name', 'neighborhood-connect') . '<span class="required"> *</span></label><input id="author" name="author" type="text" class="form-control" required></div>',
      'email'  => '<div class="form-group"><label class="form-label" for="email">' . __('Email', 'neighborhood-connect') . '<span class="required"> *</span></label><input id="email" name="email" type="email" class="form-control" required></div>',
      'url'    => '<div class="form-group"><label class="form-label" for="url">' . __('Website', 'neighborhood-connect') . '</label><input id="url" name="url" type="url" class="form-control"></div>',
    ],
  ]);
  ?>

</div>

<?php
function nc_comment($comment, $args, $depth) {
    $author = get_comment_author();
    $avatar = get_avatar($comment, 40, '', '', ['style' => 'border-radius:50%;flex-shrink:0;']);
    $date   = get_comment_date();
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('', $comment); ?>>
      <div style="display:flex;gap:1rem;align-items:flex-start;">
        <?php echo wp_kses_post($avatar); ?>
        <div style="flex:1;">
          <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.5rem;">
            <strong style="font-size:.875rem;"><?php echo esc_html($author); ?></strong>
            <time style="font-size:.75rem;color:var(--color-text-muted);" datetime="<?php echo esc_attr(get_comment_date('c')); ?>"><?php echo esc_html($date); ?></time>
          </div>
          <div class="post-content" style="font-size:.875rem;">
            <?php comment_text(); ?>
          </div>
          <?php comment_reply_link(array_merge($args, ['depth' => $depth, 'max_depth' => $args['max_depth']])); ?>
        </div>
      </div>
    </li>
    <?php
}
