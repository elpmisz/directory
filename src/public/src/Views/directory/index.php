<?php
$menu = "service";
$page = "service-directory";
include_once(__DIR__ . "/../layout/header.php");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">Directory</h4>
      </div>
      <div class="card-body">

        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm group-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <a href="javascript:void(0)" class="btn btn-success btn-sm btn-block export-btn">
              <i class="fas fa-download pr-2"></i>นำข้อมูลออก
            </a>
          </div>
          <div class="col-xl-3 mb-2">
            <button class="btn btn-info btn-sm btn-block import-btn" data-toggle="modal" data-target="#import-modal">
              <i class="fas fa-upload pr-2"></i>นำข้อมูลเข้า
            </button>
          </div>
          <div class="col-xl-3 mb-2">
            <a href="/directory/create" class="btn btn-primary btn-sm btn-block">
              <i class="fas fa-plus pr-2"></i>เพิ่ม
            </a>
          </div>
        </div>
        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm primary-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm subject-select"></select>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-xl-12">
            <div>
              <table class="table table-bordered table-hover data">
                <thead>
                  <tr>
                    <th width="10%">#</th>
                    <th width="10%">E-Mail</th>
                    <th width="10%">กลุ่ม</th>
                    <th width="10%">สาย</th>
                    <th width="10%">ฝ่าย/ภาค</th>
                    <th width="10%">ส่วน/เขต</th>
                    <th width="10%">หน่วย/สาขา</th>
                    <th width="20%">ตำแหน่ง</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="import-modal" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header mx-auto">
        <h5 class="modal-title">นำข้อมูลเข้า</h5>
      </div>
      <div class="modal-body">
        <form action="/directory/import" method="POST" enctype="multipart/form-data" class="needs-validation import" novalidate>

          <div class="row mb-2 justify-content-center">
            <div class="col-sm-10">
              <input type="file" name="file" accept=".xls, .xlsx, .csv" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2 justify-content-center">
            <div class="col-xl-4 mb-2">
              <button type="submit" class="btn btn-success btn-sm btn-block">
                <i class="fa fa-check mr-2"></i>ยืนยัน
              </button>
            </div>
            <div class="col-xl-4 mb-2">
              <button type="button" class="btn btn-danger btn-sm btn-block" data-dismiss="modal">
                <i class="fa fa-times mr-2"></i>ปิด
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="process-modal" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <h1 class="text-center"><span class="pr-5">Processing...</span><i class="fas fa-spinner fa-pulse"></i></h1>
      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  filter_datatable();

  $(document).on("change", ".group-select, .primary-select, .subject-select", function() {
    let group = ($(".group-select").val() ? $(".group-select").val() : "");
    let primary = ($(".primary-select").val() ? $(".primary-select").val() : "");
    let subject = ($(".subject-select").val() ? $(".subject-select").val() : "");

    if (group || primary || subject) {
      $(".data").DataTable().destroy();
      filter_datatable(group, primary, subject);
    } else {
      $(".data").DataTable().destroy();
      filter_datatable();
    }
  });

  function filter_datatable(group, primary, subject) {
    let datatable = $(".data").DataTable({
      scrollX: true,
      serverSide: true,
      searching: true,
      order: false,
      ajax: {
        url: "/directory/data",
        type: "POST",
        data: {
          group: group,
          primary: primary,
          subject: subject,
        }
      },
      columnDefs: [{
          targets: [0],
          className: "text-center",
        },
        {
          targets: [1, 2, 3, 4, 5, 6, 7],
          className: "text-left",
        }
      ],
      "oLanguage": {
        "sLengthMenu": "แสดง _MENU_ ลำดับ ต่อหน้า",
        "sZeroRecords": "ไม่พบข้อมูลที่ค้นหา",
        "sInfo": "แสดง _START_ ถึง _END_ ของ _TOTAL_ ลำดับ",
        "sInfoEmpty": "แสดง 0 ถึง 0 ของ 0 ลำดับ",
        "sInfoFiltered": "",
        "sSearch": "ค้นหา :",
        "oPaginate": {
          "sFirst": "หน้าแรก",
          "sLast": "หน้าสุดท้าย",
          "sNext": "ถัดไป",
          "sPrevious": "ก่อนหน้า"
        }
      },
    });
  }

  $("#import-modal").on("hidden.bs.modal", function() {
    $(this).find("form")[0].reset();
  })

  $(document).on("change", "input[name='file']", function() {
    let fileSize = ($(this)[0].files[0].size) / (1024 * 1024);
    let fileExt = $(this).val().split(".").pop().toLowerCase();
    let fileAllow = ["xls", "xlsx", "csv"];
    let convFileSize = fileSize.toFixed(2);
    if (convFileSize > 10) {
      Swal.fire({
        icon: "error",
        title: "LIMIT 10MB!",
      })
      $(this).val("");
    }

    if ($.inArray(fileExt, fileAllow) == -1) {
      Swal.fire({
        icon: "error",
        title: "เฉพาะเอกสารนามสกุล XLS XLSX CSV!",
      })
      $(this).val("");
    }
  });

  $(document).on("submit", ".import", function() {
    $("#import-modal").modal("hide");
    $("#process-modal").modal("show");
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

  $(document).on("click", ".export-btn", function() {
    let group = ($(".group-select").val() ? parseInt($(".group-select").val()) : "");
    if (!group) {
      Swal.fire({
        icon: "error",
        title: "กรุณาเลือก กลุ่มงาน!",
      });
    } else {
      let path = "/directory/export/" + group;
      window.open(path);
    }
  });
</script>