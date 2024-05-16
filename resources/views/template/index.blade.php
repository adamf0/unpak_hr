<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" /> -->
  <title>SIPAKSI</title>
  <link href="{{ Utility::loadAsset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ Utility::loadAsset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ Utility::loadAsset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" />
  <link rel="stylesheet" href="{{ Utility::loadAsset('assets/css/yearpicker.css') }}" />
  <link rel="stylesheet" href="{{ Utility::loadAsset('assets/css/datepicker.css') }}" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <style>
    .offset-x {
      margin-left: 3.33333333%;
      margin-top: 1%;
    }

    .select2-container {
      width: 100% !important;
    }
  </style>
  <style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      padding: 0 !important;
    }

    .dataTables_wrapper .dataTables_filter {
      margin-right: 0.8em !important
    }

    table.dataTable {
      width: 100% !important;
    }

    .input-disabled {
      background-color: #e9ecef;
      opacity: 1;
    }

    .input-disabled:focus {
      color: #212529;
      background-color: #e9ecef;
      opacity: 1;
      border-color: #ced4da;
      outline: 0;
      box-shadow: 0 0 0 0.25rem transparent;
    }

    .circle-tab-container {
      display: flex;
      align-items: center;
    }

    .circle-tab-container-box {
      display: flex;
      align-items: center;
      flex-direction: column;
    }

    .circle-tab {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      text-align: center;
      padding-top: 8px;
      margin-right: 10px;
      font-weight: bold;
      border: 2px solid #ccc;
      background-color: #fff;
      color: #000;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .circle-tab,
    .step-label {
      display: block;
      text-align: center;
      font-size: 12px;
    }

    .circle-tab.active {
      background-color: #007bff;
      color: #fff;
      border-color: #007bff;
    }

    .line {
      flex: 1;
      border-top: 2px solid #ccc;
    }

    .btn-draf {
      --bs-btn-color: #000;
      --bs-btn-bg: white;
      --bs-btn-border-color: #000;
      --bs-btn-hover-color: #000;
      --bs-btn-hover-bg: white;
      --bs-btn-hover-border-color: white;
      --bs-btn-focus-shadow-rgb: 130, 138, 145;
      --bs-btn-active-color: #000;
      --bs-btn-active-bg: white;
      --bs-btn-active-border-color: #000;
      --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
      --bs-btn-disabled-color: #fff;
      --bs-btn-disabled-bg: white;
      --bs-btn-disabled-border-color: white;
    }
  </style>
</head>

<body>
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="{{route('dashboard.index')}}" class="logo d-flex align-items-center">
        <x-img path="{{ Utility::loadAsset('assets/img/logo.webp') }}" islazy="true"></x-img>
        <span class="d-none d-lg-block">SIPAKSI</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> -->
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <!-- <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li> -->
        <!-- <x-top-nav-menu-dropdown></x-top-nav-menu-dropdown> -->
        @if(Utility::hasMultiLevel())
        <li class="nav-item pe-3">
          <a class="nav-link nav-mode d-flex align-items-center pe-0" href="#">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{Utility::getLevel()}}</span>
          </a>
        </li>
        @endif
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <x-img path="{{ Utility::loadAsset('assets/img/logo.webp') }}" alt='Profile' class="rounded-circle" :islazy="true"></x-img>
            <span class="d-none d-md-block dropdown-toggle ps-2">{{Utility::getName()}}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{Utility::getName()}}</h6>
              <span>{{Utility::getLevel()}}</span>
            </li>
            {{-- <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li> --}}
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{route('auth.logout')}}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <x-sidebar-item-menu title="Dashboard" icon="bi bi-menu-button-wide" link="{{route('dashboard.index')}}" :active="Utility::stateMenu(['dashboard'],request())" />

      <!-- <li class="nav-heading">Form Pengajuan</li> -->

      <x-sidebar-item-menu title="Cuti" icon="bi bi-menu-button-wide" link="{{route('cuti.index')}}" :active="Utility::stateMenu(['cuti'],request())" />
      <x-sidebar-item-menu title="JenisCuti" icon="bi bi-menu-button-wide" link="{{route('jenis_cuti.index')}}" :active="Utility::stateMenu(['jenis_cuti'],request())" />

      <x-sidebar-item-menu title="Izin" icon="bi bi-menu-button-wide" link="{{route('izin.index')}}" :active="Utility::stateMenu(['izin'],request())" />
      <x-sidebar-item-menu title="JenisIzin" icon="bi bi-menu-button-wide" link="{{route('jenis_izin.index')}}" :active="Utility::stateMenu(['jenis_izin'],request())" />

      <x-sidebar-item-menu title="Lupa Absen Keluar" icon="bi bi-menu-button-wide" link="{{route('dashboard.index')}}" :active="Utility::stateMenu([''],request())" />
      <x-sidebar-item-menu title="SPPD" icon="bi bi-menu-button-wide" link="{{route('dashboard.index')}}" :active="Utility::stateMenu([''],request())" />
      <x-sidebar-item-menu title="Lpaoran Absen" icon="bi bi-menu-button-wide" link="{{route('dashboard.index')}}" :active="Utility::stateMenu([''],request())" /> 

      <!-- <li class="nav-heading">PAGES</li> -->
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    @yield('page-title')
    <section class="section dashboard">
      @yield('content')
    </section>
  </main>

  @if(Utility::hasMultiLevel())
  <div class="modal fade" id="modalChangeMode" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Peran Sebagai</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            @foreach (Utility::getLevels() as $level)
            <div class="col-6">
              <a href="{{route('changeMode',['mode'=>$level,'url'=>request()->route()->getName()])}}" class="btn btn-primary d-md-block" id="mode{{ucfirst($level)}}">{{ucfirst($level)}}</a>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  <!-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> -->
  <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
  <script src="{{ Utility::loadAsset('assets/js/main.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ Utility::loadAsset('assets/js/yearpicker.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  @stack('scripts')
  <script>
    @if(Utility::hasMultiLevel())
    let modalChangeMode = new bootstrap.Modal(document.getElementById('modalChangeMode'));
    let btnDosen = document.getElementById('modeDosen');
    let btnReviewer = document.getElementById('modeReviewer');
    let btnChangeMode = $('.nav-mode');

    btnChangeMode.on('click', function(e) {
      e.preventDefault();
      modalChangeMode.show();
    });
    @endif

    $(".yearpicker").yearpicker();
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlidht: true,
      orientation: 'top'
    }).on('show', function(e) {
      // Mengatur posisi popover Datepicker ke center (middle).
      var $input = $(e.currentTarget);
      var $datepicker = $input.data('datepicker').picker;
      var $parent = $input.parent();
      var top = ($parent.offset().top - $datepicker.outerHeight()) + $parent.outerHeight();
      $datepicker.css({
        top: top,
        left: $parent.offset().left
      });
    });

    $('.datepicker-bottom').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlidht: true,
      orientation: 'bottom'
    }).on('show', function(e) {
      // Mengatur posisi popover Datepicker ke center (middle).
      var $input = $(e.currentTarget);
      var $datepicker = $input.data('datepicker').picker;
      var $parent = $input.parent();
      var bottom = ($parent.offset().bottom - $datepicker.outerHeight()) + $parent.outerHeight();
      $datepicker.css({
        bottom: bottom,
        left: $parent.offset().left
      });
    });
  </script>
</body>

</html>