/**
 * Place any jQuery/helper plugins in here.
 */
(function () {
    /* Callout: anchored to the Releases button, triggered by badge hover/click. */
    var badge = document.querySelector('.nsy-badge');
    var btn = document.getElementById('nsyReleaseBtn');
    if (!badge || !btn) return;
    var callout = null, timer = null;
    function show() {
        var brr = btn.getBoundingClientRect();
        if (!callout) {
            callout = document.createElement('div');
            callout.className = 'nsy-callout';
            callout.textContent = 'Click Here!';
            callout.style.position = 'fixed';
            document.body.appendChild(callout);
        }
        clearTimeout(timer);
        var cw = callout.offsetWidth || 130;
        var ch = callout.offsetHeight || 36;
        /* Default: callout sits above the Releases button, centered on it. */
        var btnCenter = brr.left + brr.width / 2;
        var x = btnCenter - cw / 2;
        var y = brr.top - 8 - ch;
        var placement = 'top';
        if (y < 8) {
            /* not enough room above → drop below the button */
            y = brr.bottom + 8;
            placement = 'bottom';
        }
        /* clamp horizontally so the callout never leaves the viewport */
        x = Math.max(8, Math.min(x, window.innerWidth - cw - 8));
        callout.style.left = x + 'px';
        callout.style.top = y + 'px';
        /* keep the arrow pointing at the button centre, even when clamped */
        callout.style.setProperty('--arrow-x', Math.max(14, Math.min(btnCenter - x, cw - 14)) + 'px');
        callout.dataset.placement = placement;
        callout.classList.add('show');
        timer = setTimeout(function () { callout.classList.remove('show'); }, 3000);
    }
    badge.addEventListener('mouseenter', show);
    badge.addEventListener('click', show);
})();
