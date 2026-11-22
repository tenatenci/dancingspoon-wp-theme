/* Dancing Spoon — festive slider: a long title is set smaller until it takes the room of two full-size lines,
   so no slide is taller than one with a two-line title (the track then stretches every slide to one height).
   Printed inline right after the slider (patterns/hero.php) so it runs before the first paint. Only beside the
   photo (782px up); never below 60 %.
   Keep the less-than sign and the ampersand out of this file: inside the block template WordPress (wptexturize)
   reads the first as the start of a tag and rewrites the second, which breaks the script and the page after it. */
(function () {
	var me = document.currentScript;
	var root = me ? me.closest('.ds-hero-fit') : null;
	if (!root) return;
	var wide = window.matchMedia('(min-width: 782px)');
	// the text's own height (the title's link is an inline-block: whole lines), whatever the h1's box is held to
	var tall = function (t, room) {
		var link = t.firstElementChild;
		return (link ? link.getBoundingClientRect().height : t.scrollHeight) > room + 1;
	};
	var fit = function () {
		root.querySelectorAll('.ds-hero .wp-block-post-title').forEach(function (t) {
			t.style.removeProperty('--ds-hero-title-fit');
			if (!wide.matches) return;
			var cs = getComputedStyle(t);
			var base = parseFloat(cs.fontSize);
			var room = 2 * (parseFloat(cs.lineHeight) || base * 1.15);   // two lines at the full size
			if (!(base > 0) || !(room > 0) || !tall(t, room)) return;
			var lo = base * 0.6;   // the floor: a title that does not fit even here keeps it and its slide grows
			var hi = base;
			for (var i = 7; i > 0; i--) {
				var mid = (lo + hi) / 2;
				t.style.setProperty('--ds-hero-title-fit', mid + 'px');
				if (tall(t, room)) { hi = mid; } else { lo = mid; }
			}
			t.style.setProperty('--ds-hero-title-fit', (Math.floor(lo * 10) / 10) + 'px');
		});
	};
	fit();
	// the heading font swaps in after the first paint, and the column's width follows the window
	var fonts = document.fonts ? document.fonts.ready : null;
	if (fonts) { fonts.then(fit); }
	var width = window.innerWidth;
	var pending = false;
	window.addEventListener('resize', function () {
		if (window.innerWidth === width || pending) return;
		pending = true;
		window.requestAnimationFrame(function () { pending = false; width = window.innerWidth; fit(); });
	});
})();
