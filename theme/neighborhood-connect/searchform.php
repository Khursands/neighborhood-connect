<form role="search" method="get" class="search-bar" action="<?php echo esc_url(home_url('/')); ?>">
  <div class="search-input-wrap" style="flex:1;">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input
      type="search"
      class="search-input"
      id="s"
      name="s"
      value="<?php echo esc_attr(get_search_query()); ?>"
      placeholder="<?php esc_attr_e('Search events, services, issues...', 'neighborhood-connect'); ?>"
      aria-label="<?php esc_attr_e('Search', 'neighborhood-connect'); ?>"
    >
  </div>
  <button type="submit" class="btn btn-primary">
    <i class="fa-solid fa-magnifying-glass"></i>
    <span class="sr-only"><?php esc_html_e('Search', 'neighborhood-connect'); ?></span>
  </button>
</form>
