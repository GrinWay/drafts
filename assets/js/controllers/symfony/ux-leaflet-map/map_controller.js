import { Controller } from '@hotwired/stimulus';
import StimulusUxMapController from '@symfony/ux-leaflet-map';

/* stimulusFetch: 'lazy' */
export default class extends StimulusUxMapController {
	connect() {
		const actionDataset = this.element.dataset.actioin ?? ''
		
		this.element.setAttribute('data-action', `${actionDataset} ux:map:marker:before-create->${this.identifier}#_beforeCreate`)
		requestAnimationFrame(() => {
			super.connect(...arguments)			
		})
	}
	
	_beforeCreate(event) {
		const { definition, L } = event.detail
		return
		const icon = L.icon({
          // Note: instead of using a hardcoded URL, you can use the `extra` parameter from `new Marker()` (PHP) and access it here with `definition.extra`.
          iconUrl: 'data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20100%20100%27%3E%3Cpolygon%20fill%3D%27%23000000%27%20points%3D%2744.2%2C5.8%2054.2%2C5.8%2054.2%2C25.8%2079.2%2C25.8%2079.2%2C35.8%2054.2%2C35.8%2049.2%2C95.8%2049.2%2C95.8%2044.2%2C35.8%2019.2%2C35.8%2019.2%2C25.8%2044.2%2C25.8%20%27%3E%3C%2Fpolygon%3E%3C%2Fsvg%3E',
          shadowUrl: 'data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20100%20100%27%3E%3Cellipse%20fill%3D%27%23000000%27%20cx%3D%2749.2%27%20cy%3D%2780.8%27%20rx%3D%2735%27%20ry%3D%2710.88%27%3E%3C%2Fellipse%3E%3C%2Fsvg%3E',
          iconSize: [50, 100], // size of the icon
          shadowSize: [40, 50], // size of the shadow
          iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
          shadowAnchor: [4, 62],  // the same for the shadow
          popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        })
  
        definition.rawOptions = {
          icon,
        }
	}
}