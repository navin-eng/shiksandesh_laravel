@extends('backend.pages.layout.master')
@push('b-title', 'Dashboard')

@section('backend-content')
@include('sweetalert::alert')

@php
  $courses    = App\Models\Course::count();
  $teachers   = App\Models\Teacher::count();
  $events     = App\Models\Event::count();
  $notices    = App\Models\Notice::count();
  $banners    = App\Models\Banner::count();
  $galleries  = App\Models\Gallery::count();
  $admissions = App\Models\AdmissionEnquiry::count();

  // Generate last 6 months for chart
  $chartLabels = [];
  $chartData = [];
  for ($i = 5; $i >= 0; $i--) {
      $date = now()->subMonths($i);
      $chartLabels[] = $date->format('M');
      $chartData[] = App\Models\AdmissionEnquiry::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count();
  }
@endphp

<div class="admin-page-header">
  <div>
    <h1 class="aph-title">Welcome back, {{ Auth::user()->name }} 👋</h1>
    <p class="aph-sub">Here is your KPI dashboard and quick actions.</p>
  </div>
</div>

{{-- KPI Stat Cards --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-4 col-xl-3">
    <a href="{{ route('admin.admissions.index') }}" class="stat-card">
      <div class="stat-icon" style="background: rgba(var(--bs-primary-rgb), 0.1); color: var(--bs-primary);"><i class="bi bi-inbox-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $admissions }}</div>
        <div class="stat-label">Admissions</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('course.table') }}" class="stat-card">
      <div class="stat-icon green"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $courses }}</div>
        <div class="stat-label">Courses</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('teacher.table') }}" class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $teachers }}</div>
        <div class="stat-label">Faculty</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('event.table') }}" class="stat-card">
      <div class="stat-icon amber"><i class="bi bi-calendar2-event-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $events }}</div>
        <div class="stat-label">Events</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-3">
    <a href="{{ route('notice.table') }}" class="stat-card">
      <div class="stat-icon rose"><i class="bi bi-bell-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $notices }}</div>
        <div class="stat-label">Notices</div>
      </div>
    </a>
  </div>
</div>

<div class="row g-3">
  {{-- Insights Graph --}}
  <div class="col-lg-7">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <span class="card-title"><i class="bi bi-graph-up-arrow"></i> Admission Insights (Last 6 Months)</span>
      </div>
      <div class="admin-card-body">
        <canvas id="admissionsChart" height="250"></canvas>
      </div>
    </div>
  </div>

  {{-- Quick Actions (Draggable) --}}
  <div class="col-lg-5">
    <div class="admin-card h-100">
      <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span class="card-title"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</span>
        <small class="text-muted"><i class="bi bi-arrows-move"></i> Drag to reorder</small>
      </div>
      <div class="admin-card-body p-3">
        <div id="quickActionsGrid" class="row g-2">
          
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="add-course">
            <a href="{{ route('course.add') }}" class="quick-action-btn">
              <i class="bi bi-mortarboard text-success"></i>
              <span>Course</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="add-faculty">
            <a href="{{ route('teacher.add') }}" class="quick-action-btn">
              <i class="bi bi-person-plus text-primary"></i>
              <span>Faculty</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="post-notice">
            <a href="{{ route('notice.add') }}" class="quick-action-btn">
              <i class="bi bi-megaphone text-danger"></i>
              <span>Notice</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="create-event">
            <a href="{{ route('event.add') }}" class="quick-action-btn">
              <i class="bi bi-calendar-plus text-warning"></i>
              <span>Event</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="site-settings">
            <a href="{{ route('site.settings.edit') }}" class="quick-action-btn">
              <i class="bi bi-sliders2 text-info"></i>
              <span>Settings</span>
            </a>
          </div>
          <div class="col-6 col-sm-4 col-md-6 col-xl-4" data-id="home-layout">
            <a href="{{ route('home.sections.index') }}" class="quick-action-btn">
              <i class="bi bi-layout-text-window-reverse text-secondary"></i>
              <span>Layout</span>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- Add Chart.js and SortableJS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
  // Setup Chart
  const ctx = document.getElementById('admissionsChart').getContext('2d');
  new Chart(ctx, {
      type: 'line',
      data: {
          labels: {!! json_encode($chartLabels) !!},
          datasets: [{
              label: 'New Applications',
              data: {!! json_encode($chartData) !!},
              borderColor: '#1a4d8c',
              backgroundColor: 'rgba(26, 77, 140, 0.1)',
              borderWidth: 2,
              pointBackgroundColor: '#f59e0b',
              fill: true,
              tension: 0.4
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
              legend: { display: false }
          },
          scales: {
              y: { beginAtZero: true, ticks: { precision: 0 } }
          }
      }
  });

  // Setup Sortable Quick Actions
  const grid = document.getElementById('quickActionsGrid');
  
  // 1. Load order from localStorage
  const savedOrder = JSON.parse(localStorage.getItem('quickActionsOrder'));
  if (savedOrder && savedOrder.length > 0) {
      const items = Array.from(grid.children);
      savedOrder.forEach(id => {
          const item = items.find(el => el.dataset.id === id);
          if (item) grid.appendChild(item); // Reorder by appending
      });
  }

  // 2. Initialize SortableJS
  new Sortable(grid, {
      animation: 150,
      ghostClass: 'sortable-ghost',
      onEnd: function () {
          // Save new order to localStorage
          const newOrder = Array.from(grid.children).map(el => el.dataset.id);
          localStorage.setItem('quickActionsOrder', JSON.stringify(newOrder));
      }
  });
</script>
@endpush

{{-- Custom CSS for Quick Actions Grid --}}
@push('styles')
<style>
  .quick-action-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 15px 10px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      text-decoration: none;
      color: #334155;
      font-weight: 500;
      font-size: 13px;
      transition: all 0.2s;
      height: 100%;
      cursor: grab;
  }
  .quick-action-btn:hover {
      background: #fff;
      border-color: #cbd5e1;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
  }
  .quick-action-btn i {
      font-size: 24px;
      margin-bottom: 8px;
  }
  .sortable-ghost {
      opacity: 0.4;
      background: #e2e8f0;
  }
</style>
@endpush
@endsection
