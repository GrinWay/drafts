import { Controller } from '@hotwired/stimulus'
import { getRelativePosition } from 'chart.js/helpers'
import pattern from 'patternomaly'

/**
 * 
 */
export default class extends Controller {
	connect() {
		this.boundOnChartPreConnect = this.onChartPreConnect.bind(this)
		this.boundOnChartConnect = this.onChartConnect.bind(this)

		this.element.addEventListener('chartjs:pre-connect', this.boundOnChartPreConnect)
		this.element.addEventListener('chartjs:connect', this.boundOnChartConnect)
		
		this.#addSaveButton()
		this.#addRandomizeButton()
	}
	
	/**
     * Stimulus action
     */
	saveCanvas(event) {
		let canvasCssSelector = event.params.canvasCssSelector ?? 'canvas'
		
		const canvas = this.element.querySelector(canvasCssSelector)
		if (!canvas) {
			return
		}
		
		const ext = 'png'
		const mime = `image/${ext}`
		
		const image = canvas.toDataURL(mime).replace(mime, "image/octet-stream")
		const aEl = document.createElement('a')
		aEl.href = image
		aEl.download = `canvas.${ext}`
		aEl.click()
	}
	
	/**
     * Helper
     */
	#addSaveButton() {
		const el = `
		<button
			type="button"
			class="btn btn-outline-primary bg-gradient p-2 m-0 border-0"
			data-action="
				${this.identifier}#saveCanvas
			"
			data-html-listener-canvas-css-selector-param="#admin-main-chart"
		>
			🔽
		</button>
		`.trim()
		this.element.insertAdjacentHTML('beforeend', el)
	}
	
	/**
     * Helper
     */
	#addRandomizeButton() {
		const el = `
		<button
			type="button"
			class="btn btn-outline-danger bg-gradient p-2 m-0 border-0"
			data-action="
				${this.identifier}#randomizeChart
			"
		>
			Random
		</button>
		`.trim()
		this.element.insertAdjacentHTML('beforeend', el)
	}
	
	disconnect() {
		this.element.removeEventListener('chartjs:pre-connect', this.boundOnChartPreConnect)
		this.element.removeEventListener('chartjs:connect', this.boundOnChartConnect)
		this.chart = null
	}
	
	/**
     * 
     */
	randomizeChart(event) {
		this.chart.data.datasets.forEach(dataset => dataset.data = this.chart.data.labels.map(() => 10))
		this.chart.update()
	}
	
	/**
     * Event listener
     */
	onChartPreConnect(event) {
		const config = event.detail.config
		
		this.#addPlugins(config)
		
		
		
		/*
		this.#xTicks(config)
		this.#yTicks(config)
		
		config.options.animation = {
			onProgress: this.onAnimationProgress.bind(this),
			onComplete: this.onAnimationComplete.bind(this),
		}
		*/
	}
	
	/**
     * Event listener
     */
	onChartConnect(event) {
		this.chart = event.detail.chart
		
		this.chart.options.onHover = this.onHover.bind(this)
		this.chart.options.onClick = this.onClick.bind(this)
	}
	
	/**
     * Event listener
     */
	onAnimationComplete(event) {
		console.log(`[Animation completed]`)
	}
	
	/**
     * Event listener
     */
	onAnimationProgress(event) {
		const progress = event.currentStep / event.numSteps
		console.log(`Animation progress: ${progress}`)
	}
	
	/**
     * Event listener (through config)
     */
	onHover(event) {
		
	}
	
	/**
     * Pre connect helper
     */
	#addPlugins(config) {
		if (undefined === config.plugins) {
			config.plugins = []
		}
		
		config.plugins.push({
			id: 'borderPlugin',
			
			beforeDraw(chart, args, pluginOptions) {
				const { ctx, chartArea: { left, top, width, height } } = chart
				
				const strokeStyle = pluginOptions.strokeStyle ?? 'gray'
				
				ctx.save()
				ctx.strokeStyle = strokeStyle
				ctx.setLineDash([5, 10])
				ctx.lineDashOffset = 4
				ctx.lineWidth = 2
				ctx.strokeRect(left, top, width, height)
				ctx.restore()
			},
		})
		
		const image = new Image()
		//image.src = 'https://www.chartjs.org/img/chartjs-logo.svg'
		image.src = 'data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20100%20100%27%3E%3Cpolygon%20class%3D%27fil0%27%20points%3D%2744.2%2C5.8%2054.2%2C5.8%2054.2%2C25.8%2079.2%2C25.8%2079.2%2C35.8%2054.2%2C35.8%2054.2%2C95.8%2044.2%2C95.8%2044.2%2C35.8%2019.2%2C35.8%2019.2%2C25.8%2044.2%2C25.8%20%27%3E%3Canimate%20attributeName%3D%27fill%27%20values%3D%27%23000000%3B%23666%3B%23000000%27%20repeatCount%3D%27indefinite%27%20dur%3D%272s%27%20calcMode%3D%22spline%22%20keySplines%3D%220%200%201%200%3B0%201%201%201%22%3E%3C%2Fanimate%3E%3C%2Fpolygon%3E%3C%2Fsvg%3E'
		
		config.plugins.push({
			id: 'customCanvasBackgroundImage',
			
			beforeDraw(chart) {
				if (image.complete) {
					const { ctx, chartArea: { left, top, width, height } } = chart
					
					const x = left + width / 15 - image.width / 2
					const y = top + height / 15 - image.height / 2
					//ctx.drawImage(image, x, y)
				} else {
					image.onload = () => chart.draw()
				}
			},
		})
	}
	
	/**
     * Pre connect helper
     */
	#xTicks(config) {
		config.options.scales.x.ticks = {
			callback: v => `${v}[N]км`,
		}
	}
	
	/**
     * Pre connect helper
     */
	#yTicks(config) {
		config.options.scales['y'] = {
			ticks: {
				callback: v => `${v} ед.`,				
			},
		}
	}
	
	/**
     * Event listener (through config)
     */
	onClick(event) {
		return
		const canvasPosition = getRelativePosition(event, this.chart)
		
		const dataX = this.chart.scales.x.getValueForPixel(canvasPosition.x)
		const dataY = this.chart.scales.y.getValueForPixel(canvasPosition.y)
		
		console.log(`x: ${dataX}; y: ${Number(dataY).toFixed(1)}`)
	}
}
