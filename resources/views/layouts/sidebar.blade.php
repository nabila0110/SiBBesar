<div class="sidebar">
    <div>
        <h3>📦 SiBBesar</h3>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
            <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Data</div>
            <a href="#" class="">📋 Daftar Perusahaan</a>
            <a href="#" class="">💰 Daftar Hutang</a>
            <a href="#" class="">💵 Daftar Piutang</a>
            <a href="#" class="">📦 Daftar Aset</a>
            <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Persediaan</div>
            <a href="{{ route('jenis_barang.index') }}" class="{{ request()->is('jenis_barang*') ? 'active' : '' }}">🧩 Jenis Barang</a>
            <a href="{{ route('merk-barang.index') }}" class="{{ request()->is('merk-barang*') ? 'active' : '' }}">🏷️ Merek Barang</a>
            <a href="{{ route('supplier.index') }}" class="{{ request()->is('supplier*') ? 'active' : '' }}">🚚 Supplier Barang</a>
            <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Akuntansi</div>
            <a href="{{ route('accounts.index') }}" class="{{ request()->is('accounts*') ? 'active' : '' }}">👤 Daftar Akun</a>
            <a href="{{ route('journals.index') }}" class="{{ request()->is('journals*') ? 'active' : '' }}">📝 Jurnal Umum</a>
            <a href="{{ route('reports.general-ledger') }}" class="{{ request()->is('reports/general-ledger*') ? 'active' : '' }}">📖 Buku Besar</a>
            <a href="{{ route('reports.trial-balance') }}" class="{{ request()->is('reports/trial-balance*') ? 'active' : '' }}">📊 Neraca Saldo Awal</a>
            <a href="{{ route('reports.balance-sheet') }}" class="{{ request()->is('reports/balance-sheet*') ? 'active' : '' }}">📉 Neraca Saldo Akhir</a>
            <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Perusahaan</div>
            <a href="{{ route('companies.index') }}" class="{{ request()->is('companies*') ? 'active' : '' }}">🏢 Daftar Perusahaan</a>
            <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Laporan</div>
            <a href="{{ route('reports.balance-sheet') }}" class="{{ request()->is('reports/balance-sheet*') ? 'active' : '' }}">💼 Laporan Posisi Keuangan</a>
            <a href="{{ route('reports.income-statement') }}" class="{{ request()->is('reports/income-statement*') ? 'active' : '' }}">💰 Laporan Laba Rugi</a>
            <div style="margin: 20px 0 10px 0; font-size: 18px; font-weight: bold; color: var(--white); text-align: left;">Penghasilan</div>
            <a href="#" class="">📊 Pajak Penghasilan</a>
        </div>
    </div>
</div>
