/* Contact page: when a mailto: link does not hand off to a mail app (desktop browsers without one),
   reveal the address with a copy button instead of doing nothing. */
(function () {
	var links = document.querySelectorAll('.ds-contact a[href^="mailto:"]');
	if (!links.length) return;
	links.forEach(function (a) {
		a.addEventListener('click', function () {
			var started = Date.now();
			setTimeout(function () {
				// a handler that took over blurs the page or navigates; focus still here = nothing opened
				if (!document.hasFocus() || document.hidden || Date.now() - started > 4000) return;
				showFallback(a);
			}, 1500);
		});
	});
	function showFallback(a) {
		if (a.nextElementSibling && a.nextElementSibling.classList.contains('ds-contact__fallback')) return;
		var address = a.getAttribute('href').replace(/^mailto:/, '').split('?')[0];
		var box = document.createElement('span');
		box.className = 'ds-contact__fallback';
		var label = document.createElement('span');
		label.textContent = (a.dataset.fallbackText || 'No mail app opened? Copy the address:') + ' ';
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'ds-contact__copy';
		btn.textContent = address;
		btn.addEventListener('click', function () {
			var done = function () { btn.textContent = a.dataset.copiedText || 'Copied'; btn.disabled = true; };
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(address).then(done, function () { selectText(btn); });
			} else { selectText(btn); }
		});
		box.appendChild(label); box.appendChild(btn);
		a.insertAdjacentElement('afterend', box);
	}
	function selectText(el) {
		var r = document.createRange(); r.selectNodeContents(el);
		var s = window.getSelection(); s.removeAllRanges(); s.addRange(r);
	}
})();
