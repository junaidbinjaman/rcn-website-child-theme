<?php
namespace ElementorPro\Modules\Posts\Skins;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
class cards_archive_skin_rcn extends \ElementorPro\Modules\ThemeBuilder\Skins\Posts_Archive_Skin_Cards {
    public function get_id() {
        return 'rcn cards';
    }

    public function get_title() {
        return __( 'RCN Cards', 'elementor-pro' );
    }


	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_card_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}

	protected function register_thumbnail_controls() {
		parent::register_thumbnail_controls();
		$this->remove_responsive_control( 'image_width' );
		$this->update_control(
			'thumbnail',
			[
				'label' => esc_html__( 'Show Image', 'elementor-pro' ),
				'options' => [
					'top' => esc_html__( 'Yes', 'elementor-pro' ),
					'none' => esc_html__( 'No', 'elementor-pro' ),
				],
				'render_type' => 'template',
			]
		);
	}

	protected function register_meta_data_controls() {
		parent::register_meta_data_controls();
		$this->update_control(
			'meta_separator',
			[
				'default' => '•',
			]
		);
	}

	public function register_additional_design_image_controls() {
		$this->update_control(
			'image_spacing',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->remove_control( 'img_border_radius' );

		$this->add_control(
			'heading_badge_style',
			[
				'label' => esc_html__( 'Badge', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_position',
			[
				'label' => 'Badge Position',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__badge' => '{{VALUE}}: 0',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn .elementor-post__badge' => 'background-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn .elementor-post__badge' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn .elementor-post__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_size',
			[
				'label' => esc_html__( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn .elementor-post__badge' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'margin: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .elementor-post__card .elementor_post_rcn .elementor-post__badge',
				'exclude' => [ 'font_size', 'line-height' ],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_avatar_style',
			[
				'label' => esc_html__( 'Avatar', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
					$this->get_control_id( 'show_avatar' ) => 'show-avatar',
				],
			]
		);

		$this->add_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 90,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__avatar' => 'top: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .elementor-post__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-post__thumbnail__link' => 'margin-bottom: calc({{SIZE}}{{UNIT}} / 2)',
				],
				'condition' => [
					$this->get_control_id( 'show_avatar' ) => 'show-avatar',
				],
			]
		);
	}

	public function register_badge_controls() {
		$this->add_control(
			'show_badge',
			[
				'label' => esc_html__( 'Badge', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'badge_taxonomy',
			[
				'label' => esc_html__( 'Badge Taxonomy', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'category',
				'options' => $this->get_taxonomies(),
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);
	}

	public function register_avatar_controls() {
		$this->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Avatar', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
				'return_value' => 'show-avatar',
				'default' => 'show-avatar',
				'separator' => 'before',
				'prefix_class' => 'elementor-posts--',
				'render_type' => 'template',
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);
	}

	public function register_design_card_controls() {
		$this->start_controls_section(
			'section_design_card',
			[
				'label' => esc_html__( 'Card', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_width',
			[
				'label' => esc_html__( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_padding',
			[
				'label' => esc_html__( 'Horizontal Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'padding: 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__meta-data' => 'padding: 10px {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__avatar' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_vertical_padding',
			[
				'label' => esc_html__( 'Vertical Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor_post_rcn' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_shadow_box_shadow_type', // The name of this control is like that, for future extensibility to group_control box shadow.
			[
				'label' => esc_html__( 'Box Shadow', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-card-shadow-',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => esc_html__( 'Hover Effect', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'none' => esc_html__( 'None', 'elementor-pro' ),
					'gradient' => esc_html__( 'Gradient', 'elementor-pro' ),
					//'zoom-in' => esc_html__( 'Zoom In', 'elementor-pro' ),
					//'zoom-out' => esc_html__( 'Zoom Out', 'elementor-pro' ),
				],
				'default' => 'gradient',
				'separator' => 'before',
				'prefix_class' => 'elementor-posts__hover-',
			]
		);

		$this->add_control(
			'meta_border_color',
			[
				'label' => esc_html__( 'Meta Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__meta-data' => 'border-top-color: {{VALUE}}',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_content_controls() {
		parent::register_design_content_controls();

		$this->remove_control( 'meta_spacing' );

		$this->update_control(
			'read_more_spacing',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-post__read-more' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			],
			[
				'recursive' => true,
			]
		);
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'elementor-post elementor-grid-item' ] ); ?>>
			<div class="elementor_post_rcn elementor-post__card">
		<?php
	}

	protected function render_categories_names() {
		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
		<div class="elementor-category-rcn"><span><?php echo __( 'Category:', 'elementor-pro' ).'</span> '.esc_html( $terms[0]->name ); ?></div>
		<?php
	}

	protected function render_post_footer() {
		?>
			</div>
		</article>
		<?php
	}

	protected function render_badge() {
		?>
		<div class="elementor-post__badge elementor_date_rcn_post"><span class="ik_post_rcn_date_day"><?php echo get_the_date('d'); ?></span><span class="ik_post_rcn_date_month"><?php echo get_the_date('M'); ?></span></div>
		<?php
	}
	
	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		$external_url = sanitize_url(get_post_meta(get_the_ID(), 'ik_external_url', true));
		$target_link = ($external_url != NULL && $external_url != '') ? ' target="_blank" ':'';
		
		$optional_attributes_html = $this->get_optional_link_attributes_html();

		$tag = Utils::validate_html_tag( $this->get_instance_value( 'title_tag' ) );
		?>
		<<?php echo $tag; ?> class="elementor-post__title">
			<a <?php echo $target_link ?> href="<?php echo $this->current_permalink; ?>" <?php echo $optional_attributes_html; ?>>
				<?php the_title(); ?>
			</a>
		</<?php echo $tag; ?>>
		<?php
	}
	
	protected function render_thumbnail() {
		if ( 'none' === $this->get_instance_value( 'thumbnail' ) ) {
			return;
		}

		$external_url = sanitize_url(get_post_meta(get_the_ID(), 'ik_external_url', true));
		$target_link = ($external_url != NULL && $external_url != '') ? ' target="_blank" ':'';
		

		$settings = $this->parent->get_settings();
		$setting_key = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<div class="ik_rcn_image_post"><a <?php echo $target_link ?> class="elementor-post__thumbnail__link" href="<?php echo esc_url( get_permalink() ); ?>" <?php Utils::print_unescaped_internal_string( $optional_attributes_html ); ?>><div class="elementor-post__thumbnail"><?php Utils::print_unescaped_internal_string( $thumbnail_html ); ?></div></a>
		<?php
		if ( $this->get_instance_value( 'show_badge' ) ) {
			$this->render_badge();
		}
		?>
		</div>
		<?php
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
        $this->render_categories_names();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_meta_data();
		$this->render_post_footer();
	}
}
    $widget->add_skin( new cards_archive_skin_rcn( $widget ) );
?>