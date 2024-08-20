export function isTurboPreview($html = document.documentElement) {
	return $html.hasAttribute('data-turbo-preview');
}