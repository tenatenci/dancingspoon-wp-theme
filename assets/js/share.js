/* Share row: copy the post link */
(function () {
	document.querySelectorAll('.ds-share__copy').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var done = function () { btn.querySelector('span').textContent = btn.dataset.copied || 'Copied'; btn.disabled = true; };
			if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(btn.dataset.url).then(done, function () { window.prompt('Copy the link:', btn.dataset.url); }); }
			else { window.prompt('Copy the link:', btn.dataset.url); }
		});
	});
})();
