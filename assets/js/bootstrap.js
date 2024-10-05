import { Controller } from "@hotwired/stimulus"
import { startStimulusApp } from '@symfony/stimulus-bridge'
import { registerActionOptions } from './settings/registerActionOptions.js'

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
))

app.stimulusUseDebug = process.env.NODE_ENV === 'development'

registerActionOptions(app)

//app.register('animated-number', AnimatedNumber);
