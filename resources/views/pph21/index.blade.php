@extends('layouts.app')

@section('title', 'Perhitungan PPh 21 - SiBBesar')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pph21.css') }}">
     <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@endpush

@section('content')
    <main style="flex:1;padding:30px;">
        <div class="header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;">
            
        </div>

        <div x-data="pph21Calculator()">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <!-- Header -->
                    <div class="flex items-center gap-3 mb-8">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h1 class="text-3xl font-bold text-gray-800">Perhitungan PPh 21</h1>
                    </div>

                    <!-- Form Input -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-6">Data Input</h2>
                        
                        <form @submit.prevent="calculate">
                            <div class="space-y-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="formData.npwp" class="w-5 h-5 text-indigo-600 rounded">
                                    <span class="text-gray-700 font-medium">NPWP Ada</span>
                                </label>

                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Status Tanggungan</label>
                                    <select x-model="formData.status_tanggungan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="TK/0">TK/0 (Tidak Kawin, 0 tanggungan)</option>
                                        <option value="TK/1">TK/1 (Tidak Kawin, 1 tanggungan)</option>
                                        <option value="TK/2">TK/2 (Tidak Kawin, 2 tanggungan)</option>
                                        <option value="TK/3">TK/3 (Tidak Kawin, 3 tanggungan)</option>
                                        <option value="K/0">K/0 (Kawin, 0 tanggungan)</option>
                                        <option value="K/1">K/1 (Kawin, 1 tanggungan)</option>
                                        <option value="K/2">K/2 (Kawin, 2 tanggungan)</option>
                                        <option value="K/3">K/3 (Kawin, 3 tanggungan)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Gaji Pokok/Bulan</label>
                                    <input type="number" x-model="formData.gaji_pokok"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Rp. 0" step="1000">
                                </div>

                                <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                                    Hitung PPh 21
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Hasil Perhitungan -->
                    <div class="space-y-6" x-show="hasil !== null" x-cloak>
                        <!-- Kesimpulan -->
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                            <h3 class="text-xl font-bold mb-6">Kesimpulan</h3>

                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm opacity-90 mb-1">Pajak/Tahun:</div>
                                    <div class="text-2xl font-bold" x-text="formatRupiah(hasil?.pajakTahun || 0)"></div>
                                </div>

                                <div>
                                    <div class="text-sm opacity-90 mb-1">Gaji Setelah PPh/Tahun:</div>
                                    <div class="text-2xl font-bold" x-text="formatRupiah(hasil?.gajiSetelahPPhTahun || 0)">
                                    </div>
                                </div>

                                <div>
                                    <div class="text-sm opacity-90 mb-1">Gaji Setelah PPh/Bulan:</div>
                                    <div class="text-2xl font-bold" x-text="formatRupiah(hasil?.gajiSetelahPPhBulan || 0)">
                                    </div>
                                </div>

                                <div>
                                    <div class="text-sm opacity-90 mb-1">Ratio (pajak : gaji):</div>
                                    <div class="text-2xl font-bold" x-text="(hasil?.ratio || 0).toFixed(2) + ' %'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Rincian -->
                        <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Rincian</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Gaji/Bulan:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.gaji || 0)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Gaji/Tahun:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.gajiTahun || 0)"></span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-600">Status Tanggungan:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="hasil?.status_tanggungan || '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">NPWP:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="hasil?.npwp ? 'Ya' : 'Tidak'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Perhitungan -->
                        <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Perhitungan</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bruto:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.bruto || 0)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Biaya Jabatan:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.biayaJabatan || 0)"></span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-gray-600 font-medium">Netto:</span>
                                    <span class="font-bold text-gray-800" x-text="formatRupiah(hasil?.netto || 0)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">PTKP:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.ptkp || 0)"></span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-gray-600 font-medium">PKP:</span>
                                    <span class="font-bold text-gray-800" x-text="formatRupiah(hasil?.pkp || 0)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- PPh Terhutang -->
                        <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">PPh Terhutang</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Layer 1 (5%):</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.pph5 || 0)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Layer 2 (15%):</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.pph15 || 0)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Layer 3 (25%):</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.pph25 || 0)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Layer 4 (30%):</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.pph30 || 0)"></span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-600">Layer 5 (35%):</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="formatRupiah(hasil?.pph35 || 0)"></span>
                                </div>
                                <div class="flex justify-between border-t-2 pt-3 border-indigo-600">
                                    <span class="text-gray-800 font-bold">Total PPh:</span>
                                    <span class="font-bold text-indigo-600 text-lg"
                                        x-text="formatRupiah(hasil?.totalPPh || 0)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function pph21Calculator() {
            return {
                formData: {
                    npwp: false,
                    status_tanggungan: 'TK/0',
                    gaji_pokok: 0
                },
                hasil: null,

                async calculate() {
                    // Validasi input
                    if (!this.formData.gaji_pokok || this.formData.gaji_pokok <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Input Tidak Valid',
                            text: 'Mohon masukkan gaji pokok yang valid!',
                            confirmButtonColor: '#4F46E5'
                        });
                        return;
                    }

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'CSRF token tidak ditemukan',
                                confirmButtonColor: '#4F46E5'
                            });
                            return;
                        }

                        // Tampilkan loading
                        Swal.fire({
                            title: 'Menghitung...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const response = await fetch('{{ route("pph21.calculate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();
                        console.log('Response:', result);
                        
                        if (result.success) {
                            this.hasil = result.data;
                            console.log('Hasil set to:', this.hasil);
                            
                            // Tutup loading dan tampilkan success
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Perhitungan PPh 21 berhasil',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            console.error('Validation errors:', result.errors);
                            
                            // Format error message
                            let errorMessage = result.message || 'Terjadi kesalahan saat menghitung PPh 21';
                            if (result.errors) {
                                errorMessage += '\n\n';
                                for (let key in result.errors) {
                                    errorMessage += result.errors[key].join('\n') + '\n';
                                }
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonColor: '#4F46E5'
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat menghitung PPh 21: ' + error.message,
                            confirmButtonColor: '#4F46E5'
                        });
                    }
                },

                formatRupiah(angka) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
                }
            }
        }
    </script>
@endpush