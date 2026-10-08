@if($categories->count() === 0)
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3 text-success"></i>
            <h6 class="mb-1">No categories found</h6>
            <small>Create your first eco category!</small>
        </td>
    </tr>
@else
    @foreach($categories as $index => $category)
        <tr data-name="{{ strtolower($category->name) }}">

            <td>{{ $categories->firstItem() + $index }}</td>

            <td class="text-left">
                <strong>{{ $category->name }}</strong>
                <div class="small text-muted">
                    {{ $category->description }}
                </div>
            </td>

            <td>{{ $category->created_at->format('d M Y') }}</td>

            <td>
                @if(auth()->user()->isSuperAdmin())
                    <button
                        class="btn btn-light eco-btn js-edit"
                        data-id="{{ $category->id }}"
                        data-name="{{ $category->name }}"
                        data-name_mm="{{ $category->name_mm }}"
                        data-description="{{ $category->description }}">
                        <i class="fas fa-pen text-primary"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-light eco-btn js-delete"
                        data-id="{{ $category->id }}"
                        data-name="{{ $category->name }}">
                        <i class="fas fa-trash text-danger"></i>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
@endif
