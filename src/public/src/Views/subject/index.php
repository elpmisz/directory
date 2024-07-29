<?php
$menu = "setting";
$page = "setting-subject";
include_once(__DIR__ . "/../layout/header.php");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">สมรรถนะ</h4>
      </div>
      <div class="card-body">

        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <a href="/subject/export" class="btn btn-success btn-sm btn-block">
              <i class="fas fa-download pr-2"></i>นำข้อมูลออก
            </a>
          </div>
          <div class="col-xl-3 mb-2">
            <button class="btn btn-info btn-sm btn-block import-btn" data-toggle="modal" data-target="#import-modal">
              <i class="fas fa-upload pr-2"></i>นำข้อมูลเข้า
            </button>
          </div>
          <div class="col-xl-3 mb-2">
            <a href="/subject/create" class="btn btn-primary btn-sm btn-block">
              <i class="fas fa-plus pr-2"></i>เพิ่ม
            </a>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-xl-12">
            <div>
              <table class="table table-bordered table-hover data">
                <thead>
                  <tr>
                    <th width="10%">#</th>
                    <th width="10%">รหัส</th>
                    <th width="70%">รายชื่อ</th>
                    <th width="10%">ประเภท</th>
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
        <form action="/subject/import" method="POST" enctype="multipart/form-data" class="needs-validation import" novalidate>

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

  function filter_datatable() {
    let datatable = $(".data").DataTable({
      scrollX: true,
      serverSide: true,
      searching: true,
      order: [],
      ajax: {
        url: "/subject/data",
        type: "POST",
      },
      columnDefs: [{
          targets: [0, 1, 3],
          className: "text-center",
        },
        {
          targets: [2],
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
  };

  $(document).on("click", ".btn-delete", function(e) {
    let uuid = ($(this).prop("id") ? $(this).prop("id") : "");

    e.preventDefault();
    Swal.fire({
      title: "ยืนยันที่จะทำรายการ?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ปิด",
    }).then((result) => {
      if (result.value) {
        axios.post("/subject/delete", {
          uuid: uuid
        }).then((res) => {
          let result = res.data;
          if (parseInt(result) === 200) {
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