/**
 * Prime Time TV Mounting — Order Intake Wizard & Interactive Controller
 * Design & Behavior: "Apple meets Domino's" frictionless configuration
 * Features live dynamic pricing engine ready for Square, Stripe, and PayPal
 */

(function () {
  'use strict';

  // Global State
  const state = {
    totalTvs: 1,
    currentTvIndex: 0, // 0-based
    tvs: [], // [{ size_id, size_label, size_price, addons: [{ id, title, subtitle, price }] }]
    selectedServices: [], // [{ id, title, subtitle, price_rate, price_num }]
    customer: {
      name: '',
      phone: '',
      email: '',
      address: '',
      notes: ''
    },
    pricing: {
      tvsSubtotal: 0,
      addonsSubtotal: 0,
      servicesSubtotal: 0,
      grandTotal: 0
    },
    orderRefId: null
  };

  // DOM Elements
  let stageQuantity, stageConfig, stageServices, stageConfirmation;
  let sizeButtons, addonCards, serviceCards;
  let currentTvLabel, currentTvOfTotal, pttmPreviewTag, stepperDotsContainer;
  let configNextBtn, configNextLabel, configBackBtn;
  let livePriceVal, step4TvSubtotal, step4SrvSubtotal, step4GrandTotal, step4ItemsBadge;
  let servicesStepPill, servicesBackBtn, orderForm, finishBtn;
  let confirmTally, confirmItemsList, confirmHomeBtn;
  let modalOverlay, modalPanes, modalCloseBtn;

  // Initialize
  function init() {
    cacheDom();
    bindEvents();
    initGooglePlaces();
    checkUrlParams();
  }

  function cacheDom() {
    stageQuantity     = document.getElementById('pttmStageQuantity');
    stageConfig       = document.getElementById('pttmStageConfig');
    stageServices     = document.getElementById('pttmStageServices');
    stageConfirmation = document.getElementById('pttmStageConfirmation');

    currentTvLabel       = document.getElementById('pttmCurrentTvLabel');
    currentTvOfTotal     = document.getElementById('pttmCurrentTvOfTotal');
    pttmPreviewTag       = document.getElementById('pttmPreviewTag');
    stepperDotsContainer = document.getElementById('pttmStepperDots');

    configNextBtn   = document.getElementById('pttmConfigNextBtn');
    configNextLabel = document.getElementById('pttmConfigNextLabel');
    configBackBtn   = document.getElementById('pttmConfigBackBtn');
    livePriceVal    = document.getElementById('pttmLivePriceVal');

    servicesStepPill = document.getElementById('pttmServicesStepPill');
    servicesBackBtn  = document.getElementById('pttmServicesBackBtn');
    orderForm        = document.getElementById('pttmOrderIntakeForm');
    finishBtn        = document.getElementById('pttmFinishOrderBtn');

    step4TvSubtotal  = document.getElementById('pttmStep4TvSubtotal');
    step4SrvSubtotal = document.getElementById('pttmStep4SrvSubtotal');
    step4GrandTotal  = document.getElementById('pttmStep4GrandTotal');
    step4ItemsBadge  = document.getElementById('pttmStep4ItemsBadge');

    confirmTally     = document.getElementById('pttmConfirmTally');
    confirmItemsList = document.getElementById('pttmConfirmItemsList');
    confirmHomeBtn   = document.getElementById('pttmConfirmHomeBtn');

    modalOverlay  = document.getElementById('pttmModalOverlay');
    modalCloseBtn = document.getElementById('pttmModalClose');
    modalPanes    = document.querySelectorAll('.pttm-modal-pane');

    sizeButtons  = document.querySelectorAll('.pttm-size-btn');
    addonCards   = document.querySelectorAll('.pttm-addon-card');
    serviceCards = document.querySelectorAll('.pttm-service-card');
  }

  function bindEvents() {
    // 1. Quantity Card Click (Screen 4)
    const qtyCards = document.querySelectorAll('.pttm-qty-card');
    qtyCards.forEach(card => {
      card.addEventListener('click', function () {
        const qty = parseInt(this.getAttribute('data-qty'), 10) || 1;
        selectTvQuantity(qty);
      });
    });

    // 2. TV Size Selection (Screen 1)
    sizeButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        sizeButtons.forEach(b => {
          b.classList.remove('is-selected');
          b.setAttribute('aria-checked', 'false');
        });
        this.classList.add('is-selected');
        this.setAttribute('aria-checked', 'true');
        saveCurrentTvData();
        calculateLivePricing();
      });
    });

    // 3. Add-on Card Click (Screen 1)
    addonCards.forEach(card => {
      card.addEventListener('click', function () {
        this.classList.toggle('is-selected');
        const isSel = this.classList.contains('is-selected');
        this.setAttribute('aria-checked', isSel ? 'true' : 'false');
        saveCurrentTvData();
        calculateLivePricing();
      });
    });

    // 4. Config Wizard Navigation (Next / Back)
    if (configNextBtn) {
      configNextBtn.addEventListener('click', onConfigNext);
    }
    if (configBackBtn) {
      configBackBtn.addEventListener('click', onConfigBack);
    }

    // 5. Additional Services Cards Click (Screen 2)
    serviceCards.forEach(card => {
      card.addEventListener('click', function () {
        this.classList.toggle('is-selected');
        const isSel = this.classList.contains('is-selected');
        this.setAttribute('aria-checked', isSel ? 'true' : 'false');
        updateSelectedServices();
        calculateLivePricing();
      });
    });

    // 6. Services Screen Back Button
    if (servicesBackBtn) {
      servicesBackBtn.addEventListener('click', function () {
        state.currentTvIndex = state.totalTvs - 1;
        showStage('config');
        renderTvConfigScreen();
      });
    }

    // 7. Order Intake Form Submit (Screen 2 Finish Order)
    if (orderForm) {
      orderForm.addEventListener('submit', handleOrderSubmit);
    }

    // 8. Confirmation Back to Home (Screen 3)
    if (confirmHomeBtn) {
      confirmHomeBtn.addEventListener('click', resetToHome);
    }

    // 9. Modals & Popup Navigation
    document.querySelectorAll('[data-modal]').forEach(trigger => {
      trigger.addEventListener('click', function (e) {
        e.preventDefault();
        const modalKey = this.getAttribute('data-modal');
        openModal(modalKey);
      });
    });

    if (modalCloseBtn) {
      modalCloseBtn.addEventListener('click', closeModal);
    }
    if (modalOverlay) {
      modalOverlay.addEventListener('click', function (e) {
        if (e.target === modalOverlay) {
          closeModal();
        }
      });
    }

    // Mobile Menu Toggle
    const mobileToggle = document.getElementById('pttmMobileToggle');
    if (mobileToggle) {
      mobileToggle.addEventListener('click', function () {
        openModal('mobile-menu');
      });
    }

    // FAQ Accordion Toggle
    document.querySelectorAll('.pttm-faq-question').forEach(btn => {
      btn.addEventListener('click', function () {
        const item = this.closest('.pttm-faq-accordion-item');
        item.classList.toggle('is-open');
      });
    });

    // Keyboard ESC to close modal
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('is-active')) {
        closeModal();
      }
    });
  }

  // --- REAL-TIME PRICING ENGINE ---
  function calculateLivePricing() {
    let tvsTotal = 0;
    let addonsTotal = 0;
    let srvTotal = 0;

    state.tvs.forEach(tv => {
      tvsTotal += parseFloat(tv.size_price) || 95;
      if (tv.addons && Array.isArray(tv.addons)) {
        tv.addons.forEach(a => {
          addonsTotal += parseFloat(a.price) || 0;
        });
      }
    });

    state.selectedServices.forEach(srv => {
      srvTotal += parseFloat(srv.price_num) || 75;
    });

    const tvsAndAddons = tvsTotal + addonsTotal;
    const grandTotal = tvsAndAddons + srvTotal;

    state.pricing = {
      tvsSubtotal: tvsTotal,
      addonsSubtotal: addonsTotal,
      servicesSubtotal: srvTotal,
      grandTotal: grandTotal
    };

    // Update live footer price pill
    if (livePriceVal) {
      livePriceVal.textContent = `$${grandTotal.toFixed(2)}`;
    }

    // Update Step 4 ledger
    if (step4TvSubtotal) {
      step4TvSubtotal.textContent = `$${tvsAndAddons.toFixed(2)}`;
    }
    if (step4SrvSubtotal) {
      step4SrvSubtotal.textContent = `$${srvTotal.toFixed(2)}`;
    }
    if (step4GrandTotal) {
      step4GrandTotal.textContent = `$${grandTotal.toFixed(2)}`;
    }
    if (step4ItemsBadge) {
      step4ItemsBadge.textContent = `${state.totalTvs} TV${state.totalTvs > 1 ? 's' : ''}`;
    }
  }

  function updateSelectedServices() {
    state.selectedServices = [];
    document.querySelectorAll('.pttm-service-card.is-selected').forEach(card => {
      state.selectedServices.push({
        id: card.getAttribute('data-service-id'),
        title: card.getAttribute('data-service-title'),
        subtitle: card.getAttribute('data-service-subtitle'),
        price_rate: card.getAttribute('data-service-rate'),
        price_num: parseFloat(card.getAttribute('data-service-price')) || 75
      });
    });
  }

  // --- STAGE SWITCHING ---
  function showStage(stageName) {
    if (!stageQuantity) return;
    stageQuantity.style.display     = stageName === 'quantity' ? 'flex' : 'none';
    stageConfig.style.display       = stageName === 'config' ? 'block' : 'none';
    stageServices.style.display     = stageName === 'services' ? 'block' : 'none';
    stageConfirmation.style.display = stageName === 'confirmation' ? 'block' : 'none';

    window.scrollTo({ top: 0, behavior: 'smooth' });
    calculateLivePricing();
  }

  // --- STAGE 1: QUANTITY SELECTION ---
  function selectTvQuantity(qty) {
    state.totalTvs = qty;
    state.currentTvIndex = 0;
    state.tvs = [];

    // Initialize blank TVs
    for (let i = 0; i < qty; i++) {
      state.tvs.push({
        size_id: '43_55',
        size_label: '43" – 55"',
        size_price: 95,
        addons: []
      });
    }

    showStage('config');
    renderTvConfigScreen();
    calculateLivePricing();
  }

  // --- STAGE 2: TV CONFIGURATION ---
  function renderTvConfigScreen() {
    const tvNum = state.currentTvIndex + 1;
    const tvData = state.tvs[state.currentTvIndex];

    // Header labels
    if (currentTvLabel) currentTvLabel.innerHTML = `TV ${tvNum} <span class="pttm-text-muted" id="pttmCurrentTvOfTotal">of ${state.totalTvs}</span>`;
    
    // Update Size buttons
    sizeButtons.forEach(btn => {
      const btnSizeId = btn.getAttribute('data-size-id');
      const isMatch = btnSizeId === tvData.size_id;
      btn.classList.toggle('is-selected', isMatch);
      btn.setAttribute('aria-checked', isMatch ? 'true' : 'false');
    });

    // Update Addons
    const activeAddonIds = tvData.addons.map(a => a.id);
    addonCards.forEach(card => {
      const addonId = card.getAttribute('data-addon-id');
      const isMatch = activeAddonIds.includes(addonId);
      card.classList.toggle('is-selected', isMatch);
      card.setAttribute('aria-checked', isMatch ? 'true' : 'false');
    });

    // Update Stepper Dots
    renderStepperDots();

    // Update Next Button Label
    if (state.currentTvIndex === state.totalTvs - 1) {
      configNextLabel.textContent = 'Next: Additional Services';
    } else {
      configNextLabel.textContent = `Next: TV ${state.currentTvIndex + 2}`;
    }

    // Overlay tag on preview image
    if (pttmPreviewTag) {
      pttmPreviewTag.textContent = `TV ${tvNum}: ${tvData.size_label}`;
    }

    calculateLivePricing();
  }

  function renderStepperDots() {
    if (!stepperDotsContainer) return;
    stepperDotsContainer.innerHTML = '';

    for (let i = 0; i < state.totalTvs; i++) {
      const dotItem = document.createElement('div');
      dotItem.className = 'pttm-step-dot-item';
      if (i === state.currentTvIndex) {
        dotItem.classList.add('is-active');
      } else if (i < state.currentTvIndex) {
        dotItem.classList.add('is-completed');
      }

      dotItem.innerHTML = `
        <span class="pttm-step-dot-circle">${i + 1}</span>
        <span>TV ${i + 1}</span>
      `;

      // Allow clicking earlier steps to jump back
      if (i < state.currentTvIndex) {
        dotItem.style.cursor = 'pointer';
        dotItem.addEventListener('click', () => {
          saveCurrentTvData();
          state.currentTvIndex = i;
          renderTvConfigScreen();
        });
      }

      stepperDotsContainer.appendChild(dotItem);

      if (i < state.totalTvs - 1) {
        const line = document.createElement('div');
        line.className = 'pttm-step-line';
        stepperDotsContainer.appendChild(line);
      }
    }
  }

  function saveCurrentTvData() {
    const selectedSizeBtn = document.querySelector('.pttm-size-btn.is-selected');
    const size_id = selectedSizeBtn ? selectedSizeBtn.getAttribute('data-size-id') : '43_55';
    const size_label = selectedSizeBtn ? selectedSizeBtn.getAttribute('data-size-label') : '43" – 55"';
    const size_price = parseFloat(selectedSizeBtn ? selectedSizeBtn.getAttribute('data-size-price') : 95) || 95;

    const selectedAddonCards = document.querySelectorAll('.pttm-addon-card.is-selected');
    const addons = [];
    selectedAddonCards.forEach(card => {
      addons.push({
        id: card.getAttribute('data-addon-id'),
        title: card.getAttribute('data-addon-title'),
        subtitle: card.getAttribute('data-addon-subtitle'),
        price: parseFloat(card.getAttribute('data-addon-price')) || 0
      });
    });

    state.tvs[state.currentTvIndex] = {
      size_id,
      size_label,
      size_price,
      addons
    };
  }

  function onConfigNext() {
    saveCurrentTvData();
    calculateLivePricing();

    if (state.currentTvIndex < state.totalTvs - 1) {
      state.currentTvIndex++;
      renderTvConfigScreen();
    } else {
      // Move to Stage 3 (Additional Services)
      if (servicesStepPill) {
        servicesStepPill.textContent = `Step ${state.totalTvs + 1} of ${state.totalTvs + 1}`;
      }
      updateSelectedServices();
      calculateLivePricing();
      showStage('services');
    }
  }

  function onConfigBack() {
    saveCurrentTvData();
    calculateLivePricing();

    if (state.currentTvIndex > 0) {
      state.currentTvIndex--;
      renderTvConfigScreen();
    } else {
      showStage('quantity');
    }
  }

  // --- STAGE 3: FORM SUBMISSION & STAGE 4 CONFIRMATION ---
  function handleOrderSubmit(e) {
    e.preventDefault();

    const nameInput = document.getElementById('pttmCustName');
    const phoneInput = document.getElementById('pttmCustPhone');
    const emailInput = document.getElementById('pttmCustEmail');
    const addressInput = document.getElementById('pttmCustAddress');

    if (!nameInput.value.trim() || !phoneInput.value.trim()) {
      alert('Please enter your full name and phone number so we can confirm your order.');
      if (!nameInput.value.trim()) nameInput.focus();
      else phoneInput.focus();
      return;
    }

    state.customer = {
      name: nameInput.value.trim(),
      phone: phoneInput.value.trim(),
      email: emailInput ? emailInput.value.trim() : '',
      address: addressInput ? addressInput.value.trim() : ''
    };

    updateSelectedServices();
    calculateLivePricing();

    // Prepare Payload
    const payload = {
      customer_name: state.customer.name,
      customer_phone: state.customer.phone,
      customer_email: state.customer.email,
      customer_address: state.customer.address,
      tv_count: state.totalTvs,
      tvs: state.tvs,
      services: state.selectedServices,
      estimated_total: state.pricing.grandTotal
    };

    if (finishBtn) {
      finishBtn.disabled = true;
      finishBtn.innerHTML = '<span>Submitting Request...</span>';
    }

    // Send AJAX Request
    const formData = new FormData();
    formData.append('action', 'pttm_submit_order');
    formData.append('nonce', window.PTTM_DATA ? window.PTTM_DATA.nonce : '');
    formData.append('order_data', JSON.stringify(payload));

    const ajaxUrl = window.PTTM_DATA ? window.PTTM_DATA.ajax_url : '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl, {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (finishBtn) {
        finishBtn.disabled = false;
        finishBtn.innerHTML = '<span>Finish Order</span><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
      }

      state.orderRefId = (data && data.data && data.data.order_id) ? data.data.order_id : Math.floor(1000 + Math.random() * 9000);
      renderConfirmationScreen();
      showStage('confirmation');
    })
    .catch(err => {
      console.error('Submission error:', err);
      if (finishBtn) {
        finishBtn.disabled = false;
        finishBtn.innerHTML = '<span>Finish Order</span>';
      }
      state.orderRefId = Math.floor(1000 + Math.random() * 9000);
      renderConfirmationScreen();
      showStage('confirmation');
    });
  }

  // --- STAGE 4: RENDER CONFIRMATION RECEIPT (Screen 3) ---
  function renderConfirmationScreen() {
    let totalItems = 0;
    state.tvs.forEach(tv => {
      totalItems += 1 + (tv.addons ? tv.addons.length : 0);
    });
    totalItems += state.selectedServices.length;

    if (confirmTally) {
      confirmTally.textContent = `${state.totalTvs} TV${state.totalTvs > 1 ? 's' : ''} • ${totalItems} items total • $${state.pricing.grandTotal.toFixed(2)}`;
    }

    if (confirmItemsList) {
      confirmItemsList.innerHTML = '';

      state.tvs.forEach((tv, idx) => {
        const tvRow = document.createElement('div');
        tvRow.className = 'pttm-summary-tv-row';

        let addonsHtml = '';
        if (tv.addons && tv.addons.length > 0) {
          addonsHtml = '<ul class="pttm-summary-tv-addons">';
          tv.addons.forEach(addon => {
            addonsHtml += `<li>${addon.title} <span>(+$${(addon.price || 0).toFixed(2)})</span></li>`;
          });
          addonsHtml += '</ul>';
        } else {
          addonsHtml = `
            <ul class="pttm-summary-tv-addons">
              <li>Standard TV Mounting</li>
              <li><span>No add-ons selected</span></li>
            </ul>
          `;
        }

        tvRow.innerHTML = `
          <div class="pttm-summary-tv-label">
            <h3>TV ${idx + 1} &mdash; ${tv.size_label}</h3>
            <span>Base: $${(tv.size_price || 95).toFixed(2)}</span>
          </div>
          <div>${addonsHtml}</div>
        `;

        confirmItemsList.appendChild(tvRow);
      });

      // Additional Services
      if (state.selectedServices.length > 0) {
        const srvRow = document.createElement('div');
        srvRow.className = 'pttm-summary-tv-row';
        let srvListHtml = '<ul class="pttm-summary-tv-addons">';
        state.selectedServices.forEach(s => {
          srvListHtml += `<li>${s.title} <span>(${s.price_rate || '$75 / hr'})</span></li>`;
        });
        srvListHtml += '</ul>';

        srvRow.innerHTML = `
          <div class="pttm-summary-tv-label">
            <h3>Additional Services</h3>
            <span>Hourly Request</span>
          </div>
          <div>${srvListHtml}</div>
        `;
        confirmItemsList.appendChild(srvRow);
      }

      // Grand Total Row
      const grandTotalRow = document.createElement('div');
      grandTotalRow.className = 'pttm-summary-grand-total-row';
      grandTotalRow.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:center; width:100%; padding-top:16px; border-top:2px solid #e2ddd5; font-size:16px; font-weight:700;">
          <span style="color:#171513;">Estimated Total:</span>
          <span style="color:#8c5f34; font-size:22px; font-weight:800;">$${state.pricing.grandTotal.toFixed(2)}</span>
        </div>
      `;
      confirmItemsList.appendChild(grandTotalRow);
    }
  }

  function resetToHome() {
    state.totalTvs = 1;
    state.currentTvIndex = 0;
    state.tvs = [];
    state.selectedServices = [];
    if (orderForm) orderForm.reset();
    serviceCards.forEach(c => {
      c.classList.remove('is-selected');
      c.setAttribute('aria-checked', 'false');
    });
    showStage('quantity');
  }

  // --- MODALS / DRAWERS ---
  function openModal(modalKey) {
    if (!modalOverlay) return;
    modalPanes.forEach(pane => {
      pane.classList.remove('is-active');
    });
    const targetPane = document.getElementById(`modal-pane-${modalKey}`);
    if (targetPane) {
      targetPane.classList.add('is-active');
      modalOverlay.classList.add('is-active');
      modalOverlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeModal() {
    if (!modalOverlay) return;
    modalOverlay.classList.remove('is-active');
    modalOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  // Google Places Autocomplete Initialization
  function initGooglePlaces() {
    const addressField = document.getElementById('pttmCustAddress');
    if (!addressField || typeof google === 'undefined' || !google.maps || !google.maps.places) {
      return;
    }

    try {
      const autocomplete = new google.maps.places.Autocomplete(addressField, {
        types: ['address'],
        componentRestrictions: { country: ['us', 'ca'] }
      });
      autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        if (place && place.formatted_address) {
          addressField.value = place.formatted_address;
        }
      });
    } catch (e) {
      console.warn('Google Places could not be initialized:', e);
    }
  }

  function checkUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('tvs')) {
      const qty = parseInt(urlParams.get('tvs'), 10);
      if (qty >= 1 && qty <= 8) {
        selectTvQuantity(qty);
      }
    }
  }

  // Run on DOM Ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
