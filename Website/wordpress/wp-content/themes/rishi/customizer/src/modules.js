import * as controls from './controls/control-components/index.js'

import * as components from './@components/index.js'

window['rishi'] = window['rishi'] ?? {}
window.rishi['customize'] = window.rishi.customize ?? {}

window.rishi.customize.controls = controls
window['rishi']['components'] = components
