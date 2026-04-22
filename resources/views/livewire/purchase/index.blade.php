<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Riwayat Pembelian (Restock)</h2>
        </div>
        <a href="/purchases/create" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg border border-pink-600 transition text-sm font-semibold shadow">
            + Transaksi Baru
        </a>
    </div>

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-pink-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-pink-50 text-pink-700 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">No. Nota</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Status Brg</th>
                        <th class="px-4 py-3">Status Bayar</th>
                        <th class="px-4 py-3 text-right">Total Tagihan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($purchases as $purchase)
                    <tr class="hover:bg-pink-50/50 transition">
                        <td class="px-4 py-3">{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $purchase->reference_number ?: '-' }}</td>
                        <td class="px-4 py-3">{{ $purchase->supplier->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($purchase->status == 'pending')
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Draft</span>
                            @elseif($purchase->status == 'ordered')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Dipesan</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Diterima</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($purchase->payment_status == 'unpaid')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Belum Bayar</span>
                            @elseif($purchase->payment_status == 'partial')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">DP/Cicil</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Lunas</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-700">
                            Rp {{ number_format($purchase->total_amount + $purchase->shipping_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="/purchases/{{ $purchase->id }}/edit" class="text-blue-500 hover:text-blue-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button wire:click="delete({{ $purchase->id }})" class="text-pink-500 hover:text-red-600 p-1" onclick="return confirm('Hapus transaksi ini? {{ $purchase->status === 'received' ? 'Stok barang juga akan dikurangi secara otomatis.' : '' }}')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($purchases->isEmpty())
            <div class="text-center py-8 text-gray-400">
                Belum ada transaksi pembelian.
            </div>
            @endif
        </div>
    </div>
</div>
