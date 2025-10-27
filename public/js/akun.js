$(document).ready(function(){

  // Inisialisasi DataTables (munculkan entries selector, search, pagination)
  const table = $('#akunTable').DataTable({
    columnDefs: [
      { orderable: false, targets: [0,5] } // No dan tindakan tidak bisa diurut
    ],
    order: [[1, 'asc']], // default: urut berdasarkan kode
    pageLength: 10,
    lengthMenu: [5,10,25,50],
    drawCallback: function() {
      // isi kolom nomor otomatis setelah table digambar
      table.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i){
        cell.innerHTML = i + 1;
      });
    }
  });

  // Handle tambah akun (masuk ke tabel)
  $('#formAkun').on('submit', function(e){
    e.preventDefault();
    const kode = $('#kodeAkun').val().trim();
    const nama = $('#namaAkun').val().trim();
    const kelompok = $('#kelompok').val();
    const tipe = $('#tipe').val();

    const rowNode = table.row.add([
      '', // nomor (akan diisikan drawCallback)
      kode,
      nama,
      kelompok,
      tipe,
      `<button class="btn btn-bukasaldo btn-sm me-1 open-saldo">Buka Saldo</button>
       <button class="btn btn-edit btn-sm me-1 edit-row">Edit</button>
       <button class="btn btn-hapus btn-sm delete-row">Hapus</button>`
    ]).draw(false).node();

    // Reset form & tutup modal
    this.reset();
    bootstrap.Modal.getInstance(document.getElementById('buatAkunModal')).hide();
  });

  // Delegated event: buka saldo per baris (mengambil nama & kode dari row)
  $('#akunTable tbody').on('click', '.open-saldo', function(){
    const tr = $(this).closest('tr');
    const data = table.row(tr).data();
    const kode = data[1];
    const nama = data[2];

    $('#saldoKodeAkun').val(kode);
    $('#saldoNamaAkun').val(kode + ' ' + nama);
    $('#saldoJumlah').val('');
    $('#saldoTanggal').val('');
    $('#saldoKeterangan').val('');
    // buka modal
    const modal = new bootstrap.Modal(document.getElementById('bukaSaldoModal'));
    modal.show();
  });

  // Jika pengguna klik tombol global +Buka Saldo lalu submit, isi ke konsol (contoh)
  $('#formSaldoGlobal').on('submit', function(e){
    e.preventDefault();
    const selected = $('#selectAkunGlobal').val().split('|');
    const kode = selected[0], nama = selected[1];
    const jenis = $('#saldoJenisGlobal').val();
    const jumlah = $('#saldoJumlahGlobal').val();
    const tanggal = $('#saldoTanggalGlobal').val();
    // contoh: tampilkan di console (di implementasi nyata, kirim AJAX ke server)
    console.log('Buka saldo global →', {kode, nama, jenis, jumlah, tanggal});
    bootstrap.Modal.getInstance(document.getElementById('bukaSaldoModalGlobal')).hide();
    alert('Pembukaan saldo disimpan (demo). Cek console untuk detail.');
  });

  // Submit pembukaan saldo per akun (modal row)
  $('#formSaldo').on('submit', function(e){
    e.preventDefault();
    const kode = $('#saldoKodeAkun').val();
    const nama = $('#saldoNamaAkun').val();
    const jenis = $('#saldoJenis').val();
    const jumlah = $('#saldoJumlah').val();
    const tanggal = $('#saldoTanggal').val();
    // demo: log saja
    console.log('Buka saldo untuk', {kode, nama, jenis, jumlah, tanggal});
    bootstrap.Modal.getInstance(document.getElementById('bukaSaldoModal')).hide();
    alert('Pembukaan saldo disimpan (demo). Lihat console untuk detail.');
  });

  // Delegated delete & edit (demo)
  $('#akunTable tbody').on('click', '.delete-row', function(){
    if (!confirm('Hapus akun ini?')) return;
    table.row($(this).closest('tr')).remove().draw();
  });

  $('#akunTable tbody').on('click', '.edit-row', function(){
    const tr = $(this).closest('tr');
    const data = table.row(tr).data();
    // isi modal buat akun dengan data, lalu buka modal untuk edit (demo sederhana)
    $('#kodeAkun').val(data[1]);
    $('#namaAkun').val(data[2]);
    $('#kelompok').val(data[3]);
    $('#tipe').val(data[4]);
    const modal = new bootstrap.Modal(document.getElementById('buatAkunModal'));
    modal.show();

    // Setelah submit, kita akan menambahkan baris baru — untuk implementasi penuh,
    // sebaiknya lakukan update pada row yang diedit (left as exercise).
  });

});
