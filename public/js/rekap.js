/**
 * TABLE SCROLL UX
 * ---------------
 * Tambahkan kode ini di dalam DOMContentLoaded di rekap.blade.php,
 * atau include sebagai <script src="..."> sebelum </body>.
 *
 * Yang dilakukan:
 *  1. Bungkus setiap .table-wrap dengan .table-scroll-outer (fade hint kanan)
 *  2. Tambahkan baris hint "Geser untuk lihat lebih →" di mobile
 *  3. Tampilkan progress bar scroll di atas tabel
 *  4. Update semua state saat di-scroll
 *  5. Sembunyikan hint otomatis setelah user pertama kali scroll
 */

function initTableScroll() {
    document.querySelectorAll('.table-wrap').forEach((wrap) => {
        // Hindari init ulang
        if (wrap.closest('.table-scroll-outer')) return;

        /* ── 1. Bungkus dengan outer container ── */
        const outer = document.createElement('div');
        outer.className = 'table-scroll-outer';
        wrap.parentNode.insertBefore(outer, wrap);
        outer.appendChild(wrap);

        /* ── 2. Progress bar (di dalam outer, di atas tabel) ── */
        const barWrap = document.createElement('div');
        barWrap.className = 'table-scroll-bar';
        const barFill = document.createElement('div');
        barFill.className = 'table-scroll-bar-fill';
        barWrap.appendChild(barFill);
        outer.insertBefore(barWrap, wrap);

        /* ── 3. Hint teks geser (di bawah progress bar) ── */
        const hint = document.createElement('div');
        hint.className = 'table-scroll-hint';
        hint.innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
            Geser kanan untuk lihat data lainnya`;
        outer.insertBefore(hint, wrap);

        /* ── 4. Event scroll ── */
        let hintDismissed = false;

        wrap.addEventListener('scroll', () => {
            const { scrollLeft, scrollWidth, clientWidth } = wrap;
            const maxScroll = scrollWidth - clientWidth;

            // Progress bar
            const pct = maxScroll > 0 ? (scrollLeft / maxScroll) * 100 : 0;
            barFill.style.width = pct + '%';

            // Fade kanan — sembunyikan saat sudah di ujung
            if (scrollLeft >= maxScroll - 4) {
                outer.classList.add('is-scrolled-end');
            } else {
                outer.classList.remove('is-scrolled-end');
            }

            // Sembunyikan hint setelah user pertama kali scroll
            if (!hintDismissed && scrollLeft > 10) {
                hintDismissed = true;
                hint.style.transition = 'opacity .4s';
                hint.style.opacity = '0';
                setTimeout(() => { hint.style.display = 'none'; }, 420);
            }
        }, { passive: true });

        /* ── 5. Cek apakah tabel perlu scroll (jika tidak, sembunyikan ornamen) ── */
        function checkScrollable() {
            const needsScroll = wrap.scrollWidth > wrap.clientWidth + 4;
            barWrap.style.display  = needsScroll ? '' : 'none';
            hint.style.display     = needsScroll ? '' : 'none';
            // Fade kanan hanya saat butuh scroll
            outer.style.setProperty('--fade-opacity', needsScroll ? '1' : '0');
            if (!needsScroll) outer.classList.add('is-scrolled-end');
        }

        checkScrollable();

        // Re-check saat resize (misal rotasi layar)
        const ro = new ResizeObserver(checkScrollable);
        ro.observe(wrap);
    });
}

// Jalankan saat DOM siap
document.addEventListener('DOMContentLoaded', initTableScroll);

// Jalankan ulang setelah data AJAX dimuat (panggil manual dari renderDaftarSppg, dll.)
// Contoh: setelah tableBody.innerHTML diisi, panggil initTableScroll()
window.initTableScroll = initTableScroll;