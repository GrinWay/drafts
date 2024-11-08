import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'
import Carousel from '@stimulus-components/carousel'
import { Navigation, Pagination } from 'swiper/modules';

export default class extends Carousel {
	get defaultOptions() {
		return {
			...super.defaultOptions,
			keyboard: {
				enabled: true,
				onlyInViewport: false,
			},
			loop: true,
		}
	}
}
