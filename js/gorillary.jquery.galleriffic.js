jQuery(document).ready(function($) {
    var gallery = $('#thumbs').galleriffic({
        delay:                     3000, // in milliseconds
        numThumbs:                 20, // The number of thumbnails to show page
        preloadAhead:              40, // Set to -1 to preload all images
        enableTopPager:            false,
        enableBottomPager:         true,
        maxPagesToShow:            7,  // The maximum number of pages to display in either the top or bottom pager
        imageContainerSel:         '#slideshow', // The CSS selector for the element within which the main slideshow image should be rendered
        controlsContainerSel:      '#controls', // The CSS selector for the element within which the slideshow controls should be rendered
        captionContainerSel:       '#caption', // The CSS selector for the element within which the captions should be rendered
        loadingContainerSel:       '#loading', // The CSS selector for the element within which should be shown when an image is loading
        renderSSControls:          true, // Specifies whether the slideshow's Play and Pause links should be rendered
        renderNavControls:         true, // Specifies whether the slideshow's Next and Previous links should be rendered
        playLinkText:              'Play',
        pauseLinkText:             'Pause',
        prevLinkText:              'Previous',
        nextLinkText:              'Next',
        nextPageLinkText:          'Next &rsaquo;',
        prevPageLinkText:          '&lsaquo; Prev',
        enableHistory:             true, // Specifies whether the url's hash and the browser's history cache should update when the current slideshow image changes
        enableKeyboardNavigation:  true, // Specifies whether keyboard navigation is enabled
        autoStart:                 false, // Specifies whether the slideshow should be playing or paused when the page first loads
        syncTransitions:           false, // Specifies whether the out and in transitions occur simultaneously or distinctly
        defaultTransitionDuration: 1000, // If using the default transitions, specifies the duration of the transitions
        onSlideChange:             undefined, // accepts a delegate like such: function(prevIndex, nextIndex) { ... }
        onTransitionOut:           undefined, // accepts a delegate like such: function(slide, caption, isSync, callback) { ... }
        onTransitionIn:            undefined, // accepts a delegate like such: function(slide, caption, isSync) { ... }
        onPageTransitionOut:       undefined, // accepts a delegate like such: function(callback) { ... }
        onPageTransitionIn:        undefined, // accepts a delegate like such: function() { ... }
        onImageAdded:              undefined, // accepts a delegate like such: function(imageData, $li) { ... }
        onImageRemoved:            undefined  // accepts a delegate like such: function(imageData, $li) { ... }
    });

	/**** Functions to support integration of galleriffic with the jquery.history plugin ****/

	// PageLoad function
	// This function is called when:
	// 1. after calling $.historyInit();
	// 2. after calling $.historyLoad();
	// 3. after pushing "Go Back" button of a browser
	function pageload(hash) {
		// alert("pageload: " + hash);
		// hash doesn't contain the first # character.
		if(hash) {
			$.galleriffic.gotoImage(hash);
		} else {
			gallery.gotoIndex(0);
		}
	}

	// Initialize history plugin.
	// The callback is called at once by present location.hash.
	$.historyInit(pageload, "advanced.html");

	// set onlick event for buttons using the jQuery 1.3 live method
	$("a[rel='history']").live('click', function(e) {
		if (e.button != 0) return true;

		var hash = this.href;
		hash = hash.replace(/^.*#/, '');

		// moves to a new page.
		// pageload is called at once.
		// hash don't contain "#", "?"
		$.historyLoad(hash);

		return false;
	});

	/****************************************************************************************/


});

