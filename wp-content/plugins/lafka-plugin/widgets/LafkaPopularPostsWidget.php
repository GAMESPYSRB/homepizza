<?php
defined( 'ABSPATH' ) || exit;

/**
 * Lafka popular posts widget class
 *
 */
class LafkaPopularPostsWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries lafka-popular-posts', 'description' => esc_html__("The most popular posts on your site", 'lafka-plugin'));
		parent::__construct('lafka-popular-posts', esc_html__('Lafka Popular Posts', 'lafka-plugin'), $widget_ops);
		$this->alt_option_name = 'widget_popular_entries';

		add_action('save_post', array($this, 'flush_widget_cache'));
		add_action('deleted_post', array($this, 'flush_widget_cache'));
		add_action('switch_theme', array($this, 'flush_widget_cache'));
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_popular_posts', 'widget');

		if (!is_array($cache))
			$cache = array();

		if (!isset($args['widget_id']))
			$args['widget_id'] = $this->id;

		if (isset($cache[$args['widget_id']])) {
			echo esc_attr($cache[$args['widget_id']]);
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Popular Posts', 'lafka-plugin') : $instance['title'], $instance, $this->id_base);
		if (empty($instance['number']) || !$number = absint($instance['number']))
			$number = 10;

		$r = new WP_Query(apply_filters('widget_posts_args', array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'orderby' => 'comment_count')));
		if ($r->have_posts()) :
			?>
			<?php echo wp_kses_post($before_widget); ?>
			<?php if ($title) echo wp_kses_post($before_title . $title . $after_title); ?>
			<ul class="post-list fixed">
				<?php while ($r->have_posts()) : $r->the_post(); ?>
					<li>
						<a href="<?php esc_url(the_permalink()) ?>" title="<?php echo esc_attr(get_the_title() ? the_title_attribute('echo=0') : get_the_ID() ); ?>">
							<?php if (has_post_thumbnail()): ?>
								<?php the_post_thumbnail('lafka-widgets-thumb'); ?>
							<?php endif; ?>
							<?php
							if (get_the_title()) {
								the_title();
							} else {
								the_ID();
							}
							?>
							<br>
							<span class="post-date"><?php echo esc_html(get_the_date()) ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php echo wp_kses_post($after_widget); ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_popular_posts', $cache, 'widget');
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if (isset($alloptions['widget_popular_entries']))
			delete_option('widget_popular_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_popular_posts', 'widget');
	}

	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'lafka-plugin'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'lafka-plugin'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

		<?php
	}

}

add_action('widgets_init', 'lafka_register_lafka_popular_widget');
if (!function_exists('lafka_register_lafka_popular_widget')) {

	function lafka_register_lafka_popular_widget() {
		register_widget('LafkaPopularPostsWidget');
	}

}
?>