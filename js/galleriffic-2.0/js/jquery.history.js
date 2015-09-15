/*
 * jQuery history plugin
 * 
 * sample page: http://www.mikage.to/jquery/jquery_history.html
 *
 * Copyright (c) 2006-2009 Taku Sano (Mikage Sawatari)
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Modified by Lincoln Cooper to add Safari support and only call the callback once during initialization
 * for msie when no initial hash supplied.
 */


jQuery.extend({
	historyCurrentHash: undefined,
	historyCallback: undefined,
	historyIframeSrc: undefined,

	historyInit: function (callback, src) {
		jQuery.historyCallback = callback;
		if (src) jQuery.historyIframeSrc = src;
		var current_hash = location.hash.replace(/\?.*$/, '');

		jQuery.historyCurrentHash = current_hash;
		if (current_hash)
			jQuery.historyCallback(current_hash.replace(/^#/, ''));
		setInterval(jQuery.historyCheck, 100);
	},

	historyAddHistory: function (hash) {
		// This makes the looping function do something
		jQuery.historyBackStack.push(hash);

		jQuery.historyForwardStack.length = 0; // clear forwardStack (true click occured)
		this.isFirst = true;
	},

	historyCheck: function () {
		// otherwise, check for location.hash
		var current_hash = location.hash.replace(/\?.*$/, '');
		if (current_hash != jQuery.historyCurrentHash) {
			jQuery.historyCurrentHash = current_hash;
			jQuery.historyCallback(current_hash.replace(/^#/, ''));
		}
	},
	historyLoad: function (hash) {
		var newhash;
		hash = decodeURIComponent(hash.replace(/\?.*$/, ''));

		newhash = '#' + hash;
		location.hash = newhash;
		jQuery.historyCurrentHash = newhash;

		jQuery.historyCallback(hash);
	}
});


