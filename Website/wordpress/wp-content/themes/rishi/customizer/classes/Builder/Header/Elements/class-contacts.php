<?php
/**
 * Class Contacts.
 */
namespace Rishi\Customizer\Header\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;
use Rishi\Customizer\Helpers\Defaults;
class Contacts extends Abstracts\Builder_Element {
	public function get_id() {
		return 'contacts';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __('Contact', 'rishi');
	}

	public function config() {
		return array(
			'name' => $this->get_label(),
			'visibilityKey' => 'header_hide_'.$this->get_id(),
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return array get options
	 */
	public function get_options() {

		$options = array(
			\Rishi\Customizer\Helpers\Basic::uniqid() => array(
				'title' => __('General', 'rishi'),
				'control' => ControlTypes::TAB,
				'options' => array(
					'header_hide_'.$this->get_id() => array(
						'label' => false,
						'control' => ControlTypes::HIDDEN,
						'value' => false,
						'disableRevertButton' => true,
						'help' => __('Hide', 'rishi'),
					),
					'contact_items' => array(
						'label' => __('Icons', 'rishi'),
						'help' => __('Select the items that you want to display.', 'rishi'),
						'control' => ControlTypes::LAYERS,
						'manageable' => true,
						'value' => array(
							array(
								'id' => 'email',
								'enabled' => true,
								'title' => __('Email:', 'rishi'),
								'content' => 'contact@yourwebsite.com',
								'link' => 'mailto:contact@yourwebsite.com',
							),
							array(
								'id' => 'phone',
								'enabled' => true,
								'title' => __('Phone:', 'rishi'),
								'content' => '123-456-7890',
								'link' => 'tel:123-456-7890',
							),
						),

						'settings' => array(
							'address' => array(
								'label' => __('Address', 'rishi'),
								'icon' => '<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_510_109944)"><path d="M6.91753 9.59995C8.68233 9.59995 10.1175 8.16475 10.1175 6.39995C10.1175 4.63515 8.68233 3.19995 6.91753 3.19995C5.15273 3.19995 3.71753 4.63515 3.71753 6.39995C3.71753 8.16475 5.15273 9.59995 6.91753 9.59995ZM6.91753 4.79995C7.79993 4.79995 8.51753 5.51755 8.51753 6.39995C8.51753 7.28235 7.79993 7.99995 6.91753 7.99995C6.03513 7.99995 5.31753 7.28235 5.31753 6.39995C5.31753 5.51755 6.03513 4.79995 6.91753 4.79995Z" fill="currentColor"/><path d="M6.45365 15.8512C6.58905 15.9479 6.75127 15.9999 6.91765 15.9999C7.08403 15.9999 7.24625 15.9479 7.38165 15.8512C7.62485 15.6792 13.3408 11.552 13.3176 6.4C13.3176 2.8712 10.4464 0 6.91765 0C3.38885 0 0.517649 2.8712 0.517649 6.396C0.494449 11.552 6.21045 15.6792 6.45365 15.8512ZM6.91765 1.6C9.56485 1.6 11.7176 3.7528 11.7176 6.404C11.7344 9.9544 8.20725 13.1424 6.91765 14.188C5.62885 13.1416 2.10085 9.9528 2.11765 6.4C2.11765 3.7528 4.27045 1.6 6.91765 1.6Z" fill="currentColor"/></g><defs><clipPath id="clip0_510_109944"><rect width="12.8001" height="16" fill="white" transform="translate(0.517578)"/></clipPath></defs></svg>',
								'options' => array(
									'title' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Address:', 'rishi'),
										'design' => 'block',
									),

									'content' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label'   => __( 'Address', 'rishi' ),
										'value'   => __('Street Name, NY 48734', 'rishi'),
										'design'  => 'block',
										'divider' => 'top',
									),
								),
							),

							'phone' => array(
								'label' => __('Phone', 'rishi'),
								'icon' => '<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_510_109971)"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.96566 10.5526C8.52899 13.1159 10.9043 13.3966 11.6017 13.4226C12.4443 13.4532 13.3043 12.7652 13.6763 12.0606C13.083 11.3646 12.3103 10.8246 11.4637 10.2392C10.965 10.7379 10.3503 11.6646 9.53033 11.3326C9.06433 11.1452 7.91366 10.6152 6.90833 9.60923C5.90233 8.6039 5.37299 7.45323 5.18433 6.9879C4.85233 6.16723 5.78166 5.55057 6.28099 5.05123C5.69566 4.19123 5.16499 3.39857 4.47033 2.83523C3.75566 3.20857 3.06366 4.0619 3.09499 4.9159C3.12099 5.61323 3.40166 7.98857 5.96566 10.5526ZM11.5523 14.7546C10.5923 14.7192 7.87166 14.3439 5.02233 11.4946C2.17366 8.6459 1.79833 5.9259 1.76233 4.96523C1.70899 3.50123 2.83033 2.07923 4.12566 1.5239C4.28164 1.45654 4.45246 1.4309 4.62135 1.44948C4.79023 1.46807 4.95139 1.53024 5.08899 1.6299C6.16099 2.4119 6.90033 3.59657 7.53566 4.52457C7.66783 4.71753 7.72837 4.95063 7.70682 5.18353C7.68528 5.41642 7.58299 5.63446 7.41766 5.7999L6.51366 6.70457C6.72366 7.1679 7.15099 7.96657 7.85099 8.66657C8.55099 9.36657 9.34966 9.7939 9.81366 10.0039L10.717 9.0999C10.8831 8.93422 11.102 8.83207 11.3357 8.81124C11.5693 8.79041 11.8029 8.85222 11.9957 8.9859C12.9423 9.6419 14.0543 10.3706 14.865 11.4086C14.9727 11.5472 15.0413 11.7122 15.0635 11.8863C15.0856 12.0605 15.0606 12.2374 14.991 12.3986C14.433 13.7006 13.021 14.8086 11.5523 14.7546Z" fill="currentColor"/></g><defs><clipPath id="clip0_510_109971"><rect width="16" height="16" fill="white" transform="translate(0.517578)"/></clipPath></defs></svg>',
								'options' => array(
									'title' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Phone:', 'rishi'),
										'design' => 'block',
									),
									'content' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Phone No.', 'rishi'),
										'value' => '123-456-7890',
										'design' => 'block',
										'divider' => 'top',
									),
									'link' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Link (optional)', 'rishi'),
										'value' => 'tel:123-456-7890',
										'design' => 'block',
										'divider' => 'top',
									),
								),
							),

							'mobile' => array(
								'label' => __('Mobile', 'rishi'),
								'icon' => '<svg width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_728_10764)"><path d="M12.5879 0C13.1183 0 13.627 0.210714 14.0021 0.585786C14.3772 0.960859 14.5879 1.46957 14.5879 2V18C14.5879 18.5304 14.3772 19.0391 14.0021 19.4142C13.627 19.7893 13.1183 20 12.5879 20H2.58789C2.05746 20 1.54875 19.7893 1.17368 19.4142C0.798604 19.0391 0.587891 18.5304 0.587891 18V2C0.587891 1.46957 0.798604 0.960859 1.17368 0.585786C1.54875 0.210714 2.05746 0 2.58789 0L12.5879 0ZM13.1879 15.388H1.98789V18C1.98789 18.1591 2.0511 18.3117 2.16363 18.4243C2.27615 18.5368 2.42876 18.6 2.58789 18.6H12.5879C12.747 18.6 12.8996 18.5368 13.0122 18.4243C13.1247 18.3117 13.1879 18.1591 13.1879 18V15.388ZM7.58789 16C7.85311 16 8.10746 16.1054 8.295 16.2929C8.48253 16.4804 8.58789 16.7348 8.58789 17C8.58789 17.2652 8.48253 17.5196 8.295 17.7071C8.10746 17.8946 7.85311 18 7.58789 18C7.32267 18 7.06832 17.8946 6.88078 17.7071C6.69325 17.5196 6.58789 17.2652 6.58789 17C6.58789 16.7348 6.69325 16.4804 6.88078 16.2929C7.06832 16.1054 7.32267 16 7.58789 16ZM12.5879 1.4H2.58789C2.42876 1.4 2.27615 1.46321 2.16363 1.57574C2.0511 1.68826 1.98789 1.84087 1.98789 2V13.988H13.1879V2C13.1879 1.84087 13.1247 1.68826 13.0122 1.57574C12.8996 1.46321 12.747 1.4 12.5879 1.4Z" fill="currentColor"/></g><defs><clipPath id="clip0_728_10764"><rect width="14" height="20" fill="white" transform="translate(0.587891)"/></clipPath></defs></svg>',
								'options' => array(
									'title' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Mobile:', 'rishi'),
										'design' => 'block',
									),

									'content' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Mobile No.', 'rishi'),
										'value' => '123-456-7890',
										'design' => 'block',
										'divider' => 'top',
									),
									'link' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Link (optional)', 'rishi'),
										'value' => 'tel:123-456-7890',
										'design' => 'block',
										'divider' => 'top',
									),
								),
							),

							'hours' => array(
								'label' => __('Work Hours', 'rishi'),
								'icon' => '<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_510_110738)"><path d="M8.51758 14.4C10.215 14.4 11.8428 13.7257 13.0431 12.5255C14.2433 11.3253 14.9176 9.69739 14.9176 8C14.9176 6.30261 14.2433 4.67475 13.0431 3.47452C11.8428 2.27428 10.215 1.6 8.51758 1.6C6.82019 1.6 5.19233 2.27428 3.99209 3.47452C2.79186 4.67475 2.11758 6.30261 2.11758 8C2.11758 9.69739 2.79186 11.3253 3.99209 12.5255C5.19233 13.7257 6.82019 14.4 8.51758 14.4ZM8.51758 0C9.56815 0 10.6084 0.206926 11.579 0.608964C12.5497 1.011 13.4316 1.60028 14.1744 2.34315C14.9173 3.08601 15.5066 3.96793 15.9086 4.93853C16.3107 5.90914 16.5176 6.94943 16.5176 8C16.5176 10.1217 15.6747 12.1566 14.1744 13.6569C12.6741 15.1571 10.6393 16 8.51758 16C4.09358 16 0.517578 12.4 0.517578 8C0.517578 5.87827 1.36043 3.84344 2.86072 2.34315C4.36102 0.842855 6.39585 0 8.51758 0ZM8.91758 4V8.2L12.5176 10.336L11.9176 11.32L7.71758 8.8V4H8.91758Z" fill="currentColor"/></g><defs><clipPath id="clip0_510_110738"><rect width="16" height="16" fill="white" transform="translate(0.517578)"/></clipPath></defs></svg>',
								'options' => array(
									'title' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Opening hours', 'rishi'),
										'design' => 'block',
									),

									'content' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label'   => __( 'Time', 'rishi' ),
										'value'   => __('9AM - 5PM', 'rishi'),
										'design'  => 'block',
										'divider' => 'top',
									),
								),
							),

							'email' => array(
								'label' => __('Email', 'rishi'),
								'icon' => '<svg width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_728_10644)"><path opacity="0.9" d="M15.2316 7.53467L21.367 13.6701L20.4962 14.5402L14.3609 8.40482L15.2316 7.53467ZM6.91286 7.56113L7.78302 8.43128L1.69071 14.5236L0.820557 13.6559L6.91286 7.56113Z" fill="currentColor"/><path d="M11.0494 11.0153C10.1263 11.0153 9.14169 10.6461 8.46477 9.90759L0.772461 2.27682L1.634 1.41528L9.32631 9.10759C10.3109 10.0922 11.8494 10.0922 12.834 9.10759L20.5263 1.41528L21.3878 2.27682L13.634 9.96913C12.9571 10.6461 11.9725 11.0153 11.0494 11.0153Z" fill="currentColor"/>
								<path d="M19.6648 16H2.43404C1.38789 16 0.587891 15.2 0.587891 14.1538V1.84615C0.587891 0.8 1.38789 0 2.43404 0H19.6648C20.711 0 21.511 0.8 21.511 1.84615V14.1538C21.511 15.2 20.711 16 19.6648 16ZM2.43404 1.23077C2.06481 1.23077 1.81866 1.47692 1.81866 1.84615V14.1538C1.81866 14.5231 2.06481 14.7692 2.43404 14.7692H19.6648C20.034 14.7692 20.2802 14.5231 20.2802 14.1538V1.84615C20.2802 1.47692 20.034 1.23077 19.6648 1.23077H2.43404Z" fill="currentColor"/></g><defs><clipPath id="clip0_728_10644"><rect width="20.9231" height="16" fill="white" transform="translate(0.587891)"/></clipPath></defs></svg>',
								'options' => array(
									'title' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Email:', 'rishi'),
										'design' => 'block',
									),

									'content' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Email', 'rishi'),
										'value' => 'contact@yourwebsite.com',
										'design' => 'block',
										'divider' => 'top',
									),
									'link' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Link (optional)', 'rishi'),
										'value' => 'mailto:contact@yourwebsite.com',
										'design' => 'block',
										'divider' => 'top',
									),
								),
							),

							'website' => array(
								'label' => __('Website', 'rishi'),
								'icon' => '<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_510_110727)"><path d="M8.51758 0C6.93533 0 5.38861 0.469192 4.07302 1.34824C2.75743 2.22729 1.73205 3.47672 1.12655 4.93853C0.521044 6.40034 0.362618 8.00887 0.671299 9.56072C0.979981 11.1126 1.74191 12.538 2.86073 13.6569C3.97955 14.7757 5.40501 15.5376 6.95686 15.8463C8.50871 16.155 10.1172 15.9965 11.579 15.391C13.0409 14.7855 14.2903 13.7602 15.1693 12.4446C16.0484 11.129 16.5176 9.58225 16.5176 8C16.5151 5.87903 15.6714 3.84565 14.1717 2.3459C12.6719 0.846145 10.6385 0.00249086 8.51758 0ZM14.9152 5.01961H11.7639C11.4209 3.59717 10.7954 2.25821 9.92464 1.08235C11.0057 1.30451 12.0199 1.77619 12.8864 2.45977C13.7528 3.14335 14.4476 4.01993 14.9152 5.01961ZM15.5764 8C15.5769 8.6908 15.4757 9.37791 15.276 10.0392H11.9576C12.1814 8.68895 12.1814 7.31105 11.9576 5.96078H15.276C15.4757 6.62209 15.5769 7.3092 15.5764 8ZM8.51758 15.0588C8.49802 15.0589 8.47867 15.0549 8.46081 15.0469C8.44296 15.0389 8.42701 15.0272 8.41405 15.0125C7.40385 13.9247 6.66189 12.5278 6.24307 10.9804H10.7921C10.3733 12.5278 9.63131 13.9247 8.62111 15.0125C8.60815 15.0272 8.5922 15.0389 8.57435 15.0469C8.55649 15.0549 8.53714 15.0589 8.51758 15.0588ZM6.03209 10.0392C5.79053 8.69047 5.79053 7.30953 6.03209 5.96078H11.0031C11.2446 7.30953 11.2446 8.69047 11.0031 10.0392H6.03209ZM1.45876 8C1.45823 7.3092 1.55945 6.62209 1.75915 5.96078H5.07758C4.85377 7.31105 4.85377 8.68895 5.07758 10.0392H1.75915C1.55945 9.37791 1.45823 8.6908 1.45876 8ZM8.51758 0.941176C8.53714 0.941068 8.55649 0.945141 8.57435 0.953122C8.5922 0.961102 8.60815 0.972807 8.62111 0.987451C9.63131 2.07529 10.3733 3.47216 10.7921 5.01961H6.24307C6.66189 3.47216 7.40385 2.07529 8.41405 0.987451C8.42701 0.972807 8.44296 0.961102 8.46081 0.953122C8.47867 0.945141 8.49802 0.941068 8.51758 0.941176ZM7.11052 1.08235C6.23972 2.25821 5.61425 3.59717 5.27131 5.01961H2.11993C2.58758 4.01993 3.28234 3.14335 4.1488 2.45977C5.01526 1.77619 6.02946 1.30451 7.11052 1.08235ZM2.11993 10.9804H5.27131C5.61425 12.4028 6.23972 13.7418 7.11052 14.9176C6.02946 14.6955 5.01526 14.2238 4.1488 13.5402C3.28234 12.8566 2.58758 11.9801 2.11993 10.9804ZM9.92464 14.9176C10.7954 13.7418 11.4209 12.4028 11.7639 10.9804H14.9152C14.4476 11.9801 13.7528 12.8566 12.8864 13.5402C12.0199 14.2238 11.0057 14.6955 9.92464 14.9176Z" fill="currentColor"/></g><defs><clipPath id="clip0_510_110727"><rect width="16" height="16" fill="white" transform="translate(0.517578)"/></clipPath></defs></svg>',
								'options' => array(
									'title' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Website:', 'rishi'),
										'design' => 'block',
									),

									'content' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label'   => __( 'Website', 'rishi' ),
										'value'   => __('Your Website Name', 'rishi' ),
										'design'  => 'block',
										'divider' => 'top',
									),
									'link' => array(
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Link (optional)', 'rishi'),
										'value' => '#',
										'design' => 'block',
										'divider' => 'top',
										'type' => 'link',
									),

								),
							),
							'fax' => array(
								'label' => __( 'Fax', 'rishi' ),
								'icon' => Defaults::lists_all_svgs('fax')['icon'],
								'options' => [
									'title' => [
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Title', 'rishi'),
										'value' => __('Fax:', 'rishi'),
										'design' => 'inline',
									],

									'content' => [
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Content', 'rishi'),
										'value' => '123-456-7890',
										'design' => 'inline',
									],

									'link' => [
										'control' => ControlTypes::INPUT_TEXT,
										'label' => __('Link (optional)', 'rishi'),
										'value' => 'tel:123-456-7890',
										'design' => 'inline',
									],
								]
							)
						),
					),

					'icon_size' => array(
						'label' => __('Icon Size', 'rishi'),
						'control' => ControlTypes::INPUT_SLIDER,
						'divider' => 'top',
						'value' => '15px',
						'responsive' => false,
						'units' => \Rishi\Customizer\Helpers\Basic::get_units(
							array(
								array(
									'unit' => 'px',
									'min' => 5,
									'max' => 50,
								),
							)
						),
					),

					'contacts_icon_shape' => array(
						'label' => __('Icon Shape', 'rishi'),
						'control' => ControlTypes::INPUT_RADIO,
						'divider' => 'top',
						'value' => 'rounded',
						'design' => 'block',
						'choices' => array(
							'simple' => __('None', 'rishi'),
							'rounded' => __('Rounded', 'rishi'),
							'square' => __('Square', 'rishi'),
						),
					),

					'contacts_icon_fill_type' => array(
						'label' => __('Icon Fill ', 'rishi'),
						'control' => ControlTypes::INPUT_RADIO,
						'value' => 'solid',
						'divider' => 'top',
						'design' => 'block',
						'choices' => array(
							'solid' => __('Solid', 'rishi'),
							'outline' => __('Outline', 'rishi'),
						),
						'conditions' => array(
							'contacts_icon_shape' => 'rounded|square',
						),
					),

					'icon_spacing' => array(
						'label' => __('Item Spacing', 'rishi'),
						'control' => ControlTypes::INPUT_SLIDER,
						'divider' => 'top',
						'value' => '15px',
						'units' => \Rishi\Customizer\Helpers\Basic::get_units(
							array(
								array(
									'unit' => 'px',
									'min' => 0,
									'max' => 50,
								),
							)
						),
					),
				),
			),

			\Rishi\Customizer\Helpers\Basic::uniqid() => array(
				'title' => __('Design', 'rishi'),
				'control' => ControlTypes::TAB,
				'options' => array(

					'header_contacts_font' => rishi_typography_control_option(array(
						'control' => ControlTypes::TYPOGRAPHY,
						'label' => __('Font', 'rishi'),
						'divider' => 'bottom',
						'value' => \Rishi\Customizer\Helpers\Defaults::typography_value(
							array(
								'size'            => array(
									'desktop' => '14px',
									'tablet'  => '14px',
									'mobile'  => '14px',
								),
								'line-height'            => array(
									'desktop' => '1.3em',
									'tablet'  => '1.3em',
									'mobile'  => '1.3em',
								),
							)
						),
					)),

					'font_color_group' => array(
						'label' => __('Font Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value' => array(
							'contacts_font_color' => array(
								'default' => array(
									'color' => 'var(--paletteColor1)',
								),
								'hover' => array(
									'color' => 'var(--paletteColor3)',
								),
							),
						),

						'settings' => array(
							'contacts_font_color' => array(
								'label' => __('Default State', 'rishi'),
								'control' => ControlTypes::COLOR_PICKER,
								'design' => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'pickers' => array(
									array(
										'title' => __('Initial', 'rishi'),
										'id' => 'default',
										'inherit' => 'var(--color)',
									),
									array(
										'title' => __('Hover', 'rishi'),
										'id' => 'hover',
										'inherit' => 'var(--hover-color)',
									),
								),
								'value' => array(
									'default' => array(
										'color' => 'var(--paletteColor1)',
									),
									'hover' => array(
										'color' => 'var(--paletteColor3)',
									),
								),
							),
						),
					),

					'icon_color_group' => array(
						'label' => __('Icons Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'responsive' => false,
						'value' => array(
							'contacts_icon_color' => array(
								'default' => array(
									'color' => 'var(--paletteColor1)',
								),
								'hover' => array(
									'color' => 'var(--paletteColor3)',
								),
							),

						),

						'settings' => array(
							'contacts_icon_color' => array(
								'label' => __('Default State', 'rishi'),
								'control' => ControlTypes::COLOR_PICKER,
								'design' => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'pickers' => array(
									array(
										'title' => __('Initial', 'rishi'),
										'id' => 'default',
										'inherit' => 'var(--color)',
									),
									array(
										'title' => __('Hover', 'rishi'),
										'id' => 'hover',
										'inherit' => 'var(--hover-color)',
									),
								),
								'value' => array(
									'default' => array(
										'color' => 'var(--paletteColor1)',
									),
									'hover' => array(
										'color' => 'var(--paletteColor3)',
									),
								),
							),
						),
					),

					'icon_bg_group' => array(
						'label' => __('Background Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'responsive' => false,
						'divider' => 'bottom',
						'value' => array(
							'contacts_icon_background' => array(
								'default' => array(
									'color' => 'var(--paletteColor6)',
								),
								'hover' => array(
									'color' => 'rgba(218, 222, 228, 0.7)',
								),
							),
						),

						'settings' => array(
							'contacts_icon_background' => array(
								'label' => __('Default State', 'rishi'),
								'control' => ControlTypes::COLOR_PICKER,
								'design' => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'pickers' => array(
									array(
										'title' => __('Initial', 'rishi'),
										'id' => 'default',
									),
									array(
										'title' => __('Hover', 'rishi'),
										'id' => 'hover',
									),
								),
								'value' => array(
									'default' => array(
										'color' => 'var(--paletteColor6)',
									),
									'hover' => array(
										'color' => 'rgba(218, 222, 228, 0.7)',
									),
								),
							),
						),
					),

					'contacts_margin' => array(
						'label' => __('Margin', 'rishi'),
						'control' => ControlTypes::INPUT_SPACING,
						'divider' => 'bottom',
						'value' => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px',
								)
							),
							'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px',
								)
							),
							'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px',
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
					),
				),
			),
		);

		return $options;
	}

	/**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return array dynamic styles
	 */
	public function dynamic_styles() {
		$contact_default = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$contacts_margin = $this->get_mod_value('contacts_margin', $contact_default['contacts_margin']);
		$icon_size = $this->get_mod_value('icon_size', $contact_default['icon_size']);
		$item_space = $this->get_mod_value('icon_spacing', $contact_default['icon_spacing']);

		$font_color_group = $this->get_mod_value(
			'font_color_group',
			array(
				'contacts_font_color' => $contact_default['contacts_font_color'],
			)
		);

		$contacts_font_color = $font_color_group['contacts_font_color'];

		$icon_color_group = $this->get_mod_value(
			'icon_color_group',
			array(
				'contacts_icon_color' => $contact_default['contacts_icon_color'],
			)
		);

		$contacts_icon_color = $icon_color_group['contacts_icon_color'];

		$icon_bg_group = $this->get_mod_value(
			'icon_bg_group',
			array(
				'contacts_icon_background' => $contact_default['contacts_icon_background'],
			)
		);

		$contacts_icon_bg = $icon_bg_group['contacts_icon_background'];

		$header_contacts_font = $this->get_mod_value('header_contacts_font', $contact_default['header_contacts_font']);
		$options = array(
			'header_contacts_font' => array(
				'value' => $header_contacts_font,
				'selector' => '#rishi-header-contacts',
				'type' => 'typography',
			),
			'contacts_margin' => array(
				'selector' => '#rishi-header-contacts',
				'variableName' => 'margin',
				'value' => $contacts_margin,
				'responsive' => true,
				'type' => 'spacing',
				'property' => 'margin',
				'unit' => 'px',
			),
			'icon_size' => array(
				'selector' => '#rishi-header-contacts',
				'variableName' => 'icon-size',
				'value' => $icon_size,
				'responsive' => false,
				'type' => 'slider',
			),
			'icon_spacing' => array(
				'selector' => '#rishi-header-contacts',
				'variableName' => 'items-spacing',
				'value' => $item_space,
				'responsive' => false,
				'type' => 'slider',
			),
			'contacts_font_color' => array(
				'value' => $contacts_font_color,
				'type' => 'color',
				'default' => $contact_default['contacts_font_color'],
				'variables' => array(
					'default' => array(
						'variable' => 'color',
						'selector' => '#rishi-header-contacts',
					),
					'hover' => array(
						'variable' => 'hover-color',
						'selector' => '#rishi-header-contacts',
					),
				),
			),
			'contacts_icon_color' => array(
				'value' => $contacts_icon_color,
				'type' => 'color',
				'default' => $contact_default['contacts_icon_color'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '#rishi-header-contacts',
					),
					'hover' => array(
						'variable' => 'icon-hover-color',
						'selector' => '#rishi-header-contacts',
					),
				),
			),
			'contacts_icon_background' => array(
				'value' => $contacts_icon_bg,
				'type' => 'color',
				'default' => $contact_default['contacts_icon_background'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '#rishi-header-contacts',
					),
					'hover' => array(
						'variable' => 'background-hover-color',
						'selector' => '#rishi-header-contacts',
					),
				),
			),
		);
		return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
	}

	/**
	 * Add markup for the element
	 *
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {

		$contactDefaultsArray = array(
			array(
				'id' => 'email',
				'enabled' => true,
				'title' => __('Email:', 'rishi'),
				'content' => 'contact@yourwebsite.com',
				'link' => 'mailto:contact@yourwebsite.com',
			),
			array(
				'id' => 'phone',
				'enabled' => true,
				'title' => __('Phone:', 'rishi'),
				'content' => '123-456-7890',
				'link' => 'tel:123-456-7890',
			),
		);

		$contact_items = $this->get_mod_value('contact_items', $contactDefaultsArray);
		$icon_shape = $this->get_mod_value('contacts_icon_shape', 'rounded');
		$contacts_icon_fill_type = $this->get_mod_value('contacts_icon_fill_type', 'solid');

		if($icon_shape) {
			$class = ' rishi-contacts-type-'.$icon_shape;
		}

		if($icon_shape == 'rounded' || $icon_shape == 'square') {
			$class .= ' rishi-contacts-fill-type-'.$contacts_icon_fill_type;
		}

		if($contact_items) {
			?>
			<div class="rishi-header-contact-info" id="rishi-header-contacts">
				<ul class="rishi-icons-types <?php echo esc_attr($class); ?>">
					<?php
					foreach($contact_items as $contact) {
						$contacts_title = !empty($contact['title']) ? $contact['title'] : '';
						$contacts_content = !empty($contact['content']) ? $contact['content'] : '';
						$contacts_link = !empty($contact['link']) && isset($contact['link']) ? $contact['link'] : '';
						$contacts_id = \Rishi\Customizer\Helpers\Defaults::lists_all_svgs($contact['id']);
						$contacts_icon = !empty($contacts_id) ? $contacts_id['icon'] : '';
						if($contact['enabled']) {
							?>
							<li>
								<span class="rishi-icon-container">
									<?php echo $contacts_icon; ?>
								</span>
								<?php if($contacts_title || $contacts_content) { ?>
									<div class="contact-info">
										<?php if($contacts_title) { ?>
											<span class="contact-title">
												<?php echo esc_html($contacts_title); ?>
											</span>
											<?php
										}
										if($contacts_content) {
											?>
											<span class="contact-text">
												<?php
												if($contacts_link) {
													echo '<a class="contact-link" href="'.esc_url($contacts_link).'">'.esc_html($contacts_content).'</a>';
												} else {
													echo esc_html($contacts_content);
												}
												?>
											</span>
										<?php } ?>
									</div>
								<?php } ?>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<?php
		}
	}
}
