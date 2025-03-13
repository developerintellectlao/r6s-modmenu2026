import Tippy from "@tippyjs/react";
import { useState } from "@wordpress/element";

export default ({ content, children, className, arrow, placement, ...rest }) => {
	const [visible, setVisible] = useState(false);
	const cls = `cw_popover ${className}`;

	return (
		<Tippy
			content={visible && content}
			className={cls}
			trigger="click"
			theme="light"
			disabled={!content}
			animation="shift-away"
			appendTo={() => document.body}
			interactive
			allowHTML
			arrow={arrow || true}
			placement={placement || "right"}
			onShow={() => setVisible(true)}
			{...rest}
		>
			<div>{children}</div>
		</Tippy>
	);
};
