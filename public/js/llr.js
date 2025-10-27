   function buatStatement() {
      const tahun = document.getElementById("tahun").value;
      document.getElementById("periode").textContent =
        "Periode: " + tahun + "-1-1 s/d " + tahun + "-12-31";
      alert("Statement tahun " + tahun + " berhasil dibuat!");
    }
