<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?> 
<div id="wrapper">
  <div class="content">
    <div class="panel_s accounting-template">
      <div class="panel-body">
        <form id="repair-order-form">
          <div class="row">
            <!-- Customer Selection -->
            <div class="col-md-6">
              <div class="form-group mbot25 items-wrapper input-group-select">
                <div class="input-group input-group-select">
                  <div class="items-select-wrapper">
                    <label for="customer_id" class="control-label">Customer</label>
                    <select id="customer_id" name="customer_id" class="selectpicker ajax-search" data-width="100%" data-none-selected-text="Select a customer" data-live-search="true" data-type="customer">
                      <option value=""></option>
                      <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['userid']; ?>" data-subtext="<?= htmlspecialchars($c['company']); ?>">
                          <?= htmlspecialchars($c['company']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="input-group-btn">
                    <a href="#" class="btn btn-default" onclick="openCustomerDrawer()" title="Add New Customer">
                      <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Device Selection -->
            <div class="col-md-6">
              <div class="form-group mbot25">
                <label for="device_id" class="control-label">Device</label>
                <select id="device_id" name="device_id" class="selectpicker form-control" data-width="100%" data-live-search="true" data-none-selected-text="Select a device">
                  <option value=""></option>
                </select>
              </div>
              <div id="device-debug-list" class="mtop15">
                <h4>Device Debug Output:</h4>
                <ul id="device-list" style="list-style: disc; margin-left: 20px;"></ul>
              </div>
            </div>
          </div>

          <!-- Device Details -->
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="device_type">Device Type</label>
                <input type="text" id="device_type" name="device_type" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="make_model">Make / Model</label>
                <input type="text" id="make_model" name="make_model" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" id="serial_number" name="serial_number" class="form-control" readonly>
              </div>
            </div>
          </div>

          <!-- Other Fields -->
          <div class="form-group">
            <label>Device Password</label>
            <input type="password" name="device_password" class="form-control" />
            <label><input type="checkbox" name="no_password"> No Password Provided</label>
          </div>

          <?php echo render_textarea('issue_description', 'Issue Description'); ?>
          <?php echo render_textarea('technician_notes', 'Technician Notes'); ?>

          <div class="form-group">
            <label>Bench Fee: $30 (Auto-added)</label>
            <?php echo render_input('quoted_cost', 'Quoted Repair Cost ($)', '', 'number'); ?>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Save & Create Estimate + Task</button>
            <a href="<?php echo admin_url('repair_checkin/repair_checkin'); ?>" class="btn btn-default">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript Section -->
<script>
  $(function () {
    init_ajax_search('customer', '#customer_id.ajax-search');

    function fetchDevicesByClientId(clientId) {
      if (!clientId) return;

      $.ajax({
        url: admin_url + 'repair_checkin/get_customer_devices/' + clientId,
        type: 'GET',
        dataType: 'json',
        success: function (devices) {
          console.log("✅ Devices received:", devices);
          const $device = $('#device_id');
          $device.empty().append('<option value=""></option>');

          devices.forEach(d => {
            const displayName = d.name && d.name.trim().length
              ? d.name + ' - ' + (d.model_id !== '0' ? d.model_id : 'Unknown Model') + ' (' + d.serial_no + ')'
              : d.code + ' - ' + (d.model_id !== '0' ? d.model_id : 'Unknown Model') + ' (' + d.serial_no + ')';

            $device.append(
              $('<option>', {
                value: d.id,
                text: displayName,
                'data-type': d.code || '',
                'data-model': d.model_id || '',
                'data-serial': d.serial_no || ''
              })
            );
          });

          $device.selectpicker('refresh');
        },
        error: function (xhr, status, err) {
          console.error('❌ Device fetch failed:', err);
        }
      });
    }

    $('#customer_id').on('changed.bs.select', function () {
      const customerId = $(this).selectpicker('val');
      console.log("📡 Customer selected:", customerId);
      fetchDevicesByClientId(customerId);
    });

    $('#device_id').on('changed.bs.select', function () {
      const opt = $(this).find('option:selected');
      $('#device_type').val(opt.data('type') || '');
      $('#make_model').val(opt.data('model') || '');
      $('#serial_number').val(opt.data('serial') || '');
    });

    $('#repair-order-form').on('submit', function (e) {
      console.warn("🚫 Outer form submitted – allowed in production but blocked now for debugging.");
    });
  });
</script>

<?php init_tail(); ?>
<?php $this->load->view('partials/drawer_add_customer'); ?>
