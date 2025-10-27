function bukaHalaman(hal) {
  document.querySelectorAll('.container-box').forEach(div => div.classList.add('d-none'));
  if (hal === 'tambah-jurnal') document.getElementById('halaman-tambah-jurnal').classList.remove('d-none');
  else if (hal === 'tambah-penyesuaian') document.getElementById('halaman-tambah-penyesuaian').classList.remove('d-none');
  else document.getElementById('halaman-jurnal').classList.remove('d-none');
}

function simpanJurnal(type) {
  const data = {
    tanggal: document.getElementById(type === 'jurnal' ? 'tanggal' : 'tanggal2').value,
    bukti: document.getElementById(type === 'jurnal' ? 'bukti' : 'bukti2').value,
    ket: document.getElementById(type === 'jurnal' ? 'keterangan' : 'keterangan2').value,
    akun: document.getElementById(type === 'jurnal' ? 'akun' : 'akun2').value,
    yol: document.getElementById(type === 'jurnal' ? 'yol' : 'yol2').value,
    sat: document.getElementById(type === 'jurnal' ? 'sat' : 'sat2').value,
    harga: document.getElementById(type === 'jurnal' ? 'harga' : 'harga2').value,
    kode: document.getElementById(type === 'jurnal' ? 'kode' : 'kode2').value,
    hp: document.getElementById(type === 'jurnal' ? 'hp' : 'hp2').value,
    debit: document.getElementById(type === 'jurnal' ? 'debit' : 'debit2').value,
    kredit: document.getElementById(type === 'jurnal' ? 'kredit' : 'kredit2').value
  };
  const key = type === 'jurnal' ? 'dataJurnal' : 'dataPenyesuaian';
  const arr = JSON.parse(localStorage.getItem(key)) || [];
  arr.push(data);
  localStorage.setItem(key, JSON.stringify(arr));
  alert("Data berhasil disimpan!");
  bukaHalaman('jurnal');
}
function tampilkanJurnal() {
  const tbody = document.querySelector('#tabelJurnal tbody');
  tbody.innerHTML = '';
  const data = JSON.parse(localStorage.getItem('dataJurnal')) || [];
  data.forEach(d => {
    const row = `<tr>
      <td>${d.tanggal}</td>
      <td>${d.bukti}</td>
      <td>${d.ket}</td>
      <td>${d.akun}</td>
      <td>${d.yol}</td>
      <td>${d.sat}</td>
      <td>${d.harga}</td>
      <td>${d.kode}</td>
      <td>${d.hp}</td>
      <td>${d.debit}</td>
      <td>${d.kredit}</td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', row);
  });
}

function cetakPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation: "landscape" });
  doc.setFontSize(14);
  doc.text("Jurnal Umum", 140, 15, { align: "center" });

  const data = JSON.parse(localStorage.getItem('dataJurnal')) || [];
  const rows = data.map(d => [
    d.tanggal, d.bukti, d.ket, d.akun, d.yol, d.sat, d.harga, d.kode, d.hp, d.debit, d.kredit
  ]);
  doc.autoTable({
    startY: 25,
    head: [["Tanggal", "No Bukti", "Keterangan", "Akun", "YOL", "SAT", "Harga", "Kode", "H/P", "Debit", "Kredit"]],
    body: rows,
    theme: 'grid',
    styles: { fontSize: 9 }
  });

  doc.save("Jurnal_Umum.pdf");
}
