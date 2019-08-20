jQuery(function($) {
	if (typeof $.wp !== 'undefined' && typeof $.wp.wpColorPicker !== 'undefined') {
		$.wp.wpColorPicker.prototype.options = {
			palettes: [
				'#e1443f',
				'#cf475f',
				'#ff963a',
				'#ebc85e',
				'#88c462',
				'#1db79b',
				'#4490c1',
				'#3dbfd9',
				'#a59a84',
				'#9457b6'
			]
		};
	}
});
