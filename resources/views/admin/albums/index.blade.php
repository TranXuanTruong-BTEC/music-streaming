@extends('admin.dashboard')

@section('content')
<div class="container-fluid" style="padding-top: 72px;">
    <h1 class="h3 mb-2 text-gray-800">Quản lý Album</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Album</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Album</th>
                            <th>Nghệ sĩ</th>
                            <th>Ngày phát hành</th>
                            <th>Số lượng bài hát</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($albums as $album)
                        <tr>
                            <td>{{ $album->id }}</td>
                            <td>{{ $album->name }}</td>
                            <td>{{ $album->artist->name }}</td>
                            <td>{{ $album->release_date ?? 'N/A' }}</td>
                            <td>{{ $album->total_tracks > 0 ? $album->total_tracks : 'N/A' }}</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">Chi tiết</a>
                                <a href="#" class="btn btn-primary btn-sm">Sửa</a>
                                <a href="#" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $albums->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection
