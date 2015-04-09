<?php
/**
 * @package Bailey
 */

if ( ! class_exists( 'Bailey_Gallery_Slider' ) ) :
/**
 * class Bailey_Gallery_Slider
 *
 * A class that defines the slider for the gallery section.
 *
 * @since 1.0.0.
 */
class Bailey_Gallery_Slider {

	/**
	 * The one instance of Bailey_Gallery_Slider
	 *
	 * @since 1.0.0.
	 *
	 * @var Bailey_Gallery_Slider
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Bailey_Gallery_Slider instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_Gallery_Slider
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add the action and filter hooks.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_Gallery_Slider
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_filter( 'post_gallery', array( $this, 'render_gallery' ), 1001, 2 );
	}

	/**
	 * Add admin-only action hooks
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	function admin_init() {
		add_action( 'wp_enqueue_media', array( $this, 'enqueue_media' ), 99 );
		add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
	}

	/**
	 * Enqueue the admin script that handles the slider settings in the Media Manager
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	function enqueue_media() {
		wp_enqueue_script(
			'bailey-admin-gallery-settings',
			get_template_directory_uri() . '/inc/gallery-slider/gallery-slider' . BAILEY_SUFFIX . '.js',
			array( 'media-views' ),
			BAILEY_VERSION,
			true
		);
	}

	/**
	 * Markup for the slider settings in the Media Manager
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	function print_media_templates() {
	?>
		<script type="text/html" id="tmpl-bailey-gallery-settings">
			<h3 style="float:left;margin-top:10px;"><?php _e( 'Slider Settings', 'bailey' ); ?></h3>
			<label class="setting">
				<span><?php _e( 'Show gallery as slider', 'bailey' ); ?></span>
				<input id="bailey-slider" type="checkbox" data-setting="bailey_slider" />
			</label>
			<div id="bailey-slider-settings">
				<label class="setting">
					<span><?php _e( 'Hide navigation arrows', 'bailey' ); ?></span>
					<input type="checkbox" data-setting="bailey_prevnext" />
				</label>
				<label class="setting">
					<span><?php _e( 'Autoplay', 'bailey' ); ?></span>
					<input type="checkbox" data-setting="bailey_autoplay" />
				</label>
				<label class="setting">
					<span><?php _e( 'Time between slides (ms)', 'bailey' ); ?></span>
					<input type="text" data-setting="bailey_delay" style="float:left;width:25%;" />
				</label>
				<label class="setting">
					<span><?php _e( 'Effect', 'bailey' ); ?></span>
					<select data-setting="bailey_effect">
						<option value="scrollHorz" selected="selected"><?php _e( 'Slide horizontal', 'bailey' ); ?></option>
						<option value="fade"><?php _e( 'Fade', 'bailey' ); ?></option>
						<option value="none"><?php _e( 'None', 'bailey' ); ?></option>
					</select>
				</label>
			</div>
		</script>
	<?php
	}

	/**
	 * Alternate gallery shortcode handler for the slider
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $output    The original shortcode output.
	 * @param  array     $attr      The shortcode attrs.
	 * @return string               The modified gallery code.
	 */
	function render_gallery( $output, $attr ) {
		// Only use this alternative output if the slider is set to true
		if ( isset( $attr['bailey_slider'] ) && true == $attr['bailey_slider'] ) {
			$post = get_post();

			if ( ! empty( $attr['ids'] ) ) {
				// 'ids' is explicitly ordered, unless you specify otherwise.
				if ( empty( $attr['orderby'] ) ) {
					$attr['orderby'] = 'post__in';
				}

				$attr['include'] = $attr['ids'];
			}

			// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
			if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( !$attr['orderby'] ) {
					unset( $attr['orderby'] );
				}
			}

			$attr = shortcode_atts( array(
				// Built-in
				'order'            => 'ASC',
				'orderby'          => 'menu_order ID',
				'id'               => $post ? $post->ID : 0,
				'size'             => 'bailey_large',
				'include'          => '',
				'exclude'          => '',

				// Bailey slider
				'bailey_slider'   => true,
				'bailey_autoplay' => false,
				'bailey_prevnext' => false,
				'bailey_delay'    => 6000,
				'bailey_effect'   => 'scrollHorz'
			), $attr, 'gallery');

			$attr['id'] = intval( $attr['id'] );
			if ( 'RAND' == $attr['order'] ) {
				$attr['orderby'] = 'none';
			}

			if ( !empty( $attr['include'] ) ) {
				$_attachments = get_posts( array(
					'include'        => $attr['include'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $attr['order'],
					'orderby'        => $attr['orderby']
				) );

				$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[ $val->ID ] = $_attachments[ $key ];
				}
			}
			elseif ( !empty( $attr['exclude'] ) ) {
				$attachments = get_posts( array(
					'post_parent'    => $attr['id'],
					'exclude'        => $attr['exclude'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $attr['order'],
					'orderby'        => $attr['orderby']
				) );
			}
			else {
				$attachments = get_posts( array(
					'post_parent'    => $attr['id'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $attr['order'],
					'orderby'        => $attr['orderby']
				) );
			}

			if ( empty( $attachments ) ) {
				return '';
			}

			if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $att_id => $attachment ) {
					$output .= wp_get_attachment_link( $att_id, $attr['size'], true ) . "\n";
				}
				return $output;
			}
			// End core code

			// Classes
			$classes = 'bailey-shortcode-slider cycle-slideshow';

			// Data attributes
			$data_attributes  = ' data-cycle-log="false"';
			$data_attributes .= ' data-cycle-slides=".cycle-slide"';
			$data_attributes .= ' data-cycle-auto-height="calc"';
			$data_attributes .= ' data-cycle-center-horz="true"';
    		$data_attributes .= ' data-cycle-center-vert="true"';
			$data_attributes .= ' data-cycle-swipe="true"';

			// No autoplay
			$autoplay = (bool) $attr['bailey_autoplay'];
			if ( false === $autoplay ) {
				$data_attributes .= ' data-cycle-paused="true"';
			}

			// Delay
			$delay = absint( $attr['bailey_delay'] );
			if ( 0 === $delay ) {
				$delay = 6000;
			}
			if ( 4000 !== $delay ) {
				$data_attributes .= ' data-cycle-timeout="' . esc_attr( $delay ) . '"';
			}

			// Effect
			$effect = trim( $attr['bailey_effect'] );
			if ( ! in_array( $effect, array( 'fade', 'scrollHorz', 'none' ) ) ) {
				$effect = 'scrollHorz';
			}
			if ( 'fade' !== $effect ) {
				$data_attributes .= ' data-cycle-fx="' . esc_attr( $effect ) . '"';
			}

			// Markup
			ob_start(); ?>
			<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $data_attributes; ?>>
				<?php foreach ( $attachments as $id => $attachment ) : ?>
				<figure class="cycle-slide">
					<?php echo wp_get_attachment_image( $id, $attr[ 'size' ], false ); ?>
					<?php if ( trim( $attachment->post_excerpt ) ) : ?>
					<figcaption class="cycle-caption">
						<?php echo wptexturize( $attachment->post_excerpt ); ?>
					</figcaption>
					<?php endif; ?>
				</figure>
				<?php endforeach; ?>
				<?php if ( true != $attr[ 'bailey_prevnext' ] ) : ?>
				<div class="cycle-prev"></div>
				<div class="cycle-next"></div>
				<?php endif; ?>
			</div>
			<?php
			$output = ob_get_clean();
		}

		return $output;
	}
}
endif;

return Bailey_Gallery_Slider::instance();