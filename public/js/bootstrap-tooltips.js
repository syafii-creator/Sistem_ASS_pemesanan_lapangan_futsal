/**
 * BOOTSTRAP 5 TOOLTIPS ENGINE & AUTO-INITIALIZER (ENHANCED)
 * Kompatibel penuh dengan sintaks data-bs-toggle="tooltip"
 * Mendukung Hover dan Click (Toggle Pin) untuk Desktop & Mobile.
 */
(function() {
  'use strict';

  let activeTooltip = null;
  let activeTooltipTrigger = null;
  let activeTooltipPlacement = 'top';

  /**
   * Mengambil teks tooltip dari data-bs-title, data-bs-original-title, atau title.
   * Menjamin atribut title tersimpan permanen di data-bs-original-title sehingga tidak pernah hilang.
   */
  function getTooltipContent(el) {
    if (!el || typeof el.getAttribute !== 'function') return '';

    let content = el.getAttribute('data-bs-title') ||
                  el.getAttribute('data-bs-original-title') ||
                  el.getAttribute('title') ||
                  '';

    // Simpan permanen ke data-bs-original-title jika belum ada, lalu hapus title asli
    // agar tooltip kuning bawaan browser tidak menimpa / bentrok.
    if (el.hasAttribute('title')) {
      const origTitle = el.getAttribute('title');
      if (origTitle && origTitle.trim()) {
        content = origTitle.trim();
        el.setAttribute('data-bs-original-title', content);
      }
      el.removeAttribute('title');
    }

    return (content || '').trim();
  }

  function createTooltipElement(content, placement) {
    const tooltip = document.createElement('div');
    tooltip.className = `tooltip bs-tooltip-${placement} fade`;
    tooltip.setAttribute('role', 'tooltip');

    const arrow = document.createElement('div');
    arrow.className = 'tooltip-arrow';

    const inner = document.createElement('div');
    inner.className = 'tooltip-inner';
    inner.innerHTML = content;

    tooltip.appendChild(arrow);
    tooltip.appendChild(inner);
    document.body.appendChild(tooltip);

    return tooltip;
  }

  function positionTooltip(el, tooltip, requestedPlacement) {
    if (!el || !tooltip) return;

    const rect = el.getBoundingClientRect();
    const tooltipRect = tooltip.getBoundingClientRect();
    const scrollY = window.pageYOffset || document.documentElement.scrollTop;
    const scrollX = window.pageXOffset || document.documentElement.scrollLeft;

    let placement = requestedPlacement || 'top';

    // Smart auto-flipping jika mepet tepi layar
    if (placement === 'top' && (rect.top - tooltipRect.height - 12 < 0)) {
      placement = 'bottom';
    } else if (placement === 'bottom' && (rect.bottom + tooltipRect.height + 12 > window.innerHeight)) {
      placement = 'top';
    } else if ((placement === 'left' || placement === 'start') && (rect.left - tooltipRect.width - 12 < 0)) {
      placement = 'right';
    } else if ((placement === 'right' || placement === 'end') && (rect.right + tooltipRect.width + 12 > window.innerWidth)) {
      placement = 'left';
    }

    // Perbarui class agar arah panah (.tooltip-arrow) sesuai posisi flip
    tooltip.className = `tooltip bs-tooltip-${placement} fade show`;

    let top = 0;
    let left = 0;

    switch (placement) {
      case 'bottom':
        top = rect.bottom + scrollY + 8;
        left = rect.left + scrollX + (rect.width / 2) - (tooltipRect.width / 2);
        break;
      case 'left':
      case 'start':
        top = rect.top + scrollY + (rect.height / 2) - (tooltipRect.height / 2);
        left = rect.left + scrollX - tooltipRect.width - 8;
        break;
      case 'right':
      case 'end':
        top = rect.top + scrollY + (rect.height / 2) - (tooltipRect.height / 2);
        left = rect.right + scrollX + 8;
        break;
      case 'top':
      default:
        top = rect.top + scrollY - tooltipRect.height - 8;
        left = rect.left + scrollX + (rect.width / 2) - (tooltipRect.width / 2);
        break;
    }

    // Boundary clamping agar tidak terpotong tepi layar horizontal
    const minLeft = scrollX + 10;
    const maxLeft = scrollX + window.innerWidth - tooltipRect.width - 10;
    if (left < minLeft) left = minLeft;
    if (left > maxLeft) left = maxLeft;

    tooltip.style.top = `${Math.round(top)}px`;
    tooltip.style.left = `${Math.round(left)}px`;
  }

  function showTooltip(el, isPinned = false) {
    if (!el) return;

    const content = getTooltipContent(el);
    if (!content) return;

    // Jika tooltip sudah aktif di elemen yang sama
    if (activeTooltip && activeTooltipTrigger === el) {
      if (isPinned) {
        el.classList.add('tooltip-pinned');
      }
      activeTooltip.classList.add('show');
      positionTooltip(el, activeTooltip, activeTooltipPlacement);
      return;
    }

    // Bersihkan tooltip sebelumnya jika beda elemen
    hideCurrentTooltip(true);

    const placement = el.getAttribute('data-bs-placement') || 'top';
    activeTooltipPlacement = placement;
    activeTooltipTrigger = el;

    if (isPinned) {
      el.classList.add('tooltip-pinned');
    }

    const tooltip = createTooltipElement(content, placement);
    activeTooltip = tooltip;

    positionTooltip(el, tooltip, placement);

    requestAnimationFrame(() => {
      if (activeTooltip === tooltip) {
        tooltip.classList.add('show');
      }
    });
  }

  function hideCurrentTooltip(immediate = false) {
    if (!activeTooltip) return;

    const tooltipToHide = activeTooltip;
    const triggerToClear = activeTooltipTrigger;

    activeTooltip = null;
    activeTooltipTrigger = null;

    if (triggerToClear) {
      triggerToClear.classList.remove('tooltip-pinned');
    }

    tooltipToHide.classList.remove('show');

    if (immediate) {
      if (tooltipToHide.parentNode) {
        tooltipToHide.parentNode.removeChild(tooltipToHide);
      }
    } else {
      setTimeout(() => {
        if (tooltipToHide && tooltipToHide.parentNode) {
          tooltipToHide.parentNode.removeChild(tooltipToHide);
        }
      }, 150);
    }
  }

  /**
   * Pre-strip native browser title on all trigger elements
   * to prevent ugly browser default yellow tooltip.
   */
  function prepAllTriggers() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      getTooltipContent(el);
    });
  }

  // =========================================================================
  // DOCUMENT-LEVEL EVENT DELEGATION (Kuat & Kebal terhadap Dynamic Re-render)
  // =========================================================================

  // 1. Mouse Enter / Hover
  document.addEventListener('mouseover', (e) => {
    const trigger = e.target.closest('[data-bs-toggle="tooltip"]');
    if (trigger) {
      showTooltip(trigger, false);
    }
  }, { passive: true });

  // 2. Mouse Leave
  document.addEventListener('mouseout', (e) => {
    const trigger = e.target.closest('[data-bs-toggle="tooltip"]');
    if (trigger) {
      const related = e.relatedTarget ? e.relatedTarget.closest('[data-bs-toggle="tooltip"]') : null;
      // Jangan tutup jika kursor masih berada di dalam elemen yang sama atau jika tooltip sedang dipin (diklik)
      if (related !== trigger && !trigger.classList.contains('tooltip-pinned')) {
        hideCurrentTooltip();
      }
    }
  }, { passive: true });

  // 3. Click / Tap (Khusus untuk istilah teknis .term-tooltip, tidak mengganggu tombol & form)
  document.addEventListener('click', (e) => {
    // Jika klik terjadi pada button, link, form, input, select, textarea -> jangan tangkap click untuk tooltip
    if (e.target.closest('button, a, input, select, textarea, form')) {
      if (activeTooltip && !activeTooltipTrigger?.classList.contains('tooltip-pinned')) {
        hideCurrentTooltip(true);
      }
      return;
    }

    const trigger = e.target.closest('.term-tooltip[data-bs-toggle="tooltip"]');
    if (trigger) {
      // Jika yang diklik adalah trigger tooltip yang sedang dipin -> toggle tutup
      if (activeTooltipTrigger === trigger && trigger.classList.contains('tooltip-pinned')) {
        hideCurrentTooltip();
      } else {
        // Tampilkan dan PIN tulisan tooltip agar pengguna leluasa membaca
        showTooltip(trigger, true);
      }
    } else {
      // Klik di luar -> tutup tooltip yang aktif
      if (activeTooltip) {
        hideCurrentTooltip();
      }
    }
  });

  // 4. Focus & Blur untuk Aksesibilitas Keyboard
  document.addEventListener('focusin', (e) => {
    const trigger = e.target.closest('[data-bs-toggle="tooltip"]');
    if (trigger) {
      showTooltip(trigger, false);
    }
  }, { passive: true });

  document.addEventListener('focusout', (e) => {
    const trigger = e.target.closest('[data-bs-toggle="tooltip"]');
    if (trigger && !trigger.classList.contains('tooltip-pinned')) {
      hideCurrentTooltip();
    }
  }, { passive: true });

  // 5. Scroll & Resize: Perbarui posisi otomatis
  window.addEventListener('scroll', () => {
    if (activeTooltip && activeTooltipTrigger) {
      positionTooltip(activeTooltipTrigger, activeTooltip, activeTooltipPlacement);
    }
  }, { passive: true });

  window.addEventListener('resize', () => {
    if (activeTooltip && activeTooltipTrigger) {
      positionTooltip(activeTooltipTrigger, activeTooltip, activeTooltipPlacement);
    }
  }, { passive: true });

  // 6. Inisialisasi awal & observasi elemen baru (Aman saat dipanggil di <head>)
  function setupBootstrapTooltipsObserver() {
    prepAllTriggers();
    if (window.MutationObserver && document.body) {
      const observer = new MutationObserver(() => {
        prepAllTriggers();
      });
      observer.observe(document.body, { childList: true, subtree: true });
    }
  }

  window.initBootstrapTooltips = prepAllTriggers;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupBootstrapTooltipsObserver);
  } else {
    setupBootstrapTooltipsObserver();
  }

})();
