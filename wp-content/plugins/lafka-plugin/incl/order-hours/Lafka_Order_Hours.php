<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Class Lafka_Order_Hours {
	public static $lafka_order_hours_options;

	public function __construct() {
		self::$lafka_order_hours_options = get_option( 'lafka_order_hours_options' );

		$current_time_zone  = date_default_timezone_get();
		$settings_time_zone = isset( self::$lafka_order_hours_options['lafka_order_hours_time_zone'] ) ? self::$lafka_order_hours_options['lafka_order_hours_time_zone'] : '';
		if ( $settings_time_zone && $current_time_zone !== $settings_time_zone ) {
			date_default_timezone_set( $settings_time_zone );
		}

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {

		$this->handle_shop_status();

		if ( is_admin() ) {
			include_once( dirname( __FILE__ ) . '/settings/Lafka_Order_Hours_Admin.php' );
			new Lafka_Order_Hours_Admin();
		}
	}

	public static function is_shop_open() {

		// Check if shop status is forced
		if ( isset( self::$lafka_order_hours_options['lafka_order_hours_force_override_check'] ) && self::$lafka_order_hours_options['lafka_order_hours_force_override_check'] ) {
			if ( self::$lafka_order_hours_options['lafka_order_hours_force_override_status'] ) {
				return true;
			} else {
				return false;
			}
		}

		$current_time = new DateTime();

		// Vacation check
		if ( isset( self::$lafka_order_hours_options['lafka_order_hours_holidays_calendar'] ) ) {
			$vacation_dates_array = explode( ', ', self::$lafka_order_hours_options['lafka_order_hours_holidays_calendar'] );

			foreach ( $vacation_dates_array as $vacation_date_string ) {
				if ( $current_time->format( 'Y-m-d' ) === $vacation_date_string ) {
					return false;
				}
			}
		}

		$numeric_day_of_the_week = $current_time->format( 'N' );

		// check is it in the open hours periods
		if ( ! isset( self::$lafka_order_hours_options['lafka_order_hours_schedule'] ) ) {
			return true;
		}
		$schedule_json = self::$lafka_order_hours_options['lafka_order_hours_schedule'];

		$schedule_array = json_decode( $schedule_json );
		if ( ! isset( $schedule_array[ $numeric_day_of_the_week - 1 ] ) ) {
			return true;
		}

		$schedule_current_day_of_week = $schedule_array[ $numeric_day_of_the_week - 1 ];
		foreach ( $schedule_current_day_of_week->periods as $period ) {
			$open_time = DateTime::createFromFormat( 'H:i', $period->start );

			if ( $period->end === '00:00' ) {
				$period->end = '24:00';
			}
			$close_time = DateTime::createFromFormat( 'H:i', $period->end );

			if ( $open_time < $current_time && $current_time < $close_time ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return object
	 */
	public static function get_shop_status() {
		if ( self::is_shop_open() ) {
			return (object) array( 'code' => 'open', 'value' => esc_html__( 'Open', 'lafka-plugin' ) );
		}

		return (object) array( 'code' => 'closed', 'value' => esc_html__( 'Closed', 'lafka-plugin' ) );
	}

	/**
	 * @return bool|DateTime
	 * @throws Exception
	 */
	public static function get_next_opening_time() {
		if ( ! self::is_shop_open() && ! isset( self::$lafka_order_hours_options['lafka_order_hours_force_override_check'] ) ) {

			$current_time            = new DateTime();
			$numeric_day_of_the_week = $current_time->format( 'N' );

			$schedule_json = self::$lafka_order_hours_options['lafka_order_hours_schedule'];

			$schedule_array = json_decode( $schedule_json );
			if ( ! isset( $schedule_array[ $numeric_day_of_the_week - 1 ] ) ) {
				return false;
			}

			$counter = 0;
			for ( $day_of_week = $numeric_day_of_the_week - 1; $day_of_week < $day_of_week + 6; $day_of_week ++ ) {
				$weekday_index = $day_of_week;
				if ( $day_of_week > 6 ) {
					$weekday_index = $day_of_week - 7;
				}
				$schedule_day_of_week = $schedule_array[ $weekday_index ];

				foreach ( $schedule_day_of_week->periods as $period ) {
					$open_time = DateTime::createFromFormat( 'H:i', $period->start )->add( DateInterval::createFromDateString( $counter . ' days' ) );

					if ( $open_time > $current_time ) {
						return $open_time;
					}
				}

				$counter ++;
			}
		}

		return false;
	}

	public function handle_shop_status() {
		if ( ! Lafka_Order_Hours::is_shop_open() ) {

			// Add classes to body
			add_filter( 'body_class', array( $this, 'add_body_class' ) );

			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
			add_action( 'woocommerce_proceed_to_checkout', array( $this, 'echo_closed_store_message' ), 20 );

			remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
			add_action( 'woocommerce_widget_shopping_cart_buttons', array( $this, 'echo_closed_store_message' ), 20 );

			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'echo_closed_store_message' ), 99 );
			add_filter( 'woocommerce_order_button_html', array( $this, 'get_closed_store_message' ) );
		}
	}

	public function add_body_class( $classes ) {
		$classes[] = 'lafka-store-closed';

		if ( isset( self::$lafka_order_hours_options['lafka_order_hours_disable_add_to_cart'] ) && self::$lafka_order_hours_options['lafka_order_hours_disable_add_to_cart'] ) {
			$classes[] = 'lafka-disabled-cart-buttons';
		}

		return $classes;
	}

	public function echo_closed_store_message() {
		global $post;

		if ( isset( self::$lafka_order_hours_options['lafka_order_hours_message'] ) && self::$lafka_order_hours_options['lafka_order_hours_message'] ) {
			?>
            <div class="lafka-closed-store-message"><?php echo esc_html( self::$lafka_order_hours_options['lafka_order_hours_message'] ) ?>
				<?php
				if ( isset( self::$lafka_order_hours_options['lafka_order_hours_message_countdown'] ) && self::$lafka_order_hours_options['lafka_order_hours_message_countdown'] ) {
					/** @var DateTime $next_opening_datetime */
					$next_opening_datetime = self::get_next_opening_time();
					if ( $next_opening_datetime ) {
						$random_num = uniqid();

						$show_days  = 'no';
						$difference = $next_opening_datetime->diff( new DateTime() );
						if ( $difference && $difference->d > 0 ) {
							$show_days = 'yes';
						}
						?>
                        <div class="count_holder_small" data-countdown-id="<?php echo esc_js( '#lafkaCountSmallLatest' . $post->ID . $random_num ) ?>"
                             data-countdown-show-days="<?php echo esc_attr( $show_days ) ?>"
                             data-countdown-to="<?php echo esc_js( $next_opening_datetime->format( 'F j, Y G:i:s' ) ) ?>">
                            <div id="lafkaCountSmallLatest<?php echo esc_attr( $post->ID . $random_num ) ?>"></div>
                            <div class="clear"></div>
                        </div>
						<?php
					}
				}
				?>
            </div>
		<?php }

	}

	public function get_closed_store_message() {
		ob_start();
		$this->echo_closed_store_message();

		return ob_get_clean();
	}
}

new Lafka_Order_Hours();