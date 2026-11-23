/**
 * Prime Time TV Mounting — Admin Settings Javascript with Dynamic Row Addition & Media Uploader
 */

jQuery(document).ready(function ($) {
  'use strict';

  // 1. WordPress Media Uploader Integration
  $(document).on('click', '.pttm-media-upload-btn, .pttm-upload-image-btn', function (e) {
    e.preventDefault();
    const button = $(this);
    const targetInputId = button.attr('data-target');
    const targetPreviewId = button.attr('data-preview');

    const targetInput = targetInputId ? $('#' + targetInputId) : $(button.data('target-input'));
    const targetPreview = targetPreviewId ? $('#' + targetPreviewId) : $(button.data('target-preview'));

    const customUploader = wp.media({
      title: 'Select or Upload Image / Icon',
      button: {
        text: 'Use this Image'
      },
      multiple: false
    });

    customUploader.on('select', function () {
      const attachment = customUploader.state().get('selection').first().toJSON();
      if (targetInput.length) {
        targetInput.val(attachment.url).trigger('change');
      }
      if (targetPreview.length) {
        targetPreview.attr('src', attachment.url);
      }
    });

    customUploader.open();
  });

  // 2. Remove Item Card Row
  $(document).on('click', '.pttm-remove-item-btn', function (e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete this item? Remember to click "Save All Platform Changes" to apply.')) {
      $(this).closest('.pttm-item-card, tr').fadeOut(200, function () {
        $(this).remove();
      });
    }
  });

  // 3. Dynamic + Add New TV Add-on
  $('#pttmAddAddonBtn').on('click', function (e) {
    e.preventDefault();
    const list = $('#pttmAddonsList');
    const newIdx = Date.now();
    const defaultImg = window.pttmAdminDefaultImg || '';

    const html = `
      <div class="pttm-item-card pttm-newly-added" style="animation: pttmFadeIn 0.3s ease;">
        <div class="pttm-card-thumb">
          <img src="${defaultImg}" alt="New Add-on" id="pttm_addon_preview_${newIdx}">
        </div>
        <div class="pttm-card-fields" style="flex:1; display:grid; grid-template-columns: 2fr 2fr 1fr 2fr; gap:12px;">
          <div>
            <label><strong>Title:</strong></label>
            <input type="text" name="pttm_addons[${newIdx}][title]" value="" placeholder="e.g. Soundbar Bracket" class="widefat" required>
          </div>
          <div>
            <label><strong>Subtitle / Source:</strong></label>
            <input type="text" name="pttm_addons[${newIdx}][subtitle]" value="" placeholder="e.g. Supplied by PTTM" class="widefat">
          </div>
          <div>
            <label><strong>Price ($):</strong></label>
            <input type="number" step="0.01" name="pttm_addons[${newIdx}][price]" value="50.00" class="widefat" required>
          </div>
          <div>
            <label><strong>Image / Icon URL:</strong></label>
            <div style="display:flex; gap:6px;">
              <input type="text" name="pttm_addons[${newIdx}][image]" value="" class="widefat pttm-img-input" id="pttm_addon_img_${newIdx}" placeholder="Image URL">
              <button type="button" class="button pttm-media-upload-btn" data-target="pttm_addon_img_${newIdx}" data-preview="pttm_addon_preview_${newIdx}">Upload</button>
            </div>
          </div>
        </div>
        <div class="pttm-card-toggle" style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
          <label><input type="checkbox" name="pttm_addons[${newIdx}][enabled]" value="1" checked> Active</label>
          <button type="button" class="button button-link-delete pttm-remove-item-btn" title="Delete this add-on"><span class="dashicons dashicons-trash"></span> Remove</button>
        </div>
      </div>
    `;

    list.append(html);
  });

  // 4. Dynamic + Add New Hourly Service
  $('#pttmAddServiceBtn').on('click', function (e) {
    e.preventDefault();
    const list = $('#pttmServicesList');
    const newIdx = Date.now();
    const defaultImg = window.pttmAdminDefaultImg || '';

    const html = `
      <div class="pttm-item-card pttm-newly-added" style="animation: pttmFadeIn 0.3s ease;">
        <div class="pttm-card-thumb">
          <img src="${defaultImg}" alt="New Service" id="pttm_srv_preview_${newIdx}">
        </div>
        <div class="pttm-card-fields" style="flex:1; display:grid; grid-template-columns: 2fr 3fr 1fr 2fr; gap:12px;">
          <div>
            <label><strong>Service Name:</strong></label>
            <input type="text" name="pttm_services[${newIdx}][title]" value="" placeholder="e.g. Chandelier Installation" class="widefat" required>
          </div>
          <div>
            <label><strong>Description / Scope:</strong></label>
            <input type="text" name="pttm_services[${newIdx}][subtitle]" value="" placeholder="e.g. Secure ceiling wiring and mounting" class="widefat">
          </div>
          <div>
            <label><strong>Hourly Rate Text:</strong></label>
            <input type="text" name="pttm_services[${newIdx}][price_rate]" value="$75 / hour" class="widefat">
          </div>
          <div>
            <label><strong>Image / Icon URL:</strong></label>
            <div style="display:flex; gap:6px;">
              <input type="text" name="pttm_services[${newIdx}][image]" value="" class="widefat pttm-img-input" id="pttm_srv_img_${newIdx}" placeholder="Image URL">
              <button type="button" class="button pttm-media-upload-btn" data-target="pttm_srv_img_${newIdx}" data-preview="pttm_srv_preview_${newIdx}">Upload</button>
            </div>
          </div>
        </div>
        <div class="pttm-card-toggle" style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
          <label><input type="checkbox" name="pttm_services[${newIdx}][enabled]" value="1" checked> Active</label>
          <button type="button" class="button button-link-delete pttm-remove-item-btn" title="Delete this service"><span class="dashicons dashicons-trash"></span> Remove</button>
        </div>
      </div>
    `;

    list.append(html);
  });

  // 5. Dynamic + Add New TV Size Bracket
  $('#pttmAddSizeBtn').on('click', function (e) {
    e.preventDefault();
    const tbody = $('#pttmSizesTableBody');
    const newIdx = Date.now();

    const html = `
      <tr class="pttm-newly-added">
        <td>
          <input type="text" name="pttm_tv_sizes[${newIdx}][label]" value="" placeholder="e.g. 85\" and Above" class="widefat" required>
        </td>
        <td>
          <input type="number" step="0.01" name="pttm_tv_sizes[${newIdx}][base_price]" value="125.00" class="widefat" required>
        </td>
        <td style="text-align:center;">
          <button type="button" class="button button-link-delete pttm-remove-item-btn"><span class="dashicons dashicons-trash"></span></button>
        </td>
      </tr>
    `;

    tbody.append(html);
  });

  // 6. Dynamic + Add New FAQ
  $('#pttmAddFaqBtn').on('click', function (e) {
    e.preventDefault();
    const list = $('#pttmFaqsList');
    const newIdx = Date.now();

    const html = `
      <div class="pttm-item-card pttm-newly-added" style="display:block; animation: pttmFadeIn 0.3s ease;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
          <label><strong>Question:</strong></label>
          <button type="button" class="button button-link-delete pttm-remove-item-btn"><span class="dashicons dashicons-trash"></span> Remove</button>
        </div>
        <div style="margin-bottom:8px;">
          <input type="text" name="pttm_faqs[${newIdx}][question]" value="" placeholder="Type question here..." class="widefat" required>
        </div>
        <div>
          <label><strong>Answer:</strong></label>
          <textarea name="pttm_faqs[${newIdx}][answer]" rows="3" placeholder="Type detailed answer here..." class="widefat" required></textarea>
        </div>
      </div>
    `;

    list.append(html);
  });

  // 7. Dynamic + Add New Review
  $('#pttmAddReviewBtn').on('click', function (e) {
    e.preventDefault();
    const list = $('#pttmReviewsList');
    const newIdx = Date.now();

    const html = `
      <div class="pttm-item-card pttm-newly-added" style="display:block; animation: pttmFadeIn 0.3s ease;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
          <label><strong>Customer Testimonial Text:</strong></label>
          <button type="button" class="button button-link-delete pttm-remove-item-btn"><span class="dashicons dashicons-trash"></span> Remove</button>
        </div>
        <div style="margin-bottom:8px;">
          <textarea name="pttm_reviews[${newIdx}][text]" rows="2" placeholder="e.g. Absolutely flawless TV mounting experience..." class="widefat" required></textarea>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div>
            <label>Author Name:</label>
            <input type="text" name="pttm_reviews[${newIdx}][author]" value="" placeholder="e.g. Sarah Jenkins" class="widefat" required>
          </div>
          <div>
            <label>Location / Badge:</label>
            <input type="text" name="pttm_reviews[${newIdx}][location]" value="Verified Customer" class="widefat">
          </div>
        </div>
      </div>
    `;

    list.append(html);
  });

});
