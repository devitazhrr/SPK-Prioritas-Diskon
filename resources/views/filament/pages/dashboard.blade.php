<x-filament::page>
    <div class="space-y-6">

        {{-- 🔹 HEADER --}}
        <div>
            <h1 class="text-2xl font-bold">Selamat Datang</h1>
            <p class="text-gray-500">
                Sistem Pendukung Keputusan Metode WASPAS
            </p>
        </div>

        {{-- 🔹 INFO BOX --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="font-semibold mb-2">Deskripsi Sistem</h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                Sistem ini digunakan untuk membantu proses pengambilan keputusan 
                dengan menggunakan metode WASPAS (Weighted Aggregated Sum Product Assessment). 
                Metode ini mengkombinasikan pendekatan penjumlahan terbobot dan 
                perkalian terbobot untuk menghasilkan peringkat alternatif terbaik 
                berdasarkan kriteria yang telah ditentukan.
            </p>
        </div>

        {{-- 🔹 FLOW / ALUR --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="font-semibold mb-3">Alur Sistem</h2>

            <div class="flex items-center gap-3 text-sm text-gray-600">
                <span>Alternatif</span>
                <span>→</span>
                <span>Kriteria</span>
                <span>→</span>
                <span>Penilaian</span>
                <span>→</span>
                <span>Perhitungan</span>
                <span>→</span>
                <span class="font-semibold text-black">Hasil Ranking</span>
            </div>
        </div>

        {{-- 🔹 TUJUAN --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="font-semibold mb-2">Tujuan Sistem</h2>

            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Membantu pengambilan keputusan secara objektif</li>
                <li>Mengolah data berdasarkan kriteria yang terstruktur</li>
                <li>Menghasilkan peringkat alternatif terbaik</li>
            </ul>
        </div>

    </div>
</x-filament::page>