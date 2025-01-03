<?php
/**
 * Default Header Placements Value.
 */
namespace Rishi\Customizer\Helpers;

class Header_Placements_Default {

	private function get_bar_structure($args = []) {
		$defaultArgs = [
			'id' => null,
			'mode' => 'placements',
			'has_secondary' => true,
			'items' => [
				'start' => [],
				'middle' => [],
				'end' => [],
				'start-middle' => [],
				'end-middle' => [],
			],
		];

		$args = array_merge($defaultArgs, $args);

		//Make sure items is an iterable array
		$args['items'] = \wp_parse_args(
			$args['items'],
			array(
				'start'        => array(),
				'middle'       => array(),
				'end'          => array(),
				'start-middle' => array(),
				'end-middle'   => array(),
			)
		);

		$placements = [
			['id' => 'start', 'items' => $args['items']['start']],
		];

		if($args['has_secondary']) {
			array_push($placements, ...[
				['id' => 'middle', 'items' => $args['items']['middle']],
				['id' => 'end', 'items' => $args['items']['end']],
				['id' => 'start-middle', 'items' => $args['items']['start-middle']],
				['id' => 'end-middle', 'items' => $args['items']['end-middle']],
			]);
		}

		return array_merge(['id' => $args['id']], $args['mode'] === 'rows' ? ['row' => []] : ['placements' => $placements]);
	}

	public function get_structure($args = []) {
		$defaultArgs = [
			'id' => null,
			'name' => null,
			'mode' => 'placements',
			'items' => [],
			'settings' => [],
		];

		$args = array_merge($defaultArgs, $args);
		$args['items'] = array_merge(['desktop' => [], 'mobile' => []], $args['items']);
		$args['items']['desktop'] = array_merge(['top-row' => [], 'middle-row' => [], 'bottom-row' => [], 'offcanvas' => []], $args['items']['desktop']);
		$args['items']['mobile'] = array_merge(['top-row' => [], 'middle-row' => [], 'bottom-row' => [], 'offcanvas' => []], $args['items']['mobile']);

		$base = [
			'id' => $args['id'],
			'mode' => $args['mode'],
			'items' => [],
			'settings' => $args['settings'],
		];

		if($args['name']) {
			$base['name'] = $args['name'];
		}

		if($args['mode'] === 'placements') {
			$base['desktop'] = array_map(function ($row) use ($args) {
				return $this->get_bar_structure(['id' => $row, 'mode' => $args['mode'], 'items' => $args['items']['desktop'][$row]]);
			}, ['top-row', 'middle-row', 'bottom-row', 'offcanvas']);

			$base['mobile'] = array_map(function ($row) use ($args) {
				return $this->get_bar_structure(['id' => $row, 'mode' => $args['mode'], 'items' => $args['items']['mobile'][$row]]);
			}, ['top-row', 'middle-row', 'bottom-row', 'offcanvas']);
		}

		if($args['mode'] === 'rows') {
			$base['desktop'] = array_map(function ($row) use ($args) {
				return $this->get_bar_structure(['id' => $row, 'mode' => $args['mode']]);
			}, ['top-row', 'middle-row', 'bottom-row']);
		}

		return $base;
	}

	public function get_value() {
		static $default_value = null;

		if($default_value) {
			return $default_value;
		}

		$default_value = array(
			'sections' => array(
				$this->get_structure(
					array(
						'id' => 'type-1',
						'mode' => 'placements',
						'items' => array(
							'desktop' => array(
								'middle-row' => array(
									'start' => array('logo'),
									'end' => array('menu', 'search'),
								),
							),

							'mobile' => array(
								'middle-row' => array(
									'start' => array('logo'),
									'end' => array('trigger'),
								),

								'offcanvas' => array(
									'start' => array(
										'mobile-menu',
									),
								),
							),
						),
					)
				)
			),
		);
		return $default_value;
	}
}
