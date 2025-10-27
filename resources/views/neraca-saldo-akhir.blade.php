@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')

  <div class="container">
    <h2>Neraca Saldo Akhir</h2>

    <div class="periode">
      <label for="tahun">Pilih Tahun Periodik:</label>
      <input type="text" id="tahun" placeholder="2023">
      <button id="btnLihat">Lihat Neraca</button>
      <button id="btnCetak">Cetak PDF</button>
    </div>

    <div class="tahun">Tahun: 2023-12-31</div>

    <table id="neracaTable">
      <thead>
        <tr>
          <th>Nama Akun</th>
          <th>Debit</th>
          <th>Kredit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>[1100] Kas Ditangan</td>
          <td>Rp. 50,000,000</td>
          <td>Rp. 0</td>
        </tr>
        <tr>
          <td>[1400] Persediaan Barang Dagang</td>
          <td>Rp. 1,000,000</td>
          <td>Rp. 0</td>
        </tr>
        <tr>
          <td>[4101] Pendapatan Penjualan</td>
          <td>Rp. 0</td>
          <td>Rp. 51,000,000</td>
        </tr>
        <tr>
          <td>[4102] Pendapatan Jasa</td>
          <td>Rp. 0</td>
          <td>Rp. 0</td>
        </tr>
      </tbody>
    </table>
  </div>


@endsection