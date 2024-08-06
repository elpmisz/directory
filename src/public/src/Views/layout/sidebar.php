<?php
$home = (isset($menu) && ($menu === "home") ? "show" : "");
$home_index = ($page === "home-index" ? 'class="active"' : "");

$service_menu = (isset($menu) && ($menu === "service") ? "show" : "");
$service_directory = ($page === "service-directory" ? 'class="active"' : "");
$service_suggestion = ($page === "service-suggestion" ? 'class="active"' : "");

$user_menu = (isset($menu) && ($menu === "user") ? "show" : "");
$user_profile = ($page === "user-profile" ? 'class="active"' : "");
$user_change = ($page === "user-change" ? 'class="active"' : "");

$setting_menu = (isset($menu) && ($menu === "setting") ? "show" : "");
$setting_system = ($page === "setting-system" ? 'class="active"' : "");
$setting_user = ($page === "setting-user" ? 'class="active"' : "");
$setting_subject = ($page === "setting-subject" ? 'class="active"' : "");
$setting_group = ($page === "setting-group" ? 'class="active"' : "");
$setting_field = ($page === "setting-field" ? 'class="active"' : "");
$setting_department = ($page === "setting-department" ? 'class="active"' : "");
$setting_zone = ($page === "setting-zone" ? 'class="active"' : "");
$setting_branch = ($page === "setting-branch" ? 'class="active"' : "");
$setting_position = ($page === "setting-position" ? 'class="active"' : "");
?>
<nav id="sidebar">
  <ul class="list-unstyled <?php echo $home ?>">
    <li <?php echo $home_index ?>>
      <a href="/home">รายงาน</a>
    </li>
    <li>
      <a href="#user-menu" data-toggle="collapse" class="dropdown-toggle">ข้อมูลส่วนตัว</a>
      <ul class="collapse list-unstyled <?php echo $user_menu ?>" id="user-menu">
        <li <?php echo $user_profile ?>>
          <a href="/user/profile">
            <i class="fa fa-address-book pr-2"></i>
            รายละเอียด
          </a>
        </li>
        <li <?php echo $user_change ?>>
          <a href="/user/change">
            <i class="fa fa-key pr-2"></i>
            เปลี่ยนรหัสผ่าน
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#service-menu" data-toggle="collapse" class="dropdown-toggle">
        บริการ
      </a>
      <ul class="collapse list-unstyled <?php echo $service_menu ?>" id="service-menu">
        <li <?php echo $service_directory ?>>
          <a href="/directory">
            <i class="fa fa-bars pr-2"></i>
            Directory
          </a>
        </li>
        <li <?php echo $service_suggestion ?>>
          <a href="/suggestion">
            <i class="fa fa-bars pr-2"></i>
            ข้อเสนอแนะ
          </a>
        </li>
      </ul>
    </li>
    <?php if (intval($user['level']) === 9) : ?>
      <li>
        <a href="#setting-menu" data-toggle="collapse" class="dropdown-toggle">ตั้งค่า</a>
        <ul class="collapse list-unstyled <?php echo $setting_menu ?>" id="setting-menu">
          <li <?php echo $setting_system ?>>
            <a href="/system">
              <i class="fa fa-gear pr-2"></i>
              ตั้งค่าระบบ
            </a>
          </li>
          <li <?php echo $setting_user ?>>
            <a href="/user">
              <i class="fa fa-gear pr-2"></i>
              ผู้ใช้งาน
            </a>
          </li>
          <li <?php echo $setting_subject ?>>
            <a href="/subject">
              <i class="fa fa-gear pr-2"></i>
              สมรรถนะ
            </a>
          </li>
          <li <?php echo $setting_group ?>>
            <a href="/group">
              <i class="fa fa-gear pr-2"></i>
              กลุ่มงาน
            </a>
          </li>
          <li <?php echo $setting_field ?>>
            <a href="/field">
              <i class="fa fa-gear pr-2"></i>
              สายงาน
            </a>
          </li>
          <li <?php echo $setting_department ?>>
            <a href="/department">
              <i class="fa fa-gear pr-2"></i>
              ฝ่าย/ภาค
            </a>
          </li>
          <li <?php echo $setting_zone ?>>
            <a href="/zone">
              <i class="fa fa-gear pr-2"></i>
              ส่วน/เขต
            </a>
          </li>
          <li <?php echo $setting_branch ?>>
            <a href="/branch">
              <i class="fa fa-gear pr-2"></i>
              หน่วย/สาขา
            </a>
          </li>
          <li <?php echo $setting_position ?>>
            <a href="/position">
              <i class="fa fa-gear pr-2"></i>
              ตำแหน่ง
            </a>
          </li>
        </ul>
      </li>
    <?php endif; ?>
  </ul>
</nav>