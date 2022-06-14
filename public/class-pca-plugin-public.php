<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Pca_Plugin
 * @subpackage Pca_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pca_Plugin
 * @subpackage Pca_Plugin/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Pca_Plugin_Public {

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

		add_shortcode( 'analysis', [$this, 'analysis_callback']);
		add_shortcode( 'show_research', [$this, 'show_research_callback']);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pca-plugin-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
	}

	function analysis_callback($atts){
		ob_start();
		$atts = shortcode_atts(
			array(
				'form' => '',
				'pca1' => '',
				'pca2' => '',
				'pca3' => '',
				'pca4' => '',
				'pca5' => '',
				'pca6' => '',
				'pca7' => '',
				'pca8' => '',
				'pca9' => '',
				'pca10' => '',
			), $atts, 'analysis' 
		);
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pca-paired-analysis.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'ajaxdata', array(
			'form' => ''.do_shortcode('[wpforms id="'.$atts['form'].'"]').'',
			'form_id' => $atts['form'],
			'type' => '',
			'data' => [],
			'pca1' => $atts['pca1'],
			'pca2' => $atts['pca2'],
			'pca3' => $atts['pca3'],
			'pca4' => $atts['pca4'],
			'pca5' => $atts['pca5'],
			'pca6' => $atts['pca6'],
			'pca7' => $atts['pca7'],
			'pca8' => $atts['pca8'],
			'pca9' => $atts['pca9'],
			'pca10' => $atts['pca10'],
			'pquestion' => ((get_option('pca_paired_question')) ? get_option('pca_paired_question') : 'What do you prefer?')
		) );

		require_once plugin_dir_path( __FILE__ )."partials/pca-plugin-public-display.php";
		return ob_get_clean();
	}

	function show_research_callback($atts){
		ob_start();
		$atts = shortcode_atts(
			array(
				'id' => null,
			), $atts, 'show_research' );
		
		if($atts['id'] == null){
			return;
		}

		$wform = get_post_meta($atts['id'], 'form_data_form', true);
		$fdata = get_post_meta($atts['id'], 'research_wpform_data', true);
		$type = get_post_meta($atts['id'], 'research__type_value', true);
		$research_texts = get_post_meta($atts['id'], 'research_texts', true);
		$research_images = get_post_meta($atts['id'], 'research_images', true);

		$data = [];
		switch ($type) {
			case 'text':
				$data = $research_texts;
				break;
			case 'image':
				$data = array_map(function ($arr){
					return $arr['image'];
				}, $research_images);
				$data = array_values($data);
				break;
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pca-paired-analysis.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'ajaxdata', array(
			'form' => ''.do_shortcode('[wpforms id="'.$wform.'"]').'',
			'form_id' => $wform,
			'data' => $data,
			'type' => $type,
			'pca1' => ((array_key_exists('pca1', $fdata))?$fdata['pca1']:''),
			'pca2' => ((array_key_exists('pca2', $fdata))?$fdata['pca2']:''),
			'pca3' => ((array_key_exists('pca3', $fdata))?$fdata['pca3']:''),
			'pca4' => ((array_key_exists('pca4', $fdata))?$fdata['pca4']:''),
			'pca5' => ((array_key_exists('pca5', $fdata))?$fdata['pca5']:''),
			'pca6' => ((array_key_exists('pca6', $fdata))?$fdata['pca6']:''),
			'pca7' => ((array_key_exists('pca7', $fdata))?$fdata['pca7']:''),
			'pca8' => ((array_key_exists('pca8', $fdata))?$fdata['pca8']:''),
			'pca9' => ((array_key_exists('pca9', $fdata))?$fdata['pca9']:''),
			'pca10' => ((array_key_exists('pca10', $fdata))?$fdata['pca10']:''),
			'pquestion' => ((get_option('pca_paired_question')) ? get_option('pca_paired_question') : 'What do you prefer?')
		) );

		if(isset($_POST['wpforms'])){
			echo do_shortcode('[wpforms id="'.$wform.'"]');
		}else{
			?>
			<!--  Questions  -->
			<div id="items_questions">
				<ul id="list__items">
					
				</ul>
			</div>
			<?php
		}
		return ob_get_clean();
	}
}
