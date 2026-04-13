<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Category\Index as CategoryIndex;
use App\Livewire\Category\Trashed as CategoryTrashed;
use App\Livewire\Category\Detail as CategoryDetail;
use App\Livewire\Owner\Index as OwnerIndex;
use App\Livewire\Owner\Detail as OwnerDetail;
use App\Livewire\Customer\Index as CustomerIndex;
use App\Livewire\Customer\Detail as CustomerDetail;
use App\Livewire\Customer\Edit as CustomerEdit;
use App\Livewire\Product\Index as ProductIndex;
use App\Livewire\Product\Create as ProductCreate;
use App\Livewire\Product\Detail as ProductDetail;
use App\Livewire\Product\Trashed as ProductTrashed;

use App\Livewire\Category\Edit as CategoryEdit;
use App\Livewire\Owner\Edit as OwnerEdit;
use App\Livewire\Product\Edit as ProductEdit;
use App\Livewire\Variant\Index as VariantIndex;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Transaction\Index as TransactionIndex;
use App\Livewire\Transaction\Detail as TransactionDetail;
use App\Livewire\Transaction\Edit as TransactionEdit;
use App\Livewire\Transaction\Trashed as TransactionTrashed;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Models\Transaction;

Route::get('/', DashboardIndex::class);

Route::get('/transactions', TransactionIndex::class);
Route::get('/transactions/trashed', TransactionTrashed::class);
Route::get('/transactions/{id}/edit', TransactionEdit::class)->where('id', '[0-9]+');
Route::get('/transactions/{id}', TransactionDetail::class)->where('id', '[0-9]+');

Route::get('/categories', CategoryIndex::class);
Route::get('/categories/trashed', CategoryTrashed::class);
Route::get('/categories/{category}/edit', CategoryEdit::class);
Route::get('/categories/{id}', CategoryDetail::class)->where('id', '[0-9]+');

Route::get('/owners', OwnerIndex::class);
Route::get('/owners/{owner}/edit', OwnerEdit::class);
Route::get('/owners/{id}', OwnerDetail::class)->where('id', '[0-9]+');

Route::get('/customers', CustomerIndex::class);
Route::get('/customers/{id}', CustomerDetail::class)->where('id', '[0-9]+');
Route::get('/customers/{id}/edit', CustomerEdit::class)->where('id', '[0-9]+');

Route::get('/variants', VariantIndex::class);

// Kasir (POS)
Route::get('/pos', PosIndex::class);
Route::get('/pos/print/{id}', function ($id) {
    $transaction = Transaction::with(['customer', 'items'])->findOrFail($id);
    return view('pos.print', compact('transaction'));
});

Route::get('/products', ProductIndex::class);
Route::get('/products/create', ProductCreate::class);
Route::get('/products/trashed', ProductTrashed::class);
Route::get('/products/{product}', ProductDetail::class);
Route::get('/products/{product}/edit', ProductEdit::class);
