@extends('layouts.app')

@section('title', 'Daftar Tagihan')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Tagihan Anda</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihans as $tagihan)
            <tr>
                <td>{{ $tagihan->deskripsi }}</td>
                <td>Rp {{ number_format($tagihan->jumlah,0,',','.') }}</td>
                <td>
                    @if($tagihan->status == 'lunas')
                        <span class="badge bg-success">Lunas</span>
                    @elseif($tagihan->status == 'belum_lunas' || $tagihan->status == 'pending' || $tagihan->status == 'UNPAID')
                        <span class="badge bg-warning text-dark">Belum Dibayar</span>
                    @elseif($tagihan->status == 'expired')
                        <span class="badge bg-danger">Expired</span>
                    @else
                        <span class="badge bg-secondary">{{ $tagihan->status }}</span>
                    @endif
                </td>
                <td>
                    @if($tagihan->status == 'UNPAID')
                    <a href="{{ route('tagihan.bayar', $tagihan->id) }}" class="btn btn-primary btn-sm">Bayar</a>
                    @else
                    -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada tagihan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
