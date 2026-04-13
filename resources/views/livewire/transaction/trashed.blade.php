<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Transaksi Terhapus</h2>
            <p class="text-sm text-gray-500">Riwayat transaksi yang sudah dinonaktifkan.</p>
        </div>
        <a href="/transactions" class="bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-sm font-semibold">Kembali ke Transaksi</a>
    </div>

    @if(session()->has('success'))
    <div class="p-3 bg-green-50 text-green-700 rounded border border-green-100 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-pink-50 overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-gray-500 bg-pink-50/50 border-b border-pink-100">
                <tr>
                    <th class="py-3 px-3">Invoice</th>
                    <th class="py-3 px-3">Pelanggan</th>
                    <th class="py-3 px-3">Total Netto</th>
                    <th class="py-3 px-3">Dihapus Pada</th>
                    <th class="py-3 px-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-3 font-semibold text-gray-800">{{ $trx->no_invoice }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ $trx->customer ? $trx->customer->nama_customer : 'Pelanggan Umum' }}</td>
                    <td class="py-3 px-3 text-pink-600 font-bold">Rp{{ number_format($trx->total_netto, 0, ',', '.') }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ $trx->deleted_at->format('d M Y H:i') }}</td>
                    <td class="py-3 px-3 text-center space-x-2">
                        <button wire:click="restore({{ $trx->id }})" wire:confirm="Apakah Anda yakin ingin mengembalikan transaksi ini?" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600 transition">Restore</button>
                        <button wire:click="forceDelete({{ $trx->id }})" wire:confirm="PERINGATAN: Ini akan menghapus transaksi secara permanen. Lanjutkan?" class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600 transition">Hapus Permanen</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada transaksi terhapus.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>