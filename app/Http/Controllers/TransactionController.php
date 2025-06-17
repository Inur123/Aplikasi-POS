<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
   public function index(Request $request)
{
    $categories = Category::all();

    // Mulai dengan query builder, bukan hasil akhir
    $query = Product::where('active', 1)->latest();

    // Filter berdasarkan kategori jika ada
    if ($request->has('category') && $request->category != '') {
        $query->where('category_id', $request->category);
    }

    // Ambil hasil akhirnya
    $products = $query->get();

    return view('transactions.index', compact('categories', 'products'));
}



    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $products = Product::all();
        return view('transactions.create', compact('products'));
    }

    /**
     * Store a newly created transaction in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qris,transfer',
            'payment' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            // Generate unique invoice number
            $invoice = 'INV-' . Str::upper(Str::random(8)) . date('Ymd');

            // Calculate totals
            $items = collect($request->items);
            $totalPrice = $items->sum(function ($item) {
                $product = Product::find($item['product_id']);
                $item['price'] = $product->price;
                $item['subtotal'] = $product->price * $item['quantity'];
                return $item['subtotal'];
            });

            // Check payment for cash method
            $payment = $request->payment;
            $change = $payment - $totalPrice;

            if ($request->payment_method === 'cash' && $change < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount is insufficient'
                ], 422);
            }

            // Create transaction
            $transaction = Transaction::create([
                'invoice' => $invoice,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'payment' => $payment,
                'change' => $change,
            ]);

            // Create transaction items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                // Update product stock (optional)
                $product->decrement('stock', $item['quantity']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'invoice' => $invoice
            ]);
        });
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            // Restore product stock (optional)
            foreach ($transaction->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $transaction->items()->delete();
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }

    /**
     * Print transaction receipt
     */
    public function print(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('transactions.print', compact('transaction'));
    }
}
