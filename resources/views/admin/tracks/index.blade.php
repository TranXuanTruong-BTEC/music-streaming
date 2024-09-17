@extends('admin.dashboard')

@section('content')
<div class="container-fluid content-container"  style="padding-top: 72px;">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Bài hát</h6>
                    <form action="{{ route('admin.tracks.fetch') }}" method="POST" class="form-inline">
                        @csrf
                        <input type="text" name="title" class="form-control mr-2" placeholder="Nhập tên bài hát">
                        <input type="text" name="artist" class="form-control mr-2" placeholder="Nhập tên nghệ sĩ">
                        <button type="submit" class="btn btn-primary btn-sm">Tìm và thêm bài hát</button>
                    </form>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên bài hát</th>
                                    <th>Album</th>
                                    <th>Nghệ sĩ</th>
                                    <th>Thời lượng</th>
                                    <th>Nghe</th>
                                    <th>Nguồn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tracks as $track)
                                <tr>
                                    <td>{{ $track->id }}</td>
                                    <td>{{ $track->name }}</td>
                                    <td>{{ $track->album->name ?? 'N/A' }}</td>
                                    <td>{{ $track->artist->name ?? 'N/A' }}</td>
                                    <td>{{ gmdate("i:s", $track->duration_ms / 1000) }}</td>
                                    <td>
                                        @if($track->audio_url)
                                            <audio controls>
                                                <source src="{{ asset('storage/' . $track->audio_url) }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @else
                                            Không có audio
                                        @endif
                                    </td>
                                    <td>
                                        @if($track->spotify_id)
                                            Spotify
                                        @elseif($track->youtube_id)
                                            YouTube
                                        @else
                                            Unknown
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $tracks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .content-container {
        padding-top: 1rem;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table th, .table td {
        white-space: nowrap;
    }
    audio {
        width: 200px;
        height: 30px;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ mục",
                "zeroRecords": "Không tìm thấy kết quả",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ tổng số mục)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Sau",
                    "previous": "Trước"
                }
            }
        });
    });
</script>
@endsection
