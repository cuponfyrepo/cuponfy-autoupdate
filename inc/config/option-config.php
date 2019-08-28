<?php

/**
 * Theme Options Config
 */

if ( ! class_exists( 'WPCoupon_Theme_Options_Config' ) ) {

	class WPCoupon_Theme_Options_Config {

		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct() {

			if ( ! class_exists( 'ReduxFramework' ) ) {
				return;
			}
			$this->initSettings();
		}


		public function initSettings() {

			// Set the default arguments
			$this->setArguments();

			// Set a few help tabs so you can see how it's done
			$this->setHelpTabs();

			// Create the sections and fields
			$this->setSections();

			if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
				return;
			}

			$this->args = apply_filters( 'st_redux_theme_options_args', $this->args );

			$this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
		}

		public function setHelpTabs() {

			// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
			$this->args['help_tabs'][] = array(
				'id'      => 'redux-help-tab-1',
				'title'   => esc_html__( 'Theme Information 1', 'wp-coupon' ),
				'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'wp-coupon' ),
			);

			$this->args['help_tabs'][] = array(
				'id'      => 'redux-help-tab-2',
				'title'   => esc_html__( 'Theme Information 2', 'wp-coupon' ),
				'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'wp-coupon' ),
			);

			// Set the help sidebar
			$this->args['help_sidebar'] = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'wp-coupon' );
		}

		/**
		 * All the possible arguments for Redux.
		 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
		 * */
		public function setArguments() {

			$theme = wp_get_theme(); // For use with some settings. Not necessary.

			$this->args = array(
				// TYPICAL -> Change these values as you need/desire
				'opt_name'           => 'st_options',
				// This is where your data is stored in the database and also becomes your global variable name.
				'display_name'       => $theme->get( 'Name' ),
				// Name that appears at the top of your panel
				'display_version'    => false,
				// Version that appears at the top of your panel
				'menu_type'          => 'menu', // submenu , menu
				// Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'     => false,
				// Show the sections below the admin menu item or not
				'menu_title'         => esc_html__( 'Cuponfy', 'wp-coupon' ),
				'page_title'         => esc_html__( 'Opções do Tema', 'wp-coupon' ),
				// You will need to generate a Google API key to use this feature.
				// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
				'google_api_key'     => '',
				// Must be defined to add google fonts to the typography module
				'async_typography'   => false,
				// Use a asynchronous font on the front end or font string
				'admin_bar'          => false,
				// Show the panel pages on the admin bar
				'global_variable'    => 'st_option',
				// Set a different name for your global variable other than the opt_name
				'dev_mode'           => false,
				// Show the time the page took to load, etc
				'customizer'         => false,
				// Enable basic customizer support
				// OPTIONAL -> Give you extra features
				// 'page_priority'      => 65,
				// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent'        => 'themes.php', // themes.php
				// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions'   => 'manage_options',
				// Permissions needed to access the options panel.
				'menu_icon'          => '',
				// Specify a custom URL to an icon
				'last_tab'           => '',
				// Force your panel to always open to a specific tab (by id)
				'page_icon'          => 'icon-themes',
				// Icon displayed in the admin panel next to your menu_title
				'page_slug'          => 'wpcoupon_options',
				// Page slug used to denote the panel
				'save_defaults'      => true,
				// On load save the defaults to DB before user clicks save or not
				'default_show'       => false,
				// If true, shows the default value next to each field that is not the default value.
				'default_mark'       => '',
				// What to print by the field's title if the value shown is default. Suggested: *
				'show_import_export' => true,
				// Shows the Import/Export panel when not used as a field.
				// CAREFUL -> These options are for advanced use only
				'transient_time'     => 60 * MINUTE_IN_SECONDS,

				'output'             => true,
				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag'         => true,
				// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				'footer_credit'     => ' ',
				// Disable the footer credit of Redux. Please leave if you can help it.
				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database'           => '',
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'system_info'        => false,
				// REMOVE
				// HINTS
				'hints'              => array(
					'icon'          => 'icon-question-sign',
					'icon_position' => 'right',
					'icon_color'    => 'lightgray',
					'icon_size'     => 'normal',
					'tip_style'     => array(
						'color'   => 'light',
						'shadow'  => true,
						'rounded' => false,
						'style'   => '',
					),
					'tip_position'  => array(
						'my' => 'top left',
						'at' => 'bottom right',
					),
					'tip_effect'    => array(
						'show' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'mouseover',
						),
						'hide' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'click mouseleave',
						),
					),
				),
			);

			// Panel Intro text -> before the form
			if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
				if ( ! empty( $this->args['global_variable'] ) ) {
					$v = $this->args['global_variable'];
				} else {
					$v = str_replace( '-', '_', $this->args['opt_name'] );
				}
				// $this->args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'wp-coupon' ), $v );
			} else {
				// $this->args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'wp-coupon' );
			}

			// Add content after the form.
			// $this->args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'wp-coupon' );
		}

		public function setSections() {

			/*
			--------------------------------------------------------*/
			/*
			 CONFIGURAÇÕES GERAIS
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Geral', 'wp-coupon' ),
				// 'desc'   => sprintf( esc_html__( 'Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: %d', 'wp-coupon' ), '<a href="' . 'https://' . 'github.com/ReduxFramework/Redux-Framework">' . 'https://' . 'github.com/ReduxFramework/Redux-Framework</a>' ),
				'desc'   => '',
				'icon'   => 'el-icon-cog el-icon-large',
				'submenu' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
				'fields' => array(

					array(
						'id'       => 'site_logo',
						'url'      => false,
						'type'     => 'media',
						'title'    => esc_html__( 'Modificar Logo do site', 'wp-coupon' ),
						'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/logo.png' ),
						'subtitle' => esc_html__( 'Fazer Upload de seu logotipo aqui.', 'wp-coupon' ),
					),
					array(
						'id'       => 'site_logo_retina',
						'url'      => false,
						'type'     => 'media',
						'title'    => esc_html__( 'Modificar Logo Retina do site', 'wp-coupon' ),
						'default'  => '',
						'subtitle' => esc_html__( 'Envie pelo exatamente 2x o tamanho do seu logotipo (opcional), o nome deve incluir @2x no final, exemplo logo@2x.png', 'wp-coupon' ),
					),

					array(
						'id'      => 'layout',
						'title'   => esc_html__( 'Layout do site', 'wp-coupon' ),
						'desc'    => esc_html__( 'Prédefinição de Layout do site', 'wp-coupon' ),
						'type'    => 'button_set',
						'default' => 'right-sidebar',
						// 'required' => array('footer_widgets','=',true, ),
						'options' => array(
							'left-sidebar'   => esc_html__( 'Barra Lateral à Esquerda', 'wp-coupon' ),
							'no-sidebar'     => esc_html__( 'Sem Barra Lateral', 'wp-coupon' ),
							'right-sidebar'  => esc_html__( 'Barra Lateral à Direita', 'wp-coupon' ),
						),
					),

					array(
						'id'       => 'coupons_listing_page',
						'url'      => false,
						'type'     => 'select',
						'data'     => 'page',
						'title'    => esc_html__( 'Página de listagem de categoria dos cupons', 'wp-coupon' ),
						'default'  => '',
					),

					array(
						'id'       => 'stores_listing_page',
						'url'      => false,
						'type'     => 'select',
						'data'     => 'page',
						'title'    => esc_html__( 'Página de listagem das lojas', 'wp-coupon' ),
						'default'  => '',
					),

					array(
						'id'       => 'stores_listing_hide_child',
						'url'      => false,
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Ocultar da listagem lojas internas à outra lojas', 'wp-coupon' ),
						'default'  => 0,
					),

					array(
						'id'   => 'divider_r',
						'type' => 'divide',
					),

					array(
						'id'       => 'search_only_coupons',
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Mostrar somente cupons no resultado de buscas', 'wp-coupon' ),
						'default'  => 1,
					),

					array(
						'id'   => 'divider_r_2',
						'desc' => '',
						'type' => 'divide',
					),

					array(
						'id'       => 'rewrite_store_slug',
						'url'      => false,
						'type'     => 'text',
						'title'    => esc_html__( 'Reescrever slug personalizado para lojas', 'wp-coupon' ),
						'subtitle'    => esc_html__( 'Padrão: store', 'wp-coupon' ),
						'default'  => 'store',
						'desc'     => sprintf( esc_html__( 'Se você alterar esta opção, por favor, vá para Configurações &#8594; %1$s e actualize a estrutura de permalink antes de seu custom post type mostrar a estrutura correta.', 'wp-coupon' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . esc_html__( 'Permalinks', 'wp-coupon' ) . '</a>' ),
					),

					array(
						'id'       => 'rewrite_category_slug',
						'url'      => false,
						'type'     => 'text',
						'title'    => esc_html__( 'Reescrever slug personalizado para categoria', 'wp-coupon' ),
						'subtitle'    => esc_html__( 'Padrão: coupon-category', 'wp-coupon' ),
						'default'  => 'coupon-category',
						'desc'     => sprintf( esc_html__( 'Se você alterar esta opção, por favor, vá para Configurações &#8594; %1$s e actualize a estrutura de permalink antes de seu custom post type mostrar a estrutura correta.', 'wp-coupon' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . esc_html__( 'Permalinks', 'wp-coupon' ) . '</a>' ),
					),

					array(
						'id'       => 'rewrite_tag_slug',
						'url'      => false,
						'type'     => 'text',
						'title'    => esc_html__( 'Reesvrever slug personalizado para tags', 'wp-coupon' ),
						'subtitle'    => esc_html__( 'Padrão: coupon-tag', 'wp-coupon' ),
						'default'  => 'coupon-tag',
						'desc'     => sprintf( esc_html__( 'Se você alterar esta opção, por favor, vá para Configurações &#8594; %1$s e actualize a estrutura de permalink antes de seu custom post type mostrar a estrutura correta.', 'wp-coupon' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . esc_html__( 'Permalinks', 'wp-coupon' ) . '</a>' ),
					),

					array(
						'id'   => 'divider_r_2',
						'desc' => '',
						'type' => 'divide',
					),

					array(
						'id'       => 'disable_feed_links',
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Desabilitar feed de links.', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Se você quiser desabilitar, bastar marcar esta opção.', 'wp-coupon' ),
					),

				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 ESTILO
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Estilos', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-idea',
				'submenu' => true,
				'fields' => array(

					array(
						'id'       => 'style_primary',
						'type'     => 'color',
						'title'    => esc_html__( 'Primary', 'wp-coupon' ),
						'default'  => '#00979d',
						'output'    => array(
							'background-color' => '
                                #header-search .header-search-submit, 
                                .newsletter-box-wrapper.shadow-box .input .ui.button,
                                .wpu-profile-wrapper .section-heading .button,
                                input[type="reset"], input[type="submit"], input[type="submit"],
                                .site-footer .widget_newsletter .newsletter-box-wrapper.shadow-box .sidebar-social a:hover,
                                .ui.button.btn_primary,
                                .site-footer .newsletter-box-wrapper .input .ui.button,
                                .site-footer .footer-social a:hover,
                                .site-footer .widget_newsletter .newsletter-box-wrapper.shadow-box .sidebar-social a:hover,
								.coupon-filter .ui.menu .item .offer-count,
								.coupon-filter .filter-coupons-buttons .store-filter-button .offer-count,
                                .newsletter-box-wrapper.shadow-box .input .ui.button,
                                .newsletter-box-wrapper.shadow-box .sidebar-social a:hover,
                                .wpu-profile-wrapper .section-heading .button,
                                .ui.btn.btn_primary,
								.ui.button.btn_primary,
								.coupon-filter .filter-coupons-buttons .submit-coupon-button:hover,
								.coupon-filter .filter-coupons-buttons .submit-coupon-button.active,
								.coupon-filter .filter-coupons-buttons .submit-coupon-button.active:hover,
								.coupon-filter .filter-coupons-buttons .submit-coupon-button.current::after,
                                .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce button.button.alt,
                                .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt
                            ',

							'color'            => '
                                .primary-color,
                                    .primary-colored,
                                    a,
                                    .ui.breadcrumb a,
                                    .screen-reader-text:hover,
                                    .screen-reader-text:active,
                                    .screen-reader-text:focus,
                                    .st-menu a:hover,
                                    .st-menu li.current-menu-item a,
                                    .nav-user-action .st-menu .menu-box a,
                                    .popular-stores .store-name a:hover,
                                    .store-listing-item .store-thumb-link .store-name a:hover,
                                    .store-listing-item .latest-coupon .coupon-title a,
                                    .store-listing-item .coupon-save:hover,
                                    .store-listing-item .coupon-saved,
                                    .coupon-modal .coupon-content .user-ratting .ui.button:hover i,
                                    .coupon-modal .coupon-content .show-detail a:hover,
                                    .coupon-modal .coupon-content .show-detail .show-detail-on,
                                    .coupon-modal .coupon-footer ul li a:hover,
                                    .coupon-listing-item .coupon-detail .user-ratting .ui.button:hover i,
                                    .coupon-listing-item .coupon-detail .user-ratting .ui.button.active i,
                                    .coupon-listing-item .coupon-listing-footer ul li a:hover, .coupon-listing-item .coupon-listing-footer ul li a.active,
                                    .coupon-listing-item .coupon-exclusive strong i,
                                    .cate-az a:hover,
                                    .cate-az .cate-parent > a,
                                    .site-footer a:hover,
                                    .site-breadcrumb .ui.breadcrumb a.section,
                                    .single-store-header .add-favorite:hover,
                                    .wpu-profile-wrapper .wpu-form-sidebar li a:hover,
                                    .ui.comments .comment a.author:hover       
                                ',
							'border-color' => '
                                textarea:focus,
                                input[type="date"]:focus,
                                input[type="datetime"]:focus,
                                input[type="datetime-local"]:focus,
                                input[type="email"]:focus,
                                input[type="month"]:focus,
                                input[type="number"]:focus,
                                input[type="password"]:focus,
                                input[type="search"]:focus,
                                input[type="tel"]:focus,
                                input[type="text"]:focus,
                                input[type="time"]:focus,
                                input[type="url"]:focus,
                                input[type="week"]:focus
                            ',
							'border-top-color' => '
                                .sf-arrows > li > .sf-with-ul:focus:after,
                                .sf-arrows > li:hover > .sf-with-ul:after,
                                .sf-arrows > .sfHover > .sf-with-ul:after
                            ',
							'border-left-color' => '
                                .sf-arrows ul li > .sf-with-ul:focus:after,
                                .sf-arrows ul li:hover > .sf-with-ul:after,
                                .sf-arrows ul .sfHover > .sf-with-ul:after,
                                .entry-content blockquote
							',
							'border-bottom-color' => '
								.coupon-filter .filter-coupons-buttons .submit-coupon-button.current::after
							',
							'border-right-color' => '
								.coupon-filter .filter-coupons-buttons .submit-coupon-button.current::after
							',
						),
					),

					array(
						'id'       => 'style_secondary',
						'type'     => 'color',
						'title'    => esc_html__( 'Secondary', 'wp-coupon' ),
						'default'  => '#ff9900',
						'output'    => array(
							'background-color' => '
                               .ui.btn,
                               .ui.btn:hover,
                               .ui.btn.btn_secondary,
                               .coupon-button-type .coupon-deal, .coupon-button-type .coupon-print, 
							   .coupon-button-type .coupon-code .get-code,
							   .coupon-filter .filter-coupons-buttons .submit-coupon-button.active.current
                            ',

							'color' => '
                                .a:hover,
                                .secondary-color,
                               .nav-user-action .st-menu .menu-box a:hover,
                               .store-listing-item .latest-coupon .coupon-title a:hover,
                               .ui.breadcrumb a:hover
                            ',

							'border-color' => '
                                .store-thumb a:hover,
                                .coupon-modal .coupon-content .modal-code .code-text,
                                .single-store-header .header-thumb .header-store-thumb a:hover
                            ',
							'border-left-color' => '
                                .coupon-button-type .coupon-code .get-code:after 
                            ',
						),
					),

					array(
						'id'       => 'style_c_code',
						'type'     => 'color',
						'title'    => esc_html__( 'Cupom de Desconto', 'wp-coupon' ),
						'default'  => '#b9dc2f',
						'output'    => array(
							'background-color' => '
                                .coupon-listing-item .c-type .c-code,
								.coupon-filter .ui.menu .item .code-count,
								.coupon-filter .filter-coupons-buttons .store-filter-button .offer-count.code-count
                            ',
						),
					),

					array(
						'id'       => 'style_c_sale',
						'type'     => 'color',
						'title'    => esc_html__( 'Link com Desconto', 'wp-coupon' ),
						'default'  => '#ea4c89',
						'output'    => array(
							'background-color' => '
                                .coupon-listing-item .c-type .c-sale,
								.coupon-filter .ui.menu .item .sale-count,
								.coupon-filter .filter-coupons-buttons .store-filter-button .offer-count.sale-count
                            ',
						),
					),
					/*
					array(
						'id'       => 'style_c_print',
						'type'     => 'color',
						'title'    => esc_html__( 'Coupon print', 'wp-coupon' ),
						'default'  => '#2d3538',
						'output'    => array(
							'background-color' => '
                                .coupon-listing-item .c-type .c-print,
								.coupon-filter .ui.menu .item .print-count,
								.coupon-filter .filter-coupons-buttons .store-filter-button .offer-count.print-count
                            ',
						),
					),
					*/
					array(
						'id'       => 'style_body_bg',
						'type'     => 'background',
						'title'    => esc_html__( 'Body background', 'wp-coupon' ),
						'default'  => array(
							'background-color' => '#f8f9f9',
						),
						'output' => array( 'body' ),
					),
				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 HEADER
			/*--------------------------------------------------------*/

			$this->sections[] = array(
				'title'  => esc_html__( 'Header', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-file',
				'submenu' => true,
				'fields' => array(

					array(
						'id'       => 'header_sticky',
						'url'      => false,
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Habilitar Menu Fixo', 'wp-coupon' ),
						'default'  => '',
						'subtitle' => esc_html__( 'Esta opção é desabilitada para Mobile.', 'wp-coupon' ),
					),

					array(
						'id'          => 'header_icons',
						'type'        => 'slides_v2',
						'title'       => esc_html__( 'Menu de Ícones', 'wp-coupon' ),
						// 'subtitle'    => esc_html__('Unlimited slides with drag and drop sortings.', 'wp-coupon'),
						'desc'        => sprintf( esc_html__( 'Você encontrar os códigos dos ícones aqui %s', 'wp-coupon' ), '<a target="_blank" href="http://semantic-ui.com/elements/icon.html">http://semantic-ui.com/elements/icon.html</a>.' ),
						'placeholder' => array(
							'title'           => esc_html__( 'Menu', 'wp-coupon' ),
							'description'     => esc_html__( 'Insira o HTML do ícone aqui.', 'wp-coupon' ),
							'url'             => esc_html__( 'Direcionar para seguinte URL', 'wp-coupon' ),
						),
						'show' => array(
							'image' => false,
							'title' => true,
							'description' => true,
							'url' => true,
						),
						'content_title' => esc_html__( 'Item', 'wp-coupon' ),
					),

					array(
						'id'       => 'top_search_stores',
						'type'     => 'select',
						'title'    => esc_html__( 'Escolhe os "Mais Pesquisados"', 'wp-coupon' ),
						'data' => 'terms',
						'sortable' => true,
						'multi' => false,
						'width' => '100%',
						'args' => array(
							'taxonomies' => 'coupon_store',
							'args' => array(),
						),
						// Must provide key => value pairs for select options
					),

					array(
						'id'       => 'header_custom_color',
						'type'     => 'switch',
						'title'    => esc_html__( 'Personalizar o menu?', 'wp-coupon' ),
						'default'  => false,
					),
					array(
						'id'       => 'header_bg',
						'type'     => 'background',
						// 'compiler' => true,
						'output'   => array( '.primary-header' ),
						'title'    => esc_html__( 'Plano de fundo do menu', 'wp-coupon' ),
						'required' => array( 'header_custom_color', '=', true ),
						'default'  => array(),
					),

					array(
						'id'       => 'header_color',
						'type'     => 'color',
						'title'    => esc_html__( 'Color', 'wp-coupon' ),
						'output'   => array( '.header-highlight a .highlight-icon', '.header-highlight a .highlight-text', '.primary-header', '.primary-header a', '#header-search .search-sample a' ),
						'required' => array( 'header_custom_color', '=', true ),
					),

				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 TIPOGRAFIA
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'      => esc_html__( 'Tipografia', 'wp-coupon' ),
				'header'     => '',
				'desc'       => '',
				'icon_class' => 'el-icon-large',
				'icon'       => 'el-icon-font',
				'submenu'    => true,
				'fields'     => array(
					array(
						'id'             => 'font_body',
						'type'           => 'typography',
						'title'          => esc_html__( 'Conteúdo Principal', 'wp-coupon' ),
						'compiler'       => true,
						'google'         => true,
						'font-backup'    => false,
						'font-weight'    => true,
						'all_styles'     => true,
						'font-style'     => false,
						'subsets'        => true,
						'font-size'      => true,
						'line-height'    => false,
						'word-spacing'   => false,
						'letter-spacing' => false,
						'color'          => true,
						'preview'        => true,
						'output'         => array( 'body, p' ),
						'units'          => 'px',
						'subtitle'       => esc_html__( 'Escolha a fonte personalizada principal do seu site', 'wp-coupon' ),
						'default'        => array(
							'font-family' => 'Open Sans',
						),
					),
					array(
						'id'             => 'font_heading',
						'type'           => 'typography',
						'title'          => esc_html__( 'Cabeçalhos', 'wp-coupon' ),
						'compiler'       => true,
						'google'         => true,
						'font-backup'    => false,
						'all_styles'     => true,
						'font-weight'    => false,
						'font-style'     => false,
						'subsets'        => true,
						'font-size'      => false,
						'line-height'    => false,
						'word-spacing'   => false,
						'letter-spacing' => true,
						'color'          => true,
						'preview'        => true,
						'output'         => array( 'h1,h2,h3,h4,h5,h6' ),
						'units'          => 'px',
						'subtitle'       => esc_html__( 'Selecionar fonte personalizada para os h1, h2, h3, ...', 'wp-coupon' ),
						'default'        => array(),
					),
				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 BARRA INFERIOR DO MENU
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Barra do Menu', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-credit-card',
				'submenu' => true,
				'fields' => array(
					array(
						'id'             => 'primary_menu_typography',
						'type'           => 'typography',
						'output'         => array(
							'.primary-navigation .st-menu > li > a,
                                                    .nav-user-action .st-menu > li > a,
                                                    .nav-user-action .st-menu > li > ul > li > a
                                                    ',
						),
						'title'          => esc_html__( 'Tipografia', 'wp-coupon' ),
						'compiler'       => true,
						'google'         => true,
						'font-backup'    => false,
						'text-align'     => false,
						'text-transform' => true,
						'font-weight'    => true,
						'all_styles'     => false,
						'font-style'     => true,
						'subsets'        => true,
						'font-size'      => true,
						'line-height'    => false,
						'word-spacing'   => false,
						'letter-spacing' => true,
						'color'          => true,
						'preview'        => true,
						'units'          => 'px',
						'subtitle'       => esc_html__( 'Tipografia para o menu principal', 'wp-coupon' ),
						'default'        => array(),
					),
				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 PÁGINA
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Página', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-file',
				'submenu' => true,
				'fields' => array(

					// Page settings
					array(
						'id'      => 'page_header',
						'title'   => esc_html__( 'Cabeçalho da página', 'wp-coupon' ),
						'type'    => 'button_set',
						'default' => 'on',
						// 'required' => array('footer_widgets','=',true, ),
						'options' => array(
							'on'    => esc_html__( 'Mostras cabeçalho da página', 'wp-coupon' ),
							'off'   => esc_html__( 'Ocultar cabeçalho da página', 'wp-coupon' ),
						),
					),

					array(
						'id'      => 'page_header_breadcrumb',
						'type'    => 'switch',
						'title'   => esc_html__( 'Mostrar breadcrumb do cabeçalho', 'wp-coupon' ),
						'required' => array( 'page_header', '=', array( 'on' ) ),
						'default'  => true,
						'desc'  => esc_html__( 'Você precisa ter o plugin Breadcrumb Navxt para esta função funcionar.', 'wp-coupon' ),
					),

					array(
						'id'      => 'page_header_cover',
						'type'    => 'switch',
						'title'   => esc_html__( 'Mostrar imagem de capa', 'wp-coupon' ),
						'required' => array( 'page_header', '=', array( 'on' ) ),
						// 'subtitle' => esc_html__('Look, it\'s on!', 'redux-framework-demo'),
						'default'  => false,
					),

					array(
						'id'      => 'page_header_cover_img',
						'type'    => 'media',
						'title'   => esc_html__( 'Header cover image', 'wp-coupon' ),
						'required' => array( 'page_header_cover', '=', array( true ) ),
						// 'subtitle' => esc_html__('Look, it\'s on!', 'redux-framework-demo'),
						'default'  => '',
					),

				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 BLOG
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Blog', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-pencil',
				'submenu' => true,
				'fields' => array(

					array(
						'id'      => 'blog_header',
						'title'   => esc_html__( 'Cabeçalho do Blog', 'wp-coupon' ),
						'type'    => 'button_set',
						'default' => 'on',
						'options' => array(
							'on'    => esc_html__( 'Mostrar cabeçalho do Blog', 'wp-coupon' ),
							'off'   => esc_html__( 'Ocultar Cabeçalho do Blog', 'wp-coupon' ),
						),
					),

					array(
						'id'      => 'blog_header_title',
						'type'    => 'text',
						'title'   => esc_html__( 'Título Personalizado para o Blog', 'wp-coupon' ),
						'required' => array( 'blog_header', '=', array( 'on' ) ),
						'default'  => '',
					),

					array(
						'id'      => 'blog_header_breadcrumb',
						'type'    => 'switch',
						'title'   => esc_html__( 'Mostras breadcrumb do Blog', 'wp-coupon' ),
						'required' => array( 'blog_header', '=', array( 'on' ) ),
						'default'  => true,
						'desc'  => esc_html__( 'Você precisa instalar o plugin Breadcrumb Navxt para usar esta função.', 'wp-coupon' ),
					),

					array(
						'id'      => 'blog_header_cover',
						'type'    => 'switch',
						'title'   => esc_html__( 'Mostrar imagem de capa', 'wp-coupon' ),
						'required' => array( 'blog_header', '=', array( 'on' ) ),
						// 'subtitle' => esc_html__('Look, it\'s on!', 'redux-framework-demo'),
						'default'  => false,
					),

					array(
						'id'      => 'blog_header_cover_img',
						'type'    => 'media',
						'title'   => esc_html__( 'Header cover image', 'wp-coupon' ),
						'required' => array( 'blog_header_cover', '=', array( true ) ),
						'default'  => '',
					),

				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 LOJA
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Página individual de loja', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-shopping-cart',
				'submenu' => true,
				'fields' => array(

					array(
						'id'      => 'store_loop_tpl',
						'title'   => esc_html__( 'Modelo de Página', 'wp-coupon' ),
						'desc'    => esc_html__( 'Selecione o modelo para loja', 'wp-coupon' ),
						'type'    => 'select',
						'default' => 'full',
						'options' => array(
							'full'  => esc_html__( 'Full', 'wp-coupon' ),
							'cat'   => esc_html__( 'Less', 'wp-coupon' ),
						),
					),

					array(
						'id'       => 'coupon_store_show_thumb',
						'type'     => 'select',
						'default' => 'default',
						'title'    => esc_html__( 'Mostrar thumbnail de cupom', 'wp-coupon' ),
						'options' => array(
							'default'                   => esc_html__( 'Padrão: Mostrar thumbnail do cupom, se não houver, mostrar da loja.', 'wp-coupon' ),
							'hide_if_no_thumb'          => esc_html__( 'Mostrar se tiver thumbnail', 'wp-coupon' ),
							'save_value'                => esc_html__( 'Mostrar valor do desconto como thumbnail', 'wp-coupon' ),
							'hide'                      => esc_html__( 'Ocultar', 'wp-coupon' ),
						),
					),

					array(
						'id'      => 'store_layout',
						'title'   => esc_html__( 'Layout de página individual da loja', 'wp-coupon' ),
						'desc'    => esc_html__( 'Padrão de estilo da página.', 'wp-coupon' ),
						'type'    => 'button_set',
						'default' => 'left-sidebar',
						// 'required' => array('footer_widgets','=',true, ),
						'options' => array(
							'left-sidebar'   => esc_html__( 'Left sidebar', 'wp-coupon' ),
							'right-sidebar'  => esc_html__( 'Right sidebar', 'wp-coupon' ),
						),
					),
					array(
						'id'       => 'store_socialshare',
						'type'     => 'switch',
						'title'    => esc_html__( 'Compartilhamento de loja', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Habilitar botão de compartilhamento abaixo da descrição', 'wp-coupon' ),
						'default'  => true,
					),
					array(
						'id'       => 'store_heading',
						'type'     => 'textarea',
						'default' => '<strong>%store_name%</strong> Coupons & Promo Codes',
						'title'    => esc_html__( 'Cabeçalho personalizado da página individal de loja', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Use %store_name% para repor o nome da', 'wp-coupon' ),
					),
					array(
						'id'       => 'store_unpopular_coupon',
						'type'     => 'text',
						'default' => 'Cupons impopulares %store_name%',
						'title'    => esc_html__( 'Texto para cupons impopulares', 'wp-coupon' ),
					),
					array(
						'id'       => 'store_expired_coupon',
						'type'     => 'text',
						'default' => 'Cupons expirados %store_name%',
						'title'    => esc_html__( 'Texto para cupons expirados', 'wp-coupon' ),
					),
					array(
						'id'       => 'store_number_active',
						'type'     => 'text',
						'default' => '15',
						'title'    => esc_html__( 'Número de cupons para mostrar', 'wp-coupon' ),
					),
					array(
						'id'       => 'go_store_slug',
						'type'     => 'text',
						'default' => 'go-store',
						'title'    => esc_html__( 'Personalizar goto slug', 'wp-coupon' ),
						'desc'    => sprintf( esc_html__( 'Quando você ativar esta função, permalinks podem ser afetados, para resolver isso, vá até %1$s e clique no botão "Salvar Configurações".', 'wp-coupon' ), '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">' . esc_html__( 'Permalinks Settings', 'wp-coupon' ) . '</a>' ),
					),

					array(
						'id'       => 'store_enable_sidebar_filter',
						'type'     => 'switch',
						'title'    => esc_html__( 'Habilitar filtro de loja', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Habilitar filtro de loja na barra lateral, disponível na Página Individual de Loja.', 'wp-coupon' ),
						'default'  => true,
					),
					array(
						'id'       => 'store_sidebar_filter_title',
						'type'     => 'text',
						'title'    => esc_html__( 'Título do Filtro de Loja', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Insira um título para o Filtro de Loja.', 'wp-coupon' ),
						'default'  => 'Filtro de Loja',
						'required' => array('store_enable_sidebar_filter','=',1)
					),

				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 CATEGORIA DOS CUPONS
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Categoria dos Cupons', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-tags',
				'submenu' => true,
				'fields' => array(

					array(
						'id'      => 'coupon_cate_tpl',
						'title'   => esc_html__( 'Modelo de Página da Categoria dos Cupons', 'wp-coupon' ),
						'desc'    => esc_html__( 'Selecione o modelo de página.', 'wp-coupon' ),
						'type'    => 'select',
						'default' => 'cat',
						'options' => array(
							'cat'   => esc_html__( 'Less', 'wp-coupon' ),
							'full'  => esc_html__( 'Full', 'wp-coupon' ),
						),
					),

					array(
						'id'       => 'coupon_cate_show_thumb',
						'type'     => 'select',
						'default' => 'default',
						'title'    => esc_html__( 'Mostras thumbnail do Cupom', 'wp-coupon' ),
						'options' => array(
							'default'                   => esc_html__( 'Padrão: Mostrar thumbnail do cupom, caso contrário, da loja.', 'wp-coupon' ),
							'hide_if_no_thumb'          => esc_html__( 'Mostrar somente thumbnail do cupom', 'wp-coupon' ),
							'save_value'                => esc_html__( 'Mostrar valor do desconto no lugar da thumbnail', 'wp-coupon' ),
							'hide'                      => esc_html__( 'Ocultar', 'wp-coupon' ),
						),
					),

					array(
						'id'      => 'coupon_cate_layout',
						'title'   => esc_html__( 'Layout de Categoria do Cupom', 'wp-coupon' ),
						'desc'    => esc_html__( 'Parão de layout da página.', 'wp-coupon' ),
						'type'    => 'button_set',
						'default' => 'left-sidebar',
						'options' => array(
							'left-sidebar'   => esc_html__( 'Barra Lateral à Esquerda', 'wp-coupon' ),
							'right-sidebar'  => esc_html__( 'Barra Lateral à Direita', 'wp-coupon' ),
						),
					),
					array(
						'id'       => 'coupon_cate_socialshare',
						'type'     => 'switch',
						'title'    => esc_html__( 'Compartilhamento de Categoria de Cupons', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Habilitar compartilhamento abaixo da descrição da categoria.', 'wp-coupon' ),
						'default'  => true,
					),
					array(
						'id'       => 'coupon_cate_heading',
						'type'     => 'textarea',
						'default' => '<strong>%coupon_cate%</strong> Cupons e Promoções',
						'title'    => esc_html__( 'Cabeçalho Personalizado de Categorias', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Cabeçalho personalizado para exibir na página de categoria. Use %coupon_cate% para exibir o nome da categoria.', 'wp-coupon' ),
					),
					array(
						'id'       => 'coupon_cate_subheading',
						'type'     => 'text',
						'default'  => esc_html__( 'Novos Cupons da Categoria %coupon_cate%', 'wp-coupon' ),
						'title'    => esc_html__( 'Subtítulo da Categoria do Cupom', 'wp-coupon' ),
						'subtitle' => esc_html__( 'You can use %coupon_cate% to display category name.', 'wp-coupon' ),
					),
					array(
						'id'       => 'coupon_cate_number',
						'type'     => 'text',
						'default'  => '15',
						'title'    => esc_html__( 'Quantos cupons exibir por padrão?', 'wp-coupon' ),
					),
					array(
						'id'      => 'coupon_cate_paging',
						'title'   => esc_html__( 'Páginação da Lista de Cupons', 'wp-coupon' ),
						'type'    => 'button_set',
						'default' => 'ajax_loadmore',
						'options' => array(
							'paging_navigation' => esc_html__( 'Páginação de Cupons', 'wp-coupon' ),
							'ajax_loadmore'     => esc_html__( 'Carregamento Contínuo', 'wp-coupon' ),
						),
					),

					array(
						'id'      => 'coupon_cate_ads',
						'title'   => esc_html__( 'Anúncio na Página Individual de Categoria', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Exibir um Anúncio personalizado após a listagem de cupons na página individual de categoria', 'wp-coupon' ),
						'type'    => 'textarea',
						'default' => '',
					),
					array(
						'id'       => 'coupon_cate_sidebar_filter',
						'type'     => 'switch',
						'title'    => esc_html__( 'Habilitar Filtro de Categoria', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Habilitar filtro de categoria na barra lateral da página individual de categoria', 'wp-coupon' ),
						'default'  => true,
					),
					array(
						'id'       => 'coupon_cate_filter_title',
						'type'     => 'text',
						'title'    => esc_html__( 'Título do Filtro de Categorias', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Insira o título do filtro de categoria da barra lateral.', 'wp-coupon' ),
						'default'  => 'Filter',
						'required' => array( 'coupon_cate_sidebar_filter', '=', 1 ),
					),
				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 CUPOM
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Cupons', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-tag',
				'submenu' => true,
				'fields' => array(

					array(
						'id'       => 'enable_single_coupon',
						'type'     => 'checkbox',
						'default' => true,
						'title'    => esc_html__( 'Habilitar Página Individual de Cupom.', 'wp-coupon' ),
						'desc'    => sprintf( esc_html__( 'Quando esta opções for habilitada permalinks podem ser afetados, para resovler isso, vá até %1$s e clique no botão  "Salvar Alterações".', 'wp-coupon' ), '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">' . esc_html__( 'Permalinks Settings', 'wp-coupon' ) . '</a>' ),
					),

					array(
						'id'      => 'coupon_filter_tabs',
						'type'    => 'sorter',
						'title'   => esc_html__( 'Filtros de Cupons', 'wp-coupon' ),
						'subtitle'    => esc_html__( 'Personalizar abas de filtros de cupons.', 'wp-coupon' ),
						'options' => array(
							'Habilitado'  => array_merge( array( 'all' => __( 'All', 'wp-coupon' ) ), wpcoupon_get_coupon_types( true ) ),
							'Desabilitado' => array(),
						),
					),

					array(
						'id'       => 'auto_open_coupon_modal',
						'type'     => 'checkbox',
						'default' => false,
						'title'    => esc_html__( 'Abrir automaticamente modal na página indicidual de cupom.', 'wp-coupon' ),
						'required' => array( 'enable_single_coupon', 'equals', '1' ),
					),

					array(
						'id'       => 'enable_single_popular',
						'type'     => 'checkbox',
						'default' => true,
						'title'    => esc_html__( 'Habilitar Página Individual de Cupons Populares.', 'wp-coupon' ),
						'required' => array( 'enable_single_coupon', 'equals', '1' ),
					),

					array(
						'id'       => 'single_popular_text',
						'type'     => 'text',
						'default'   => esc_html__( 'Cupons {store} mais populares.', 'wp-coupon' ),
						'title'    => esc_html__( 'Texto personalizado de cupons populares.', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Use {store} para exibir o nome da loja.', 'wp-coupon' ),
						'required' => array( 'enable_single_popular', 'equals', '1' ),
					),

					array(
						'id'       => 'single_popular_number',
						'type'     => 'text',
						'default' => 3,
						'title'    => esc_html__( 'Número de cupons populares na página individual.', 'wp-coupon' ),
						'required' => array( 'enable_single_popular', 'equals', '1' ),
					),

					array(
						'id'       => 'coupon_item_logo',
						'type'     => 'select',
						'default' => 'default',
						'title'    => esc_html__( 'Mostrar thumbnail de cupons', 'wp-coupon' ),
						'options' => array(
							'default'                   => esc_html__( 'Padrão: Mostrar thumbnail do cupom, caso contrário, use thumbnail da loja.', 'wp-coupon' ),
							'hide_if_no_thumb'          => esc_html__( 'Mostre a thumbnail do cupom', 'wp-coupon' ),
							'save_value'                => esc_html__( 'Mostre o valor do desconto', 'wp-coupon' ),
							'hide'                      => esc_html__( 'Ocultar', 'wp-coupon' ),
						),
					),

					array(
						'id'       => 'coupon_more_desc',
						'type'     => 'checkbox',
						'default' => 1,
						'title'    => esc_html__( 'Deseja mostrar a opção "Leia Mais" na descrição?', 'wp-coupon' ),
					),

					array(
						'id'       => 'coupon_time_zone_local',
						'type'     => 'checkbox',
						'default' => false,
						'title'    => esc_html__( 'Usar horário local?', 'wp-coupon' ),
						'desc'    => esc_html__( 'Cupons expiram de acordo com o horário local selecionado.', 'wp-coupon' ),
					),

					array(
						'id'       => 'coupon_human_time',
						'type'     => 'checkbox',
						'default' => 0,
						'title'    => esc_html__( 'Mostrar dias restantes?', 'wp-coupon' ),
						'desc'    => esc_html__( 'Exemplo: 3 dias restantes, 2 dias restantes,...', 'wp-coupon' ),
					),

					array(
						'id'       => 'coupon_item_exclusive',
						'type'     => 'text',
						'default'  => '<strong><i class="protect icon"></i>Exclusivo:</strong> Esse cupom só pode ser encontrado no nosso site.',
						'title'    => esc_html__( 'Informe que esse cupom é exclusivo', 'wp-coupon' ),
					),

					array(
						'id'      => 'coupon_expires_action',
						'title'   => esc_html__( 'Quando o cupom expirar:', 'wp-coupon' ),
						'type'    => 'select',
						'default' => 'do_nothing',
						'options' => array(
							'do_nothing' => esc_html__( 'Fazer nada', 'wp-coupon' ),
							'set_status' => esc_html__( 'Desabilitar', 'wp-coupon' ),
							'remove'     => esc_html__( 'Remover', 'wp-coupon' ),
						),
					),

					array(
						'id'      => 'coupon_expires_time',
						'title'   => esc_html__( 'Quando executar ação após o cupom expirar:', 'wp-coupon' ),
						'desc'    => esc_html__( 'Contagem para ação em segundos, Padrão: 604800 (1 semana)', 'wp-coupon' ),
						'type'    => 'text',
						'default' => 604800, // 1 week
					),

					/*array(
						'id'       => 'print_prev_tab',
						'type'     => 'checkbox',
						// 'required' => array( 'enable_single_coupon','!=','1'),
						'default' => false,
						'title'    => esc_html__( 'Open store website in new tab when click on print button.', 'wp-coupon' ),
					),*/

					array(
						'id'       => 'sale_prev_tab',
						'type'     => 'checkbox',
						// 'required' => array( 'enable_single_coupon','!=','1'),
						'default' => true,
						'title'    => esc_html__( 'Abrir site da loja, em uma nova aba, quando clicar em "Ver Oferta"?', 'wp-coupon' ),
					),

					array(
						'id'       => 'code_prev_tab',
						'type'     => 'checkbox',
						// 'required' => array( 'enable_single_coupon','!=','1'),
						'default' => true,
						'title'    => esc_html__( 'Abrir site da loja, em uma nova aba, quando clicar em "Ver Cupom"?', 'wp-coupon' ),
					),

					array(
						'id'       => 'coupon_click_action',
						'type'     => 'button_set',
						'default' => 'prev',
						'options' => array(
							'prev' => __( 'Abrir aba atrás', 'wp-coupon' ),
							'next' => __( 'Abrir aba a frente', 'wp-coupon' ),
						),
						'title'    => esc_html__( 'Ação quando abrir site da loja', 'wp-coupon' ),
					),

					array(
						'id'      => 'coupon_num_words_excerpt',
						'title'   => esc_html__( 'Tamanho do trecho em número de palavras', 'wp-coupon' ),
						'type'    => 'text',
						'default' => 10,
					),

					array(
						'id'       => 'go_out_slug',
						'type'     => 'text',
						'default' => 'out',
						'title'    => esc_html__( 'Personalizar slug de saída do link', 'wp-coupon' ),
						'desc'    => sprintf( esc_html__( 'Quando habilitado, o permalink poderá ser afetado, para resolver isso, vá para %1$s e clique no botão "Salvar Alterações".', 'wp-coupon' ), '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">' . esc_html__( 'Permalinks Settings', 'wp-coupon' ) . '</a>' ),
					),
					/*
					array(
						'id'       => 'use_deal_txt',
						'type'     => 'checkbox',
						'default'  => 0,
						'title'    => esc_html__( 'Use "Deal" text instead of "Sale"', 'wp-coupon' ),
					),*/

				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 RODAPÉ
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Rodapé', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-photo',
				'submenu' => true,
				'fields' => array(

					array(
						'id'       => 'before_footer',
						'type'     => 'editor',
						'title'    => esc_html__( 'Antes do Rodapé', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Note: Esse campo só é exibido na página inicial', 'wp-coupon' ),
						'default'  => '',
					),

					array(
						'id'      => 'before_footer_apply',
						'type'    => 'radio',
						'title'   => esc_html__( 'Itens antes do rodapé', 'wp-coupon' ),
						'desc'    => esc_html__( '', 'wp-coupon' ),/* Note: Setting home page goto Settings -> Reading -> Front page displays -> Verifique página estática -> Selecione uma página */
						'default' => 'home',
						'required' => array( 'footer_widgets', '=', true ),
						'options' => array(
							'home'   => esc_html__( 'Somente na página inicial.', 'wp-coupon' ),
							'all'   => esc_html__( 'Todas as páginas.', 'wp-coupon' ),
						),
					),

					array(
						'id'       => 'footer_widgets',
						'type'     => 'switch',
						'title'    => esc_html__( 'Habilitar área de widgets no rodapé', 'wp-coupon' ),
						'default'  => true,
					),
					array(
						'id'      => 'footer_columns',
						'type'    => 'button_set',
						'title'   => esc_html__( 'Colunas do Rodapé', 'wp-coupon' ),
						'desc'    => esc_html__( 'Selecione o número de colunas que você gostaria para o seu rodapé.', 'wp-coupon' ),
						'default' => '4',
						'required' => array( 'footer_widgets', '=', true ),
						'options' => array(
							'1'   => esc_html__( '1 Coluna', 'wp-coupon' ),
							'2'   => esc_html__( '2 Colunas', 'wp-coupon' ),
							'3'   => esc_html__( '3 Colunas', 'wp-coupon' ),
							'4'   => esc_html__( '4 Colunas', 'wp-coupon' ),
						),
					),

					array(
						'id'       => 'footer_columns_layout_2',
						'type'     => 'text',
						'required' => array( 'footer_columns', '=', 2 ),
						'default' => '8+8',
						'title'    => esc_html__( 'Rodapé de 2 colunas', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Custom footer columns width', 'wp-coupon' ),
						'desc'     => esc_html__( 'Escolha o estilo de coluna, o número deve ser menor ou igual a 16, separados por "+"', 'wp-coupon' ),
					),

					array(
						'id'       => 'footer_columns_layout_3',
						'type'     => 'text',
						'default' => '6+5+5',
						'required' => array( 'footer_columns', '=', 3 ),
						'title'    => esc_html__( 'Rodapé de 3 colunas', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Custom footer columns width', 'wp-coupon' ),
						'desc'     => esc_html__( 'Escolha o estilo de coluna, o número deve ser menor ou igual a 16, separados por "+"', 'wp-coupon' ),
					),

					array(
						'id'       => 'footer_columns_layout_4',
						'type'     => 'text',
						'default' => '4+4+4+4',
						'required' => array( 'footer_columns', '=', 4 ),
						'title'    => esc_html__( 'Rodapé de 4 colunas', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Custom footer columns width', 'wp-coupon' ),
						'desc'     => esc_html__( 'Escolha o estilo de coluna, o número deve ser menor ou igual a 16, separados por "+"', 'wp-coupon' ),
					),

					array(
						'id'       => 'footer_copyright',
						'type'     => 'textarea',
						'title'    => esc_html__( 'Copyright do Rodapé', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Insire o texto de Copyright do Rodapé.', 'wp-coupon' ),
					),

					array(
						'id'       => 'enable_footer_author',
						'type'     => 'switch',
						'title'    => esc_html__( 'Habilitar link do autor do tema.', 'wp-coupon' ),
						'default'  => true,
					),

					array(
						'id'       => 'footer_custom_color',
						'type'     => 'switch',
						'title'    => esc_html__( 'Personalizar o estilo do rodapé?', 'wp-coupon' ),
						'default'  => false,
					),
					array(
						'id'       => 'footer_bg',
						'type'     => 'background',
						// 'compiler' => true,
						'output'   => array( '.site-footer ' ),
						'title'    => esc_html__( 'Plano de fundo do rodapé', 'wp-coupon' ),
						'required' => array( 'footer_custom_color', '=', true ),
						'default'  => array(
							'background-color' => '#222222',
						),
					),
					array(
						'id'       => 'footer_text_color',
						'type'     => 'color',
						'compiler' => true,
						'output'   => array( '.site-footer, .site-footer .widget, .site-footer p' ),
						'title'    => esc_html__( 'Cor do texto do rodapé.', 'wp-coupon' ),
						'default'  => '#777777',
						'required' => array( 'footer_custom_color', '=', true ),
					),
					array(
						'id'       => 'footer_link_color',
						'type'     => 'color',
						'compiler' => true,
						'output'   => array( '.site-footer a, .site-footer .widget a' ),
						'title'    => esc_html__( 'Cor dos links no rodapé.', 'wp-coupon' ),
						'default'  => '#CCCCCC',
						'required' => array( 'footer_custom_color', '=', true ),
					),
					array(
						'id'       => 'footer_link_color_hover',
						'type'     => 'color',
						'compiler' => true,
						'output'   => array( '.site-footer a:hover, .site-footer .widget a:hover' ),
						'title'    => esc_html__( 'Cor dos links ao passar o mouse.', 'wp-coupon' ),
						'default'  => '#ffffff',
						'required' => array( 'footer_custom_color', '=', true ),
					),
					array(
						'id'       => 'footer_widget_title_color',
						'type'     => 'color',
						'compiler' => true,
						'output'   => array( '.site-footer .footer-columns .footer-column .widget .widget-title, .site-footer #wp-calendar caption' ),
						'title'    => esc_html__( 'Cor do título do widget, no rodapé.', 'wp-coupon' ),
						'default'  => '#777777',
						'required' => array( 'footer_custom_color', '=', true ),
					),
				),
			);

			/*
			--------------------------------------------------------*/
			/*
			 EMAIL
			/*--------------------------------------------------------*/
			$this->sections[] = array(
				'title'  => esc_html__( 'Email', 'wp-coupon' ),
				'desc'   => '',
				'icon'   => 'el-icon-envelope',
				'submenu' => true,
				'fields' => array(

					array(
						'id'       => 'email_share_coupon_title',
						'type'     => 'text',
						'title'    => esc_html__( 'Título de email ao compartilhar o cupom', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Tags Diponíveis: {coupon_title}, {coupon_description}, {coupon_destination_url}, {coupon_print_image_url}, {coupon_code}, {store_name}, {store_go_out_url}, {store_url}, {store_aff_url}, {home_url}, {share_email}', 'wp-coupon' ),
						'default'  => '{coupon_title}',
					),

					array(
						'id'       => 'email_share_coupon_code',
						'type'     => 'editor',
						'title'    => esc_html__( 'Modelo de email ao compartilhar o cupom', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Tags Disponíveis: {coupon_title}, {coupon_description}, {coupon_destination_url}, {coupon_print_image}, {coupon_print_image_url}, {coupon_code}, {store_name}, {store_image}, {store_go_out_url}, {store_url}, {store_aff_url}, {home_url}, {share_email}', 'wp-coupon' ),
						'default'  => wpcoupon_get_share_email_template( 'code' ),
					),

					array(
						'id'       => 'email_share_coupon_sale',
						'type'     => 'editor',
						'title'    => esc_html__( 'Modelo de email ao compartilhar link de oferta', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Available Tags: {coupon_title}, {coupon_description}, {coupon_destination_url}, {coupon_print_image}, {coupon_print_image_url}, {coupon_code}, {store_name}, {store_image}, {store_go_out_url}, {store_url}, {store_aff_url}, {home_url}, {share_email}', 'wp-coupon' ),
						'default'  => wpcoupon_get_share_email_template( 'sale' ),
					),

					/*array(
						'id'       => 'email_share_coupon_print',
						'type'     => 'editor',
						'title'    => esc_html__( 'Share coupon print email template', 'wp-coupon' ),
						'subtitle' => esc_html__( 'Available Tags: {coupon_title}, {coupon_description}, {coupon_destination_url}, {coupon_print_image}, {coupon_print_image_url}, {coupon_code}, {store_name}, {store_image}, {store_go_out_url}, {store_url}, {store_aff_url}, {home_url}, {share_email}', 'wp-coupon' ),
						'default'  => wpcoupon_get_share_email_template( 'print' ),
					),*/
				),
			);

			$this->sections = apply_filters( 'wpcoupon_more_options_settings', $this->sections );

		}

	}

	global $reduxConfig;
	function wpcoupon_options_init() {
		global $reduxConfig;
		// force remove sample redux demo option
		delete_option( 'ReduxFrameworkPlugin' );
		$reduxConfig = new WPCoupon_Theme_Options_Config();
	}
	add_action( 'init', 'wpcoupon_options_init' );

}


/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'wp_coupon_remove_demo' ) ) {
	function wp_coupon_remove_demo() {
		// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter(
				'plugin_row_meta',
				array(
					ReduxFrameworkPlugin::instance(),
					'plugin_metalinks',
				),
				null,
				2
			);

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}
}
wp_coupon_remove_demo();



/*
 * Load Redux extensions
 */
function wpcoupon_register_redux_extensions( $ReduxFramework ) {
	$path    = get_template_directory() . '/inc/redux-extensions/';

	$folders = scandir( $path, 1 );

	foreach ( $folders as $folder ) {
		if ( $folder === '.' or $folder === '..' or ! is_dir( $path . $folder ) ) {
			continue;
		}
		$extension_class = 'ReduxFramework_extension_' . $folder;
		if ( ! class_exists( $extension_class ) ) {
			// In case you wanted override your override, hah.
			$class_file = $path . $folder . '/extension_' . $folder . '.php';

			if ( is_file( $class_file ) ) {
				require_once $class_file;
			}
		}

		if ( ! isset( $ReduxFramework->extensions[ $folder ] ) ) {
			$ReduxFramework->extensions[ $folder ] = new $extension_class( $ReduxFramework );
		}
	}
}
add_action( 'redux/extensions/before', 'wpcoupon_register_redux_extensions' );
