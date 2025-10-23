@extends('layouts.app')
@section('title', 'Dashboard - SiBBesar')

@section('content')
@php
    $cashBalance = $cashBalance ?? 0;
    $hutangUsaha = $hutang ?? 0;
    $piutangUsaha = $piutang ?? 0;
    $jurnal = $journalCount ?? 0;
@endphp

<h1 style="margin-bottom:20px;">📊 Dashboard</h1>

<div class="card" style="background:rgba(255,255,255,0.1);color:white;">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
        <div style="background:rgba(255,255,255,0.15);padding:20px;border-radius:12px;">
            <h3>Saldo Kas</h3>
            <p style="font-size:24px;font-weight:bold;">Rp {{ number_format($cashBalance, 0, ',', '.') }}</p>
        </div>
        <div style="background:rgba(255,255,255,0.15);padding:20px;border-radius:12px;">
            <h3>Hutang Usaha</h3>
            <p style="font-size:24px;font-weight:bold;">Rp {{ number_format($hutangUsaha, 0, ',', '.') }}</p>
        </div>
        <div style="background:rgba(255,255,255,0.15);padding:20px;border-radius:12px;">
            <h3>Piutang Usaha</h3>
            <p style="font-size:24px;font-weight:bold;">Rp {{ number_format($piutangUsaha, 0, ',', '.') }}</p>
        </div>
        <div style="background:rgba(255,255,255,0.15);padding:20px;border-radius:12px;">
            <h3>Total Jurnal</h3>
            <p style="font-size:24px;font-weight:bold;">{{ number_format($jurnal, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="card">
    <h2>📘 Ringkasan Aktivitas</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Saldo Kas</td><td>Rp {{ number_format($cashBalance,0,',','.') }}</td><td>Saldo kas terakhir</td></tr>
            <tr><td>Hutang Usaha</td><td>Rp {{ number_format($hutangUsaha,0,',','.') }}</td><td>Jumlah hutang</td></tr>
            <tr><td>Piutang Usaha</td><td>Rp {{ number_format($piutangUsaha,0,',','.') }}</td><td>Jumlah piutang</td></tr>
            <tr><td>Jumlah Jurnal</td><td>{{ number_format($jurnal,0,',','.') }}</td><td>Transaksi tercatat</td></tr>
        </tbody>
    </table>
</div>
@endsection
