/* Dancing Spoon — recipe carousel: scroll-snap track, arrows, optional autoplay. No dependencies. */
(function () {
	document.querySelectorAll('.ds-carousel').forEach(function (c) {
		var track = c.querySelector('.ds-carousel__track');
		var prev = c.querySelector('.ds-carousel__prev');
		var next = c.querySelector('.ds-carousel__next');
		if (!track || !prev || !next) return;
		var step = function () {
			var item = track.querySelector(':scope > li');
			var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
			return item ? item.getBoundingClientRect().width + gap : track.clientWidth;
		};
		var atEnd = function () { return track.scrollLeft + track.clientWidth >= track.scrollWidth - 2; };
		var atStart = function () { return track.scrollLeft <= 2; };
		var go = function (dir) {
			if (dir > 0 && atEnd()) { track.scrollTo({ left: 0, behavior: 'smooth' }); return; }
			if (dir < 0 && atStart()) { track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' }); return; }
			track.scrollBy({ left: dir * step(), behavior: 'smooth' });
		};
		prev.addEventListener('click', function () { go(-1); });
		next.addEventListener('click', function () { go(1); });
		// optional dots (the festive hero slider): one per item, the current one marked as the track scrolls
		var dots = c.querySelector('.ds-carousel__dots');
		if (dots) {
			var label = dots.getAttribute('data-label') || '';
			var items = track.querySelectorAll(':scope > li');
			var all = [];
			items.forEach(function (_, i) {
				var d = document.createElement('button');
				d.type = 'button'; d.className = 'ds-carousel__dot';
				d.setAttribute('aria-label', (label + ' ' + (i + 1)).trim());
				d.addEventListener('click', function () { track.scrollTo({ left: i * step(), behavior: 'smooth' }); });
				dots.appendChild(d); all.push(d);
			});
			var mark = function () {
				var cur = Math.round(track.scrollLeft / step());
				all.forEach(function (d, j) { d.setAttribute('aria-current', j === cur ? 'true' : 'false'); });
			};
			var pending = false;
			track.addEventListener('scroll', function () { if (!pending) { pending = true; window.requestAnimationFrame(function () { pending = false; mark(); }); } }, { passive: true });
			mark();
		}
		track.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowRight') { go(1); e.preventDefault(); }
			if (e.key === 'ArrowLeft') { go(-1); e.preventDefault(); }
		});
		var auto = c.getAttribute('data-autoplay') === '1' && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var timer = null;
		var start = function () { if (auto && !timer) timer = setInterval(function () { go(1); }, 5000); };
		var stop = function () { clearInterval(timer); timer = null; };
		['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { c.addEventListener(ev, stop, { passive: true }); });
		['mouseleave', 'focusout'].forEach(function (ev) { c.addEventListener(ev, start); });
		document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
		start();
	});
})();
