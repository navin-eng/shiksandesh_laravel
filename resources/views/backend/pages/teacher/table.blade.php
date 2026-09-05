@extends('backend.pages.layout.master')
@push('b-title', 'Team')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Our Team</h1><p class="aph-sub">Manage administrative, teaching, and non-teaching staff.</p></div>
  <a href="{{ route('teacher.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Member</a>
</div>
<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-people-fill"></i> All Team Members</span>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th><i class="bi bi-arrows-move text-muted"></i></th><th>Photo</th><th>Name</th><th>Role</th><th>Staff Type</th><th>Order</th><th>Profile</th><th>Action</th></tr></thead>
        <tbody id="teacherList">
          @forelse($teacher as $data)
          <tr data-id="{{ $data->id }}" style="cursor: grab;">
            <td><i class="bi bi-grip-vertical text-muted"></i></td>
            <td><img src="{{ asset($data->image) }}" class="table-img-round" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td>{{ $data->role }}</td>
            <td>
              @php
                $typeLabel = match($data->staff_type) {
                  'administrative' => 'Administrative',
                  'non_teaching'   => 'Non-Teaching',
                  default          => 'Teaching',
                };
                $typeClass = match($data->staff_type) {
                  'administrative' => 'badge-info',
                  'non_teaching'   => 'badge-warning',
                  default          => 'badge-green',
                };
              @endphp
              <span class="badge-admin {{ $typeClass }}">{{ $typeLabel }}</span>
            </td>
            <td><span class="badge-admin badge-secondary">{{ $data->sort_order ?? 0 }}</span></td>
            <td>
              @if($data->facebook_link)
                <a href="{{ $data->facebook_link }}" target="_blank" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-facebook"></i> Profile</a>
              @else <span style="color:#718096;">—</span> @endif
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('teacher.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('teacher.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#718096;">No faculty added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('teacherList');
    if (el) {
        var sortable = Sortable.create(el, {
            animation: 150,
            ghostClass: 'bg-light',
            handle: 'tr', // Whole row is draggable, but you can also restrict to the grip icon
            onEnd: function (evt) {
                var order = [];
                el.querySelectorAll('tr[data-id]').forEach(function(row, index) {
                    order.push({
                        id: row.getAttribute('data-id'),
                        position: index + 1
                    });
                });

                // Update the UI order column badges immediately
                el.querySelectorAll('tr[data-id]').forEach(function(row, index) {
                    var orderBadge = row.querySelector('.badge-secondary');
                    if(orderBadge) orderBadge.textContent = index + 1;
                });

                // Send ajax request to save order
                fetch('{{ route('teacher.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if(!data.success) {
                        alert('Failed to save order.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});
</script>
@endpush
