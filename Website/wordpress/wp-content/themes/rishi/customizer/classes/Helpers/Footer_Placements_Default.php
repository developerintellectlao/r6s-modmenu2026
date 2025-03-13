<?php
/**
 * Default Footer Placements Value.
 */
namespace Rishi\Customizer\Helpers;

class Footer_Placements_Default {
	private function get_bar_structure($args = []) {
		$args = array_merge(['id' => null, 'columns' => array_fill(0, 3, [])], $args);
		return ['id' => $args['id'], 'columns' => $args['columns']];
	}

	public function construct_structure($args = []) {
		$args = array_merge(['id' => null, 'rows' => []], $args);
		$args['rows'] = array_merge(['top-row' => [], 'middle-row' => [], 'bottom-row' => []], $args['rows']);

		$resultRows = array_map(function ($row) use ($args) {
			return $this->get_bar_structure(array_merge(['id' => $row], $args['rows'][$row]));
		}, ['top-row', 'middle-row', 'bottom-row']);

		return ['id' => $args['id'], 'rows' => $resultRows, 'items' => [], 'settings' => []];
	}

	public function get_value() {
		return [
			'sections' => [
				$this->construct_structure([
					'id' => 'type-1',
					'rows' => [
						'top-row' => ['columns' => array_fill(0, 2, [])],
						'middle-row' => ['columns' => array_fill(0, 3, [])],
						'bottom-row' => ['columns' => [['copyright']]],
					],
				]),
			],
		];
	}
}
