/* Share row: copy the post link, say so briefly */
(function () {
	document.querySelectorAll('.ds-share__copy').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var done = function () {
				var note = btn.parentNode.querySelector('.ds-share__note');
				if (!note) { note = document.createElement('span'); note.className = 'ds-share__note'; btn.parentNode.appendChild(note); }
				note.textContent = btn.dataset.copied || 'Link copied';
				setTimeout(function () { note.textContent = ''; }, 2500);
			};
			if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(btn.dataset.url).then(done, function () { window.prompt('Copy the link:', btn.dataset.url); }); }
			else { window.prompt('Copy the link:', btn.dataset.url); }
		});
	});
})();
