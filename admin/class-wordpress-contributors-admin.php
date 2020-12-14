<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/bhavsinh-ker
 * @since      1.0.0
 *
 * @package    Wordpress_Contributors
 * @subpackage Wordpress_Contributors/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wordpress_Contributors
 * @subpackage Wordpress_Contributors/admin
 * @author     Bhavsinh Ker <ker.bhavik@gmail.com>
 */
class Wordpress_Contributors_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add Contributors Meta Box container
	 *
	 * @since	1.0.0
	 * @param	Type $post_type post type name
	 */
	public function add_contributors_meta_box( $post_type ) {

		// Limit meta box to certain post types.
        $post_types = array( 'post' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'contributors_meta_box',
                __( 'Contributors', 'wordpress-contributors' ),
                array( $this, 'render_contributors_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }

	}

	/**
	 * Render Contributors Meta Box content
	 *
	 * @since	1.0.0
	 * @param	Type $post post object
	 */
	public function render_contributors_meta_box_content( $post ) {

		$users_args = array( 
			'role' 		=> 'contributor'
		);
		$contributors = get_users( $users_args );
		$total_contributors = count($contributors);
		if(is_wp_error($contributors)) {
			_e('Something is wrong! please try again.', 'wordpress-contributors');
			return;
		}

		if(empty($contributors)) {
			echo '<p>'.__('Contributor user is not available', 'wordpress-contributors').', '.__('click', 'wordpress-contributors').' <a href="'.admin_url('user-new.php').'" target="_blank">'.__('here', 'wordpress-contributors').'</a> '.__('to add new contributor user.', 'wordpress-contributors').'</p>';
			return;
		}

		$contributor_values = get_post_meta($post->ID, 'post_contributors', true);
		?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">
						<label for="blogname"><?php _e('Select Contributors', 'wordpress-contributors') ?></label>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text">
								<span><?php _e('Contributors', 'wordpress-contributors') ?></span>
							</legend>
							<?php
								$i=0;
								foreach ($contributors as $contributor) {
									if(!isset($contributor->data->ID)) {
										continue;
									}
									
									$contributor_id = $contributor->data->ID;
									$display_name = $contributor->data->display_name;
									$checked = (is_array($contributor_values) && in_array($contributor_id, $contributor_values)) ?'checked="checked"' : '';
									?>
									<label for="contributor_<?php echo $contributor_id; ?>">
										<input name="post_contributors[]" type="checkbox" id="contributor_<?php echo $contributor_id; ?>" value="<?php echo $contributor_id; ?>" <?php echo $checked; ?>>
										<?php echo $display_name; ?>
									</label>
									<?php
									echo ($i+1<$total_contributors) ? '<br />' : '';
									$i++;
								}
							?>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<?php

	}

	/**
	 * Save Contributors Meta Box Data
	 *
	 * @since	1.0.0
	 * @param 	Type $post_id post id
	 */
	public function save_contributors_meta_box_data( $post_id ) {

		if ( ! isset( $_POST['post_contributors'] ) ) {
			$post_contributors = get_post_meta($post_id, 'post_contributors', true);
			if(!empty($post_contributors)) {
				delete_post_meta($post_id, 'post_contributors');
			}
			return $post_id;
        }

		if(!empty($_POST['post_contributors'])) {
			update_post_meta($post_id, 'post_contributors', $_POST['post_contributors']);
		}

		return $post_id;
	}
}