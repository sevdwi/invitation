/**
 * FILE: public/js/app.js
 * Wedding Invitation — JavaScript
 */

/* ── Axios setup ──────────────────────────────────── */
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.content ?? '';
axios.defaults.headers.common['Accept'] = 'application/json';

/* ── Helpers ──────────────────────────────────────── */
const $  = (s, el = document) => el.querySelector(s);
const $$ = (s, el = document) => [...el.querySelectorAll(s)];
const pad = n => String(n).padStart(2, '0');

function showAlert(el, type, html) {
    if (!el) return;
    const isOk = type === 'success';
    el.className = `mb-5 px-4 py-3 text-sm border-l-4 ${
        isOk ? 'bg-green-50 border-green-400 text-green-800'
              : 'bg-red-50 border-red-400 text-red-800'
    }`;
    el.innerHTML = html;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 7000);
}

function setBtn(btn, loading, label) {
    if (!btn) return;
    btn.disabled = loading;
    btn.innerHTML = loading
        ? `<svg class="animate-spin w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
           </svg>`
        : label;
}

/* ── 1. COVER OPENER ──────────────────────────────── */
(function () {
    const cover   = document.getElementById('cover');
    const main    = document.getElementById('main');
    const openBtn = document.getElementById('open-btn');
    if (!cover || !openBtn || !main) return;

    openBtn.addEventListener('click', () => {
        cover.classList.add('hide');
        main.classList.remove('hidden');

        // Auto-play musik setelah interaksi user
        const audio = document.getElementById('bgm');
        if (audio) {
            audio.play().catch(() => {});
            setMusicState(true);
        }

        window.scrollTo({ top: 0, behavior: 'instant' });
        setTimeout(observeReveal, 100);
    });
})();

/* ── 2. SCROLL REVEAL ─────────────────────────────── */
const ro = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
        if (!e.isIntersecting) return;
        setTimeout(() => e.target.classList.add('on'), i * 60);
        ro.unobserve(e.target);
    });
}, { threshold: 0.1 });

function observeReveal() {
    $$('.reveal:not(.on)').forEach(el => ro.observe(el));
}
observeReveal();

/* ── 3. COUNTDOWN ─────────────────────────────────── */
(function () {
    const wrap = document.getElementById('cdown');
    if (!wrap) return;
    const target = new Date(wrap.dataset.target).getTime();

    function tick() {
        const diff = target - Date.now();
        const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = pad(v); };
        if (diff <= 0) { ['cd-d','cd-h','cd-m','cd-s'].forEach(id => set(id, 0)); return; }
        set('cd-d', Math.floor(diff / 86400000));
        set('cd-h', Math.floor((diff % 86400000) / 3600000));
        set('cd-m', Math.floor((diff % 3600000) / 60000));
        set('cd-s', Math.floor((diff % 60000) / 1000));
    }
    tick();
    setInterval(tick, 1000);
})();

/* ── 4. GOOGLE MAPS ───────────────────────────────── */
function initMap() {
    const el = document.getElementById('gmap');
    if (!el || !window.W?.lat) return;

    const pos = { lat: W.lat, lng: W.lng };
    const map = new google.maps.Map(el, {
        center: pos,
        zoom: 16,
        disableDefaultUI: true,
        zoomControl: true,
        styles: [
            { elementType: 'geometry',        stylers: [{ color: '#f5ede0' }] },
            { featureType: 'road',            stylers: [{ color: '#e8d5b0' }] },
            { featureType: 'water',           stylers: [{ color: '#c9a96e' }] },
            { featureType: 'poi',             stylers: [{ visibility: 'off' }] },
            { featureType: 'transit',         stylers: [{ visibility: 'off' }] },
        ],
    });

    new google.maps.Marker({
        position: pos, map,
        title: W.loc,
        animation: google.maps.Animation.DROP,
        icon: {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z',
            fillColor: '#C9A96E',
            fillOpacity: 1,
            strokeColor: '#9A7340',
            strokeWeight: 1,
            scale: 1.8,
            anchor: new google.maps.Point(12, 22),
        }
    });
}

/* ── 5. GALLERY LIGHTBOX ──────────────────────────── */
(function () {
    const lbx    = document.getElementById('lbx');
    const lbimg  = document.getElementById('lbimg');
    const lbcap  = document.getElementById('lbcap');
    const lbclose = document.getElementById('lbclose');
    if (!lbx) return;

    $$('.g-item').forEach(item => {
        item.addEventListener('click', () => {
            lbimg.src    = item.dataset.src;
            lbcap.textContent = item.dataset.cap || '';
            lbx.classList.remove('hidden');
            lbx.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    });

    const closeLb = () => {
        lbx.classList.add('hidden');
        lbx.classList.remove('flex');
        document.body.style.overflow = '';
        lbimg.src = '';
    };

    lbclose?.addEventListener('click', closeLb);
    lbx.addEventListener('click', e => { if (e.target === lbx) closeLb(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLb(); });
})();

/* ── 6. RSVP FORM ─────────────────────────────────── */
(function () {
    // Counter tamu
    const countEl = document.getElementById('guest-count');
    document.getElementById('dec')?.addEventListener('click', () => {
        const v = parseInt(countEl.value); if (v > 1) countEl.value = v - 1;
    });
    document.getElementById('inc')?.addEventListener('click', () => {
        const v = parseInt(countEl.value); if (v < 10) countEl.value = v + 1;
    });

    // Submit RSVP
    const form  = document.getElementById('rsvp-form');
    const alert = document.getElementById('rsvp-alert');
    const btn   = document.getElementById('rsvp-btn');
    if (!form) return;

    form.addEventListener('submit', async e => {
        e.preventDefault();
        const origLabel = btn.textContent;
        setBtn(btn, true, '');

        const data = Object.fromEntries(new FormData(form).entries());

        try {
            const res = await axios.post('/rsvp', data);
            showAlert(alert, 'success', '✅ ' + res.data.message);
            form.reset();
            countEl.value = 1;
            // Uncheck radio
            $$('input[name="attendance"]', form).forEach(r => r.checked = false);
            $$('.attendance-box').forEach(b => b.classList.remove('border-gold','bg-gold/10'));
        } catch (err) {
            const msg    = err.response?.data?.message || 'Terjadi kesalahan. Coba lagi.';
            const errors = err.response?.data?.errors;
            let html = '❌ ' + msg;
            if (errors) {
                html += '<ul class="mt-1 pl-4 list-disc">';
                Object.values(errors).flat().forEach(e => html += `<li>${e}</li>`);
                html += '</ul>';
            }
            showAlert(alert, 'error', html);
        } finally {
            setBtn(btn, false, origLabel);
        }
    });
})();

/* ── 7. WISH FORM & LIST ──────────────────────────── */
(function () {
    const form    = document.getElementById('wish-form');
    const alert   = document.getElementById('wish-alert');
    const list    = document.getElementById('wish-list');
    const moreWrap = document.getElementById('more-wrap');
    const moreBtn = document.getElementById('more-btn');
    const noWish  = document.getElementById('no-wish');
    if (!form) return;

    const wishBtn  = document.getElementById('wish-btn');
    const ORIG_LBL = wishBtn?.textContent ?? '💌 Kirim Ucapan';

    form.addEventListener('submit', async e => {
        e.preventDefault();
        setBtn(wishBtn, true, '');

        const data = Object.fromEntries(new FormData(form).entries());

        try {
            const res  = await axios.post('/wishes', data);
            const wish = res.data.data;

            showAlert(alert, 'success', '💕 ' + res.data.message);
            form.reset();
            noWish?.remove();

            // Prepend kartu baru
            const card = buildCard(wish);
            list.insertAdjacentHTML('afterbegin', card);
            list.firstElementChild?.classList.add('wish-in');
        } catch (err) {
            const msg    = err.response?.data?.message || 'Gagal mengirim ucapan.';
            const errors = err.response?.data?.errors;
            let html = '❌ ' + msg;
            if (errors) {
                html += '<ul class="mt-1 pl-4 list-disc">';
                Object.values(errors).flat().forEach(e => html += `<li>${e}</li>`);
                html += '</ul>';
            }
            showAlert(alert, 'error', html);
        } finally {
            setBtn(wishBtn, false, ORIG_LBL);
        }
    });

    // Load more
    moreBtn?.addEventListener('click', async () => {
        const page     = parseInt(moreBtn.dataset.page, 10);
        const origLabel = moreBtn.textContent;
        setBtn(moreBtn, true, '');

        try {
            const res = await axios.get(`/wishes?page=${page}`);
            const { data, has_more } = res.data;

            data.forEach(w => list.insertAdjacentHTML('beforeend', buildCard(w)));

            if (has_more) {
                moreBtn.dataset.page = page + 1;
                setBtn(moreBtn, false, origLabel);
            } else {
                moreWrap?.remove();
            }
        } catch {
            setBtn(moreBtn, false, origLabel);
        }
    });

    function buildCard(w) {
        const initial = (w.sender_name || '?').charAt(0).toUpperCase();
        const name    = _.escape(w.sender_name);
        const msg     = _.escape(w.message);
        const time    = w.time_ago || 'Baru saja';
        return `
        <div class="bg-white border border-[rgba(201,169,110,0.2)] p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#C9A96E] to-[#9A7340] flex items-center justify-center text-white font-display text-lg flex-shrink-0 shadow-sm">
                    ${initial}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[#1a0a00] text-sm font-semibold">${name}</p>
                    <p class="text-[rgba(59,44,26,0.65)] text-sm mt-1 leading-relaxed">${msg}</p>
                    <p class="text-[rgba(59,44,26,0.35)] text-xs mt-2">${time}</p>
                </div>
            </div>
        </div>`;
    }
})();

/* ── 8. MUSIC PLAYER ──────────────────────────────── */
(function () {
    const btn   = document.getElementById('mbtn');
    const audio = document.getElementById('bgm');
    const bars  = document.getElementById('mbars');
    if (!btn || !audio) return;

    btn.addEventListener('click', () => {
        if (audio.paused) {
            audio.play().catch(() => {});
            setMusicState(true);
        } else {
            audio.pause();
            setMusicState(false);
        }
    });
})();

function setMusicState(playing) {
    const ico  = document.getElementById('mico');
    const bars = document.getElementById('mbars');

    if (ico) {
        ico.innerHTML = playing
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6"/>`  // pause icon path (SVG)
            : `<path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/>`;
    }

    if (bars) {
        if (playing) { bars.classList.remove('hidden'); bars.classList.add('flex'); }
        else         { bars.classList.add('hidden');    bars.classList.remove('flex'); }
    }
}