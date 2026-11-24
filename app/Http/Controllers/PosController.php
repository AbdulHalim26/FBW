<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePosRequest;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'price', 'stock', 'unit']);

        return view('pos.index', compact('products'));
    }

    public function store(StorePosRequest $request): RedirectResponse
    {
        try {
            $transaction = DB::transaction(function () use ($request) {
                $total = 0;
                $details = [];

                foreach ($request->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi (tersisa {$product->stock} {$product->unit}).");
                    }

                    $subtotal = $product->price * $item['qty'];
                    $total += $subtotal;

                    $details[] = [
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];

                    $product->decrement('stock', $item['qty']);
                }

                if ($request->pay < $total) {
                    throw new \Exception('Nominal bayar kurang dari total belanja.');
                }

                $trx = Transaction::create([
                    'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad(
                        Transaction::whereDate('created_at', now())->count() + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'user_id' => Auth::id(),
                    'total' => $total,
                    'pay' => $request->pay,
                    'change' => $request->pay - $total,
                ]);

                $trx->details()->createMany($details);

                return $trx;
            });

            return redirect()->route('pos.receipt', $transaction)
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function receipt(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');

        return view('pos.receipt', compact('transaction'));
    }

    public function history(): View
    {
        $transactions = Transaction::withSum('details as total_qty', 'qty')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pos.history', compact('transactions'));
    }
}
