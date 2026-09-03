@extends('backend.pages.layout.master')
@push('b-title', 'Navbar Menu')
@section('backend-content')
    <div class="row" style="margin-bottom:16px;">
        <div class="col-6">
            <h5 class="h4">Navbar Menu Items</h5>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('navbar_menu.add') }}" class="btn btn-primary">+ Add New Link</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-menus">
                @forelse ($menus as $menu)
                    <tr data-id="{{ $menu->id }}">
                        <td>
                            <i class="fas fa-grip-vertical handle" style="cursor: grab; color: #aaa; margin-right: 10px;" title="Drag to reorder"></i>
                            <span class="menu-order">{{ $menu->order }}</span>
                        </td>
                        <td><strong>{{ $menu->name }}</strong></td>
                        <td>
                            @if($menu->url)
                                <a href="{{ url($menu->url) }}" target="_blank" class="text-primary">{{ $menu->url }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($menu->type == 'course_dropdown')
                                <span class="badge bg-info">Courses Dropdown</span>
                            @else
                                <span class="badge bg-secondary">Standard Link</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('navbar_menu.status', $menu->id) }}">
                                @if ($menu->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('navbar_menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('navbar_menu.destroy', $menu->id) }}" class="btn btn-sm btn-danger deleteBtn"
                               data-href="{{ route('navbar_menu.destroy', $menu->id) }}">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No menu items found. <a href="{{ route('navbar_menu.add') }}">Add one now.</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sortableList = document.getElementById('sortable-menus');
        if(sortableList && sortableList.querySelectorAll('tr[data-id]').length > 1) {
            new Sortable(sortableList, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: function(evt) {
                    const rows = sortableList.querySelectorAll('tr[data-id]');
                    let ids = [];
                    rows.forEach((row, index) => {
                        ids.push(row.getAttribute('data-id'));
                        // Update visual order
                        const orderSpan = row.querySelector('.menu-order');
                        if (orderSpan) orderSpan.innerText = index + 1;
                    });

                    fetch('{{ route("navbar_menu.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            console.log('Order updated successfully.');
                        } else {
                            console.error('Failed to update order');
                        }
                    })
                    .catch(error => console.error('Error reordering:', error));
                }
            });
        }
    });
</script>
@endpush
