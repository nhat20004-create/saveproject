@extends('Layout.index')

@section('title', 'Danh mục - Quản trị')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-3">QUẢN LÝ DANH MỤC</h4>
    @include('admin.nav')

    <form action="{{ route('admin.categories.store') }}" method="post"
          class="form-inline mb-4">
        @csrf
        <input type="text" name="name" class="form-control mr-2" required
               placeholder="Tên danh mục mới" value="{{ old('name') }}">
        <button class="btn btn-primary">Thêm</button>
    </form>

    <table class="table table-sm">
        <thead>
            <tr><th>Tên</th><th>Slug</th><th>Số bài</th><th></th></tr>
        </thead>
        <tbody>
        @foreach($categories as $item)
            @php($up = route('admin.categories.update', $item))
            @php($del = route('admin.categories.destroy', $item))
            <tr>
                <td>
                    <form id="edit-{{ $item->id }}" method="post"
                          action="{{ $up }}">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" class="form-control"
                               value="{{ $item->name }}" required>
                    </form>
                </td>
                <td>{{ $item->slug }}</td>
                <td>{{ $item->posts_count }}</td>
                <td class="text-right">
                    <button form="edit-{{ $item->id }}"
                            class="btn btn-sm btn-outline-primary">Lưu</button>
                    <form method="post" class="d-inline" action="{{ $del }}"
                          onsubmit="return confirm('Xóa danh mục này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
