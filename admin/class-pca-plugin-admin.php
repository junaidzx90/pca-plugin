<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Pca_Plugin
 * @subpackage Pca_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pca_Plugin
 * @subpackage Pca_Plugin/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Pca_Plugin_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pca-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pca-plugin-admin.js', array( 'jquery' ), $this->version, false );

		global $post;
		if($post && $post->post_type === 'research'){
			wp_localize_script( $this->plugin_name, 'ajaxd', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			) );
		}
		
	}

	function research_comparison(){
		$labels = array(
			'name'                  => __( 'Research', $this->plugin_name ),
			'singular_name'         => __( 'Research', $this->plugin_name ),
			'menu_name'             => __( 'Research', $this->plugin_name ),
			'name_admin_bar'        => __( 'Research', $this->plugin_name ),
			'add_new'               => __( 'New research', $this->plugin_name ),
			'add_new_item'          => __( 'New research', $this->plugin_name ),
			'new_item'              => __( 'New research', $this->plugin_name ),
			'edit_item'             => __( 'Edit research', $this->plugin_name ),
			'view_item'             => __( 'View research', $this->plugin_name ),
			'all_items'             => __( 'Research', $this->plugin_name ),
			'search_items'          => __( 'Search researchs', $this->plugin_name ),
			'parent_item_colon'     => __( 'Parent researchs:', $this->plugin_name ),
			'not_found'             => __( 'No researchs found.', $this->plugin_name ),
			'not_found_in_trash'    => __( 'No researchs found in Trash.', $this->plugin_name )
		);
		$args = array(
			'labels'             => $labels,
			'description'        => 'research custom post type.',
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'research' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 45,
			'menu_icon'      	 => 'dashicons-screenoptions',
			'supports'           => array( 'title' ),
			'show_in_rest'       => false
		);
		  
		register_post_type( 'research', $args );
	}

	function research_comparison_meta_boxes(){
		global $wp_meta_boxes;
		unset($wp_meta_boxes['research']);
		add_meta_box( 'submitdiv', "Create Job", 'post_submit_meta_box', 'research', 'side' );
		add_meta_box( 'researchBox', "Researches", [$this, 'research_meta_box'], 'research', 'advanced' );
		add_meta_box( 'shortcodeDiv', "Shortcode", [$this, 'shortcode_meta_box'], 'research', 'side' );
		add_meta_box( 'wpformsParam', "Form parameters", [$this, 'wpform_parameters_callback'], 'research', 'side' );
	}
	
	function research_meta_box($post){
		require_once plugin_dir_path( __FILE__ )."partials/pca-plugin-admin-display.php";
	}

	function shortcode_meta_box($post){
		echo '<input class="widefat" type="text" readonly value=\'[show_research id="'.$post->ID.'"]\'>';
	}

	function get_wpforms_fields(){
		if(isset($_POST['form_id'])){
			try {
				$form = wpforms()->form->get( intval($_POST['form_id']) );
				$form_data = ! empty( $form->post_content ) ? wpforms_decode( $form->post_content ) : '';
				
				if(is_array($form_data) && array_key_exists('fields', $form_data)){
					echo json_encode(array("data" => $form_data['fields']));
					die;
				}
			} catch (\Throwable $th) {
				//throw $th;
			}
		}
	}

	function wpform_parameters_callback($post){
		$wform = get_post_meta($post->ID, 'form_data_form', true);
		$fdata = get_post_meta($post->ID, 'research_wpform_data', true);
		?>
		<div class="wpf_paremers">
			<label for="formId">Form ID
				<input class="widefat" type="number" name="form_data_form" id="formId" value="<?php echo $wform ?>">

				<button id="getFormFields" class="button-secondary">Get Form fields</button>
			</label>
			
			<div class="fieldIds">
				<?php
				if(is_array($fdata) && sizeof($fdata)>0){
					echo '<ul>';
					$i = 1;
					foreach($fdata as $data){
						echo '<li><strong>PCA-'.$i.' - </strong> ID: '.$data.'</li>';
						$i++;
					}
					echo '</ul>';
				}
				?>
			</div>
		</div>
		<?php
	}

	// Manage table columns
	function manage_research_columns($columns) {
		unset(
			$columns['title'],
			$columns['date']
		);
	
		$new_columns = array(
			'title' => __('Title', 'research'),
			'type' => __('Type', 'research'),
			'entries' => __('Entries', 'research'),
			'shortcode' => __('Shortcode', 'research'),
			'date' => __('Created', 'research'),
		);
	
		return array_merge($columns, $new_columns);
	}

	function manage_research_columns_view($column_id, $post_id){
		$type = get_post_meta($post_id, 'research__type_value', true);
		$research_texts = get_post_meta($post_id, 'research_texts', true);
		$research_images = get_post_meta($post_id, 'research_images', true);

		switch ($column_id) {
			case 'type':
				echo ucfirst($type);
				break;
			case 'entries':
				if($type === 'text'){
					echo sizeof($research_texts);
				}
				if($type === 'image'){
					echo sizeof($research_images);
				}
				break;
			case 'shortcode':
				echo '<input type="text" readonly value=\'[show_research id="'.$post_id.'"]\'>';
				break;
		}
	}
	
	// Remove Quick edit
	function remove_quick_edit_research( $actions, $post ) {
		if(get_post_type( $post ) === 'research'){
			unset($actions['inline hide-if-no-js']);
			return $actions;
		}else{
			return $actions;
		}
	}

	function save_post_research_cb($post_id){

		if(isset($_POST['research__type_value'])){
			update_post_meta( $post_id, 'research__type_value', $_POST['research__type_value'] );
		}

		if(isset($_POST['research_texts'])){
			$research_texts = $_POST['research_texts'];
			$research_texts = array_map(function($str){
				return sanitize_text_field( $str );
			}, $research_texts);
			update_post_meta( $post_id, 'research_texts', $research_texts );
		}

		if(isset($_POST['research_images'])){
			$research_images = $_POST['research_images'];

			$data = [];
			foreach($research_images as $key => $image){
				$s = [
					'image' => $image['img'],
					'desc' => sanitize_text_field( $image['desc'] )
				];
				$data[$key] = $s;
			}

			update_post_meta( $post_id, 'research_images', $data );
		}

		if(isset($_POST['form_data_form'])){
			update_post_meta($post_id, 'form_data_form', $_POST['form_data_form']);
		}
		if(isset($_POST['form_data'])){
			update_post_meta($post_id, 'research_wpform_data', $_POST['form_data']);
		}
	}

	function admin_menupage(){
		add_submenu_page( "edit.php?post_type=research", "Settings", "Settings", "manage_options", "pca-settings", [$this, "analysis_comparision_setting"], null );
		add_settings_section( 'pca_opt_section', '', '', 'pca_opt_page' );
		// Paired question
		add_settings_field( 'pca_paired_question', 'Paired question', [$this, 'pca_paired_question_cb'], 'pca_opt_page','pca_opt_section' );
		register_setting( 'pca_opt_section', 'pca_paired_question' );
		add_settings_field( 'pca_paired_analysis', 'Paired analysis', [$this, 'pca_paired_analysis_cb'], 'pca_opt_page','pca_opt_section' );
	}

	function pca_paired_question_cb(){
		echo '<input type="text" class="widefat" name="pca_paired_question" value="'.get_option('pca_paired_question').'" placeholder="What do you prefer?">';
	}

	function pca_paired_analysis_cb(){
		echo '<p><code>[analysis form="" pca1="" pca2="" pca3="" pca4="" pca5="" pca6="" pca7="" pca8="" pca9="" pca10=""]</code></p>';
		echo '<p>All parameters expect field ID</p>';
	}

	function analysis_comparision_setting(){
		?>
		<h3>Settings</h3>
		<hr>
		<form style="width: 50%" action="options.php" method="post">
			<?php
			settings_fields( 'pca_opt_section' );
			do_settings_sections( 'pca_opt_page' );
			submit_button(  );
			?>
		</form>
		<?php
	}
}
