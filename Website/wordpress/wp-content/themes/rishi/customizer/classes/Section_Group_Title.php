<?php
/**
 * New Section class for Grouping sections.
 */
namespace Rishi\Customizer;

/**
 * Class Section_Group_Title.
 */
class Section_Group_Title extends \WP_Customize_Section {

	/**
	 * Section Type.
	 *
	 * @var string
	 */
	public $type = 'section-group--title';

	/**
	 * Section Type/Kind.
	 *
	 * @var string
	 */
	public $kind = 'default';

	/**
	 * Output
	 */
	public function render() {
		$class = "accordion-section rishi-group-title {$this->type}";
		?>
		<li
			id="accordion-section-<?php echo esc_attr( $this->id ); ?>"
			class="<?php echo \esc_attr( $class ); ?> !block">
			<?php if ( ! empty( $this->title ) ) { ?>
				<h3>
					<?php echo \esc_html( $this->title ); ?>
				</h3>
			<?php } ?>

			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="description">
					<?php echo \esc_html( $this->description ); ?>
				</span>
			<?php } ?>
		</li>
		<?php
	}

}
