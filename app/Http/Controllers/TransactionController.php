<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        // Start with query builder
        $query = Product::where('active', 1)->latest();

        // Filter by category if provided
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Get the final results
        $products = $query->get();

        return view('transactions.index', compact('categories', 'products'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $products = Product::where('active', 1)->get();
        return view('transactions.create', compact('products'));
    }

    /**
     * Store a newly created transaction in storage.
     */
   public function store(Request $request)
{
    try {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qris,transfer',
            'payment' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            // Generate unique invoice number
            $invoice = 'INV-' . Str::upper(Str::random(8)) . '-' . date('Ymd');

            // Calculate totals
            $items = collect($request->items);
            $totalPrice = 0;
            $processedItems = [];

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product not found: {$item['product_id']}");
                }

                if (!$product->active) {
                    throw new \Exception("Product is not active: {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                $processedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ];
            }

            // Validate payment for cash method
            $payment = $request->payment;
            $change = $payment - $totalPrice;

            if ($request->payment_method === 'cash' && $change < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount is insufficient. Total: Rp ' . number_format($totalPrice, 0, ',', '.') . ', Payment: Rp ' . number_format($payment, 0, ',', '.')
                ], 422);
            }

            // For non-cash payments, set payment equal to total
            if ($request->payment_method !== 'cash') {
                $payment = $totalPrice;
                $change = 0;
            }

            // Create transaction
            $transaction = Transaction::create([
                'invoice' => $invoice,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'payment' => $payment,
                'change' => $change,
                'status' => 'completed',
                'created_at' => now(),
            ]);

            // Create transaction items
            foreach ($processedItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => [
                    'invoice' => $invoice,
                    'total_price' => $totalPrice,
                    'payment' => $payment,
                    'change' => $change,
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $transaction->id
                ]
            ]);
        });

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Transaction creation failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
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
        try {
            DB::transaction(function () use ($transaction) {
                // Restore product stock
                foreach ($transaction->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                $transaction->items()->delete();
                $transaction->delete();
            });

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction deleted successfully');
        } catch (\Exception $e) {
            Log::error('Transaction deletion failed: ' . $e->getMessage());
            return redirect()->route('transactions.index')
                ->with('error', 'Failed to delete transaction');
        }
    }

    /**
     * Print transaction receipt
     */
    public function print(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('transactions.print', compact('transaction'));
    }

    /**
     * Get transaction history
     */
    public function history(Request $request)
    {
        $query = Transaction::with('items.product')->latest();

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->paginate(20);

        return view('transactions.history', compact('transactions'));
    }
}
