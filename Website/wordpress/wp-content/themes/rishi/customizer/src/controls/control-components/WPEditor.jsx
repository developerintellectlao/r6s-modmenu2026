import styled from '@emotion/styled'
import {
	useCallback, useEffect, useRef
} from '@wordpress/element'
import md5 from 'md5'

const WPEditor = styled.div`
	> .mce-panel{
		border: 1px solid var(--cw__border-color);
		border-radius: var(--cw__border-radius);
		overflow: hidden;
	}
	.mce-stack-layout{
		> .mce-first, > .mce-last{
			&::before{
				content: none;
			}
			background-color: var(--cw__background-color);
		}
	}
`

export default ({ id, value, option, onChange }) => {
	const el = useRef()
	const editorRef = useRef(`${id}-${md5(Math.random() + '-' + Math.random() + '-' + Math.random())}`)
	const editorId = editorRef.current

	const correctEditor = () => wp.oldEditor || wp.editor

	const listener = useCallback(() => onChange(correctEditor().getContent(editorId)), [editorId])

	const EDITOR_ARGS = {
		quicktags: true,
		mediaButtons: true,
		...option,
		tinymce: {
			toolbar1: 'formatselect,styleselect,bold,italic,bullist,numlist,link,alignleft,aligncenter,alignright,wp_adv',
			toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
			...(typeof option.tinymce === 'object' ? option.tinymce : {}),
			style_formats_merge: true,
			style_formats: [],
		}
	}

	useEffect(() => {
		correctEditor().initialize(editorId, EDITOR_ARGS)

		setTimeout(() => {
			if (window.tinymce.editors[editorId]) {
				window.tinymce.editors[editorId].on('change', listener)
			}
		})

		return () => {
			if (window.tinymce.editors[editorId]) {
				window.tinymce.editors[editorId].off('change', listener)
				correctEditor().remove(editorId)
			}
		}
	}, [])

	return (
		<WPEditor className="rishi-option-editor" {...(option.attr || {})}>
			<textarea
				id={editorId}
				ref={el}
				value={value}
				className="wp-editor-area"
				{...(option.field_attr || {})}
				{...(option.attr && option.attr.placeholder ? { placeholder: option.attr.placeholder } : {})}
				onChange={({ target: { value } }) => onChange(value)}
			/>
		</WPEditor>
	)
}
