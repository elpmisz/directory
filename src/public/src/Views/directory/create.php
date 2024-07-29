<?php
$menu = "service";
$page = "service-directory";
include_once(__DIR__ . "/../layout/header.php");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">เพิ่ม</h4>
      </div>
      <div class="card-body">
        <form action="/directory/create" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">E-Mail</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="email" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">กลุ่มงาน</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm group-select" name="group_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">สายงาน</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm field-select" name="field_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ฝ่าย/ภาค</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm department-select" name="department_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ส่วน/เขต</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm zone-select" name="zone_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">หน่วย/สาขา</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm branch-select" name="branch_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ตำแหน่ง</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm position-select" name="position_id" required></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-sm-11">
              <div class="table-responsive">
                <table class="table table-bordered table-sm subject-table">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th width="40%">สมรรถนะ</th>
                      <th width="50%">รายวิชา</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="item-tr">
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-success item-increase">+</button>
                        <button type="button" class="btn btn-sm btn-danger item-decrease">-</button>
                      </td>
                      <td class="text-left">
                        <select class="form-control form-control-sm primary-select" name="item_primary[]"></select>
                        <div class="invalid-feedback">
                          กรุณากรอกข้อมูล!
                        </div>
                      </td>
                      <td class="text-left">
                        <select class="form-control form-control-sm subject-select" name="item_subject[0][]" multiple></select>
                        <div class="invalid-feedback">
                          กรุณากรอกข้อมูล!
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-xl-3 mb-2">
              <button type="submit" class="btn btn-sm btn-success btn-block">
                <i class="fas fa-check pr-2"></i>ยืนยัน
              </button>
            </div>
            <div class="col-xl-3 mb-2">
              <a href="/directory" class="btn btn-sm btn-danger btn-block">
                <i class="fa fa-arrow-left pr-2"></i>กลับ
              </a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  var i = 1;
  $(".item-decrease").hide();
  $(document).on("click", ".item-increase", function() {
    $(".primary-select, .subject-select").select2('destroy');
    let row = $(".item-tr:last");
    let clone = row.clone();
    clone.find("input, select, textarea, span").val("").empty();
    clone.find("input, select").each(function() {
      this.name = this.name.replace(/\[(\d+)\]/, function(str, count) {
        return '[' + (parseInt(count, 10) + i) + ']'
      });
    });
    clone.find(".item-increase").hide();
    clone.find(".item-decrease").show();
    clone.find(".item-decrease").on("click", function() {
      $(this).closest("tr").remove();
    });
    row.after(clone);
    clone.show();

    $(".primary-select").select2({
      placeholder: "-- สมรรถนะ --",
      allowClear: true,
      width: "100%",
      ajax: {
        url: "/directory/primary-select",
        method: "POST",
        dataType: "json",
        delay: 100,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    $(".subject-select").select2({
      placeholder: "-- รายวิชา --",
      allowClear: true,
      width: "100%",
      ajax: {
        url: "/directory/subject-select",
        method: "POST",
        dataType: "json",
        delay: 100,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  });

  $(".group-select").select2({
    placeholder: "-- กลุ่มงาน --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/group-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".field-select").select2({
    placeholder: "-- สายงาน --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/field-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".department-select").select2({
    placeholder: "-- ฝ่าย/ภาค --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/department-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".zone-select").select2({
    placeholder: "-- ส่วน/เขต --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/zone-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".branch-select").select2({
    placeholder: "-- หน่วย/สาขา --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/branch-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".position-select").select2({
    placeholder: "-- ตำแหน่ง --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/position-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".primary-select").select2({
    placeholder: "-- สมรรถนะ --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/primary-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });

  $(".subject-select").select2({
    placeholder: "-- รายวิชา --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/subject-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
</script>