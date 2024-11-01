<?php
namespace TinyFolders;

final class Plugin {
    const TAXONOMY = TINYFOLDERS_PLUGIN_NAME . '-media-category';

    public static function run() {
        static $instance = false;
        $instance = $instance ? $instance : new Plugin();
    }
    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'localization' ] );
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'restrict_manage_posts', [ $this, 'addAuthorDropdown' ] );
        add_action( 'pre_get_posts', [ $this, 'authorFilter' ] );
    }
    public function localization() {
        load_plugin_textdomain( 'tinyfolders', false, dirname(TINYFOLDERS_PLUGIN_BASE_NAME) . '/languages/' );
    }
    public function init() {
        $labels = [
            'name'              => esc_html_x( 'Media Categories', 'taxonomy general name', 'tinyfolders' ),
            'singular_name'     => esc_html_x( 'Media Category', 'taxonomy singular name', 'tinyfolders' ),
            'search_items'      => esc_html__( 'Search Media Categories', 'tinyfolders' ),
            'all_items'         => esc_html__( 'All Media Categories', 'tinyfolders' ),
            'parent_item'       => esc_html__( 'Parent Media Category', 'tinyfolders' ),
            'parent_item_colon' => esc_html__( 'Parent Media Category:', 'tinyfolders' ),
            'edit_item'         => esc_html__( 'Edit Media Category', 'tinyfolders' ),
            'update_item'       => esc_html__( 'Update Media Category', 'tinyfolders' ),
            'add_new_item'      => esc_html__( 'Add New Media Category', 'tinyfolders' ),
            'new_item_name'     => esc_html__( 'New Media Category Name', 'tinyfolders' ),
            'menu_name'         => esc_html__( 'Media Categories', 'tinyfolders' )
        ];

        $args = [
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => [ 'slug' => 'media-categories' ]
        ];

        register_taxonomy( self::TAXONOMY, [ 'attachment' ], $args );
    }
    public function addAuthorDropdown() {
        $screen = get_current_screen();
        if ( 'upload' !== $screen->base ) return;

        $category = sanitize_key( filter_input(INPUT_GET, self::TAXONOMY, FILTER_DEFAULT ) );
        $selected = intval( $category ) > 0 ? $category : '-1';
        $args = [
            'show_option_none' => 'All Categories',
            'name' => self::TAXONOMY,
            'selected' => $selected,
            'taxonomy' => self::TAXONOMY,
            'hide_empty' => 0
        ];
        wp_dropdown_categories( $args );
    }
    public function authorFilter( $query ) {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        if ( is_admin() && $query->is_main_query() ) {
            if ( isset( $_GET[ self::TAXONOMY ] ) ) {
                if ( $_GET[ self::TAXONOMY ] == -1 ) {
                    $query->set( self::TAXONOMY, '' );
                }else {
                    $term = get_term_by( 'id', intval( $_GET[ self::TAXONOMY ] ), self::TAXONOMY );
                    $query->set( self::TAXONOMY, $term->name );
                }
            }
        }
        // phpcs:enable
    }
}