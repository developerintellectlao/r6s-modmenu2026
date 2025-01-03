<?php
/**
 *
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\Abstracts\Customize_Settings;
use \Rishi\Customizer\ControlTypes;

class Single_Setting extends Customize_Settings {

	public function add_settings() {
		$this->add_container_settings();
	}

	protected function get_page_layout_choices() {
		return array(
			'right-sidebar' => array(
				'src'   => '<svg width="118" height="139" viewBox="0 0 118 139" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.448052" y="1.29742" width="116.683" height="137.104" rx="1.34416" fill="white"/><g clip-path="url(#clip0_522_2181)"><path opacity="0.25" d="M67.9072 111.45H7.24805V112.705H67.9072V111.45Z" fill="#42474B"/><path opacity="0.25" d="M72.0994 114.565H7.24805V115.82H72.0994V114.565Z" fill="#42474B"/><path opacity="0.25" d="M68.399 108.613H7.24805V109.868H68.399V108.613Z" fill="#42474B"/><path opacity="0.25" d="M68.399 117.681H7.24805V118.936H68.399V117.681Z" fill="#42474B"/><path opacity="0.25" d="M67.8228 121.173H6.67188V122.428H67.8228V121.173Z" fill="#42474B"/><path opacity="0.4" d="M16.7498 7.72945H7.24805V9.4624H16.7498V7.72945Z" fill="#42474B"/><path opacity="0.4" d="M27.678 7.72945H17.7012V9.4624H27.678V7.72945Z" fill="#42474B"/><path opacity="0.3" d="M71.2462 12.2335H7.24805V15.4724H71.2462V12.2335Z" fill="#42474B"/><path opacity="0.3" d="M48.0139 17.4157H7.24805V20.6546H48.0139V17.4157Z" fill="#42474B"/><path opacity="0.23" d="M8.67335 27.3699C9.46046 27.3699 10.0986 26.594 10.0986 25.6369C10.0986 24.6798 9.46046 23.9039 8.67335 23.9039C7.88615 23.9039 7.24805 24.6798 7.24805 25.6369C7.24805 26.594 7.88615 27.3699 8.67335 27.3699Z" fill="#42474B"/><path opacity="0.4" d="M21.0111 25.2193H11.4219V27.1625H21.0111V25.2193Z" fill="#42474B"/><path opacity="0.25" d="M39.6543 25.2193H23.6719V27.1625H39.6543V25.2193Z" fill="#42474B"/><path opacity="0.25" d="M22.9895 24.8952L22.0742 26.8228" stroke="#42474B" stroke-width="0.747287"/><path opacity="0.1" d="M82.8945 7.72945H110.544V131.032H82.8945V7.72945Z" fill="#42474B"/><path opacity="0.2" d="M7.24805 34.2874H72.0979V79.0796H7.24805V34.2874Z" fill="#42474B"/><g opacity="0.4"><path d="M44.1694 50.7305C44.1694 51.2922 44.3064 51.8413 44.5631 52.3083C44.8198 52.7754 45.1846 53.1394 45.6114 53.3542C46.0382 53.5691 46.5078 53.6253 46.9609 53.5156C47.414 53.406 47.8302 53.1354 48.1567 52.7382C48.4834 52.3409 48.7057 51.8348 48.7957 51.2839C48.8857 50.7329 48.8394 50.1619 48.6624 49.643C48.4855 49.1241 48.186 48.6807 47.8018 48.3688C47.4176 48.0569 46.966 47.8905 46.504 47.8907C45.8848 47.891 45.291 48.1904 44.8531 48.7229C44.4154 49.2554 44.1694 49.9775 44.1694 50.7305ZM48.8395 64.9318H30.1543L34.8254 49.7855L41.0538 59.2516L44.168 56.4118L48.8395 64.9318Z" fill="white"/></g><path opacity="0.25" d="M68.2658 86.0847H12.7793V87.3394H68.2658V86.0847Z" fill="#42474B"/><path opacity="0.25" d="M72.1006 89.2002H12.7793V90.4549H72.1006V89.2002Z" fill="#42474B"/><path opacity="0.25" d="M67.9072 95.153H7.24805V96.4077H67.9072V95.153Z" fill="#42474B"/><path opacity="0.25" d="M72.0994 98.2692H7.24805V99.5239H72.0994V98.2692Z" fill="#42474B"/><path opacity="0.25" d="M68.399 92.3171H7.24805V93.5718H68.399V92.3171Z" fill="#42474B"/><path opacity="0.25" d="M68.399 101.385H7.24805V102.64H68.399V101.385Z" fill="#42474B"/><path opacity="0.25" d="M67.8228 104.877H6.67188V106.131H67.8228V104.877Z" fill="#42474B"/><g opacity="0.25"><path opacity="0.25" d="M10.5737 85.714H7.24805V90.9129H10.5737V85.714Z" fill="white"/><path opacity="0.25" d="M10.0981 86.2917H7.72266V90.3352H10.0981V86.2917Z" stroke="#42474B" stroke-width="1.49457"/></g></g><rect x="0.448052" y="1.29742" width="116.683" height="137.104" rx="1.34416" stroke="#E0E3E7" stroke-width="0.896104"/><defs><clipPath id="clip0_522_2181"><rect width="103.241" height="123.662" fill="white" transform="translate(7.16992 8.01817)"/></clipPath></defs></svg>
				',
				'title' => __( 'Right Sidebar', 'rishi' ),
			),

			'left-sidebar'  => array(
				'src'   => '<svg width="122" height="139" viewBox="0 0 122 139" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_522_2213)"><rect y="0.849365" width="121.345" height="138" rx="2.37931" fill="white"/><path opacity="0.4" d="M56.9508 6.78818H47.0566V8.64704H56.9508V6.78818Z" fill="#566779"/><path opacity="0.4" d="M67.9469 6.78818H58.0527V8.64704H67.9469V6.78818Z" fill="#566779"/><path d="M55.8558 7.40765H48.1602V7.71762H55.8558V7.40765Z" fill="white"/><path d="M66.848 7.40765H59.1523V7.71762H66.848V7.40765Z" fill="white"/><path opacity="0.3" d="M113.09 11.0966H47.0566V14.1947H113.09V11.0966Z" fill="#566779"/><path opacity="0.3" d="M89.1193 16.0536H47.0566V19.1519H89.1193V16.0536Z" fill="#566779"/><path opacity="0.23" d="M48.7057 25.9964C49.6164 25.9964 50.3547 25.1641 50.3547 24.1376C50.3547 23.111 49.6164 22.2787 48.7057 22.2787C47.795 22.2787 47.0566 23.111 47.0566 24.1376C47.0566 25.1641 47.795 25.9964 48.7057 25.9964Z" fill="#566779"/><path opacity="0.4" d="M61.2575 23.5182H51.3633V25.3769H61.2575V23.5182Z" fill="#566779"/><path opacity="0.25" d="M80.4968 23.5182H64.0059V25.3769H80.4968V23.5182Z" fill="#566779"/><path opacity="0.25" d="M63.2978 23.2082L62.3535 25.0519" stroke="#707070" stroke-width="0.774332"/><path opacity="0.2" d="M47.0566 32.1923H113.969V75.0387H47.0566V32.1923Z" fill="#566779"/><g opacity="0.4"><path d="M85.1561 47.921C85.1561 48.4583 85.2978 48.9836 85.5626 49.4303C85.8275 49.8771 86.2038 50.2252 86.6444 50.4308C87.0842 50.6364 87.5689 50.6901 88.0366 50.5852C88.5043 50.4803 88.9333 50.2214 89.2701 49.8414C89.6077 49.4614 89.837 48.9773 89.9299 48.4503C90.0228 47.9233 89.9748 47.3771 89.792 46.8808C89.6093 46.3844 89.3003 45.9603 88.9039 45.6619C88.5074 45.3636 88.042 45.2044 87.5651 45.2047C86.9262 45.2049 86.3137 45.4912 85.8615 46.0007C85.4101 46.51 85.1561 47.2008 85.1561 47.921ZM89.9748 61.5053H70.6953L75.5149 47.0171L81.9419 56.0719L85.1546 53.3555L89.9748 61.5053Z" fill="white"/></g><path opacity="0.25" d="M110.015 81.739H52.7637V82.9392H110.015V81.739Z" fill="#566779"/><path opacity="0.25" d="M109.645 90.4138H47.0566V91.6141H109.645V90.4138Z" fill="#566779"/><path opacity="0.25" d="M109.645 99.0887H47.0566V100.289H109.645V99.0887Z" fill="#566779"/><path opacity="0.25" d="M113.972 84.7194H52.7637V85.9196H113.972V84.7194Z" fill="#566779"/><path opacity="0.25" d="M113.971 93.3943H47.0566V94.5945H113.971V93.3943Z" fill="#566779"/><path opacity="0.25" d="M113.971 102.069H47.0566V103.269H113.971V102.069Z" fill="#566779"/><path opacity="0.25" d="M110.152 87.7006H47.0566V88.9008H110.152V87.7006Z" fill="#566779"/><path opacity="0.25" d="M110.152 96.3746H47.0566V97.5749H110.152V96.3746Z" fill="#566779"/><path opacity="0.25" d="M110.152 105.049H47.0566V106.25H110.152V105.049Z" fill="#566779"/><path opacity="0.25" d="M110.152 107.763H47.0566V108.963H110.152V107.763Z" fill="#566779"/><g opacity="0.25"><path opacity="0.25" d="M50.9062 81.7622H47.0586V86.0993H50.9062V81.7622Z" fill="white"/><path opacity="0.25" d="M50.4159 82.3151H47.5488V85.5464H50.4159V82.3151Z" stroke="#566779" stroke-width="1.54866"/></g><path opacity="0.1" d="M6.49805 6.44917H35.9227V134.214H6.49805V6.44917Z" fill="#566779"/><path opacity="0.25" d="M110.01 113.319H46.4023V114.563H110.01V113.319Z" fill="#566779"/><path opacity="0.25" d="M114.405 116.407H46.4023V117.652H114.405V116.407Z" fill="#566779"/><path opacity="0.25" d="M110.525 110.507H46.4023V111.751H110.525V110.507Z" fill="#566779"/><path opacity="0.25" d="M110.525 119.497H46.4023V120.741H110.525V119.497Z" fill="#566779"/><path opacity="0.25" d="M110.525 123.369H46.4023V124.612H110.525V123.369Z" fill="#566779"/><path opacity="0.25" d="M110.525 126.466H46.4023V127.71H110.525V126.466Z" fill="#566779"/></g><rect x="0.594828" y="1.44419" width="120.155" height="136.81" rx="1.78448" stroke="#E0E3E7" stroke-width="1.18966"/><defs><clipPath id="clip0_522_2213"><rect y="0.849365" width="121.345" height="138" rx="2.37931" fill="white"/></clipPath></defs></svg>',
				'title' => __( 'Left Sidebar', 'rishi' ),
			),

			'no-sidebar'    => array(
				'src'   => '<svg width="109" height="139" viewBox="0 0 109 139" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="1.34937" width="108" height="137" rx="1.5" fill="white"/><path opacity="0.4" d="M53.9054 7.84956H40.8145V9.77103H53.9054V7.84956Z" fill="#566779"/><path opacity="0.4" d="M68.4503 7.84956H55.3594V9.77103H68.4503V7.84956Z" fill="#566779"/><path d="M52.4494 8.49004H42.2676V8.81029H52.4494V8.49004Z" fill="white"/><path d="M66.9943 8.49004H56.8125V8.81029H66.9943V8.49004Z" fill="white"/><path opacity="0.3" d="M98.3614 12.3028H10.9941V15.5053H98.3614V12.3028Z" fill="#566779"/><path opacity="0.3" d="M82.6477 17.4268H26.9961V20.6292H82.6477V17.4268Z" fill="#566779"/><path opacity="0.23" d="M34.9904 27.7049C36.1954 27.7049 37.1722 26.8446 37.1722 25.7834C37.1722 24.7222 36.1954 23.8619 34.9904 23.8619C33.7854 23.8619 32.8086 24.7222 32.8086 25.7834C32.8086 26.8446 33.7854 27.7049 34.9904 27.7049Z" fill="#566779"/><path opacity="0.4" d="M51.5929 25.1429H38.502V27.0643H51.5929V25.1429Z" fill="#566779"/><path opacity="0.25" d="M77.0506 25.1429H55.2324V27.0643H77.0506V25.1429Z" fill="#566779"/><path opacity="0.25" d="M54.2949 24.823L53.0455 26.7289" stroke="#707070" stroke-width="0.727273"/><path opacity="0.2" d="M10.9941 34.1097H99.5265V78.399H10.9941V34.1097Z" fill="#566779"/><g opacity="0.4"><path d="M61.3985 50.368C61.3985 50.9233 61.5855 51.4662 61.9358 51.928C62.2862 52.3897 62.7841 52.7496 63.3667 52.9621C63.9493 53.1747 64.5904 53.2303 65.2089 53.1219C65.8274 53.0136 66.3955 52.7462 66.8414 52.3535C67.2873 51.9608 67.5909 51.4604 67.714 50.9158C67.837 50.3711 67.7738 49.8065 67.5325 49.2934C67.2912 48.7803 66.8825 48.3418 66.3582 48.0333C65.8339 47.7247 65.2175 47.5601 64.5869 47.5601C63.7412 47.5601 62.9303 47.8559 62.3323 48.3825C61.7344 48.9091 61.3985 49.6233 61.3985 50.368ZM67.7752 64.4082H42.2676L48.6443 49.4322L57.1476 58.7924L61.3992 55.9844L67.7752 64.4082Z" fill="white"/></g><path opacity="0.25" d="M94.2906 83.4038H18.543V84.6444H94.2906V83.4038Z" fill="#566779"/><path opacity="0.25" d="M93.8029 92.3708H10.9941V93.6114H93.8029V92.3708Z" fill="#566779"/><path opacity="0.25" d="M93.8029 104.143H10.9941V105.384H93.8029V104.143Z" fill="#566779"/><path opacity="0.25" d="M93.8029 124.719H10.9941V125.96H93.8029V124.719Z" fill="#566779"/><path opacity="0.25" d="M98.3631 86.4846H18.4453V87.7252H98.3631V86.4846Z" fill="#566779"/><path opacity="0.25" d="M95.4523 95.4514H10.9941V96.6921H95.4523V95.4514Z" fill="#566779"/><path opacity="0.25" d="M95.4523 116.029H10.9941V117.269H95.4523V116.029Z" fill="#566779"/><path opacity="0.25" d="M98.3614 107.224H10.9941V108.464H98.3614V107.224Z" fill="#566779"/><path opacity="0.25" d="M98.3614 127.8H10.9941V129.041H98.3614V127.8Z" fill="#566779"/><path opacity="0.25" d="M94.4749 89.5661H10.9941V90.8067H94.4749V89.5661Z" fill="#566779"/><path opacity="0.25" d="M94.4749 101.338H10.9941V102.578H94.4749V101.338Z" fill="#566779"/><path opacity="0.25" d="M94.4749 121.915H10.9941V123.155H94.4749V121.915Z" fill="#566779"/><path opacity="0.25" d="M94.4749 98.5329H10.9941V99.7735H94.4749V98.5329Z" fill="#566779"/><path opacity="0.25" d="M94.4749 119.109H10.9941V120.35H94.4749V119.109Z" fill="#566779"/><path opacity="0.25" d="M94.4749 110.305H10.9941V111.545H94.4749V110.305Z" fill="#566779"/><path opacity="0.25" d="M94.4749 130.882H10.9941V132.122H94.4749V130.882Z" fill="#566779"/><g opacity="0.25"><path d="M16.085 83.4275H10.9941V87.911H16.085V83.4275Z" fill="white"/><path d="M15.3571 84.0681H11.7207V87.2705H15.3571V84.0681Z" stroke="#566779" stroke-width="1.45455"/></g><rect x="0.5" y="1.34937" width="108" height="137" rx="1.5" stroke="#E0E3E7"/></svg>',
				'title' => __( 'Fullwidth', 'rishi' ),
			),
			'centered'      => array(
				'src'   => '<svg width="118" height="142" viewBox="0 0 118 142" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_522_2293)"><rect y="0.849365" width="117.538" height="141.045" rx="2.15486" fill="white"/><path opacity="0.4" d="M59.7112 10.2524H49.1445V12.6032H59.7112V10.2524Z" fill="#566779"/><path opacity="0.4" d="M71.4514 10.2524H60.8848V12.6032H71.4514V10.2524Z" fill="#566779"/><path d="M58.5354 11.036H50.3164V11.4278H58.5354V11.036Z" fill="white"/><path d="M70.2776 11.036H62.0586V11.4278H70.2776V11.036Z" fill="white"/><path opacity="0.3" d="M95.5969 15.7007H25.0742V19.6186H95.5969V15.7007Z" fill="#566779"/><path opacity="0.3" d="M82.9105 21.9693H37.9883V25.8873H82.9105V21.9693Z" fill="#566779"/><path opacity="0.23" d="M44.4463 34.5436C45.4195 34.5436 46.2078 33.4912 46.2078 32.1928C46.2078 30.8945 45.4195 29.8421 44.4463 29.8421C43.4738 29.8421 42.6855 30.8945 42.6855 32.1928C42.6855 33.4912 43.4738 34.5436 44.4463 34.5436Z" fill="#566779"/><path opacity="0.4" d="M57.8499 31.4092H47.2832V33.76H57.8499V31.4092Z" fill="#566779"/><path opacity="0.25" d="M78.397 31.4092H60.7852V33.76H78.397V31.4092Z" fill="#566779"/><path opacity="0.25" d="M60.0288 31.0178L59.0195 33.3495" stroke="#707070" stroke-width="0.783586"/><path opacity="0.2" d="M10.9688 42.3794H106.356V96.5636H10.9688V42.3794Z" fill="#566779"/><g opacity="0.4"><path d="M65.2763 62.27C65.2763 62.9494 65.4777 63.6136 65.8554 64.1785C66.233 64.7434 66.769 65.1837 67.3967 65.4437C68.0243 65.7037 68.7155 65.7717 69.3815 65.6392C70.0483 65.5066 70.6603 65.1795 71.1406 64.6991C71.621 64.2186 71.9485 63.6065 72.0809 62.9402C72.2134 62.2738 72.1452 61.5831 71.885 60.9553C71.6249 60.3276 71.1853 59.7912 70.6203 59.4136C70.0554 59.0362 69.3909 58.8347 68.7115 58.8347C67.8002 58.8347 66.9265 59.1966 66.2824 59.8408C65.6383 60.4851 65.2763 61.3589 65.2763 62.27ZM72.1468 79.4469H44.6641L51.5345 61.1251L60.6962 72.5765L65.2771 69.1412L72.1468 79.4469Z" fill="white"/></g><path opacity="0.25" d="M92.3092 102.716H31.166V103.614H92.3092V102.716Z" fill="#566779"/><path opacity="0.25" d="M91.9164 109.205H25.0742V110.103H91.9164V109.205Z" fill="#566779"/><path opacity="0.25" d="M91.9164 117.726H25.0742V118.624H91.9164V117.726Z" fill="#566779"/><path opacity="0.25" d="M91.9164 132.619H25.0742V133.517H91.9164V132.619Z" fill="#566779"/><path opacity="0.25" d="M95.5985 104.946H31.0898V105.844H95.5985V104.946Z" fill="#566779"/><path opacity="0.25" d="M93.2477 111.435H25.0742V112.333H93.2477V111.435Z" fill="#566779"/><path opacity="0.25" d="M93.2477 126.329H25.0742V127.227H93.2477V126.329Z" fill="#566779"/><path opacity="0.25" d="M95.5962 119.956H25.0742V120.854H95.5962V119.956Z" fill="#566779"/><path opacity="0.25" d="M95.5962 134.849H25.0742V135.747H95.5962V134.849Z" fill="#566779"/><path opacity="0.25" d="M92.4595 107.176H25.0742V108.074H92.4595V107.176Z" fill="#566779"/><path opacity="0.25" d="M92.4595 115.696H25.0742V116.594H92.4595V115.696Z" fill="#566779"/><path opacity="0.25" d="M92.4595 130.589H25.0742V131.487H92.4595V130.589Z" fill="#566779"/><path opacity="0.25" d="M92.4595 113.665H25.0742V114.563H92.4595V113.665Z" fill="#566779"/><path opacity="0.25" d="M92.4595 128.558H25.0742V129.456H92.4595V128.558Z" fill="#566779"/><path opacity="0.25" d="M92.4595 122.185H25.0742V123.083H92.4595V122.185Z" fill="#566779"/><path opacity="0.25" d="M92.4595 137.079H25.0742V137.977H92.4595V137.079Z" fill="#566779"/><g opacity="0.25"><path opacity="0.25" d="M29.1833 102.733H25.0742V105.978H29.1833V102.733Z" fill="white"/><path opacity="0.25" d="M28.5973 103.196H25.6621V105.514H28.5973V103.196Z" stroke="#566779" stroke-width="1.56717"/></g></g><rect x="0.538715" y="1.38808" width="116.46" height="139.968" rx="1.61615" stroke="#E0E3E7" stroke-width="1.07743"/><defs><clipPath id="clip0_522_2293"><rect y="0.849365" width="117.538" height="141.045" rx="2.15486" fill="white"/></clipPath></defs></svg>',
				'title' => __( 'Fullwidth Centered', 'rishi' ),
			),
		);
	}

	protected function add_container_settings() {

		$posts_defaults = \Rishi\Customizer\Helpers\Defaults::get_posts_default_value();

		$prefix = 'single_post_';

		$this->add_setting( 'single_post_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => apply_filters(
				'rishi_single_general_tab',
				array(
					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'POST ELEMENT', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),
					'single_blog_post_meta'  => array(
						'control'  => ControlTypes::LAYERS,
						'attr'     => array( 'data-layers' => 'title-elements' ),
						'design'   => 'block',
						'value'    => \Rishi\Customizer\Helpers\Defaults::singlepost_structure_defaults(),

						'settings' => array(
							'categories'     => array(
								'label'   => __( 'Categories', 'rishi' ),
								'options' => array(
									'separator' => array(
										'label'   => __( 'Separator', 'rishi' ),
										'control' => ControlTypes::INPUT_SEPARATOR,
										'value'   => 'dot',
										'design'  => 'block',
										'choices' => array(
											'dot'          => __( 'Dot', 'rishi' ),
											'normal-slash' => __( 'Normal Slash', 'rishi' ),
											'pipe'         => __( 'Pipe', 'rishi' ),
											'back-slash'   => __( 'Back Slash', 'rishi' ),
										),
									),
								),
							),
							'featured_image' => array(
								'label'   => __( 'Featured Image', 'rishi' ),
								'options' => array(
									'featured_image_ratio' => array(
										'label'   => __( 'Image Ratio', 'rishi' ),
										'control' => ControlTypes::INPUT_SELECT,
										'design'  => 'inline',
										'value'   => 'auto',
										'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
											array(
												'auto' => __('Original', 'rishi'),
												'1/1'  => __( 'Square - 1:1', 'rishi'),
												'4/3'  => __('Standard - 4:3', 'rishi'),
												'3/4'  => __('Portrait - 3:4', 'rishi'),
												'3/2'  => __('Classic - 3:2', 'rishi'),
												'2/3'  => __( 'Classic Portrait - 2:3', 'rishi'),
												'16/9' => __('Wide - 16:9', 'rishi'),
												'9/16' => __('Tall - 9:16', 'rishi'),
											)
										),
									),
									'featured_image_scale' => array(
										'label'   => __( 'Image Scale', 'rishi' ),
										'control' => ControlTypes::INPUT_SELECT,
										'design'  => 'inline',
										'divider' => 'top',
										'value'   => 'contain',
										'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
											array(
												'contain' => __( 'Contain', 'rishi' ),
												'cover'   => __( 'Cover', 'rishi' ),
												'fill'    => __( 'Fill', 'rishi' ),
											)
										),
									),
									'featured_image_size'  => array(
										'label'   => __( 'Image Size', 'rishi' ),
										'control' => ControlTypes::INPUT_SELECT,
										'design'  => 'inline',
										'divider' => 'top',
										'value'   => 'full',
										'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( \Rishi\Customizer\Helpers\Basic::get_all_image_sizes() ),
									),
									'featured_image_visibility' => array(
										'label'   => __( 'Image Visibility', 'rishi' ),
										'control' => ControlTypes::VISIBILITY,
										'design'  => 'block',
										'divider' => 'top',
										'value'   => array(
											'desktop' => 'desktop',
											'tablet'  => 'tablet',
											'mobile'  => 'mobile',
										),
										'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
											array(
												'desktop' => __( 'Desktop', 'rishi' ),
												'tablet'  => __( 'Tablet', 'rishi' ),
												'mobile'  => __( 'Mobile', 'rishi' ),
											)
										),
									),
								),
							),
							'custom_title'   => array(
								'label'   => __( 'Title', 'rishi' ),
								'options' => array(
									'heading_tag' => array(
										'label'   => __( 'Heading tag', 'rishi' ),
										'control' => ControlTypes::INPUT_SELECT,
										'design'  => 'inline',
										'value'   => 'h2',
										'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
											array(
												'h1' => 'H1',
												'h2' => 'H2',
												'h3' => 'H3',
												'h4' => 'H4',
												'h5' => 'H5',
												'h6' => 'H6',
											)
										),
									),
									'font_size'   => array(
										'label'      => __( 'Font Size', 'rishi' ),
										'control'    => ControlTypes::INPUT_SLIDER,
										'divider'    => 'top',
										'units'      => \Rishi\Customizer\Helpers\Basic::get_units(
											array(
												array(
													'unit' => 'px',
													'min'  => 0,
													'max'  => 150,
												),
											)
										),
										'value'      => array(
											'desktop' => '36px',
											'tablet'  => '28px',
											'mobile'  => '24px',
										),
										'responsive' => true,
									),
								),
							),
							'custom_meta'    => apply_filters( 'rishi_multiple_authors',
								array(
									'label'   => __( 'Post Meta', 'rishi' ),
									'options' => array(
										'archive_postmeta' => array(
											'label'      => __( 'Post Meta', 'rishi' ),
											'control'    => ControlTypes::INPUT_SELECT,
											'isMultiple' => true,
											'value'      => array( 'author', 'published-date', 'comments' ),
											'view'       => 'text',
											'choices'    => \Rishi\Customizer\Helpers\Basic::ordered_keys(
												array(
													'author' => __( 'Author', 'rishi' ),
													'comments' => __( 'Comments', 'rishi' ),
													'reading-time' => __( 'Reading Time', 'rishi' ),
													'published-date' => __( 'Published Date', 'rishi' ),
													'updated-date' => __( 'Updated Date', 'rishi' ),
												)
											),
										),
										'divider_divider'  => array(
											'label'   => __( 'Category Separator', 'rishi' ),
											'control' => ControlTypes::INPUT_SEPARATOR,
											'value'   => 'dot',
											'design'  => 'block',
											'choices' => array(
												'dot'  => __( 'Dot', 'rishi' ),
												'normal-slash' => __( 'Normal Slash', 'rishi' ),
												'pipe' => __( 'Pipe', 'rishi' ),
												'back-slash' => __( 'Back Slash', 'rishi' ),
											),
										),
										'has_author_avatar' => array(
											'label'   => __( 'Show Avatar', 'rishi' ),
											'control' => ControlTypes::INPUT_SWITCH,
											'divider' => 'top',
											'value'   => 'no',
										),
										'avatar_size'      => array(
											'label'   => __( 'Avatar Size', 'rishi' ),
											'control' => ControlTypes::INPUT_SLIDER,
											// 'conditions' => [ 'has_author_avatar' => 'yes' ],
											'units'   => \Rishi\Customizer\Helpers\Basic::get_units(
												array(
													array(
														'unit' => 'px',
														'min'  => 0,
														'max'  => 300,
													),
												)
											),
											'value'   => '34px',
											'divider' => 'top',
										),
										'label'            => array(
											'label'   => __( 'Label', 'rishi' ),
											'control' => ControlTypes::INPUT_TEXT,
											'design'  => 'block',
											'divider' => 'top',
											'value'   => __( 'By', 'rishi' ),
										),
										'words_per_minute' => array(
											'label'      => __( 'Words Per Minute', 'rishi' ),
											'control'    => ControlTypes::INPUT_NUMBER,
											'design'     => 'inline',
											'value'      => 200,
											'min'        => 100,
											'max'        => 400,
											'divider'    => 'top',
											'responsive' => false,
										),
										'show_updated_date_label' => array(
											'label'   => __( 'Show Updated Date Label', 'rishi' ),
											'control' => ControlTypes::INPUT_SWITCH,
											'divider' => 'top',
											'value'   => 'yes',
										),
										'updated_date_label' => array(
											'label'   => __( 'Label', 'rishi' ),
											'control' => ControlTypes::INPUT_TEXT,
											'design'  => 'block',
											'divider' => 'top',
											'value'   => __( 'Updated On', 'rishi' ),
										),
									),
								)
							),
						),
					),
					'breadcrumbs_ed_single_post'  => array(
						'label'   => __( 'Breadcrumb', 'rishi' ),
						'divider' => 'top',
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['breadcrumbs_ed_single_post'],

					),
					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'LINK HIGHLIGHTS', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),
					'ed_link_highlight'           => array(
						'label'   => __( 'Enable Highlights', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => 'yes',

					),
					'underlinestyle'              => array(
						'label'   => __( 'Select Style', 'rishi' ),
						'control' => ControlTypes::INPUT_SELECT,
						'value'   => $posts_defaults['underlinestyle'],
						'divider' => 'top',
						'design'  => 'block',
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
							array(
								'style1' => __( 'Style 1', 'rishi' ),
								'style2' => __( 'Style 2', 'rishi' ),
							)
						),
					),

					'post_sidebar_layout'         => array(
						'label'   => __( 'Sidebar Layout', 'rishi' ),
						'control' => ControlTypes::IMAGE_PICKER,
						'divider' => 'top',
						'value'   => $posts_defaults['post_sidebar_layout'],
						'attr'    => array(
							'data-type'    => 'background',
							'data-usage'   => 'layout-style',
							'data-columns' => '2',
						),
						'help'    => __( 'Choose sidebar layout for post.', 'rishi' ),
						'choices' => $this->get_page_layout_choices(),
					),
					'blog_post_layout'            => array(
						'label'   => __( 'Container Layout', 'rishi' ),
						'control' => ControlTypes::INPUT_SELECT,
						'value'   => $posts_defaults['blog_post_layout'],
						'design'  => 'block',
						'divider' => 'top',
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
							array(
								'default'              => __( 'Default', 'rishi' ),
								'boxed'                => __( 'Boxed', 'rishi' ),
								'content_boxed'        => __( 'Content Boxed', 'rishi' ),
								'full_width_contained' => __( 'Unboxed', 'rishi' ),
							)
						),
						'help'    => __( 'Choose post container layout.', 'rishi' ),
					),
					'blog_post_streched_ed'       => array(
						'label'   => __( 'Stretch Layout', 'rishi' ),
						'divider' => 'top',
						'help'    => __( 'This setting stretches the container width.', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['blog_post_streched_ed'],

					),
					'ed_post_tags'                => array(
						'label'   => __( 'Post Tags', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['ed_post_tags'],
						'divider' => 'top',

					),
					'ed_show_post_navigation'     => array(
						'label'   => __( 'Post Navigation', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['ed_show_post_navigation'],
						'divider' => 'top',

					),
					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'AUTHOR BOX', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),
					'ed_show_post_author'         => array(
						'label'   => __( 'Show Author Box', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['ed_show_post_author'],
						'help'    => __( 'This setting shows author box on your posts.', 'rishi' ),

					),
					'author_box_layout'           => array(
						'label'   => __( 'Layout', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'divider' => 'top',
						'value'   => $posts_defaults['author_box_layout'],
						'design'  => 'block',
						'choices' => array(
							'layout-one' => __( 'Layout One', 'rishi' ),
							'layout-two' => __( 'Layout Two', 'rishi' ),
						),
					),
					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'RELATED POSTS', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),
					'ed_related'                  => array(
						'label'   => __( 'Show Related Posts', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['ed_related'],
						'help'    => __( 'This setting shows related posts of your posts.', 'rishi' ),

					),

					'single_related_title'        => array(
						'label'   => __( 'Section Title', 'rishi' ),
						'divider' => 'top',
						'control' => ControlTypes::INPUT_TEXT,
						'design'  => 'block',
						'value'   => $posts_defaults['single_related_title'],
					),
					'related_taxonomy'            => array(
						'label'   => __( 'Show Posts based on', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'value'   => $posts_defaults['related_taxonomy'],
						'design'  => 'block',
						'divider' => 'top',
						'choices' => array(
							'cat' => __( 'Category', 'rishi' ),
							'tag' => __( 'Tags', 'rishi' ),
						),
					),
					'no_of_related_post'          => array(
						'label'   => __( 'Number of Posts', 'rishi' ),
						'control' => ControlTypes::INPUT_NUMBER,
						'design'  => 'inline',
						'divider' => 'top',
						'value'   => $posts_defaults['no_of_related_post'],
						'min'     => 1,
						'max'     => 15,
					),
					'related_post_per_row'        => array(
						'label'   => __( 'Posts Per Row', 'rishi' ),
						'control' => ControlTypes::INPUT_NUMBER,
						'design'  => 'inline',
						'divider' => 'top',
						'value'   => $posts_defaults['related_post_per_row'],
						'min'     => 2,
						'max'     => 4,
					),
					'ed_related_after_comment'    => array(
						'label'   => __( 'Show After Comments', 'rishi' ),
						'divider' => 'top',
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['ed_related_after_comment'],
						'help'    => __( 'Enable to show related posts after the comment section.', 'rishi' ),

					),

					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'COMMENTS', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),
					'ed_comment'                  => array(
						'label'   => __( 'Show Comments', 'rishi' ),
						'help'    => __( 'Enable to display comments section in your single posts.', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $posts_defaults['ed_comment'],
						'divider' => 'top',

					),

					'ed_comment_form_above_clist' => array(
						'label'      => __( 'Comment Form Position', 'rishi' ),
						'control'    => ControlTypes::INPUT_RADIO,
						'divider'    => 'top',
						'value'      => $posts_defaults['ed_comment_form_above_clist'],
						'design'     => 'block',
						'choices'    => array(
							'yes' => __( 'Above', 'rishi' ),
							'no'  => __( 'Below', 'rishi' ),
						),
						'conditions' => array( 'ed_comment' => 'yes' ),
					),

					'ed_comment_below_content'    => array(
						'label'      => __( 'Comment Section', 'rishi' ),
						'control'    => ControlTypes::INPUT_RADIO,
						'value'      => $posts_defaults['ed_comment_below_content'],
						'divider'    => 'top',
						'design'     => 'block',
						'choices'    => array(
							'default' => __( 'Default', 'rishi' ),
							'below'   => __( 'Below Post Content', 'rishi' ),
						),
						'conditions' => array( 'ed_comment' => 'yes' ),
					),

					'single_content_area_spacing' => array(
						'label'      => __( 'Content Area Vertical Spacing', 'rishi' ),
						'help'       => sprintf(
							__( 'You can set the spacing value under Global > Container. Click %1$shere%2$s to configure it.', 'rishi' ),
							sprintf(
								'<a href="%s" data-trigger-section="layout">',
								admin_url( '/customize.php?autofocus[section]=container-panel' )
							),
							'</a>'
						),
						'conditions' => array( 'ed_comment' => 'yes' ),
						'control'    => ControlTypes::INPUT_RADIO,
						'value'      => 'both',
						'design'     => 'block',
						'divider'    => 'top',
						'attr'       => array( 'data-type' => 'content-spacing' ),
						'choices'    => array(
							'both'   => __( 'Top & Bottom', 'rishi' ),
							'top'    => __( 'Only Top', 'rishi' ),
							'bottom' => __( 'Only Bottom', 'rishi' ),
							'none'   => __( 'Disabled', 'rishi' ),
						),
					),

				)
			),
		) );


		$this->add_setting( 'single_post_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => apply_filters(
				'rishi_single_design_tab',
				array(
					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'LINK HIGHLIGHTS', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),
					'linkHighlightColor'              => array(
						'label'   => __( 'Text Color', 'rishi' ),
						'control' => ControlTypes::COLOR_PICKER,
						'colorPalette'	  => true,
						'design'  => 'inline',
						'value'   => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor1)',
							),
						),

						'pickers' => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi' ),
								'id'    => 'hover',
							),
						),
					),

					'linkHighlightBackgroundColor'    => array(
						'label'      => __( 'Background Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'design'     => 'inline',
						'colorPalette'	  => true,
						'conditions' => array( 'underlinestyle' => 'style2' ),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor6)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),

						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi' ),
								'id'    => 'hover',
							),
						),
					),

					\Rishi\Customizer\Helpers\Basic::uniqid() => array(
						'label'   => __( 'CONTENT AREA', 'rishi' ),
						'control' => ControlTypes::TITLE,
					),

					$prefix . 'content_background'    => array(
						'label'   => __( 'Background', 'rishi' ),
						'control' => ControlTypes::COLOR_PICKER,
						'colorPalette'	  => true,
						'design'  => 'inline',
						'value'   => array(
							'default' => array(
								'color' => 'var(--paletteColor5)',
							),
						),
						'pickers' => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),
						),
					),

					$prefix . 'content_boxed_shadow'  => array(
						'label'      => __( 'Box Shadow', 'rishi' ),
						'control'    => ControlTypes::BOX_SHADOW,
						'responsive' => false,
						'divider'    => 'top',
						'design'     => 'inline',
						'value'      => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
							array(
								'enable'   => false,
								'h_offset' => '0px',
								'v_offset' => '12px',
								'blur'     => '18px',
								'spread'   => '-6px',
								'inset'    => false,
								'color'    => 'rgba(34, 56, 101, 0.04)',
							)
						),
					),

					$prefix . 'boxed_content_spacing' => array(
						'label'      => __( 'Padding', 'rishi' ),
						'control'    => ControlTypes::INPUT_SPACING,
						'divider'    => 'top',
						'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '40',
									'left'   => '40',
									'right'  => '40',
									'bottom' => '40',
									'unit'   => 'px',
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '15',
									'left'   => '15',
									'right'  => '15',
									'bottom' => '15',
									'unit'   => 'px',
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '15',
									'left'   => '15',
									'right'  => '15',
									'bottom' => '15',
									'unit'   => 'px',
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
						'responsive' => true,
					),

					$prefix . 'content_boxed_radius'  => array(
						'label'      => __( 'Border Radius', 'rishi' ),
						'control'    => ControlTypes::INPUT_SPACING,
						'divider'    => 'top:bottom',
						'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '3',
									'left'   => '3',
									'right'  => '3',
									'bottom' => '3',
									'unit'   => 'px',
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '3',
									'left'   => '3',
									'right'  => '3',
									'bottom' => '3',
									'unit'   => 'px',
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '3',
									'left'   => '3',
									'right'  => '3',
									'bottom' => '3',
									'unit'   => 'px',
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
						'responsive' => true,
					),
				)
			),
		));

	}

}
