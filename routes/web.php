<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Category\Index as CategoryIndex;
use App\Livewire\Owner\Index as OwnerIndex;
use App\Livewire\Product\Index as ProductIndex;
use App\Livewire\Product\Create as ProductCreate;

use App\Livewire\Product\Detail as ProductDetail;

use App\Livewire\Category\Edit as CategoryEdit;
use App\Livewire\Owner\Edit as OwnerEdit;
use App\Livewire\Product\Edit as ProductEdit;
use App\Livewire\Variant\Index as VariantIndex;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Transaction\Index as TransactionIndex;
use App\Livewire\Transaction\Detail as TransactionDetail;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Models\Transaction;

Route::get('/', DashboardIndex::class);

Route::get('/transactions', TransactionIndex::class);
Route::get('/transactions/{id}', TransactionDetail::class)->where('id', '[0-9]+');

Route::get('/categories', CategoryIndex::class);
Route::get('/categories/{category}/edit', CategoryEdit::class);

Route::get('/owners', OwnerIndex::class);
Route::get('/owners/{owner}/edit', OwnerEdit::class);

Route::get('/variants', VariantIndex::class);

// Kasir (POS)
Route::get('/pos', PosIndex::class);
Route::get('/pos/print/{id}', function($id) {
    $transaction = Transaction::with(['customer', 'items'])->findOrFail($id);
    return view('pos.print', compact('transaction'));
});

Route::get('/products', ProductIndex::class);
Route::get('/products/create', ProductCreate::class);
Route::get('/products/{product}', ProductDetail::class);
Route::get('/products/{product}/edit', ProductEdit::class);
