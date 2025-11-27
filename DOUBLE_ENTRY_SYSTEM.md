# SISTEM DOUBLE ENTRY ACCOUNTING

## 📋 PERUBAHAN SISTEM

Sistem jurnal telah diupgrade menjadi **Double Entry Accounting** yang sesuai standar akuntansi internasional.

---

## ✅ APA YANG BERUBAH?

### **SEBELUM (Single Entry):**
```
User input: Beban Gaji 10.000.000 (OUT + TIDAK LUNAS)
Database: 1 baris saja
  - Akun: Beban Gaji (5-1100)
  - Total: 10.000.000

Masalah:
❌ Laba Rugi: Benar (Beban tercatat)
❌ Neraca: SALAH (Hutang tidak tercatat - tidak balance!)
```

### **SESUDAH (Double Entry):**
```
User input: Beban Gaji 10.000.000 (OUT + TIDAK LUNAS)
Database: 2 baris otomatis
  Baris 1:
    - Akun: Beban Gaji (5-1100)
    - Debit: 10.000.000
    - Kredit: 0
    
  Baris 2 (Otomatis):
    - Akun: Hutang Usaha (2-1100)
    - Debit: 0
    - Kredit: 10.000.000

Hasil:
✅ Laba Rugi: Benar (Beban tercatat)
✅ Neraca: Benar (Hutang tercatat - BALANCE!)
✅ Total Debit = Total Kredit (Balanced!)
```

---

## 🔄 LOGIKA JURNAL OTOMATIS

### **IN + LUNAS**
```
User pilih: Pendapatan Jasa (4-1100) - 10.000.000
Sistem otomatis buat:
  Baris 1: Debit Kas (1-1100) +10.000.000
  Baris 2: Kredit Pendapatan (4-1100) +10.000.000
```

### **IN + TIDAK LUNAS**
```
User pilih: Pendapatan Jasa (4-1100) - 10.000.000
Sistem otomatis buat:
  Baris 1: Debit Piutang (1-1300) +10.000.000
  Baris 2: Kredit Pendapatan (4-1100) +10.000.000
```

### **OUT + LUNAS**
```
User pilih: Beban Gaji (5-1100) - 10.000.000
Sistem otomatis buat:
  Baris 1: Debit Beban Gaji (5-1100) +10.000.000
  Baris 2: Kredit Kas (1-1100) +10.000.000
```

### **OUT + TIDAK LUNAS**
```
User pilih: Beban Gaji (5-1100) - 10.000.000
Sistem otomatis buat:
  Baris 1: Debit Beban Gaji (5-1100) +10.000.000
  Baris 2: Kredit Hutang (2-1100) +10.000.000
```

---

## 📊 TABEL LOGIKA LENGKAP

| IN/OUT | Status | Akun User Pilih | Jurnal Otomatis Kedua |
|--------|--------|-----------------|----------------------|
| IN | LUNAS | Pendapatan (4-xxxx) | Debit: Kas (1-1100) |
| IN | TIDAK LUNAS | Pendapatan (4-xxxx) | Debit: Piutang (1-1300) |
| OUT | LUNAS | Beban (5-xxxx) | Kredit: Kas (1-1100) |
| OUT | TIDAK LUNAS | Beban (5-xxxx) | Kredit: Hutang (2-1100) |

---

## 💡 CARA KERJA DI INTERFACE

### **Tampilan User:**
- Tetap **1 baris per transaksi** (user-friendly)
- Tidak terlihat jurnal pasangan (disembunyikan otomatis)

### **Database:**
- Setiap transaksi = **2 baris** (double entry)
- Baris pertama: Akun yang user pilih
- Baris kedua: Akun pasangan (otomatis)

### **Laporan:**
- **Jurnal Umum:** Tampil 1 baris per transaksi
- **Neraca:** Hitung dari SEMUA baris (termasuk pasangan)
- **Laba Rugi:** Hitung dari SEMUA baris (termasuk pasangan)

---

## 🗃️ STRUKTUR DATABASE

### **Kolom Baru di Tabel `journals`:**

1. **`debit`** (decimal 15,2)
   - Jumlah debit transaksi
   - Default: 0

2. **`kredit`** (decimal 15,2)
   - Jumlah kredit transaksi
   - Default: 0

3. **`paired_journal_id`** (bigint, nullable)
   - ID jurnal pasangannya
   - Foreign key ke journals.id
   - Null jika belum ada pasangan

4. **`is_paired`** (boolean)
   - `false` = Jurnal utama (tampil di interface)
   - `true` = Jurnal pasangan (disembunyikan)

---

## 🛠️ AKUN YANG DIPERLUKAN

Sistem membutuhkan akun-akun berikut **HARUS ADA** di database:

### **1. Kas (1-1100)**
- Kategori: Aset Lancar (1)
- Kode: 1100
- Untuk: Transaksi tunai (IN/OUT LUNAS)

### **2. Piutang Usaha (1-1300)**
- Kategori: Aset Lancar (1)
- Kode: 1300
- Untuk: IN + TIDAK LUNAS

### **3. Hutang Usaha (2-1100)**
- Kategori: Kewajiban Lancar (2)
- Kode: 1100
- Untuk: OUT + TIDAK LUNAS

**Jika akun tidak ada, sistem akan error!**

---

## 📝 CONTOH KASUS NYATA

### **Kasus 1: Terima Pembayaran Tunai**
```
Input User:
- Tanggal: 20 Nov 2025
- Item: Jasa Konsultasi
- Jumlah: 50.000.000
- Akun: Pendapatan Jasa (4-1100)
- IN/OUT: IN
- Status: LUNAS

Sistem Otomatis Buat:
Baris 1: Debit Kas +50.000.000 (Kas bertambah)
Baris 2: Kredit Pendapatan +50.000.000 (Revenue bertambah)

Laporan:
- Laba Rugi: Pendapatan +50.000.000 ✅
- Neraca: Kas +50.000.000 ✅
```

### **Kasus 2: Bayar Gaji Belum Lunas (Hutang)**
```
Input User:
- Tanggal: 21 Nov 2025
- Item: Gaji Karyawan
- Jumlah: 30.000.000
- Akun: Beban Gaji (5-1100)
- IN/OUT: OUT
- Status: TIDAK LUNAS

Sistem Otomatis Buat:
Baris 1: Debit Beban Gaji +30.000.000 (Expenses bertambah)
Baris 2: Kredit Hutang +30.000.000 (Liabilities bertambah)

Laporan:
- Laba Rugi: Beban +30.000.000 ✅
- Neraca: Hutang +30.000.000 ✅
```

---

## 🔍 VALIDASI BALANCE

Sistem otomatis memastikan:

```php
Total Debit = Total Kredit (SELALU)
```

**Contoh Check:**
```sql
SELECT 
  SUM(debit) as total_debit,
  SUM(kredit) as total_kredit,
  SUM(debit) - SUM(kredit) as balance
FROM journals;

-- Hasil harus:
-- balance = 0 (BALANCED!)
```

---

## ⚠️ PENTING!

### **Edit Transaksi:**
- Edit 1 transaksi = otomatis update pasangannya juga
- Tidak perlu manual

### **Hapus Transaksi:**
- Hapus 1 transaksi = otomatis hapus pasangannya juga
- Tidak perlu manual

### **Laporan:**
- Gunakan query SUM untuk total yang benar
- Jangan double count

---

## 🚀 KEUNTUNGAN SISTEM BARU

1. ✅ **Akuntansi Benar**
   - Sesuai standar internasional
   - Neraca selalu balance

2. ✅ **Piutang & Hutang Tercatat**
   - Tidak ada transaksi hilang
   - Audit trail lengkap

3. ✅ **User-Friendly**
   - Tampilan tetap sederhana (1 baris)
   - Kompleksitas tersembunyi di backend

4. ✅ **Otomatis**
   - User tidak perlu manual entry 2x
   - Sistem handle semua

---

## 📞 SUPPORT

Jika ada masalah atau pertanyaan:
- Pastikan akun Kas, Piutang, Hutang sudah ada
- Check log error di `storage/logs/laravel.log`
- Hubungi developer
