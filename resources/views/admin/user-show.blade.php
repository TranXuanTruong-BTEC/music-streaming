@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Thông tin người dùng</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Vai trò</th>
                        <td>{{ $user->getRole() }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Sửa</a>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
