<?php
/**
 * WooCommerce General Settings
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\Abstracts\Customize_Settings;
use Rishi\Customizer\ControlTypes;

class Woo_General_Setting extends Customize_Settings {

	public function add_settings() {
		$this->add_woo_general_settings();
	}

	protected function add_woo_general_settings() {
		$woo_defaults = self::get_woo_general_default_value();

		$this->add_setting( 'woo_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => apply_filters('rishi_woo_general_design',
				array(
					'woocommerce_sidebar_layout'   => array(
						'label'   => __( 'WooCommerce  Layout', 'rishi' ),
						'control'    => ControlTypes::IMAGE_PICKER,
						'value'   => $woo_defaults['woocommerce_sidebar_layout'],
						'attr'    => array(
							'data-type'    => 'background',
							'data-usage'   => 'layout-style',
							'data-columns' => '2',
						),
						'help'    => __( 'Choose sidebar layout.', 'rishi' ),
						'choices' => array(
							'right-sidebar' => array(
								'src'   => '<svg width="118" height="139" viewBox="0 0 118 139" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="0.448052" y="1.29742" width="116.683" height="137.104" rx="1.34416" fill="white"/>
								<g clip-path="url(#clip0_522_2181)">
								<path opacity="0.25" d="M67.9072 111.45H7.24805V112.705H67.9072V111.45Z" fill="#42474B"/>
								<path opacity="0.25" d="M72.0994 114.565H7.24805V115.82H72.0994V114.565Z" fill="#42474B"/>
								<path opacity="0.25" d="M68.399 108.613H7.24805V109.868H68.399V108.613Z" fill="#42474B"/>
								<path opacity="0.25" d="M68.399 117.681H7.24805V118.936H68.399V117.681Z" fill="#42474B"/>
								<path opacity="0.25" d="M67.8228 121.173H6.67188V122.428H67.8228V121.173Z" fill="#42474B"/>
								<path opacity="0.4" d="M16.7498 7.72945H7.24805V9.4624H16.7498V7.72945Z" fill="#42474B"/>
								<path opacity="0.4" d="M27.678 7.72945H17.7012V9.4624H27.678V7.72945Z" fill="#42474B"/>
								<path opacity="0.3" d="M71.2462 12.2335H7.24805V15.4724H71.2462V12.2335Z" fill="#42474B"/>
								<path opacity="0.3" d="M48.0139 17.4157H7.24805V20.6546H48.0139V17.4157Z" fill="#42474B"/>
								<path opacity="0.23" d="M8.67335 27.3699C9.46046 27.3699 10.0986 26.594 10.0986 25.6369C10.0986 24.6798 9.46046 23.9039 8.67335 23.9039C7.88615 23.9039 7.24805 24.6798 7.24805 25.6369C7.24805 26.594 7.88615 27.3699 8.67335 27.3699Z" fill="#42474B"/>
								<path opacity="0.4" d="M21.0111 25.2193H11.4219V27.1625H21.0111V25.2193Z" fill="#42474B"/>
								<path opacity="0.25" d="M39.6543 25.2193H23.6719V27.1625H39.6543V25.2193Z" fill="#42474B"/>
								<path opacity="0.25" d="M22.9895 24.8952L22.0742 26.8228" stroke="#42474B" stroke-width="0.747287"/>
								<path opacity="0.1" d="M82.8945 7.72945H110.544V131.032H82.8945V7.72945Z" fill="#42474B"/>
								<path opacity="0.2" d="M7.24805 34.2874H72.0979V79.0796H7.24805V34.2874Z" fill="#42474B"/>
								<g opacity="0.4">
								<path d="M44.1694 50.7305C44.1694 51.2922 44.3064 51.8413 44.5631 52.3083C44.8198 52.7754 45.1846 53.1394 45.6114 53.3542C46.0382 53.5691 46.5078 53.6253 46.9609 53.5156C47.414 53.406 47.8302 53.1354 48.1567 52.7382C48.4834 52.3409 48.7057 51.8348 48.7957 51.2839C48.8857 50.7329 48.8394 50.1619 48.6624 49.643C48.4855 49.1241 48.186 48.6807 47.8018 48.3688C47.4176 48.0569 46.966 47.8905 46.504 47.8907C45.8848 47.891 45.291 48.1904 44.8531 48.7229C44.4154 49.2554 44.1694 49.9775 44.1694 50.7305ZM48.8395 64.9318H30.1543L34.8254 49.7855L41.0538 59.2516L44.168 56.4118L48.8395 64.9318Z" fill="white"/>
								</g>
								<path opacity="0.25" d="M68.2658 86.0847H12.7793V87.3394H68.2658V86.0847Z" fill="#42474B"/>
								<path opacity="0.25" d="M72.1006 89.2002H12.7793V90.4549H72.1006V89.2002Z" fill="#42474B"/>
								<path opacity="0.25" d="M67.9072 95.153H7.24805V96.4077H67.9072V95.153Z" fill="#42474B"/>
								<path opacity="0.25" d="M72.0994 98.2692H7.24805V99.5239H72.0994V98.2692Z" fill="#42474B"/>
								<path opacity="0.25" d="M68.399 92.3171H7.24805V93.5718H68.399V92.3171Z" fill="#42474B"/>
								<path opacity="0.25" d="M68.399 101.385H7.24805V102.64H68.399V101.385Z" fill="#42474B"/>
								<path opacity="0.25" d="M67.8228 104.877H6.67188V106.131H67.8228V104.877Z" fill="#42474B"/>
								<g opacity="0.25">
								<path opacity="0.25" d="M10.5737 85.714H7.24805V90.9129H10.5737V85.714Z" fill="white"/>
								<path opacity="0.25" d="M10.0981 86.2917H7.72266V90.3352H10.0981V86.2917Z" stroke="#42474B" stroke-width="1.49457"/>
								</g>
								</g>
								<rect x="0.448052" y="1.29742" width="116.683" height="137.104" rx="1.34416" stroke="#E0E3E7" stroke-width="0.896104"/>
								<defs>
								<clipPath id="clip0_522_2181">
								<rect width="103.241" height="123.662" fill="white" transform="translate(7.16992 8.01817)"/>
								</clipPath>
								</defs>
								</svg>
								',
								'title' => __( 'Right Sidebar', 'rishi' ),
							),

							'left-sidebar'  => array(
								'src'   => '<svg width="122" height="139" viewBox="0 0 122 139" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_522_2213)">
								<rect y="0.849365" width="121.345" height="138" rx="2.37931" fill="white"/>
								<path opacity="0.4" d="M56.9508 6.78818H47.0566V8.64704H56.9508V6.78818Z" fill="#566779"/>
								<path opacity="0.4" d="M67.9469 6.78818H58.0527V8.64704H67.9469V6.78818Z" fill="#566779"/>
								<path d="M55.8558 7.40765H48.1602V7.71762H55.8558V7.40765Z" fill="white"/>
								<path d="M66.848 7.40765H59.1523V7.71762H66.848V7.40765Z" fill="white"/>
								<path opacity="0.3" d="M113.09 11.0966H47.0566V14.1947H113.09V11.0966Z" fill="#566779"/>
								<path opacity="0.3" d="M89.1193 16.0536H47.0566V19.1519H89.1193V16.0536Z" fill="#566779"/>
								<path opacity="0.23" d="M48.7057 25.9964C49.6164 25.9964 50.3547 25.1641 50.3547 24.1376C50.3547 23.111 49.6164 22.2787 48.7057 22.2787C47.795 22.2787 47.0566 23.111 47.0566 24.1376C47.0566 25.1641 47.795 25.9964 48.7057 25.9964Z" fill="#566779"/>
								<path opacity="0.4" d="M61.2575 23.5182H51.3633V25.3769H61.2575V23.5182Z" fill="#566779"/>
								<path opacity="0.25" d="M80.4968 23.5182H64.0059V25.3769H80.4968V23.5182Z" fill="#566779"/>
								<path opacity="0.25" d="M63.2978 23.2082L62.3535 25.0519" stroke="#707070" stroke-width="0.774332"/>
								<path opacity="0.2" d="M47.0566 32.1923H113.969V75.0387H47.0566V32.1923Z" fill="#566779"/>
								<g opacity="0.4">
								<path d="M85.1561 47.921C85.1561 48.4583 85.2978 48.9836 85.5626 49.4303C85.8275 49.8771 86.2038 50.2252 86.6444 50.4308C87.0842 50.6364 87.5689 50.6901 88.0366 50.5852C88.5043 50.4803 88.9333 50.2214 89.2701 49.8414C89.6077 49.4614 89.837 48.9773 89.9299 48.4503C90.0228 47.9233 89.9748 47.3771 89.792 46.8808C89.6093 46.3844 89.3003 45.9603 88.9039 45.6619C88.5074 45.3636 88.042 45.2044 87.5651 45.2047C86.9262 45.2049 86.3137 45.4912 85.8615 46.0007C85.4101 46.51 85.1561 47.2008 85.1561 47.921ZM89.9748 61.5053H70.6953L75.5149 47.0171L81.9419 56.0719L85.1546 53.3555L89.9748 61.5053Z" fill="white"/>
								</g>
								<path opacity="0.25" d="M110.015 81.739H52.7637V82.9392H110.015V81.739Z" fill="#566779"/>
								<path opacity="0.25" d="M109.645 90.4138H47.0566V91.6141H109.645V90.4138Z" fill="#566779"/>
								<path opacity="0.25" d="M109.645 99.0887H47.0566V100.289H109.645V99.0887Z" fill="#566779"/>
								<path opacity="0.25" d="M113.972 84.7194H52.7637V85.9196H113.972V84.7194Z" fill="#566779"/>
								<path opacity="0.25" d="M113.971 93.3943H47.0566V94.5945H113.971V93.3943Z" fill="#566779"/>
								<path opacity="0.25" d="M113.971 102.069H47.0566V103.269H113.971V102.069Z" fill="#566779"/>
								<path opacity="0.25" d="M110.152 87.7006H47.0566V88.9008H110.152V87.7006Z" fill="#566779"/>
								<path opacity="0.25" d="M110.152 96.3746H47.0566V97.5749H110.152V96.3746Z" fill="#566779"/>
								<path opacity="0.25" d="M110.152 105.049H47.0566V106.25H110.152V105.049Z" fill="#566779"/>
								<path opacity="0.25" d="M110.152 107.763H47.0566V108.963H110.152V107.763Z" fill="#566779"/>
								<g opacity="0.25">
								<path opacity="0.25" d="M50.9062 81.7622H47.0586V86.0993H50.9062V81.7622Z" fill="white"/>
								<path opacity="0.25" d="M50.4159 82.3151H47.5488V85.5464H50.4159V82.3151Z" stroke="#566779" stroke-width="1.54866"/>
								</g>
								<path opacity="0.1" d="M6.49805 6.44917H35.9227V134.214H6.49805V6.44917Z" fill="#566779"/>
								<path opacity="0.25" d="M110.01 113.319H46.4023V114.563H110.01V113.319Z" fill="#566779"/>
								<path opacity="0.25" d="M114.405 116.407H46.4023V117.652H114.405V116.407Z" fill="#566779"/>
								<path opacity="0.25" d="M110.525 110.507H46.4023V111.751H110.525V110.507Z" fill="#566779"/>
								<path opacity="0.25" d="M110.525 119.497H46.4023V120.741H110.525V119.497Z" fill="#566779"/>
								<path opacity="0.25" d="M110.525 123.369H46.4023V124.612H110.525V123.369Z" fill="#566779"/>
								<path opacity="0.25" d="M110.525 126.466H46.4023V127.71H110.525V126.466Z" fill="#566779"/>
								</g>
								<rect x="0.594828" y="1.44419" width="120.155" height="136.81" rx="1.78448" stroke="#E0E3E7" stroke-width="1.18966"/>
								<defs>
								<clipPath id="clip0_522_2213">
								<rect y="0.849365" width="121.345" height="138" rx="2.37931" fill="white"/>
								</clipPath>
								</defs>
								</svg>',
								'title' => __( 'Left Sidebar', 'rishi' ),
							),

							'no-sidebar'    => array(
								'src'   => '<svg width="109" height="139" viewBox="0 0 109 139" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="0.5" y="1.34937" width="108" height="137" rx="1.5" fill="white"/>
								<path opacity="0.4" d="M53.9054 7.84956H40.8145V9.77103H53.9054V7.84956Z" fill="#566779"/>
								<path opacity="0.4" d="M68.4503 7.84956H55.3594V9.77103H68.4503V7.84956Z" fill="#566779"/>
								<path d="M52.4494 8.49004H42.2676V8.81029H52.4494V8.49004Z" fill="white"/>
								<path d="M66.9943 8.49004H56.8125V8.81029H66.9943V8.49004Z" fill="white"/>
								<path opacity="0.3" d="M98.3614 12.3028H10.9941V15.5053H98.3614V12.3028Z" fill="#566779"/>
								<path opacity="0.3" d="M82.6477 17.4268H26.9961V20.6292H82.6477V17.4268Z" fill="#566779"/>
								<path opacity="0.23" d="M34.9904 27.7049C36.1954 27.7049 37.1722 26.8446 37.1722 25.7834C37.1722 24.7222 36.1954 23.8619 34.9904 23.8619C33.7854 23.8619 32.8086 24.7222 32.8086 25.7834C32.8086 26.8446 33.7854 27.7049 34.9904 27.7049Z" fill="#566779"/>
								<path opacity="0.4" d="M51.5929 25.1429H38.502V27.0643H51.5929V25.1429Z" fill="#566779"/>
								<path opacity="0.25" d="M77.0506 25.1429H55.2324V27.0643H77.0506V25.1429Z" fill="#566779"/>
								<path opacity="0.25" d="M54.2949 24.823L53.0455 26.7289" stroke="#707070" stroke-width="0.727273"/>
								<path opacity="0.2" d="M10.9941 34.1097H99.5265V78.399H10.9941V34.1097Z" fill="#566779"/>
								<g opacity="0.4">
								<path d="M61.3985 50.368C61.3985 50.9233 61.5855 51.4662 61.9358 51.928C62.2862 52.3897 62.7841 52.7496 63.3667 52.9621C63.9493 53.1747 64.5904 53.2303 65.2089 53.1219C65.8274 53.0136 66.3955 52.7462 66.8414 52.3535C67.2873 51.9608 67.5909 51.4604 67.714 50.9158C67.837 50.3711 67.7738 49.8065 67.5325 49.2934C67.2912 48.7803 66.8825 48.3418 66.3582 48.0333C65.8339 47.7247 65.2175 47.5601 64.5869 47.5601C63.7412 47.5601 62.9303 47.8559 62.3323 48.3825C61.7344 48.9091 61.3985 49.6233 61.3985 50.368ZM67.7752 64.4082H42.2676L48.6443 49.4322L57.1476 58.7924L61.3992 55.9844L67.7752 64.4082Z" fill="white"/>
								</g>
								<path opacity="0.25" d="M94.2906 83.4038H18.543V84.6444H94.2906V83.4038Z" fill="#566779"/>
								<path opacity="0.25" d="M93.8029 92.3708H10.9941V93.6114H93.8029V92.3708Z" fill="#566779"/>
								<path opacity="0.25" d="M93.8029 104.143H10.9941V105.384H93.8029V104.143Z" fill="#566779"/>
								<path opacity="0.25" d="M93.8029 124.719H10.9941V125.96H93.8029V124.719Z" fill="#566779"/>
								<path opacity="0.25" d="M98.3631 86.4846H18.4453V87.7252H98.3631V86.4846Z" fill="#566779"/>
								<path opacity="0.25" d="M95.4523 95.4514H10.9941V96.6921H95.4523V95.4514Z" fill="#566779"/>
								<path opacity="0.25" d="M95.4523 116.029H10.9941V117.269H95.4523V116.029Z" fill="#566779"/>
								<path opacity="0.25" d="M98.3614 107.224H10.9941V108.464H98.3614V107.224Z" fill="#566779"/>
								<path opacity="0.25" d="M98.3614 127.8H10.9941V129.041H98.3614V127.8Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 89.5661H10.9941V90.8067H94.4749V89.5661Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 101.338H10.9941V102.578H94.4749V101.338Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 121.915H10.9941V123.155H94.4749V121.915Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 98.5329H10.9941V99.7735H94.4749V98.5329Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 119.109H10.9941V120.35H94.4749V119.109Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 110.305H10.9941V111.545H94.4749V110.305Z" fill="#566779"/>
								<path opacity="0.25" d="M94.4749 130.882H10.9941V132.122H94.4749V130.882Z" fill="#566779"/>
								<g opacity="0.25">
								<path d="M16.085 83.4275H10.9941V87.911H16.085V83.4275Z" fill="white"/>
								<path d="M15.3571 84.0681H11.7207V87.2705H15.3571V84.0681Z" stroke="#566779" stroke-width="1.45455"/>
								</g>
								<rect x="0.5" y="1.34937" width="108" height="137" rx="1.5" stroke="#E0E3E7"/>
								</svg>
								',
								'title' => __( 'Fullwidth', 'rishi' ),
							)
						),
					),
					'woocommerce_layout' => array(
						'label'   => __( 'Shop Page Layout', 'rishi' ),
						'control'    => ControlTypes::INPUT_RADIO,
						'divider'    => 'top:bottom',
						'value'   => $woo_defaults['woocommerce_layout'],
						'design'  => 'block',
						'choices' => array(
							'boxed'                => __('Boxed', 'rishi'),
							'content_boxed'        => __('Content Boxed', 'rishi'),
							'full_width_contained' => __('Unboxed', 'rishi'),
						),
					),
					'woo_layout_streched_ed' => array(
						'label' => __( 'Stretch Layout', 'rishi' ),
						'control'  => ControlTypes::INPUT_SWITCH,
						'divider'    => 'bottom',
						'value' => $woo_defaults['woo_layout_streched_ed'],

						'help'  => __( 'This setting stretches the container width.', 'rishi' ),
					),
					'woo_sales_badge_panel' => array(
						'label'         => __( 'Sales Badge Options', 'rishi' ),
						'control'       => ControlTypes::PANEL,
						'divider'    => 'bottom',
						'innerControls' => array(
							\Rishi\Customizer\Helpers\Basic::uniqid() => array(
								'title'   => __( 'General', 'rishi' ),
								'control'    => ControlTypes::TAB,
								'options' => array(
									'has_sale_badge' => array(
										'label' => __( 'Sale Badge', 'rishi' ),
										'control'  => ControlTypes::INPUT_SWITCH,
										'value' => $woo_defaults['has_sale_badge'],
										'divider'    => 'bottom',

									),
									'sales_badge_title'     => array(
										'label'      => __( 'Label', 'rishi' ),
										'control'    => ControlTypes::INPUT_TEXT,
										'design'     => 'block',
										'value'      => $woo_defaults['sales_badge_title'],
										'divider'    => 'bottom',
										'conditions' => [ 'has_sale_badge' => 'yes']
									),
									'shop_cards_sales_badge_design' => [
										'label'   => __( 'Sale Badge Design', 'rishi' ),
										'control' => ControlTypes::IMAGE_PICKER,
										'value'   => $woo_defaults['shop_cards_sales_badge_design'],
										'divider'    => 'bottom',
										'attr'    => [
											'data-usage' => 'logotitletagline',
											'data-columns' => '4'
										],
										'choices' => [
											'circle'     => [
												'src'   => '<svg width="45" height="45" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="28" cy="28" r="28" fill="#566779"/><path d="M16.6037 25.6719C16.5639 25.2997 16.3963 25.0099 16.1009 24.8026C15.8082 24.5952 15.4276 24.4915 14.9588 24.4915C14.6293 24.4915 14.3466 24.5412 14.1108 24.6406C13.875 24.7401 13.6946 24.875 13.5696 25.0455C13.4446 25.2159 13.3807 25.4105 13.3778 25.6293C13.3778 25.8111 13.419 25.9687 13.5014 26.1023C13.5866 26.2358 13.7017 26.3494 13.8466 26.4432C13.9915 26.5341 14.152 26.6108 14.3281 26.6733C14.5043 26.7358 14.6818 26.7884 14.8608 26.831L15.679 27.0355C16.0085 27.1122 16.3253 27.2159 16.6293 27.3466C16.9361 27.4773 17.2102 27.642 17.4517 27.8409C17.696 28.0398 17.8892 28.2798 18.0312 28.5611C18.1733 28.8423 18.2443 29.1719 18.2443 29.5497C18.2443 30.0611 18.1136 30.5114 17.8523 30.9006C17.5909 31.2869 17.2131 31.5895 16.7188 31.8082C16.2273 32.0241 15.6321 32.1321 14.9332 32.1321C14.2543 32.1321 13.6648 32.027 13.1648 31.8168C12.6676 31.6065 12.2784 31.2997 11.9972 30.8963C11.7188 30.4929 11.5682 30.0014 11.5455 29.4219H13.1009C13.1236 29.7259 13.2173 29.9787 13.3821 30.1804C13.5469 30.3821 13.7614 30.5327 14.0256 30.6321C14.2926 30.7315 14.5909 30.7812 14.9205 30.7812C15.2642 30.7812 15.5653 30.7301 15.8239 30.6278C16.0852 30.5227 16.2898 30.3778 16.4375 30.1932C16.5852 30.0057 16.6605 29.7869 16.6634 29.5369C16.6605 29.3097 16.5938 29.1222 16.4631 28.9744C16.3324 28.8239 16.1491 28.6989 15.9134 28.5994C15.6804 28.4972 15.4077 28.4062 15.0952 28.3267L14.1023 28.071C13.3835 27.8864 12.8153 27.6065 12.3977 27.2315C11.983 26.8537 11.7756 26.3523 11.7756 25.7273C11.7756 25.2131 11.9148 24.7628 12.1932 24.3764C12.4744 23.9901 12.8565 23.6903 13.3395 23.4773C13.8224 23.2614 14.3693 23.1534 14.9801 23.1534C15.5994 23.1534 16.142 23.2614 16.608 23.4773C17.0767 23.6903 17.4446 23.9872 17.7116 24.3679C17.9787 24.7457 18.1165 25.1804 18.125 25.6719H16.6037ZM21.5939 32H19.9064L22.9788 23.2727H24.9305L28.0072 32H26.3197L23.9888 25.0625H23.9206L21.5939 32ZM21.6493 28.5781H26.2515V29.848H21.6493V28.5781ZM30.1167 32V23.2727H31.6977V30.6747H35.5414V32H30.1167ZM37.8736 32V23.2727H43.5497V24.598H39.4546V26.9673H43.2557V28.2926H39.4546V30.6747H43.5838V32H37.8736Z" fill="white"/></svg>',
												'title' => __( 'Circle', 'rishi' ),
											],

											'square'     => [
												'src'   => '<svg width="45" height="24" viewBox="0 0 58 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 2C0 0.89543 0.895431 0 2 0H56C57.1046 0 58 0.895431 58 2V22C58 23.1046 57.1046 24 56 24H2C0.895432 24 0 23.1046 0 22V2Z" fill="#566779"/><path d="M17.6037 9.67188C17.5639 9.29972 17.3963 9.00994 17.1009 8.80256C16.8082 8.59517 16.4276 8.49148 15.9588 8.49148C15.6293 8.49148 15.3466 8.54119 15.1108 8.64062C14.875 8.74006 14.6946 8.875 14.5696 9.04545C14.4446 9.21591 14.3807 9.41051 14.3778 9.62926C14.3778 9.81108 14.419 9.96875 14.5014 10.1023C14.5866 10.2358 14.7017 10.3494 14.8466 10.4432C14.9915 10.5341 15.152 10.6108 15.3281 10.6733C15.5043 10.7358 15.6818 10.7884 15.8608 10.831L16.679 11.0355C17.0085 11.1122 17.3253 11.2159 17.6293 11.3466C17.9361 11.4773 18.2102 11.642 18.4517 11.8409C18.696 12.0398 18.8892 12.2798 19.0312 12.5611C19.1733 12.8423 19.2443 13.1719 19.2443 13.5497C19.2443 14.0611 19.1136 14.5114 18.8523 14.9006C18.5909 15.2869 18.2131 15.5895 17.7188 15.8082C17.2273 16.0241 16.6321 16.1321 15.9332 16.1321C15.2543 16.1321 14.6648 16.027 14.1648 15.8168C13.6676 15.6065 13.2784 15.2997 12.9972 14.8963C12.7188 14.4929 12.5682 14.0014 12.5455 13.4219H14.1009C14.1236 13.7259 14.2173 13.9787 14.3821 14.1804C14.5469 14.3821 14.7614 14.5327 15.0256 14.6321C15.2926 14.7315 15.5909 14.7812 15.9205 14.7812C16.2642 14.7812 16.5653 14.7301 16.8239 14.6278C17.0852 14.5227 17.2898 14.3778 17.4375 14.1932C17.5852 14.0057 17.6605 13.7869 17.6634 13.5369C17.6605 13.3097 17.5938 13.1222 17.4631 12.9744C17.3324 12.8239 17.1491 12.6989 16.9134 12.5994C16.6804 12.4972 16.4077 12.4062 16.0952 12.3267L15.1023 12.071C14.3835 11.8864 13.8153 11.6065 13.3977 11.2315C12.983 10.8537 12.7756 10.3523 12.7756 9.72727C12.7756 9.21307 12.9148 8.76278 13.1932 8.37642C13.4744 7.99006 13.8565 7.69034 14.3395 7.47727C14.8224 7.26136 15.3693 7.15341 15.9801 7.15341C16.5994 7.15341 17.142 7.26136 17.608 7.47727C18.0767 7.69034 18.4446 7.98722 18.7116 8.3679C18.9787 8.74574 19.1165 9.1804 19.125 9.67188H17.6037ZM22.5939 16H20.9064L23.9788 7.27273H25.9305L29.0072 16H27.3197L24.9888 9.0625H24.9206L22.5939 16ZM22.6493 12.5781H27.2515V13.848H22.6493V12.5781ZM31.1167 16V7.27273H32.6977V14.6747H36.5414V16H31.1167ZM38.8736 16V7.27273H44.5497V8.59801H40.4546V10.9673H44.2557V12.2926H40.4546V14.6747H44.5838V16H38.8736Z" fill="white"/></svg>',
												'title' => __( 'Square', 'rishi' ),
											],

											'oval'   => [
												'src'   => '<svg width="45" height="24" viewBox="0 0 58 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 12C0 5.37258 5.37258 0 12 0H46C52.6274 0 58 5.37258 58 12C58 18.6274 52.6274 24 46 24H12C5.37258 24 0 18.6274 0 12Z" fill="#566779"/><path d="M17.6037 9.67188C17.5639 9.29972 17.3963 9.00994 17.1009 8.80256C16.8082 8.59517 16.4276 8.49148 15.9588 8.49148C15.6293 8.49148 15.3466 8.54119 15.1108 8.64062C14.875 8.74006 14.6946 8.875 14.5696 9.04545C14.4446 9.21591 14.3807 9.41051 14.3778 9.62926C14.3778 9.81108 14.419 9.96875 14.5014 10.1023C14.5866 10.2358 14.7017 10.3494 14.8466 10.4432C14.9915 10.5341 15.152 10.6108 15.3281 10.6733C15.5043 10.7358 15.6818 10.7884 15.8608 10.831L16.679 11.0355C17.0085 11.1122 17.3253 11.2159 17.6293 11.3466C17.9361 11.4773 18.2102 11.642 18.4517 11.8409C18.696 12.0398 18.8892 12.2798 19.0312 12.5611C19.1733 12.8423 19.2443 13.1719 19.2443 13.5497C19.2443 14.0611 19.1136 14.5114 18.8523 14.9006C18.5909 15.2869 18.2131 15.5895 17.7188 15.8082C17.2273 16.0241 16.6321 16.1321 15.9332 16.1321C15.2543 16.1321 14.6648 16.027 14.1648 15.8168C13.6676 15.6065 13.2784 15.2997 12.9972 14.8963C12.7188 14.4929 12.5682 14.0014 12.5455 13.4219H14.1009C14.1236 13.7259 14.2173 13.9787 14.3821 14.1804C14.5469 14.3821 14.7614 14.5327 15.0256 14.6321C15.2926 14.7315 15.5909 14.7812 15.9205 14.7812C16.2642 14.7812 16.5653 14.7301 16.8239 14.6278C17.0852 14.5227 17.2898 14.3778 17.4375 14.1932C17.5852 14.0057 17.6605 13.7869 17.6634 13.5369C17.6605 13.3097 17.5938 13.1222 17.4631 12.9744C17.3324 12.8239 17.1491 12.6989 16.9134 12.5994C16.6804 12.4972 16.4077 12.4062 16.0952 12.3267L15.1023 12.071C14.3835 11.8864 13.8153 11.6065 13.3977 11.2315C12.983 10.8537 12.7756 10.3523 12.7756 9.72727C12.7756 9.21307 12.9148 8.76278 13.1932 8.37642C13.4744 7.99006 13.8565 7.69034 14.3395 7.47727C14.8224 7.26136 15.3693 7.15341 15.9801 7.15341C16.5994 7.15341 17.142 7.26136 17.608 7.47727C18.0767 7.69034 18.4446 7.98722 18.7116 8.3679C18.9787 8.74574 19.1165 9.1804 19.125 9.67188H17.6037ZM22.5939 16H20.9064L23.9788 7.27273H25.9305L29.0072 16H27.3197L24.9888 9.0625H24.9206L22.5939 16ZM22.6493 12.5781H27.2515V13.848H22.6493V12.5781ZM31.1167 16V7.27273H32.6977V14.6747H36.5414V16H31.1167ZM38.8736 16V7.27273H44.5497V8.59801H40.4546V10.9673H44.2557V12.2926H40.4546V14.6747H44.5838V16H38.8736Z" fill="white"/></svg>',
												'title' => __( 'Oval', 'rishi' ),
											],

											'semi-oval' => [
												'src'   => '<svg width="45" height="24" viewBox="0 0 58 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0H46C52.6274 0 58 5.37258 58 12C58 18.6274 52.6274 24 46 24H0V0Z" fill="#566779"/><path d="M17.6037 9.67188C17.5639 9.29972 17.3963 9.00994 17.1009 8.80256C16.8082 8.59517 16.4276 8.49148 15.9588 8.49148C15.6293 8.49148 15.3466 8.54119 15.1108 8.64062C14.875 8.74006 14.6946 8.875 14.5696 9.04545C14.4446 9.21591 14.3807 9.41051 14.3778 9.62926C14.3778 9.81108 14.419 9.96875 14.5014 10.1023C14.5866 10.2358 14.7017 10.3494 14.8466 10.4432C14.9915 10.5341 15.152 10.6108 15.3281 10.6733C15.5043 10.7358 15.6818 10.7884 15.8608 10.831L16.679 11.0355C17.0085 11.1122 17.3253 11.2159 17.6293 11.3466C17.9361 11.4773 18.2102 11.642 18.4517 11.8409C18.696 12.0398 18.8892 12.2798 19.0312 12.5611C19.1733 12.8423 19.2443 13.1719 19.2443 13.5497C19.2443 14.0611 19.1136 14.5114 18.8523 14.9006C18.5909 15.2869 18.2131 15.5895 17.7188 15.8082C17.2273 16.0241 16.6321 16.1321 15.9332 16.1321C15.2543 16.1321 14.6648 16.027 14.1648 15.8168C13.6676 15.6065 13.2784 15.2997 12.9972 14.8963C12.7188 14.4929 12.5682 14.0014 12.5455 13.4219H14.1009C14.1236 13.7259 14.2173 13.9787 14.3821 14.1804C14.5469 14.3821 14.7614 14.5327 15.0256 14.6321C15.2926 14.7315 15.5909 14.7812 15.9205 14.7812C16.2642 14.7812 16.5653 14.7301 16.8239 14.6278C17.0852 14.5227 17.2898 14.3778 17.4375 14.1932C17.5852 14.0057 17.6605 13.7869 17.6634 13.5369C17.6605 13.3097 17.5938 13.1222 17.4631 12.9744C17.3324 12.8239 17.1491 12.6989 16.9134 12.5994C16.6804 12.4972 16.4077 12.4062 16.0952 12.3267L15.1023 12.071C14.3835 11.8864 13.8153 11.6065 13.3977 11.2315C12.983 10.8537 12.7756 10.3523 12.7756 9.72727C12.7756 9.21307 12.9148 8.76278 13.1932 8.37642C13.4744 7.99006 13.8565 7.69034 14.3395 7.47727C14.8224 7.26136 15.3693 7.15341 15.9801 7.15341C16.5994 7.15341 17.142 7.26136 17.608 7.47727C18.0767 7.69034 18.4446 7.98722 18.7116 8.3679C18.9787 8.74574 19.1165 9.1804 19.125 9.67188H17.6037ZM22.5939 16H20.9064L23.9788 7.27273H25.9305L29.0072 16H27.3197L24.9888 9.0625H24.9206L22.5939 16ZM22.6493 12.5781H27.2515V13.848H22.6493V12.5781ZM31.1167 16V7.27273H32.6977V14.6747H36.5414V16H31.1167ZM38.8736 16V7.27273H44.5497V8.59801H40.4546V10.9673H44.2557V12.2926H40.4546V14.6747H44.5838V16H38.8736Z" fill="white"/></svg>',
												'title' => __( 'Semi Oval', 'rishi' ),
											],
										],
										'conditions' => [ 'has_sale_badge' => 'yes']
									],
								),
							),
							\Rishi\Customizer\Helpers\Basic::uniqid() => array(
								'title'   => __( 'Design', 'rishi' ),
								'control'    => ControlTypes::TAB,
								'options' => array(
									'salesBagdgeColor' => [
										'label'           => __('Sales Badge Color', 'rishi'),
										'control'         => ControlTypes::COLOR_PICKER,
										'colorPalette'	  => true,
										'design'          => 'inline',
										'divider'         => 'bottom',
										'responsive'      => false,
										'skipEditPalette' => true,
										'value'           => $woo_defaults['salesBagdgeColor'],
										'pickers' => [
											[
												'title' => __( 'Text Color', 'rishi' ),
												'id' => 'default',
											],

											[
												'title' => __( 'Background', 'rishi' ),
												'id' => 'background',
											],
										],
									],
								),
							),
						),
					),
				)
			)
		));

		$this->add_setting( 'woo_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'woo_content_background' => [
					'label'           => __('Content Area Background', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'colorPalette'	  => true,
					'design'          => 'inline',
					'divider'         => 'bottom',
					'responsive'      => false,
					'value'           => $woo_defaults['woo_content_background'],
					'pickers' => [
						[
							'title' => __( 'Background Color', 'rishi' ),
							'id' => 'default',
						]
					],
					'conditions' => [
						'woocommerce_layout' => 'boxed|content_boxed'
					]
				],
				'woo_content_boxed_shadow'                         => [
					'label'      => __( 'Content Area Shadow', 'rishi' ),
					'control'    => ControlTypes::BOX_SHADOW,
					'value'      => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value( [
						'enable'   => false,
						'h_offset' => '0px',
						'v_offset' => '12px',
						'blur'     => '18px',
						'spread'   => '-6px',
						'inset'    => false,
						'color'    => 'rgba(34, 56, 101, 0.04)',
					] ),
					'design' => 'inline',
					'conditions' => [
						'woocommerce_layout' => 'boxed|content_boxed'
					]
				],
				'woo_boxed_content_spacing' => [
					'label'   => __('Content Area Padding', 'rishi'),
					'control'    => ControlTypes::INPUT_SPACING,
					'divider' => 'top',
					'value'      => $woo_defaults['woo_boxed_content_spacing'],
					'conditions' => [
						'woocommerce_layout' => 'boxed|content_boxed'
					],
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				],
				'woo_content_boxed_radius' => [
					'label' => __( 'Content Area Border Radius', 'rishi' ),
					'control'    => ControlTypes::INPUT_SPACING,
					'divider' => 'top',
					'value'      => $woo_defaults['woo_content_boxed_radius'],
					'conditions' => [
						'woocommerce_layout' => 'boxed|content_boxed'
					],
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				],

				\Rishi\Customizer\Helpers\Basic::uniqid() => [
					'control'  => ControlTypes::TITLE,
					'label' => __( 'WooCommerce Button Settings', 'rishi' ),
					'desc' => sprintf(
						__( 'WooCommerce Button works for %1$sAdd to cart%2$s Button, %1$sCart Page%2$s Button, %1$sCheckout Page%2$s Buttons, %1$sMy account Page%2$s button and other %1$sMessage%2$s buttons', 'rishi' ),
						'<b>',
						'</b>'
					),
				],
				'woo_btn_text_color' => [
					'label'           => __('Text Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'top',
					'responsive'      => false,
					'value'           => $woo_defaults['woo_btn_text_color'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						],
						[
							'title' => __('Hover', 'rishi'),
							'id' => 'hover',
						],
					],
				],
				'woo_btn_bg_color' => [
					'label'           => __('Background Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'divider'         => 'top',
					'colorPalette'	  => true,
					'responsive'      => false,
					'value'           => $woo_defaults['woo_btn_bg_color'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						],
						[
							'title' => __('Hover', 'rishi'),
							'id' => 'hover',
						],
					],
				],
				'woo_btn_border_color' => [
					'label'           => __('Border Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'divider'         => 'top',
					'responsive'      => false,
					'colorPalette'	  => true,
					'value'           => $woo_defaults['woo_btn_border_color'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						],
						[
							'title' => __('Hover', 'rishi'),
							'id' => 'hover',
						],
					],
				],

				'woo_general_padding' => [
					'label'   => __('Padding', 'rishi'),
					'control'    => ControlTypes::INPUT_SPACING,
					'divider' => 'top',
					'value'      => $woo_defaults['woo_general_padding'],
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				],
				'woo_general_radius' => [
					'label' => __( 'Border Radius', 'rishi' ),
					'control'    => ControlTypes::INPUT_SPACING,
					'divider' => 'top',
					'value'      => $woo_defaults['woo_general_radius'],
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				],
			)
		));
	}

	/**
	 * Set default value for Woo page.
	 */
	protected static function get_woo_general_default_value() {

		$woo_defaults = array(
			'woocommerce_sidebar_layout'         => 'no-sidebar',
			'woocommerce_layout'                 => 'boxed',
			'woo_layout_streched_ed'             => 'no',
			'has_sale_badge'                     => 'yes',
			'shop_page_content_background_color' => 'yes',
			'sales_badge_title'                  => __('SALE!', 'rishi'),
			'shop_cards_sales_badge_design'      => 'circle',
			'woo_boxed_content_spacing'      => array(
				'linked' => true,
				'top'    => '40',
				'left'   => '40',
				'right'  => '40',
				'bottom' => '40',
				'unit' => 'px',
			),
			'woo_general_padding'      => array(
				'linked' => true,
				'top'    => '10',
				'left'   => '32',
				'right'  => '32',
				'bottom' => '10',
				'unit' => 'px',
			),
			'woo_content_boxed_radius'      => array(
				'linked' => true,
				'top'    => '3',
				'left'   => '3',
				'right'  => '3',
				'bottom' => '3',
				'unit' => 'px',
			),
			'woo_general_radius'      => array(
				'linked' => true,
				'top'    => '3',
				'left'   => '3',
				'right'  => '3',
				'bottom' => '3',
				'unit' => 'px',
			),
			'salesBagdgeColor' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],

				'background' => [
					'color' => '#E71919',
				],
			],
			'woo_content_background' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],
			],
			'woo_content_boxed_shadow'   => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value( [
				'enable'   => false,
				'h_offset' => '0px',
				'v_offset' => '12px',
				'blur'     => '18px',
				'spread'   => '-6px',
				'inset'    => false,
				'color'    => 'rgba(34, 56, 101, 0.04)',
			] ),
			'woo_btn_text_color' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],

				'hover' => [
					'color' => 'var(--paletteColor5)',
				],
			],
			'woo_btn_bg_color' => [
				'default' => [
					'color' => 'var(--paletteColor3)',
				],

				'hover' => [
					'color' => 'var(--paletteColor4)',
				],
			],
			'woo_btn_border_color' => [
				'default' => [
					'color' => 'var(--paletteColor3)',
				],

				'hover' => [
					'color' => 'var(--paletteColor4)',
				],
			]
		);

		return $woo_defaults;
	}
}
