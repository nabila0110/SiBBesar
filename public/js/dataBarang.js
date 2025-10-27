  function tambahBarang() {
    const kode = document.getElementById("kode").value;
    const nama = document.getElementById("nama").value;
    const harga = document.getElementById("harga").value;
    const stok = document.getElementById("stok").value;

    if (!kode || !nama || !harga || !stok) {
      alert("Semua field wajib diisi!");
      return;
    }

    const tbody = document.getElementById("dataBarang");
    const rowCount = tbody.rows.length + 1;

    const row = tbody.insertRow();
    row.innerHTML = `
      <td>${rowCount}</td>
      <td>${kode}</td>
      <td>${nama}</td>
      <td>${harga}</td>
      <td>${stok}</td>
      <td>
        <span class="action-link">Update</span> |
        <span class="action-link" onclick="hapusBarang(this)">Delete</span>
      </td>
    `;

    document.getElementById("kode").value = "";
    document.getElementById("nama").value = "";
    document.getElementById("harga").value = "";
    document.getElementById("stok").value = "";

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
    modal.hide();
  }

  function hapusBarang(el) {
    const row = el.parentNode.parentNode;
    row.remove();
  }