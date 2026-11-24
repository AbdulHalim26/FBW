@extends('layouts.app')

@section('title', 'Kasir (POS)')
@section('header', 'Transaksi Penjualan (Point of Sale)')

@section('content')
<div x-data="posCart(@js($products))" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Kolom Kiri: Pencarian & Daftar Produk --}}
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between gap-3 mb-4">
            <input type="text" x-model="keyword" placeholder="Cari nama atau kode produk..."
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            <a href="{{ route('pos.history') }}"
                class="shrink-0 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-3 py-2 rounded-md">
                Riwayat Saya
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-[460px] overflow-y-auto pr-1">
            <template x-for="product in filteredProducts()" :key="product.id">
                <button type="button" @click="addItem(product)"
                    class="border border-gray-200 rounded-lg p-3 text-left hover:bg-indigo-50 hover:border-indigo-300 transition cursor-pointer">
                    <p class="text-[11px] font-mono text-gray-400" x-text="product.code"></p>
                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="product.name"></p>
                    <p class="text-xs text-gray-500 mt-1" x-text="'Stok: ' + product.stock + ' ' + product.unit"></p>
                    <p class="text-sm font-bold text-indigo-600 mt-1" x-text="formatRupiah(product.price)"></p>
                </button>
            </template>
        </div>
    </div>

    {{-- Kolom Kanan: Keranjang Belanja --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 h-fit">
        <h3 class="text-base font-semibold text-gray-800 mb-3">Keranjang Belanja</h3>

        <form action="{{ route('pos.store') }}" method="POST">
            @csrf

            <div class="space-y-2.5 max-h-[260px] overflow-y-auto mb-3">
                <template x-if="cart.length === 0">
                    <p class="text-sm text-gray-400 text-center py-6 border border-dashed rounded-md">
                        Belum ada item dipilih.
                    </p>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center justify-between gap-2 text-sm border-b pb-2">
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-gray-900 truncate" x-text="item.name"></p>
                            <p class="text-xs text-gray-500" x-text="formatRupiah(item.price) + ' / ' + item.unit"></p>
                        </div>

                        <input type="number" min="1" :max="item.stock" x-model.number="item.qty"
                            @input="clampQty(item)"
                            class="w-16 rounded-md border border-gray-300 px-2 py-1 text-center text-sm">

                        <button type="button" @click="removeItem(index)"
                            class="text-red-500 hover:text-red-700 font-bold px-1 cursor-pointer">&times;</button>

                        <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                        <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">
                    </div>
                </template>
            </div>

            <div class="border-t pt-3 space-y-2">
                <div class="flex justify-between text-base font-bold text-gray-900">
                    <span>Total</span>
                    <span x-text="formatRupiah(total())"></span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nominal Bayar (Rp)</label>
                    <input type="number" name="pay" x-model.number="pay" min="0"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                </div>

                <div class="flex justify-between text-sm text-gray-700 pt-1">
                    <span>Kembalian</span>
                    <span class="font-semibold text-emerald-700" x-text="formatRupiah(change())"></span>
                </div>

                <button type="submit" :disabled="cart.length === 0 || pay < total()"
                    class="w-full mt-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-md disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function posCart(products) {
        return {
            products: products,
            cart: [],
            keyword: '',
            pay: 0,

            filteredProducts() {
                if (!this.keyword) return this.products;
                const k = this.keyword.toLowerCase();
                return this.products.filter(p =>
                    p.name.toLowerCase().includes(k) || p.code.toLowerCase().includes(k)
                );
            },

            addItem(product) {
                const existing = this.cart.find(i => i.id === product.id);
                if (existing) {
                    if (existing.qty < product.stock) existing.qty++;
                    return;
                }
                this.cart.push({
                    ...product,
                    qty: 1
                });
            },

            clampQty(item) {
                if (item.qty < 1) item.qty = 1;
                if (item.qty > item.stock) item.qty = item.stock;
            },

            removeItem(index) {
                this.cart.splice(index, 1);
            },

            total() {
                return this.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            },

            change() {
                return Math.max(this.pay - this.total(), 0);
            },

            formatRupiah(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            },
        };
    }
</script>
@endpush