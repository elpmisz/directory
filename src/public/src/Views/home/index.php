<?php
$menu = "home";
$page = "home-index";
include_once(__DIR__ . "/../layout/header.php");

use App\Classes\Dashboard;

$DASHBOARD = new Dashboard();
$card = $DASHBOARD->dashboard_card();
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายงาน</h4>
      </div>
      <div class="card-body">

        <div class="row mb-2">
          <div class="col mb-2">
            <div class="card h-100 bg-primary text-white shadow card-summary" id="1">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['request']) ? $card['request'] : 0) ?></h3>
                <h5 class="text-right">รายการทั้งหมด</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-success text-white shadow card-summary" id="2">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['primary']) ? $card['primary'] : 0) ?></h3>
                <h5 class="text-right">สมรรถนะ</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-warning text-white shadow card-summary" id="3">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['subject']) ? $card['subject'] : 0) ?></h3>
                <h5 class="text-right">รายวิชา</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-danger text-white shadow card-summary" id="4">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['group']) ? $card['group'] : 0) ?></h3>
                <h5 class="text-right">กลุ่ม</h5>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col mb-2">
            <div class="card h-100 bg-primary text-white shadow card-summary" id="2">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['field']) ? $card['field'] : 0) ?></h3>
                <h5 class="text-right">สายงาน</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-success text-white shadow card-summary" id="3">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['department']) ? $card['department'] : 0) ?></h3>
                <h5 class="text-right">ฝ่าย/ภาค</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-warning text-white shadow card-summary" id="4">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['zone']) ? $card['zone'] : 0) ?></h3>
                <h5 class="text-right">ส่วน/เขต</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-danger text-white shadow card-summary" id="4">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['branch']) ? $card['branch'] : 0) ?></h3>
                <h5 class="text-right">หน่วย/สาขา</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card h-100 bg-info text-white shadow card-summary" id="1">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['position']) ? $card['position'] : 0) ?></h3>
                <h5 class="text-right">ตำแหน่ง</h5>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/../layout/footer.php"); ?>