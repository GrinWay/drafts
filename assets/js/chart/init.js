//import zoomPlugin from 'chartjs-plugin-zoom'
import { BoxPlotController, BoxAndWiskers } from '@sgratzl/chartjs-chart-boxplot'
import { Chart, LinearScale, CategoryScale } from 'chart.js'
import { BarWithErrorBarsController, BarWithErrorBar } from 'chartjs-chart-error-bars'

class InitChartjs {
	constructor() {
		
		if (__webpack_modules__[require.resolveWeak('chart.js')]) {
			this.boundInit = this.init.bind(this)
			document.addEventListener('chartjs:init', this.boundInit)
			console.log('__ Chartjs event listeners were added __')			
		}
	}

	/**
     * Helper
     */
	#clearAllEventListeners() {
		document.removeEventListener('chartjs:init', this.boundInit)
	}

	/**
     * 
     */
	init(event) {
		const chart = event.detail.Chart
		
		this.#registerPlugins(chart)
		this.#overrideDefaults(chart)
	}

	/**
     * 
     */
	#registerPlugins(chart) {
		chart.register(
			//zoomPlugin,
			BoxPlotController,
			BoxAndWiskers,
			LinearScale,
			CategoryScale,
			BarWithErrorBarsController,
			BarWithErrorBar,
		)
	}

	/**
     * 
     */
	#overrideDefaults(chart) {
		const img = new Image()
		img.width = 10
		img.height = 10
		img.src = 'https://pixlr.com/images/generator/text-to-image.webp'
		
		chart.defaults.devicePixelRatio = 6
		chart.defaults.font.size = 15
		chart.defaults.color = '#FFFFFF'
		chart.defaults.elements.point.radius = 5
		chart.defaults.elements.point.backgroundColor = '#000000'
		chart.defaults.elements.point.borderColor = '#000000'
		chart.defaults.elements.point.hoverRadius = 10
		chart.defaults.elements.point.pointStyle = 'triangle'
		
		chart.defaults.elements.line.cubicInterpolationMode = 'monotone'
		chart.defaults.elements.line.borderWidth = 3
		chart.defaults.elements.line.hoverBorderWidth = 30
		chart.defaults.elements.point.pointHoverBackgroundColor = 'red'
		chart.defaults.elements.point.pointHoverBorderWidth = 0
		
		chart.defaults.elements.arc.borderColor = 'transparent'
		chart.defaults.elements.arc.hoverBackgroundColor = '#FFFFFF'
		
		chart.defaults.layout.padding = 0
		
		chart.overrides['line'].plugins = {
			legend: {
				display: true,
				align: 'start',
			},
		}
		chart.defaults.elements.bar.borderWidth = 2
		chart.defaults.elements.bar.borderRadius = 2
		chart.defaults.datasets.bar.barPercentage = 1
		//chart.defaults.datasets.bar.grouped = false
		
		chart.defaults.elements.arc.borderDash = [ 3, 5 ]
		
		chart.defaults.interaction.intersect = false
		chart.defaults.interaction.mode = 'index' // point dataset nearest index
		chart.defaults.interaction.axis = 'xy'
		chart.defaults.interaction.includeInvisible = false
		
		//chart.defaults.plugins.tooltip.external = () => {} 
		chart.defaults.plugins.tooltip.intersect = false 
		chart.defaults.plugins.tooltip.backgroundColor = 'white' 
		chart.defaults.plugins.tooltip.title = false 
		chart.defaults.plugins.tooltip.titleFont = 'roman' 
		chart.defaults.plugins.tooltip.titleAlign = 'center' 
		chart.defaults.plugins.tooltip.bodyColor = '#000000' 
		chart.defaults.plugins.tooltip.footerColor = '#000000' 
		chart.defaults.plugins.tooltip.cornerRadius = 2 
		chart.defaults.plugins.tooltip.position = 'nearest'
		chart.defaults.plugins.tooltip.callbacks.footer = () => '🤡'
		//chart.defaults.plugins.tooltip.callbacks.label = context => `[${context.label}] ${context.dataset.label}`
	}
}

export default new InitChartjs();