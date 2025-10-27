<link rel="stylesheet" href="{{ asset('css/pph21.css') }}">
@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')
    <main style="flex:1;padding:30px;">
        <div x-data="pph21Calculator()">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-3 mb-8">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h1 class="text-3xl font-bold text-gray-800">Perhitungan PPh 21</h1>
                </div>

                <!-- Form Input -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Data Input</h2>

                    <form @submit.prevent="calculate">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center gap-2 mb-4 cursor-pointer">
                                    <input type="checkbox" x-model="formData.npwp"
                                        class="w-5 h-5 text-indigo-600 rounded">
                                    <span class="text-gray-700 font-medium">NPWP Ada</span>
                                </label>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">Status Tanggungan</label>
                                    <select x-model="formData.status_tanggungan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="TK">TK, Lajang (tidak menikah)</option>
                                        <option value="K">K/0 (Menikah, 0 tanggungan)</option>
                                        <option value="K/1">K/1 (Menikah, 1 tanggungan)</option>
                                        <option value="K/2">K/2 (Menikah, 2 tanggungan)</option>
                                        <option value="K/3">K/3 (Menikah, 3 tanggungan)</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">Gaji Pokok/Bulan</label>
                                    <input type="number" x-model="formData.gaji_pokok"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Rp. 0" step="1000">
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">THR</label>
                                    <input type="number" x-model="formData.thr"
                                        class="w-full px-4py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Rp. 0" step="1000">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">Tanggungan</label>
                                    <input type="number" x-model="formData.tanggungan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Rp. 0" step="1000">
                                </div>

                                <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                    Hitung PPh 21
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Hasil Perhitungan -->
                <div class="grid md:grid-cols-2 gap-6" x-show="hasil !== null" x-cloak>
                    <!-- Kesimpulan -->
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                        <h3 class="text-xl font-bold mb-4">Kesimpulan</h3>

                        <div class="mb-4">
                            <div class="text-sm opacity-90 mb-1">Pajak/Tahun:</div>
                            <div class="text-2xl font-bold" x-text="formatRupiah(hasil?.pajakTahun || 0)"></div>
                        </div>

                        <div class="mb-4">
                            <div class="text-sm opacity-90 mb-1">Gaji Setelah PPh/Tahun:</div>
                            <div class="text-2xl font-bold" x-text="formatRupiah(hasil?.gajiSetelahPPhTahun || 0)">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="text-sm opacity-90 mb-1">Gaji Setelah PPh/Bulan:</div>
                            <div class="text-2xl font-bold" x-text="formatRupiah(hasil?.gajiSetelahPPhBulan || 0)">
                            </div>
                        </div>

                        <div>
                            <div class="text-sm opacity-90 mb-1">Ratio (pajak : gaji):</div>
                            <div class="text-2xl font-bold" x-text="(hasil?.ratio || 0).toFixed(2) + ' %'"></div>
                        </div>
                    </div>

                    <!-- Rincian -->
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Rincian</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Gaji:</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.gaji || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Gaji/Tahun:</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.gajiTahun || 0)"></span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">THR:</span>
                                <span class="font-semibold text-gray-800" x-text="formatRupiah(hasil?.thr || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggungan:</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.tanggungan || 0)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Perhitungan -->
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Perhitungan</h3>

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
                        </div>
                    </div>

                    <!-- PPh Terhutang -->
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">PPh Terhutang</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">1. (5%):</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.pph5 || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">2. (15%):</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.pph15 || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">3. (25%):</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.pph25 || 0)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">4. (30%):</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.pph30 || 0)"></span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-600">5. (35%):</span>
                                <span class="font-semibold text-gray-800"
                                    x-text="formatRupiah(hasil?.pph35 || 0)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="{{ asset('js/pph.js') }}"></script>
@endsection