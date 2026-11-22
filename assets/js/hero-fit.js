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
	root.classList.add('ds-hero-fit--on');   // lifts the two-line cap style.css holds the titles under until now
	// The heading font swaps in after the first paint and sets the titles wider. A hidden line of text in that font
	// changes width in the very frame the font arrives, and a ResizeObserver runs before that frame is painted, so
	// the refit is never a frame late (fonts.ready is, by a few frames). The probe is not a title: watching the
	// titles themselves would loop, the fit resizes them.
	var first = root.querySelector('.ds-hero .wp-block-post-title');
	if (first ? window.ResizeObserver : false) {
		var cs = getComputedStyle(first);
		var probe = document.createElement('span');
		probe.setAttribute('aria-hidden', 'true');
		probe.textContent = first.textContent;
		probe.style.cssText = 'position:absolute;left:0;top:0;visibility:hidden;pointer-events:none;white-space:nowrap;font-size:16px';
		probe.style.fontFamily = cs.fontFamily;
		probe.style.fontWeight = cs.fontWeight;
		probe.style.fontStyle = cs.fontStyle;
		root.appendChild(probe);
		new ResizeObserver(fit).observe(probe);
	} else if (document.fonts) {
		document.fonts.ready.then(fit);
	}
	// and the column's width follows the window
	var width = window.innerWidth;
	var pending = false;
	window.addEventListener('resize', function () {
		if (window.innerWidth === width || pending) return;
		pending = true;
		window.requestAnimationFrame(function () { pending = false; width = window.innerWidth; fit(); });
	});
})();
