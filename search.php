<?php get_header(); ?>

<div id="content" class="<?php echo arras_layout_columns( 'content' ); ?>">
<?php arras_above_content() ?>

<?php if (have_posts()) : ?>
<div class="search-results">
    <h1 class="search-results-title"><?php _e('Search Results', 'arras') ?></h1>
    <div class="search-results-content clearfix">
	<p><?php printf( __('Search Results for <strong>&#8216;' . '%s' . '&#8217;</strong></p>', 'arras'), esc_html($s, 1) ) ?>
    <?php get_search_form(); ?>
    </div>
</div>

<div id="archive-posts">
<?php arras_render_posts( null, ( arras_get_option( 'default_tapestry' ) ) ? arras_get_option( 'default_tapestry' ) : 'quick' ); ?>
</div>

<?php if(function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
    <div class="navigation clearfix">
        <div class="floatleft"><?php next_posts_link( __('&laquo; Older Entries', 'arras') ) ?></div>
		<div class="floatright"><?php previous_posts_link( __('Newer Entries &raquo;', 'arras') ) ?></div>
    </div>
<?php } ?>

<?php else: ?>

<div class="search-results">
    <h2>Search Results</h2>
    <div class="search-results-content clearfix">
    <p><?php _e('<strong>Sorry, we couldn\'t find any results based on your search query.</strong>', 'arras') ?></p>
    <?php get_search_form() ?>
    </div>
</div>

<h2 class="home-title"><?php _e('Blog Archive', 'arras') ?></h2>
<?php query_posts(''); ?>
<?php arras_render_posts( null, ( arras_get_option( 'default_tapestry' ) ) ? arras_get_option( 'default_tapestry' ) : 'quick' ); ?>

<?php if(function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
    <div class="navigation clearfix">
        <div class="floatleft"><?php next_posts_link( __('&laquo; Older Entries', 'arras') ) ?></div>
        <div class="floatright"><?php previous_posts_link( __('Newer Entries &raquo;', 'arras') ) ?></div>
    </div>
<?php } ?>
<?php endif; ?>

<?php arras_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>