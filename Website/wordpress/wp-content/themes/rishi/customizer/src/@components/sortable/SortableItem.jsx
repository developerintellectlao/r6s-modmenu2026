import { useSortable } from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'
import styled from '@emotion/styled'
import Icons from '../assets/Icons'

const SortableItemStyle = styled.div`
	width: 100%;
	position: relative;
	margin: 0 !important;
    height: unset !important;
	.cw__control-item{
		transition: transform 200ms ease 0s;
	}
    .wc__sort-button{
        padding: 0;
        background-color: transparent;
        font-size: 0;
        border: none;
        width: 12px;
        height: 20px;
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: move;
        color: #42474B;
        opacity: .5;
        svg{
            vertical-align: top;
            width: 100%;
            height: 100%;
        }
        &:hover{
            color: var(--cw__secondary-color);
            opacity: 1;
        }
    }
    > .cw__control-item{
        border: 1px solid var(--cw__border-color);
        border-radius: var(--cw__border-radius);
        padding: 12px;
        padding-left: 34px;
        background-color: #ffffff;
    }
	&[data-enabled="false"]{
		.wc__sort-button, .rishi-control_header label, .rishi-control_wrapper, .rishi-control_header label .cw__icon{
			color: #CED0D3;
			pointer-events: none;
		}
	}
	&[aria-pressed="true"]{
		z-index: 1;
		--scaleX: 1.05;
		--scaleY: 1.05;
		.cw__control-item{
			box-shadow: 0 10px 15px;
		}
	}
`

const SortableItem = (props) => {
	const { attributes, listeners, setNodeRef, transform, transition } = useSortable({ id: props.id })

	const { children, enabled, ...rest } = props
	const style = {
		transform: CSS.Transform.toString({ ...transform, x: 0, scaleX: `var(--scaleX, ${transform?.scaleX || 1})`, scaleY: `var(--scaleY,${transform?.scaleY || 1})` }),
		transition,
	}

	return (
		<SortableItemStyle ref={setNodeRef} style={style} {...rest} data-enabled={enabled} aria-pressed={attributes['aria-pressed']}>
			<button className="wc__sort-button" type="button" {...attributes} {...listeners}>
				{Icons.move}
			</button>
			{children}
		</SortableItemStyle>
	)
}

export default SortableItem
