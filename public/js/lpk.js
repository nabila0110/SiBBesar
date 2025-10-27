   function buatStatement() {
      const tahun = document.getElementById("tahun").value;
      document.getElementById("periode").textContent = "Periode Berakhir: 31 Desember " + tahun;
      alert("Laporan tahun " + tahun + " berhasil dibuat!");
    }
