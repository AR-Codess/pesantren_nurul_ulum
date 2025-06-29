@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap">
<style>
    body,
    .form-label,
    .form-control,
    .btn,
    .card-header,
    .card-body {
        font-family: 'Roboto', Arial, sans-serif;
    }

    .table thead th {
        background: #f8f9fa;
        vertical-align: middle;
        text-align: center;
    }

    .table tbody td {
        vertical-align: middle;
    }

    .attendance-radio {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
</style>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold fs-5">Input Absensi Harian Santri</div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf
                        <div class="mb-3 row">
                            <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Santri</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Hadir</th>
                                        <th>Sakit</th>
                                        <th>Izin</th>
                                        <th>Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $i => $user)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td class="text-start">{{ $user->name }}</td>
                                        <td>{{ $user->nis ?? '-' }}</td>
                                        <td>{{ $user->nama ?? '-' }}</td>
                                        <td class="attendance-radio">
                                            <input type="radio" name="absensi[{{ $user->id }}][status]" value="hadir" {{ old('absensi.'.$user->id.'.status', 'hadir') == 'hadir' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="attendance-radio">
                                            <input type="radio" name="absensi[{{ $user->id }}][status]" value="sakit" {{ old('absensi.'.$user->id.'.status') == 'sakit' ? 'checked' : '' }}>
                                        </td>
                                        <td class="attendance-radio">
                                            <input type="radio" name="absensi[{{ $user->id }}][status]" value="izin" {{ old('absensi.'.$user->id.'.status') == 'izin' ? 'checked' : '' }}>
                                        </td>
                                        <td class="attendance-radio">
                                            <input type="radio" name="absensi[{{ $user->id }}][status]" value="alpha" {{ old('absensi.'.$user->id.'.status') == 'alpha' ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="submit" class="btn btn-primary px-4">Simpan Absensi</button>
                                <a href="{{ route('absensi.index') }}" class="btn btn-secondary px-4">Batal</a>
                            </div>
                            <div>
                                <a href="{{ route('absensi.export', ['format' => 'pdf', 'tanggal' => request('tanggal', date('Y-m-d'))]) }}" class="btn btn-danger px-4 me-2" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                                </a>
                                <a href="{{ route('absensi.export', ['format' => 'excel', 'tanggal' => request('tanggal', date('Y-m-d'))]) }}" class="btn btn-success px-4" target="_blank">
                                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection