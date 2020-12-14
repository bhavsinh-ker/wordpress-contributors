<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/bhavsinh-ker
 * @since      1.0.0
 *
 * @package    Wordpress_Contributors
 * @subpackage Wordpress_Contributors/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wordpress_Contributors
 * @subpackage Wordpress_Contributors/public
 * @author     Bhavsinh Ker <ker.bhavik@gmail.com>
 */
class Wordpress_Contributors_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wordpress_Contributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wordpress_Contributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordpress-contributors-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Append cpost contributors in post content.
	 *
	 * @since	1.0.0
	 * @param 	Type $content post content
	 */
	public function append_contributors_content($content) {

		if(!is_singular( 'post' ) ) {
			return $content;
		}

		return apply_filters( 'wp_contributors_content', $content );		
	}

	/**
	 * Post Contributors HTML.
	 *
	 * @since	1.0.0
	 * @param 	Type $content post content
	 */
	public function post_contributors_html($content) {
		$post_id = get_the_ID();
		$post_contributors = get_post_meta($post_id, 'post_contributors', true);
		if(is_wp_error($post_contributors) || !is_array($post_contributors) || empty($post_contributors)) {
			return $content;
		}
		
		ob_start();
		?>
		<div class="wordpress-contributors">
			<?php 
				$title = '<h5 class="wp-contributors-content-title">'.__('Contributors', 'wordpress-contributors').'</h5>';
				echo apply_filters( 'wp_contributors_content_title', $title ); 
				echo apply_filters( 'wp_contributors_content_before_list', '' );
			?>
			<ul class="wp-contributors-content-list">
				<?php 
					foreach($post_contributors as $post_contributor_id) {
						$author_obj = get_user_by('id', $post_contributor_id);
						if(is_wp_error($author_obj) || empty($author_obj) || !isset($author_obj->data->display_name)) {
							continue;
						}
						echo apply_filters( 'wp_contributors_content_before_list_item', '' );
					?>
				<li>
					<a href="<?php echo esc_url( get_author_posts_url( $post_contributor_id ) ); ?>">
						<?php echo get_avatar( $post_contributor_id, 25 ); ?><span><?php echo $author_obj->data->display_name; ?></span>
					</a>
				</li>
					<?php 
						echo apply_filters( 'wp_contributors_content_after_list_item', '' );
					} 
				?>
			</ul>
			<?php 
				echo apply_filters( 'wp_contributors_content_after_list', '' );
			?>
		</div>
		<?php
		$contributors_html = ob_get_clean();
		return $content.$contributors_html;
	}

}
