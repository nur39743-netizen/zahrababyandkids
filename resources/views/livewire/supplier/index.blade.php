<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl font-bold text-pink-700">Manajemen Supplier</h2>
        </div>
        <button wire:click="openModal" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg border border-pink-600 transition text-sm font-semibold shadow">
            + Tambah Supplier
        </button>
    </div>

    <!-- List Suppliers -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($suppliers as $supplier)
        <div class="bg-white p-5 rounded-xl shadow-sm border border-pink-100 hover:shadow-md transition">
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-bold text-gray-800 text-lg">{{ $supplier->name }}</h3>
                <div class="flex gap-1">
                    <button wire:click="edit({{ $supplier->id }})" class="text-blue-400 hover:text-blue-600 transition p-1 hover:bg-blue-50 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button wire:click="delete({{ $supplier->id }})" class="text-pink-400 hover:text-red-500 transition p-1 hover:bg-pink-50 rounded-full" onclick="return confirm('Hapus supplier ini?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
            <div class="text-sm text-gray-600 space-y-1">
                @if($supplier->contact_person)
                <p><span class="font-semibold">Kontak:</span> {{ $supplier->contact_person }}</p>
                @endif
                @if($supplier->phone)
                <p><span class="font-semibold">Telp/WA:</span> {{ $supplier->phone }}</p>
                @endif
                @if($supplier->address)
                <p><span class="font-semibold">Alamat:</span> {{ Str::limit($supplier->address, 50) }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($suppliers->isEmpty())
    <div class="text-center text-gray-400 py-8 text-sm bg-white rounded-xl border border-dashed border-gray-200">
        Belum ada data supplier.
    </div>
    @endif

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" wire:click="closeModal"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full border border-gray-100">
                <form wire:submit="save">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                                {{ $isEdit ? 'Edit Supplier' : 'Tambah Supplier Baru' }}
                            </h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-red-500 transition bg-gray-50 hover:bg-red-50 rounded-full p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Supplier / Toko *</label>
                                <input type="text" wire:model="name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-4 py-2.5 border bg-gray-50 focus:bg-white transition" placeholder="Masukkan nama supplier" required>
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Contact Person</label>
                                <input type="text" wire:model="contact_person" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-4 py-2.5 border bg-gray-50 focus:bg-white transition" placeholder="Nama perwakilan (opsional)">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon / WA</label>
                                <input type="text" wire:model="phone" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-4 py-2.5 border bg-gray-50 focus:bg-white transition" placeholder="08...">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea wire:model="address" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-4 py-2.5 border bg-gray-50 focus:bg-white transition" placeholder="Alamat detail..."></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                                <textarea wire:model="notes" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-4 py-2.5 border bg-gray-50 focus:bg-white transition" placeholder="Catatan internal..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg border border-transparent px-6 py-2.5 bg-pink-600 text-sm font-bold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 shadow-md transition">
                            Simpan Data
                        </button>
                        <button type="button" wire:click="closeModal" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg border border-gray-300 px-6 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 shadow-sm transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
