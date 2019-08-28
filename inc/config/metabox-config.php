<?php
/**
 * Metabox config file
 *
 * @package Cuponfy/inc/config
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

/**
 * Get the bootstrap!
 */
if ( file_exists(  get_template_directory() . '/inc/metabox/init.php' ) ) {
	require_once  get_template_directory() . '/inc/metabox/init.php';
    require_once  get_template_directory() . '/inc/metabox-addons/extra-types.php';
    require_once  get_template_directory() . '/inc/metabox-addons/icon/icon.php';
}


function cmb2_change_minutes_step( $l10n ){
    $l10n['defaults']['time_picker']['stepMinute'] = 1;
    return $l10n;
}

add_filter( 'cmb2_localized_data', 'cmb2_change_minutes_step' );


/**
 * Sanitizes WYSIWYG fields like WordPress does for post_content fields.
 */
function cmb2_html_content_sanitize( $content ) {
    return apply_filters( 'content_save_pre', $content );
}



/**
 * Metabox for Show on page IDs callback
 * @author Tom Morton
 * @link https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-show_on-filters
 *
 * @param bool $display
 * @param array $meta_box
 * @return bool display metabox
 */
function wpcoupon_metabox_show_on_cb( $field ) {
    global $post;

    $meta_box = $field->args;
    if ( ! isset( $meta_box['show_on_page'] ) ) {
        return true ;
    }

    $post_id = $post->ID;

    if ( ! $post_id ) {
        return false;
    }

    // See if there's a match
    return in_array( $post_id, (array) $meta_box['show_on_page'] );
}



add_action( 'cmb2_init', 'wpcoupon_coupon_meta_boxes' );
add_action( 'cmb2_init', 'wpcoupon_page_meta_boxes' );

/**
 * Add metabox for coupon
 * @since 1.0.0
 */
function wpcoupon_coupon_meta_boxes() {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_wpc_';

    $coupon_meta = new_cmb2_box( array(
        'id'            => $prefix . 'coupon',
        'title'         => esc_html__( 'Configurações de Cupons', 'wp-coupon' ),
        'object_types'  => array( 'coupon', ), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        // 'context'    => 'normal',
        // 'priority'   => 'high',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
    ) );


    $coupon_meta->add_field( array(
        'name'             => esc_html__( 'Tipo de Cupom', 'wp-coupon' ),
        'id'               => $prefix . 'coupon_type',
        'type'             => 'select',
        'show_option_none' => false,
        'options'          => wpcoupon_get_coupon_types(),
    ) );


    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Código de Cupom', 'wp-coupon' ),
        'id'            => $prefix . 'coupon_type_code',
        'type'          => 'text_medium',
        'attributes'    => array(
            'placeholder'   => esc_html__( 'Examplo: DESCONTO200', 'wp-coupon' ),
        ),
        'before_row'    => '<div class="st-condition-field cmb-row" data-show-when = "code" data-show-on="' . $prefix . 'coupon_type' . '">',
        'after_row'     => '</div>'

    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Coupon Printable Image', 'wp-coupon' ),
        'id'            => $prefix . 'coupon_type_printable',
        'type'          => 'file',
        'attributes'    => array(
            'placeholder'   => esc_html__( 'http://...', 'wp-coupon' ),
        ),
        'before_row'    => '<div class="st-condition-field cmb-row" data-show-when = "print" data-show-on="' . $prefix . 'coupon_type' . '">',
        'after_row'     => '</div>'
    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'URL do Cupom', 'wp-coupon' ),
        'id'            => $prefix . 'destination_url',
        'type'          => 'text_url',
        'desc'          => esc_html__( 'URL do cupom, se este campo estiver vazio, o link da loja será usado.', 'wp-coupon' ),
        'attributes'    => array(
            'placeholder'   => esc_html__( 'http://...', 'wp-coupon' ),
        ),
    ) );

    $coupon_meta->add_field( array(
        'name'       => esc_html__( 'Data de Início', 'wp-coupon' ),
        'id'         => $prefix . 'start_on',
        'type'       => 'text_datetime_timestamp',
        'desc'       => sprintf( __( 'Escolha data e hora de início da Promoção.', 'wp-coupon' ), esc_url( admin_url( 'admin.php?page=wpcoupon_options&tab=9' ) ) ),
    ) );/* By default start date based on GMT+0, <a href="%1$s" target="_blank">Click here</a> making the coupons start date based on selected timezone. */ 

    $coupon_meta->add_field( array(
        'name'       => esc_html__( 'Data de Término', 'wp-coupon' ),
        'id'         => $prefix . 'expires',
        'type'       => 'text_datetime_timestamp',
        'desc'       => sprintf( __( 'Escolha data e hora para término da Promoção', 'wp-coupon' ), esc_url( admin_url( 'admin.php?page=wpcoupon_options&tab=9' ) ) ),
    ) );/* By default expires date based on GMT+0, <a href="%1$s" target="_blank">Click here</a> to making the coupons get expired based on selected timezone. */
    
    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Valor do Desconto', 'wp-coupon' ),
        'id'            => $prefix . 'coupon_save',
        'type'          => 'text_medium',
        'attributes'    => array(
            'placeholder'   => esc_html__( 'Exemplo: 15% OFF', 'wp-coupon' ),
        ),
        'desc'          => esc_html__( 'Esse texto pode ser mostrado na Thumbnail da Promoção', 'wp-coupon' ),
        'before_row'    => '<div class="st-condition-field cmb-row">',
        'after_row'     => '</div>'
    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Cupom de Frete Grátis', 'wp-coupon' ),
        'desc'          => esc_html__( 'Esse cupom é de Frete Grátis?', 'wp-coupon' ),
        'id'            => $prefix . 'free_shipping',
        'type'          => 'checkbox'
    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Exclusive Coupon', 'wp-coupon' ),
        'desc'          => esc_html__( 'Esse cupom é de uso exclusivo?', 'wp-coupon' ),
        'id'            => $prefix . 'exclusive',
        'type'          => 'checkbox'
    ) );


    // Custom tracking
    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Número de vezes que o cupom foi trackeado', 'wp-coupon' ),
        'desc'          => esc_html__( '', 'wp-coupon' ),
        'id'            => $prefix . 'used',
        'type'          => 'text'
    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Número de vezes que o cupom foi visualizado', 'wp-coupon' ),
        'desc'          => esc_html__( '', 'wp-coupon' ),
        'id'            => $prefix . 'views',
        'type'          => 'text'
    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Votos Positivos', 'wp-coupon' ),
        'desc'          => esc_html__( '', 'wp-coupon' ),
        'id'            => $prefix . 'vote_up',
        'type'          => 'text'
    ) );

    $coupon_meta->add_field( array(
        'name'          => esc_html__( 'Votos Negativos', 'wp-coupon' ),
        'desc'          => esc_html__( '', 'wp-coupon' ),
        'id'            => $prefix . 'vote_down',
        'type'          => 'text'
    ) );


}



/**
 * Add meta box for pages
 */
function wpcoupon_page_meta_boxes() {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_wpc_';

    $page_meta = new_cmb2_box( array(
        'id'            => $prefix . 'page',
        'title'         => esc_html__( 'Configurações de Página', 'wp-coupon' ),
        'object_types'  => array( 'page' ), // Post type
    ) );

    $page_meta->add_field( array(
        'name'             => esc_html__( 'Layout de Página', 'wp-coupon' ),
        'desc'             => esc_html__( 'Escolha o layout de página para exibir, deixa este campo vazio para usar modelo padrão.', 'wp-coupon' ),
        'id'               => $prefix . 'layout',
        'type'             => 'select',
        'show_option_none' => esc_html__( 'Modelo Padrão (Opções do Tema)', 'wp-coupon' ),
        'default'          => '',
        'options'          => array(
            'right-sidebar'   => esc_html__( 'Barra Lateral à Direita', 'wp-coupon' ),
            'left-sidebar'    => esc_html__( 'Barra Lateral à Esquerda', 'wp-coupon' ),
            'no-sidebar'      => esc_html__( 'Sem Barra Lateral', 'wp-coupon' ),
        ),
    ) );

    $page_meta->add_field( array(
        'name'             => esc_html__( 'Exibir conteúdo em caixa sombreada', 'wp-coupon' ),
        'desc'             => esc_html__( 'Exibir divisórias de conteúdo em caixa sombreada.', 'wp-coupon' ),
        'id'               => $prefix . 'shadow_box',
        'type'             => 'select',
        'default'          => 'yes',
        'options'          => array(
            'no'    => esc_html__( 'Não, exibir o padrão', 'wp-coupon' ),
            'yes'   => esc_html__( 'Sim, exibir conteúdo em caixa sombreada', 'wp-coupon' ),
        ),
    ) );

    $page_meta->add_field( array(
        'name'             => esc_html__( 'Cabeçalho de página personalizado', 'wp-coupon' ),
        'desc'             => esc_html__( 'Cebeçalho de página personalizado.', 'wp-coupon' ),
        'id'               => $prefix . 'show_header',
        'type'             => 'select',
        'show_option_none' => esc_html__( 'Padrão (Opções do Tema)', 'wp-coupon' ),
        'default'          => 'on',
        'options'          => array(
            'on'    => esc_html__( 'Mostrar Título da Página', 'wp-coupon' ),
            'off'   => esc_html__( 'Ocultar Título da Página', 'wp-coupon' ),

        ),
    ) );

    $page_meta->add_field( array(
        'name'          => esc_html__( 'Ocultar breadcrumb', 'wp-coupon' ),
        'id'            => $prefix . 'hide_breadcrumb',
        'desc'          => sprintf( esc_html__( 'Você precisa instalar o  plugin %1$s para usar esta função.', 'wp-coupon' ),  '<a target="_blank" href="'.admin_url( 'update.php?action=install-plugin&plugin=breadcrumb-navxt&_wpnonce='.wp_create_nonce() ).'">'.esc_html__( 'Breadcrumb Navxt', 'wp-coupon' ).'</a>' ),
        'type'          => 'checkbox',
        //'default'       => 'on'
    ) );

    $page_meta->add_field( array(
        'name'          => esc_html__( 'Título de Página Personalizado', 'wp-coupon' ),
        'id'            => $prefix . 'custom_title',
        'desc'          => esc_html__( 'Mostrar a diferença do título da página no título acima.', 'wp-coupon' ),
        'type'          => 'text_medium',
    ) );

    $page_meta->add_field( array(
        'name'          => esc_html__( 'Ocultar capa do cabeçalho', 'wp-coupon' ),
        'id'            => $prefix . 'hide_cover',
        'desc'          => esc_html__( 'Marqui aqui se você quiser ocultar a capa do cabeçalho', 'wp-coupon' ),
        'type'          => 'checkbox',
        //'default'       => 'on'
    ) );

    $page_meta->add_field( array(
        'name'          => esc_html__( 'Imagem de Plano de Fundo', 'wp-coupon' ),
        'id'            => $prefix . 'cover_image',
        'type'          => 'file',
    ) );

    $page_meta->add_field( array(
        'name'    => esc_html__( 'Cor de Plano de Fundo', 'wp-coupon' ),
        'id'      => $prefix . 'cover_color',
        'type'    => 'colorpicker',
        'default' => '',
    ) );


    if ( wpcoupon_is_wc() ) {
        $shop_id = wc_get_page_id( 'shop' );
        $page_meta->add_field(array(
            'name' => esc_html__('Número de Produtos para Mostrar', 'wp-coupon'),
            'id' => $prefix . 'shop_number_products',
            'type' => 'text',
            'default' => '',
            'show_on_cb' => 'wpcoupon_metabox_show_on_cb',
            'show_on_page' => $shop_id, // Specific post IDs to display this metabox
        ));

        $page_meta->add_field(array(
            'name' => esc_html__('Número de Produtos por Linha', 'wp-coupon'),
            'id' => $prefix . 'shop_number_products_per_row',
            'type' => 'select',
            'default' => '',
            'show_on_cb' => 'wpcoupon_metabox_show_on_cb',
            'show_on_page' => $shop_id, // Specific post IDs to display this metabox
            'show_option_none' => esc_html__( 'Padrão', 'wp-coupon' ),
            'options'          => array(
                '2'   => esc_html__( '2 Colunas', 'wp-coupon' ),
                '3'   => esc_html__( '3 Colunas', 'wp-coupon' ),
                '4'   => esc_html__( '4 Colunas', 'wp-coupon' ),
                '5'   => esc_html__( '5 Colunas', 'wp-coupon' ),
                '6'   => esc_html__( '6 Colunas', 'wp-coupon' ),
            ),
        ));


    }



}



add_action( 'cmb2_admin_init', 'wpcoupon_register_coupon_store_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function wpcoupon_register_coupon_store_taxonomy_metabox() {
    $prefix = '_wpc_';

    /**
     * Metabox to add fields to coupon store
     */
    $store_meta = new_cmb2_box( array(
        'id'               => $prefix . 'store_meta',
        'title'            => esc_html__( 'Descrição de Loja', 'wp-coupon' ),
        'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
        'taxonomies'       => array( 'coupon_store' ), // Tells CMB2 which taxonomies should have these fields
        // 'new_term_section' => true, // Will display in the "Add New Category" section
    ) );

	$store_meta->add_field( array(
        'name'          => esc_html__( 'URL da Página Inicial', 'wp-coupon' ),
        'id'            => $prefix . 'store_url',
        'desc'          => esc_html__( 'URL da Página Inicial da Loja.', 'wp-coupon' ),
        'type'          => 'text_url',
        'attributes'    => array(
            'placeholder'   => esc_html__( 'http://example.com', 'wp-coupon' ),
        ),
    ) );

    $store_meta->add_field( array(
        'name'          => esc_html__( 'URL de Afiliado', 'wp-coupon' ),
        'id'            => $prefix . 'store_aff_url',
        'desc'          => esc_html__( 'URL de Afiliado de Loja.', 'wp-coupon' ),
        'type'          => 'text_url',
        'attributes'    => array(
            'placeholder'   => esc_html__( 'http://example.com', 'wp-coupon' ),
        ),
    ) );


    $store_meta->add_field( array(
        'name'          => esc_html__( 'Gerar Automaticamente a thumbnail', 'wp-coupon' ),
        'desc'          => esc_html__( 'Download Automático do screenshot da página inicial da loja e usá-lo como thumbnail se o URL da loja estiver correto. Esta função é desabilitada caso insira uma thumnail.', 'wp-coupon' ),
        'id'            => $prefix . 'auto_thumbnail',
        'type'          => 'checkbox'
    ) );

    $store_meta->add_field( array(
        'name'    => esc_html__( 'Thumbnail', 'wp-coupon' ),
        'id'      => $prefix . 'store_image',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
            //'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Adicionar ou Fazer Upload do Arquivo"
        ),
    ) );

	$store_meta->add_field( array(
        'name'          => esc_html__( 'Cabeçado de Loja Personalizado', 'wp-coupon' ),
        'id'            => $prefix . 'store_heading',
        'desc'          => esc_html__( 'Este cabeçalho será exibido na página individual de loja, exemplo: Lojas Americanas - Cupons de Descontos e Ofertas, se este campo estiver vazio, será usado cabeçalho do tema. Você pode usar %store_name% para o nome da loja.', 'wp-coupon' ),
        'type'          => 'text_medium',
        'sanitization_cb'    => 'cmb2_html_content_sanitize'
    ) );

    $store_meta->add_field( array(
        'name'          => esc_html__( 'Loja de Destaque', 'wp-coupon' ),
        'desc'          => esc_html__( 'Marque aqui se quiser dar destaque a loja.', 'wp-coupon' ),
        'id'            => $prefix . 'is_featured',
        'type'          => 'checkbox'
    ) );


	$store_meta->add_field( array(
        'name'     => esc_html__( 'Informações Extras', 'wp-coupon' ),
        'desc'     => esc_html__( 'Este conteúdo será exibido após a listagem de cupons.', 'wp-coupon' ),
        'id'       => $prefix . 'extra_info',
        'type'     => 'wysiwyg',
        'options' => array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => true, // show insert/upload button(s)
            ///'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
            'textarea_rows' => get_option('default_post_edit_rows', 6), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
            'editor_class' => '', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        ),
        'on_front' => true,
    ) );


    /**
     * Metabox to add fields to Coupon categories
     */
    $cat_meta = new_cmb2_box( array(
        'id'               => $prefix . 'coupon_category_meta',
        'title'            => esc_html__( 'Informações de Categorias', 'wp-coupon' ),
        'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
        'taxonomies'       => array( 'coupon_category' ), // Tells CMB2 which taxonomies should have these fields
        // 'new_term_section' => true, // Will display in the "Add New Category" section
    ) );

    $cat_meta->add_field( array(
        'name'          => esc_html__( 'Ícone', 'wp-coupon' ),
        'id'            => $prefix . 'icon',
        'type'          => 'icon',
        'desc'          => 'Ícone da Categoria',
    ) );


    $cat_meta->add_field( array(
        'name'    => esc_html__( 'Imagem', 'wp-coupon' ),
        'desc'    => 'Esta imagem é usada como thumbnail na página da Categoria',
        'id'      => $prefix . 'cat_image',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
            //'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
        ),
    ) );


}
