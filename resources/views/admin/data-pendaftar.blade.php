@extends('admin.layouts.template-admin')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $title }}</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                            $jalur = ['KETM', 'Disabilitas', 'Kondisi Tertentu', 'Prestasi Rapor', 'Prestasi Kejuaraan', 'Perpindahan Orang Tua/Anak Guru', 'Zonasi'];
                        @endphp
                        <table id="data-pendaftar" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor Pendaftaran</th>
                                    <th>Nama</th>
                                    <th>NISN(s)</th>
                                    <th>Tempat, tanggal lahir</th>
                                    <th>Jalur Pendaftaran</th>
                                    <th>Asal Sekolah</th>
                                    <th>Status Verifikasi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($biodata as $item)
                                    <tr role="row">
                                        <td id="{{ $item->noreg_ppdb }}">
                                            {{ $item->noreg_ppdb }}
                                        </td>
                                        <td style="width: 10rem">
                                            {{ $item->nama }}
                                        </td>
                                        <td>
                                            {{ $item->nisn }}
                                        </td>
                                        <td>
                                            {{ $item->tempat_lahir . ', ' . \Carbon\Carbon::parse($item->tanggal_lahir)->isoFormat('D MMMM Y') }}
                                        </td>
                                        <td style="width: 7rem">
                                            {{ $jalur[$item->jalur_pendaftaran - 1] }}
                                        </td>
                                        <td>
                                            {{ $item->asal_sekolah }}
                                        </td>
                                        <td id="{{ $item->is_verified }}">
                                            {{ $item->is_verified == 0 ? 'Belum Verifikasi' : 'Terverifikasi' }}
                                        </td>
                                        <td class="d-flex justify-content-between align-items-center">
                                            @if ($item->is_verified == 0)
                                                <button class="btn btn-xs btn-success" id="edit-status-verifikasi" type="button">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-xs btn-warning" id="edit-data-pendaftar">
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-success disabled" disabled id="edit-status-verifikasi" type="button">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-xs btn-warning disabled" disabled id="edit-data-pendaftar">
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <script>
        var table = $("#data-pendaftar").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"],
            init: function() {},
        }).buttons().container().appendTo('#data-pendaftar_wrapper .col-md-6:eq(0)');

        $('#data-pendaftar thead tr th').css('text-align', 'center');

        $('#data-pendaftar_wrapper .col-md-6:eq(0) .btn-group .buttons-excel').removeClass('btn-secondary').addClass('btn-success');
        $('#data-pendaftar_wrapper .col-md-6:eq(0) .btn-group .buttons-pdf').removeClass('btn-secondary').addClass('btn-danger');

        $('#data-pendaftar_wrapper .col-md-6:eq(0) .btn-group .buttons-excel span').html('<i class="fas fa-file-excel"></i> &nbsp;Excel')
        $('#data-pendaftar_wrapper .col-md-6:eq(0) .btn-group .buttons-pdf span').html('<i class="fas fa-file-pdf"></i> &nbsp;Pdf')
        $('#data-pendaftar_wrapper .col-md-6:eq(0) .btn-group .buttons-print span').html('<i class="fas fa-print"></i> &nbsp;Print')

        $('#data-pendaftar tbody').on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                $('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#data-pendaftar tbody').on('click', '#edit-status-verifikasi', function() {
            let selectedRow = $(this).parent().parent();
            var noregPPDB = selectedRow.find('td:nth-child(1)').attr('id')
            let namaPendaftar = selectedRow.find('td:nth-child(2)').html()
            let statusVerifikasi = selectedRow.find('td:nth-child(7)').attr('id')
            Swal.fire({
                'icon': 'question',
                'title': 'Verifikasi Pendaftar',
                'html': 'Anda akan memverifikasi pendaftar <strong>' + namaPendaftar + '</strong> dengan nomor pendaftaran <strong>' + noregPPDB + '</strong> ?',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ url('admin/verifikasi') }}" + '/' + noregPPDB,
                        data: $('#modalPrompt').serialize(),
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                title: 'Melakukan verifikasi...',
                                timer: 1000,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            }).
                            then((dismiss) => {
                                if (response.status == 200) {
                                    $('#modal-default').modal('hide');
                                    $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').html('Terverifikasi');
                                    $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').attr('id', '1');
                                    selectedRow.find('#edit-status-verifikasi').addClass('disabled');
                                    selectedRow.find('#edit-status-verifikasi').attr('disabled', 'disabled');
                                    selectedRow.find('#edit-data-pendaftar').addClass('disabled');
                                    selectedRow.find('#edit-data-pendaftar').attr('disabled', 'disabled');
                                } else {
                                    $('#modal-default').modal('hide');
                                    $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').html('Belum Terverifikasi');
                                    $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').attr('id', '0');
                                }
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    showCloseButton: true,
                                    timer: 3000,
                                    timerProgressBar: true,
                                })

                                Toast.fire({
                                    icon: 'success',
                                    title: response.success
                                })
                            })

                        }
                    });
                }
            })
        });

        function verifiedPendaftar() {
            let noregTable = $('#data-pendaftar tbody tr.selected');
            let noregPPDB = noregTable.find('td:nth-child(1)').attr('id')
            $.ajax({
                type: "post",
                url: "{{ url('admin/verifikasi') }}" + '/' + noregPPDB,
                data: $('#modalPrompt').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $('#modal-default').modal('hide');
                        $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').html('Terverifikasi');
                        $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').attr('id', '1');
                    } else {
                        $('#modal-default').modal('hide');
                        $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').html('Belum Terverifikasi');
                        $('#data-pendaftar tbody tr.selected').find('td:nth-child(7)').attr('id', '0');
                    }
                    toastr["success"](response.success);
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                }
            });
        }
    </script>
@endpush
