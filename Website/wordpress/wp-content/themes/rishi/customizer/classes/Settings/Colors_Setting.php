<?php

namespace Rishi\Customizer\Settings;

use Rishi\Customizer\Abstracts\Customize_Settings;

class Colors_Setting extends Customize_Settings {

	protected function add_settings() {
		$this->add_setting( 'colorPalette', [
			'label'       => __( 'Global Color Palette', 'rishi' ),
			'control'     => self::COLOR_PALETTE_PICKER,
			'design'      => 'block',
			'predefined'  => true,
			'wrapperAttr' => [
				'data-type'  => 'color-palette',
				'data-label' => 'heading-label',
			],
			'value'       => self::get_default_palette_value(),
		] );

		$this->add_setting( 'primary_color', [
			'label'           => __( 'Base Font Color', 'rishi' ),
			'colorPalette'	  => true,
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor1)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
			],
		] );

		$this->add_setting( 'genheadingColor', [
			'label'           => __( 'Heading Color', 'rishi' ),
			'colorPalette'	  => true,
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor2)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
			],
		] );

		$this->add_setting( 'genLinkColor', [
			'label'           => __( 'Link Color', 'rishi' ),
			'colorPalette'	  => true,
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor3)' ],
				'hover'   => [ 'color' => 'var(--paletteColor4)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
				[
					'title' => __( 'Hover', 'rishi' ),
					'id'    => 'hover',
				],
			],
		] );

		$this->add_setting( 'textSelectionColor', [
			'label'           => __( 'Text Selection Color', 'rishi' ),
			'colorPalette'	  => true,
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor5)' ],
				'hover'   => [ 'color' => 'var(--paletteColor4)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
				[
					'title' => __( 'Highlighted', 'rishi' ),
					'id'    => 'hover',
				],
			],
		] );

		$this->add_setting( 'genborderColor', [
			'label'           => __( 'Border Color', 'rishi' ),
			'colorPalette'	  => true,
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor6)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
			],
		] );

		$this->add_setting( 'accentColors', [
			'label'           => __( 'Accent Color', 'rishi' ),
			'colorPalette'	  => true,
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor5)' ],
				'default_2' => [ 'color' => 'var(--paletteColor5)' ],
				'default_3' => [ 'color' => 'var(--paletteColor5)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Accent Color One', 'rishi' ),
					'id'    => 'default',
				],
				[
					'title' => __( 'Accent Color Two', 'rishi' ),
					'id'    => 'default_2',
				],
				[
					'title' => __( 'Accent Color Three', 'rishi' ),
					'id'    => 'default_3',
				],
			],
		] );

		$this->add_setting( 'base_color', [
			'label'           => __( 'Section Background Color', 'rishi' ),
			'colorPalette'    => true,
			'help'            => __( 'This color is used in some sections of the site as a background.', 'rishi' ),
			'control'         => self::COLOR_PICKER,
			'design'          => 'inline',
			'divider'         => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor7)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
			],
		] );

		$this->add_setting( 'site_background_color', [
			'label'        => __( 'Site Background', 'rishi' ),
			'colorPalette' => true,
			'control'      => self::COLOR_PICKER,
			'design'       => 'inline',
			'responsive'   => false,
			'divider'      => 'top',
			'value'           => [
				'default' => [ 'color' => 'var(--paletteColor8)' ],
			],
			'pickers'         => [
				[
					'title' => __( 'Initial', 'rishi' ),
					'id'    => 'default',
				],
			],
		] );
	}

	protected static function get_default_palette_value() {
		$_palettes = [
			[ 'rgba(41, 41, 41, 0.9)', '#292929', '#216BDB', '#5081F5', '#ffffff', '#EDF2FE', '#e9f1fa', '#F9FBFE' ],
			[ 'rgba(0, 26, 26, 0.8)', 'rgba(0, 26, 26, 0.9)', '#03a6a6', '#001a1a', '#ffffff', '#E5E8E8', '#F4FCFC', '#FEFEFE' ],
			[ '#1e2436', '#242b40', '#ff8b3c', '#8E919A', '#ffffff', '#E9E9EC', '#FFF7F1', '#FFFBF9' ],
			[ '#8D8D8D', '#31332e', '#8cb369', '#A3C287', '#ffffff', '#E8F0E1', '#F3F7F0', '#ffffff' ],
			[ '#21201d', '#21201d', '#dea200', '#343330', '#ffffff', '#F8ECCC', '#FDF8ED', '#fdfcf7' ],
		];

		$current_palette     = 'palette-1';
		$color_palette_value = [ 'current_palette' => $current_palette ];
		foreach ( $_palettes[0] as $index => $color_code ) {
			$color_palette_value[ 'color' . ( $index + 1 ) ] = [ 'color' => $color_code ];
		}

		unset( $color_code, $index );

		$palettes = [];

		foreach ( $_palettes as $index => $palette ) {
			$_palette['id'] = 'palette-' . ( $index + 1 );
			foreach ( $palette as $_index => $color_code ) {
				$_palette[ 'color' . ( $_index + 1 ) ] = [ 'color' => $color_code ];
			}
			$palettes[] = $_palette;
		}

		$color_palette_value['palettes'] = $palettes;
		return $color_palette_value;
	}

}
