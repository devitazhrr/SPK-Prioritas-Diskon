<x-filament::page>
    <div class="space-y-6">

        {{-- Judul --}}
        <div>
            <p class="text-sm text-gray-500">
                Menampilkan hasil akhir perankingan alternatif berdasarkan metode WASPAS.
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow rounded-xl p-4">

            <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 text-left w-32">Ranking</th>
                        <th class="p-3 text-left">Alternatif</th>
                        <th class="p-3 text-left w-40">Nilai Qi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->hitungWaspas() as $row)
                        <tr class="border-t hover:bg-gray-50 transition">

                            {{-- Ranking --}}
                            <td class="p-3">
                                @if ($row['rank'] == 1)
                                    <span class="px-3 py-1 text-sm font-semibold bg-green-100 text-green-700 rounded-lg">
                                        #1
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-700 rounded-lg">
                                        #{{ $row['rank'] }}
                                    </span>
                                @endif
                            </td>

                            {{-- Alternatif --}}
                            <td class="p-3 font-medium text-gray-800">
                                {{ $row['alternatif'] }}
                            </td>

                            {{-- Nilai Qi --}}
                            <td class="p-3 font-semibold text-gray-700">
                                {{ number_format($row['qi'], 4) }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-6 text-gray-500">
                                Data belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</x-filament::page>