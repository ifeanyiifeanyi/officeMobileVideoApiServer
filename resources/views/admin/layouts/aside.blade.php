@php
  $prefix = Request::route()->getPrefix();
  $route = Route::current()->getName();
@endphp
<style>
  
.active{
  background-image: url('https://img.freepik.com/free-vector/pink-purple-shades-wavy-background_23-2148897830.jpg') !important;
  background-position: center !important;
  background-size: cover !important;
  background-repeat: no-repeat !important;
  color: #fff !important;
}
</style>
<div class="min-height-400 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-dark navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#!" target="_blank">
        <img src="{{ asset('logo/logo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Admin Dashboard</span>
        <p class="text-info text-center">{{ ucwords(auth()->user()->username) }}</p>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-100 " id="sidenav-collapse-main" style="height: 100vh">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ $route == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'category.view' ? 'active' : '' }}" href="{{ route('category.view') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Movie Categories</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'payment.plan' ? 'active' : '' }}" href="{{ route('payment.plan') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Payment Plans</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'users.all' ? 'active' : '' }}" href="{{ route('users.all') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Users</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'videos' ? 'active' : '' }}" href="{{ route('videos') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Videos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'genre' ? 'active' : '' }}" href="{{ route('genre') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Genre</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'ratings' ? 'active' : '' }}" href="{{ route('ratings') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-pie-35 text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Video Rating</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'blog' ? 'active' : '' }}" href="{{ route('blog') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-pie-35 text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Blog</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{{ route('comments') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-pie-35 text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Blog Comments</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $route == 'parentcontrol' ? 'active' : '' }}" href="{{ route('parentcontrol') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-circle-08 text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Parental Controls</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>