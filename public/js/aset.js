    $(document).ready(function () {
      const tabel = $('#tabelAset').DataTable({
        paging: true,
        searching: true,
        info: true,
        lengthChange: true,
      });

      // Fungsi Simpan Aset Baru
      $('#simpanAset').click(function () {
        const nama = $('#namaAset').val();
        const tanggal = $('#tanggalPerolehan').val();
        const unit = $('#unit').val();
        const umur = $('#umurManfaat').val();
        const harga = $('#hargaPerolehan').val();
        const penyusutan = $('#akumulasiPenyusutan').val();

        if (nama && tanggal && unit && umur && harga && penyusutan) {
          const nomor = tabel.rows().count() + 1;
          tabel.row.add([
            nomor,
            nama,
            tanggal,
            unit,
            umur,
            harga,
            penyusutan
          ]).draw();

          $('#modalTambahAset').modal('hide');
          $('#formAset')[0].reset();
        } else {
          alert('Harap isi semua kolom.');
        }
      });
    });
