/**
 * Custom Quote workbench (/custom-quote)
 * - Gravity Forms stays the engine; this script is the presentation layer.
 * - Locks later steps until a tool category is chosen.
 * - Groups the Work Material select by ISO group and shows its swatch.
 * - "Modify a standard tool": EDP lookup prefills the form and records
 *   what the customer changed (hidden "Changes From Original" field).
 * - Series Selection Guide: material + operation -> best / secondary series.
 * - Side panel: tool drawing, live summary, "specs to go" counter.
 *
 * Option data and field IDs come from inc/custom-quote.php (gwsCustomQuote).
 */

(function ($) {
  const D = window.gwsCustomQuote;
  if (!D) return;

  const F = D.fields;
  const FORM_ID = D.formId;
  const SEP = ' — ';
  const ISO_GROUPS = {
    P: 'P – Steel',
    M: 'M – Stainless steel',
    K: 'K – Cast iron',
    N: 'N – Non-ferrous',
    S: 'S – Superalloys & titanium',
    H: 'H – Hardened steel',
    O: 'O – Non-metals'
  };
  // Key dimensions shown in the side panel, per category.
  const SUMMARY_DIMS = {
    milling: [112, 114, 116, 117],
    holemaking: [131, 136, 135, 137],
    threading: [151, 152],
    inserts: [171],
    specialty: [181, 182, 183]
  };

  // Values loaded from a standard tool, kept across GF ajax re-renders.
  const state = { originals: {}, lookedUp: null, guide: { material: '', op: '' } };

  // ---------------------------
  // Utilities
  // ---------------------------
  const form = () => document.getElementById(`gform_${FORM_ID}`);
  const input = (fid) => document.getElementById(`input_${FORM_ID}_${fid}`);
  const fieldEl = (fid) => document.getElementById(`field_${FORM_ID}_${fid}`);
  const isShown = (el) => !!el && el.offsetParent !== null;

  function radioValue(fid) {
    const el = form()?.querySelector(`input[name="input_${fid}"]:checked`);
    return el ? el.value : '';
  }

  function value(fid) {
    const radio = form()?.querySelector(`input[name="input_${fid}"][type="radio"]`);
    if (radio) return radioValue(fid);
    const el = input(fid);
    return el ? el.value.trim() : '';
  }

  // Set a GF input and fire the events GF's conditional logic listens for.
  function setValue(fid, val) {
    const radios = form()?.querySelectorAll(`input[name="input_${fid}"][type="radio"]`);
    if (radios && radios.length) {
      radios.forEach((r) => {
        if (r.value === val) {
          r.checked = true;
          $(r).trigger('click').trigger('change');
        }
      });
      return;
    }
    const el = input(fid);
    if (!el) return;
    if (el.tagName === 'SELECT' && ![...el.options].some((o) => o.value === val)) return;
    el.value = val;
    $(el).trigger('change').trigger('keyup');
  }

  function label(fid) {
    const el = fieldEl(fid)?.querySelector('.gfield_label');
    if (!el) return `Field ${fid}`;
    const clone = el.cloneNode(true);
    clone.querySelectorAll('.gfield_required').forEach((n) => n.remove());
    return clone.textContent.trim();
  }

  function selectedText(fid) {
    const el = input(fid);
    if (el && el.tagName === 'SELECT') return el.selectedIndex > 0 ? el.options[el.selectedIndex].text : '';
    const radio = form()?.querySelector(`input[name="input_${fid}"]:checked`);
    if (radio) return form().querySelector(`label[for="${radio.id}"]`)?.textContent.trim() || radio.value;
    return value(fid);
  }

  function seriesInfo(category, series) {
    return (D.series[category] || []).find((s) => s.series === series) || null;
  }

  function iconUrl(code) {
    return code && code !== 'Other' ? `${D.iconBase}${code}_icon.png` : '';
  }

  // ---------------------------
  // Steps: lock until a category is chosen
  // ---------------------------
  function syncLock() {
    const f = form();
    if (f) f.classList.toggle('cq-locked', !value(F.category));
  }

  // ---------------------------
  // Work Material: ISO optgroups + swatch
  // ---------------------------
  function groupMaterials() {
    const select = input(F.material);
    if (!select || select.dataset.cqGrouped) return;
    select.dataset.cqGrouped = '1';
    const groups = {};
    [...select.options].forEach((opt) => {
      const group = ISO_GROUPS[opt.value.charAt(0)];
      if (!opt.value || opt.value === 'Other' || !group) return;
      if (!groups[group]) {
        groups[group] = document.createElement('optgroup');
        groups[group].label = group;
      }
      groups[group].appendChild(opt);
    });
    const other = [...select.options].find((o) => o.value === 'Other');
    Object.values(groups).forEach((g) => select.insertBefore(g, other || null));

    const swatch = document.createElement('span');
    swatch.className = 'cq-swatch';
    swatch.setAttribute('aria-hidden', 'true');
    select.parentNode.insertBefore(swatch, select);
    select.addEventListener('change', syncSwatch);
    syncSwatch();
  }

  function syncSwatch() {
    const select = input(F.material);
    const swatch = select?.parentNode.querySelector('.cq-swatch');
    if (!swatch) return;
    const url = iconUrl(select.value);
    swatch.style.backgroundImage = url ? `url("${url}")` : '';
    swatch.classList.toggle('is-empty', !url);
  }

  // ---------------------------
  // Modify a standard tool
  // ---------------------------
  function injectLookup() {
    const edp = input(F.edp);
    if (!edp || edp.dataset.cqLookup) return;
    edp.dataset.cqLookup = '1';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn light-blue cq-lookup-btn';
    btn.textContent = 'Look Up';
    const status = document.createElement('p');
    status.className = 'cq-lookup-status';
    status.setAttribute('aria-live', 'polite');
    edp.insertAdjacentElement('afterend', btn);
    btn.insertAdjacentElement('afterend', status);
    btn.addEventListener('click', () => lookUp(edp.value));
    edp.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        lookUp(edp.value);
      }
    });
    if (state.lookedUp) showLookupStatus(state.lookedUp);
  }

  function showLookupStatus(data, isError) {
    const status = fieldEl(F.edp)?.querySelector('.cq-lookup-status');
    if (!status) return;
    status.classList.toggle('is-error', !!isError);
    status.textContent = isError
      ? data
      : `Based on EDP ${data.part}${data.series ? ' · Series ' + data.series : ''}. Change any value below.`;
  }

  async function lookUp(edp) {
    edp = (edp || '').trim();
    if (edp.length < 3) {
      showLookupStatus('Enter a part number (EDP).', true);
      return;
    }
    showLookupStatus('Looking up…', false);
    try {
      const res = await fetch(`${D.restUrl}?edp=${encodeURIComponent(edp)}`, { headers: { Accept: 'application/json' } });
      const data = await res.json();
      if (!res.ok || !data.found) {
        showLookupStatus(data.message || "We couldn't look up that EDP.", true);
        return;
      }
      applyTool(data);
    } catch (e) {
      showLookupStatus("We couldn't look up that EDP right now. Please try again, or describe it in Notes.", true);
    }
  }

  function applyTool(data) {
    setValue(F.category, data.category);
    setValue(F.mode, 'modify');
    if (input(F.edp)) input(F.edp).value = data.part;
    setValue(data.typeField, data.type);
    if (data.series) setValue(data.seriesField, data.series);
    setValue(F.units, data.units);

    state.originals = {};
    Object.entries(data.prefill).forEach(([fid, val]) => {
      setValue(fid, val);
      state.originals[fid] = val;
    });
    state.lookedUp = data;
    showLookupStatus(data);
    syncChanged();
    refresh();
  }

  function syncChanged() {
    Object.keys(state.originals).forEach((fid) => {
      fieldEl(fid)?.classList.toggle('cq-changed', value(fid) !== state.originals[fid]);
    });
  }

  // "Cutting Diameter (D1): 0.2500 → 0.3125" lines for engineering.
  function writeChanges() {
    const hidden = input(F.changes);
    if (!hidden) return;
    if (!state.lookedUp || value(F.mode) !== 'modify') {
      hidden.value = '';
      return;
    }
    const lines = Object.entries(state.originals)
      .filter(([fid, orig]) => value(fid) !== orig)
      .map(([fid, orig]) => `${label(fid)}: ${orig} → ${value(fid) || '(blank)'}`);
    hidden.value = lines.length ? lines.join('; ') : `No dimension changes from EDP ${state.lookedUp.part}`;
  }

  // ---------------------------
  // Series Selection Guide
  // ---------------------------
  function ratedSeries(category) {
    return (D.series[category] || []).filter((s) => Object.keys(s.ratings).length);
  }

  function injectGuideLink() {
    Object.entries(F.series).forEach(([category, fid]) => {
      const field = fieldEl(fid);
      if (!field || field.querySelector('.cq-guide-link') || !ratedSeries(category).length) return;
      const link = document.createElement('button');
      link.type = 'button';
      link.className = 'cq-guide-link';
      link.textContent = 'Unsure which series? Use the guide';
      link.addEventListener('click', () => openGuide(category));
      field.appendChild(link);
    });
  }

  function guideEl(id) {
    return document.getElementById(id);
  }

  function openGuide(category) {
    const modal = guideEl('cq-guide');
    if (!modal) return;
    modal.dataset.category = category;
    state.guide.material = value(F.material) && value(F.material) !== 'Other' ? value(F.material) : '';
    state.guide.op = '';

    const swatches = guideEl('cq-guide-materials');
    swatches.innerHTML = '';
    D.materials.filter((m) => m.code !== 'Other').forEach((m) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'cq-swatch-btn';
      b.dataset.code = m.code;
      b.innerHTML = `<img src="${iconUrl(m.code)}" alt=""><span></span>`;
      b.querySelector('span').textContent = m.label;
      b.addEventListener('click', () => {
        state.guide.material = m.code;
        renderGuide();
      });
      swatches.appendChild(b);
    });

    const ops = D.operations[category] || {};
    const opWrap = guideEl('cq-guide-op-wrap');
    const opBox = guideEl('cq-guide-ops');
    opBox.innerHTML = '';
    opWrap.hidden = !Object.keys(ops).length;
    Object.entries(ops).forEach(([token, text]) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'cq-op-btn';
      b.dataset.op = token;
      b.textContent = text;
      b.addEventListener('click', () => {
        state.guide.op = state.guide.op === token ? '' : token;
        renderGuide();
      });
      opBox.appendChild(b);
    });

    modal.hidden = false;
    document.body.classList.add('cq-modal-open');
    renderGuide();
    modal.querySelector('.cq-modal-close').focus();
  }

  function closeGuide() {
    const modal = guideEl('cq-guide');
    if (!modal || modal.hidden) return;
    modal.hidden = true;
    document.body.classList.remove('cq-modal-open');
  }

  function renderGuide() {
    const modal = guideEl('cq-guide');
    const category = modal.dataset.category;
    const { material, op } = state.guide;
    modal.querySelectorAll('.cq-swatch-btn').forEach((b) => b.classList.toggle('is-active', b.dataset.code === material));
    modal.querySelectorAll('.cq-op-btn').forEach((b) => b.classList.toggle('is-active', b.dataset.op === op));

    const results = guideEl('cq-guide-results');
    results.innerHTML = '';
    if (!material) {
      results.innerHTML = '<p class="cq-guide-hint">Pick a material to see recommended series.</p>';
      return;
    }
    const matches = ratedSeries(category).filter((s) => s.ratings[material] && (!op || s.ops.includes(op)));
    const groups = [
      ['best', 'Best Choice', 'Our recommended series for this material and operation.'],
      ['good', 'Secondary Choice', 'Also suitable for this material.']
    ];
    let shown = 0;
    groups.forEach(([rating, title, blurb]) => {
      const list = matches.filter((s) => s.ratings[material] === rating);
      if (!list.length) return;
      shown += list.length;
      const section = document.createElement('div');
      section.className = 'cq-result-group';
      section.innerHTML = `<h3></h3><p class="cq-guide-hint"></p><div class="cq-result-cards"></div>`;
      section.querySelector('h3').textContent = title;
      section.querySelector('p').textContent = blurb;
      const cards = section.querySelector('.cq-result-cards');
      list.forEach((s) => cards.appendChild(seriesCard(category, s, material)));
      results.appendChild(section);
    });
    if (!shown) {
      results.innerHTML = '<p class="cq-guide-hint">No rated series for that combination. Choose Design a special and describe it in Notes, or contact us and our engineers will recommend one.</p>';
    }
  }

  function seriesCard(category, s, material) {
    const card = document.createElement('div');
    card.className = 'cq-result-card';
    card.innerHTML = `${s.image ? '<img alt="">' : ''}<div><strong></strong><p></p><button type="button" class="btn light-blue">Use this series</button></div>`;
    if (s.image) card.querySelector('img').src = s.image;
    card.querySelector('strong').textContent = `Series ${s.series}`;
    card.querySelector('p').textContent = s.subtitle;
    card.querySelector('button').addEventListener('click', () => {
      setValue(F.series[category], s.series);
      setValue(F.material, material);
      syncSwatch();
      closeGuide();
      refresh();
    });
    return card;
  }

  // ---------------------------
  // Side panel: drawing, summary, specs to go
  // ---------------------------
  function currentImage(category) {
    if (!category || category === 'print-only') return '';
    const series = seriesInfo(category, value(F.series[category]));
    if (series && series.image) return series.image;
    if (state.lookedUp && state.lookedUp.category === category && state.lookedUp.image) return state.lookedUp.image;
    const type = value(F.type[category]);
    if (!type) return '';
    const base = type.split(SEP)[0].toLowerCase();
    const match = (D.series[category] || []).find((s) => {
      const sub = (s.subType || '').toLowerCase();
      return s.image && sub && (sub.includes(base) || base.includes(sub));
    });
    return match ? match.image : '';
  }

  function renderPanel() {
    const category = value(F.category);
    const img = document.getElementById('cq-diagram');
    const empty = document.getElementById('cq-diagram-empty');
    if (img) {
      const src = currentImage(category);
      img.hidden = !src;
      if (src && img.getAttribute('src') !== src) img.src = src;
      if (empty) empty.hidden = !!src;
    }

    const rows = [];
    if (category) rows.push(['Category', selectedText(F.category)]);
    if (category && F.type[category] && value(F.type[category])) rows.push([label(F.type[category]), value(F.type[category])]);
    if (category && F.series[category] && value(F.series[category])) rows.push(['Series', value(F.series[category])]);
    if (state.lookedUp && value(F.mode) === 'modify') rows.push(['Based on', `EDP ${state.lookedUp.part}`]);
    if (value(F.material)) rows.push(['Material', value(F.material)]);
    const units = value(F.units) === 'Metric' ? 'mm' : 'in';
    (SUMMARY_DIMS[category] || []).forEach((fid) => {
      const v = value(fid);
      if (!v) return;
      const numeric = /^[\d.]+$/.test(v);
      rows.push([label(fid), numeric ? `${v} ${units}` : v]);
    });
    const dl = document.getElementById('cq-summary');
    if (dl) {
      dl.innerHTML = '';
      rows.forEach(([k, v]) => {
        const dt = document.createElement('dt');
        const dd = document.createElement('dd');
        dt.textContent = k;
        dd.textContent = v;
        dl.append(dt, dd);
      });
    }

    const fit = document.getElementById('cq-material-fit');
    if (fit) {
      const series = category ? seriesInfo(category, value(F.series[category])) : null;
      const material = value(F.material);
      if (series && material && material !== 'Other') {
        const rating = series.ratings[material];
        fit.hidden = false;
        fit.className = `cq-fit is-${rating || 'none'}`;
        fit.textContent = rating === 'best'
          ? `Series ${series.series}: best choice for ${material}`
          : rating === 'good'
            ? `Series ${series.series}: also applicable for ${material}`
            : `Series ${series.series} isn't rated for ${material} - our engineers will confirm.`;
      } else {
        fit.hidden = true;
      }
    }

    const left = [...(form()?.querySelectorAll('.gfield_contains_required') || [])].filter((field) => {
      if (!isShown(field)) return false;
      const controls = field.querySelectorAll('input:not([type="hidden"]), select, textarea');
      return ![...controls].some((c) => (c.type === 'radio' || c.type === 'checkbox' ? c.checked : c.value.trim() !== ''));
    }).length;
    const counter = document.getElementById('cq-specs-to-go');
    if (counter) counter.textContent = left ? `${left} required ${left === 1 ? 'field' : 'fields'} to go` : 'Ready to submit';
  }

  function refresh() {
    syncLock();
    syncChanged();
    renderPanel();
  }

  // ---------------------------
  // Init (runs again after every GF ajax re-render)
  // ---------------------------
  function init() {
    const f = form();
    if (!f) return;
    groupMaterials();
    injectLookup();
    injectGuideLink();
    if (!f.dataset.cqBound) {
      f.dataset.cqBound = '1';
      f.addEventListener('change', () => setTimeout(refresh, 0));
      f.addEventListener('input', () => setTimeout(refresh, 0));
      f.addEventListener('submit', writeChanges, true);
    }
    refresh();
  }

  function initOnce() {
    const proxy = document.getElementById('cq-submit-proxy');
    proxy?.addEventListener('click', () => {
      writeChanges();
      document.getElementById(`gform_submit_button_${FORM_ID}`)?.click();
    });
    document.addEventListener('click', (e) => {
      if (e.target.closest('[data-cq-close]') || e.target.id === 'cq-guide') closeGuide();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeGuide();
    });

    // ?edp= deep link from a product page. Read client-side: the page HTML is
    // cached once for all visitors, so the server can't prefill per request.
    const edp = new URLSearchParams(window.location.search).get('edp');
    if (edp) lookUp(edp);
  }

  $(document).on('gform_post_render', (e, formId) => {
    if (Number(formId) === Number(FORM_ID)) init();
  });
  $(() => {
    init();
    initOnce();
  });
})(jQuery);
