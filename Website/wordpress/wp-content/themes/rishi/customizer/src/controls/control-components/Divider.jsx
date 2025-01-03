import classnames from 'classnames';

const Divider = ({ option: { attr: { class: className, ...attr } = {} } }) => (
	<div className={classnames('rt-divider', className)} {...attr} />
)

Divider.config = { design: 'none' }

export default Divider
