@extends('admin.dashboard')

@section('content')
<div class="container-fluid" style="padding-top: 20px;"> <!-- Thêm padding-top ở đây -->
    <h1 class="h3 mb-2 text-gray-800">Quản lý Nghệ sĩ</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Nghệ sĩ</h6>
            <a href="#" class="btn btn-primary btn-sm">Thêm Nghệ sĩ</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Spotify ID</th>
                            <th>Hình ảnh</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($artists as $artist)
                        <tr>
                            <td>{{ $artist->id }}</td>
                            <td>{{ $artist->name }}</td>
                            <td>{{ $artist->spotify_id }}</td>
                            <td>
                                @if($artist->image_url)
                                    <img src="{{ Storage::disk('public')->url($artist->image_url) }}" alt="{{ $artist->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-artist.jpg') }}" alt="{{ $artist->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                            </td>
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
            <div class="d-flex justify-content-end">
                {{ $artists->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 15,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
            }
        });
    });
</script>
@endsection