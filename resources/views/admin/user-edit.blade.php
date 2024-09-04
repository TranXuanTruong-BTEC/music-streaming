@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Sửa thông tin người dùng</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_admin" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_admin">Là Admin</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection
