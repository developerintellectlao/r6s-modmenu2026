import { Icons } from '@components'
import styled from '@emotion/styled'
import { useDeviceView } from './control-components/ControlsContainer/context'
import { isControlEnabledForDevice } from './helper'

const ResponsiveButtons = styled.div`
	display: inline-flex;
	align-items: center;
	gap: 0.25rem;
	.cw__responsive-button {
		font-size: 15px;
		cursor: pointer;
		color: var(--cw__inactive-color);
		transition: var(--cw__transition);
		padding: 0;
		border: none;
		background: none;
		svg {
			width: 1em;
			height: 1em;
			vertical-align: -0.12em;
		}
		&:hover,
		&.active {
			color: var(--cw__secondary-color);
		}
	}
`

const ResponsiveControls = ({ responsiveDescriptor }) => {
	const [currentDevice, setCurrentDevice] = useDeviceView();
	const devices = ['desktop', 'tablet', 'mobile'];

	return (
		<ResponsiveButtons className="cw__responsive-buttons">
			{devices
				.filter(device => isControlEnabledForDevice(device, responsiveDescriptor) !== 'skip')
				.map(device => {
					const isActive = device === currentDevice;
					const isDisabled = !isControlEnabledForDevice(device, responsiveDescriptor);
					const buttonClass = `cw__responsive-button rt-${device} ${isActive ? 'active' : ''} ${isDisabled ? 'rt-disabled' : ''}`;

					return (
						<button
							key={device}
							type="button"
							title={device}
							className={buttonClass}
							onClick={() => setCurrentDevice(device)}
						>
							{Icons[device]}
						</button>
					);
				})}
		</ResponsiveButtons>
	);
};

// HOC with conditional component rendering
function withResponsiveControls(WrappedComponent) {
	return function ({ showControls, ...props }) {
		return showControls ? <WrappedComponent {...props} /> : null
	}
}

export default withResponsiveControls(ResponsiveControls)
