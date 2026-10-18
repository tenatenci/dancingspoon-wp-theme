/* Dancing Spoon — Christmas snow: a fixed canvas behind the sticky header with a light drift of flakes.
   No dependencies. Not started for reduced motion, paused in a hidden tab, flake count follows the viewport area. */
(function () {
	if (!document.body.classList.contains('ds-xmas') || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
	var canvas = document.createElement('canvas');
	canvas.className = 'ds-snow';
	canvas.setAttribute('aria-hidden', 'true');
	document.body.appendChild(canvas);
	var ctx = canvas.getContext('2d');
	if (!ctx) return;
	var flakes = [], w = 0, h = 0, raf = 0, last = 0;
	var make = function (anywhere) {
		var r = 1.4 + Math.random() * 2.8;
		return { x: Math.random() * w, y: anywhere ? Math.random() * h : -8, r: r, v: 16 + r * 12, a: .45 + Math.random() * .5, p: Math.random() * Math.PI * 2, s: .5 + Math.random() };
	};
	var size = function () {
		var dpr = Math.min(window.devicePixelRatio || 1, 2);
		w = window.innerWidth; h = window.innerHeight;
		canvas.width = Math.round(w * dpr); canvas.height = Math.round(h * dpr);
		ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
		var n = Math.min(260, Math.round(w * h / 8000));
		while (flakes.length < n) flakes.push(make(true));
		flakes.length = n;
	};
	var tick = function (t) {
		var dt = Math.min((t - last) / 1000, .05); last = t;
		ctx.clearRect(0, 0, w, h);
		ctx.fillStyle = '#f3f6fa';
		ctx.strokeStyle = 'rgba(84,42,53,.12)'; ctx.lineWidth = .5;
		for (var i = 0; i < flakes.length; i++) {
			var f = flakes[i];
			f.p += dt * f.s; f.y += f.v * dt; f.x += Math.sin(f.p) * 14 * dt;
			if (f.y > h + 8) { flakes[i] = f = make(false); }
			if (f.x < -8) f.x = w + 8; else if (f.x > w + 8) f.x = -8;
			ctx.globalAlpha = f.a;
			ctx.beginPath(); ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2); ctx.fill(); ctx.stroke();
		}
		raf = window.requestAnimationFrame(tick);
	};
	var start = function () { if (!raf) { last = performance.now(); raf = window.requestAnimationFrame(tick); } };
	var stop = function () { window.cancelAnimationFrame(raf); raf = 0; };
	size();
	window.addEventListener('resize', size);
	document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
	start();
})();
