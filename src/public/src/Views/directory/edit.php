<?php
$menu = "service";
$page = "service-directory";
include_once(__DIR__ . "/../layout/header.php");
$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Directory;

$DIRECTORY = new Directory();
$row = $DIRECTORY->directory_view([$uuid]);
$primary = $DIRECTORY->primary_view([$row['group_id']]);
?>
<style>
  .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: grid;
  }
</style>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายละเอียด</h4>
      </div>
      <div class="card-body">
        <form action="/directory/update" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">ID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="id" value="<?php echo $row['id'] ?>" readonly>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">UUID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="uuid" value="<?php echo $row['uuid'] ?>" readonly>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">E-Mail</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="email" value="<?php echo $row['email'] ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">กลุ่มงาน</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm group-select" name="group_id" required>
                <?php
                if (!empty($row['group_name'])) {
                  echo '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">สายงาน</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm field-select" name="field_id" required>
                <?php
                if (!empty($row['field_name'])) {
                  echo '<option value="' . $row['field_id'] . '">' . $row['field_name'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ฝ่าย/ภาค</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm department-select" name="department_id" required>
                <?php
                if (!empty($row['department_name'])) {
                  echo '<option value="' . $row['department_id'] . '">' . $row['department_name'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ส่วน/เขต</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm zone-select" name="zone_id">
                <?php
                if (!empty($row['zone_name'])) {
                  echo '<option value="' . $row['zone_id'] . '">' . $row['zone_name'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">หน่วย/สาขา</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm branch-select" name="branch_id">
                <?php
                if (!empty($row['branch_name'])) {
                  echo '<option value="' . $row['branch_id'] . '">' . $row['branch_name'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ตำแหน่ง</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm position-select" name="position_id" required>
                <?php
                if (!empty($row['position_name'])) {
                  echo '<option value="' . $row['position_id'] . '">' . $row['position_name'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-sm-11">
              <div class="">
                <table class="table table-bordered table-sm subject-table">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th width="45%">สมรรถนะ</th>
                      <th width="45%">รายวิชา</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($primary as $key => $pm) : ?>
                      <tr>
                        <td class="text-center">
                          <a href="javascript:void(0)" class="badge badge-danger font-weight-light item-delete" id="<?php echo $pm['id'] ?>">ลบ</a>
                          <input type="hidden" class="form-control form-control-sm text-center" name="item_key[]" value="<?php echo $pm['key'] ?>" readonly>
                          <input type="hidden" class="form-control form-control-sm text-center" name="item_primary[]" value="<?php echo $pm['subject_code'] ?>" readonly>
                        </td>
                        <td>
                          <?php echo $pm['subject_name'] ?>
                        </td>
                        <td>
                          <select class="form-control form-control-sm subject-select" style="width:500px;" name="item_subject[<?php echo $key ?>][]" multiple>
                            <?php
                            $subject = $DIRECTORY->subject_view([$row['branch_id'], $row['position_id'], $pm['key']]);
                            foreach ($subject as $sub) {
                              if (!empty($sub['subject_code'])) {
                                echo "<option value='{$sub['subject_code']}' selected>{$sub['subject_name']}</option>";
                              }
                            }
                            ?>
                          </select>
                          <div class="invalid-feedback">
                            กรุณากรอกข้อมูล!
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <tr class="item-tr">
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-success item-increase">+</button>
                        <button type="button" class="btn btn-sm btn-danger item-decrease">-</button>
                      </td>
                      <td class="text-left">
                        <select class="form-control form-control-sm primary-select" name="item__primary[]"></select>
                        <div class="invalid-feedback">
                          กรุณากรอกข้อมูล!
                        </div>
                      </td>
                      <td class="text-left">
                        <select class="form-control form-control-sm subject-select" name="item__subject[0][]" multiple></select>
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

          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">สถานะ</label>
            <div class="col-xl-8">
              <div class="row pt-2">
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="status" value="1" <?php echo (intval($row['status']) === 1 ? "checked" : "") ?> required>
                    <span class="text-success">ใช้งาน</span>
                  </label>
                </div>
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="status" value="2" <?php echo (intval($row['status']) === 2 ? "checked" : "") ?> required>
                    <span class="text-danger">ระงับการใช้งาน</span>
                  </label>
                </div>
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
    $(".item-select").select2('destroy');
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
      // width: "100%",
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
    // width: "100%",
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

  $(document).on("click", ".item-delete", function(e) {
    let id = ($(this).prop("id") ? $(this).prop("id") : "");
    e.preventDefault();
    Swal.fire({
      title: "ยืนยันที่จะทำรายการ?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ตกลง",
      cancelButtonText: "ปิด",
    }).then((result) => {
      if (result.value) {
        axios.post("/directory/primary-delete", {
          id: id,
        }).then((res) => {
          let result = res.data;
          if (result === 200) {
            location.reload()
          } else {
            location.reload()
          }
        }).catch((error) => {
          console.log(error);
        });
      } else {
        return false;
      }
    })
  });
</script>