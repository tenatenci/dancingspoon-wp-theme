/* Dancing Spoon — recipe cards: the intro text shows as many lines as its card has room for (style.css, 0.19.0).
   The box keeps its height whatever the text does, so a ResizeObserver on it cannot loop: it fires once when
   watched, and again when the title wraps differently (heading font arriving, window width, a taller neighbour). */
(function () {
	if (!window.ResizeObserver) return;
	var fill = function (box) {
		var text = box.querySelector('.wp-block-post-excerpt__excerpt');
		if (!text) return;
		var line = parseFloat(getComputedStyle(text).lineHeight);
		if (!(line > 0)) return;
		text.style.setProperty('--ds-lines', Math.max(3, Math.floor((box.clientHeight + 1) / line)));
		// cut at the last line that fits: the text then stands on the card's bottom line, level with its neighbours
		var filled = text.scrollHeight > text.clientHeight + 1;
		box.classList.toggle('is-filled', filled);
		// what is left over (less than a line) would all sit under the title: the title moves down by half of it
		box.parentNode.style.setProperty('--ds-slack', (filled ? Math.max(0, box.clientHeight - text.clientHeight) : 0) + 'px');
	};
	var watch = new ResizeObserver(function (entries) { entries.forEach(function (e) { fill(e.target); }); });
	document.querySelectorAll('.ds-card__body > .wp-block-post-excerpt').forEach(function (box) { watch.observe(box); });
})();
