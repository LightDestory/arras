<?php

function arras_add_slideshow() {
	global $post_blacklist, $paged;
	if ( !is_home() || $paged ) return false;

	$slideshow_cat = arras_get_option('slideshow_cat');

	if (arras_get_option('enable_slideshow') == false) return false;

	$query = arras_prep_query( array(
		'list'				=> $slideshow_cat,
		'taxonomy'			=> arras_get_option('slideshow_tax'),
		'query'				=> array(
			'posts_per_page'	=> arras_get_option('slideshow_count'),
			'exclude'			=> $post_blacklist,
			'post_type'			=> arras_get_option('slideshow_posttype'),
			'paged'				=> $paged
		)
	) );

	$q = new WP_Query( apply_filters('arras_slideshow_query', $query) );
	if ($q->have_posts()) :
	?>
	<!-- Featured Slideshow -->
	<div class="featured group">
		<?php if ($q->post_count > 1) : ?>
		<div id="controls">
			<div class="cycle-prev"><?php _e('Prev', 'arras') ?></div>
			<div class="cycle-next"><?php _e('Next', 'arras') ?></div>
		</div>
		<?php endif ?>
		<div class="cycle-slideshow" data-cycle-slides="> div" data-cycle-prev=".cycle-prev" data-cycle-next=".cycle-next">
			<?php $count = 0; ?>

			<?php while ($q->have_posts()) : $q->the_post(); ?>
			<div class="featured-slideshow-inner" <?php if ($count != 0) echo 'style="display: none"'; ?>>
				<a class="featured-article" href="<?php the_permalink(); ?>" rel="bookmark">
				<?php echo arras_get_thumbnail('featured-slideshow-thumb'); ?>
				</a>
				<div class="featured-entry">
					<a class="entry-title" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					<div class="entry-summary"><?php the_excerpt() ?></div>
					<div class="progress"></div>
				</div>
			</div>
			<?php
			arras_blacklist_duplicates(); // required for duplicate posts function to work.
			$count++; endwhile; ?>
		</div>
	</div>
	<?php endif;
}

add_action('arras_above_content', 'arras_add_slideshow');

function arras_add_slideshow_js() {
	wp_register_script( 'slideshow-settings', get_template_directory_uri() . '/js/slideshowsettings.js', array( 'jquery-cycle' ), null, true );
	$slideshow_size = arras_get_image_size('featured-slideshow-thumb');
	wp_localize_script( 'slideshow-settings', 'show_size', $slideshow_size );
	if (is_home() || is_front_page()) {
		wp_enqueue_script( 'slideshow-settings' );
	}
}
add_action( 'wp_enqueue_scripts', 'arras_add_slideshow_js' );

function arras_load_slideshow_scripts() {
	if ( ( arras_get_option('enable_slideshow') ) && is_home() || is_front_page() ) {
		wp_enqueue_script('jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle2-min.js', array( 'jquery' ), null, true );
	}
}
add_action('wp_enqueue_scripts', 'arras_load_slideshow_scripts' );

function arras_add_slideshow_thumb_size() {
	$layout = arras_get_option('layout');

	if ( strpos($layout, '1c') !== false ) {
		$size = array(950, 450);
	} else if ( preg_match('/3c/', $layout) ) {
		$size = array(490, 225);
	} else {
		$size = array(640, 300);
	}

	$size = apply_filters('arras_slideshow_thumb_size', $size);
	arras_add_image_size( 'featured-slideshow-thumb', __('Featured Slideshow', 'arras'), $size[0], $size[1]);
}
add_action('arras_add_default_thumbnails', 'arras_add_slideshow_thumb_size', 5);

function arras_slideshow_styles() {
	$slideshow_size = arras_get_image_size('featured-slideshow-thumb');
	$slideshow_size_w = $slideshow_size['w'];
	$slideshow_size_h = $slideshow_size['h'];
	?>
	.featured-article {
		height: <?php echo $slideshow_size_h ?>px;
		width: 100%;
		}
	.featured-article img {
		width: 100%;
	}
	#controls {
		top: <?php echo ($slideshow_size_h / 2) - 15 ?>px;
		width: 100%;
		}
	#controls .next { left: <?php echo $slideshow_size_w - 30 ?>px; }
	.featured-entry { height: <?php echo ceil($slideshow_size_h / 3) ?>px; top: -<?php echo ceil($slideshow_size_h / 3) ?>px; }
	.featured-slideshow-inner {
		height: <?php echo $slideshow_size_h ?>px;
		width: 100%;
	}
	<?php
}
add_action('arras_custom_styles', 'arras_slideshow_styles');

/* End of file slideshow.php */
/* Location: ./library/slideshow.php */