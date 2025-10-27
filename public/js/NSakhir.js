   // Pastikan tombol bisa diklik
    document.getElementById("btnLihat").addEventListener("click", function() {
      const tahun = document.getElementById("tahun").value.trim();
      if (tahun) {
        document.querySelector(".tahun").textContent = Tahun: ${tahun}-12-31;
      } else {
        alert("Masukkan tahun terlebih dahulu!");
      }
    });

    document.getElementById("btnCetak").addEventListener("click", function() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      const content = document.querySelector(".container");

      doc.html(content, {
        callback: function (doc) {
          doc.save("Neraca_Saldo_Akhir.pdf");
        },
        x: 10,
        y: 10,
        width: 190,
        windowWidth: 900
      });
    });
