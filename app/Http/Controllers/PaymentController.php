<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Menambahkan Payment baru
    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'account' => 'required|integer',
            'category' => 'required|integer',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:CR,DR', // Validasi nilai type hanya CR atau DR
            'datetime' => 'nullable|date_format:Y-m-d H:i:s',
        ]);
    
        // Simpan data pembayaran
        $payment = new Payment($validated);
        $payment->save();
    
        $account = Account::findOrFail($validated['account']); 
    
        if ($validated['type'] == "CR") {
            $account->income += $validated['amount'];
            $account->balance += $validated['amount'];
        } elseif ($validated['type'] == "DR") {
            $account->expense += $validated['amount'];
            $account->balance -= $validated['amount'];
        }
        $account->save();

        $category = Category::findOrFail($validated['category']);
        if ($validated['type'] == "DR"){
            $category->expense += $validated['amount'];
        }
        $category->save();
    
        return response()->json([
            'id' => $payment->id,
            'message' => 'Payment created successfully.',
        ], 201);
    }
    

    // Menampilkan daftar pembayaran dengan filter
    public function index(Request $request)
    {
        $query = Payment::query();
        
        // Filter berdasarkan rentang waktu
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('datetime', [$startDate, $endDate]);
        }

        // Filter berdasarkan jenis pembayaran (DR/CR)
        if ($request->has('type')) {
            $type = $request->input('type');
            $query->where('type', $type);
        }

        // Filter berdasarkan akun
        if ($request->has('account_id')) {
            $accountId = $request->input('account_id');
            $query->where('account', $accountId);
        }

        // Filter berdasarkan kategori
        if ($request->has('category_id')) {
            $categoryId = $request->input('category_id');
            $query->where('category', $categoryId);
        }

        // Ambil hasil query dengan urutan tanggal terbaru
        $payments = $query->with(['account', 'category'])->orderByDesc('datetime')->get();

        return response()->json($payments);
    }

    // Mengupdate Payment
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'account' => 'required|integer',
            'category' => 'required|integer',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:CR,DR', // Validasi nilai type hanya CR atau DR
            'datetime' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        // Temukan data pembayaran lama
        $payment = Payment::findOrFail($id);

        // Ambil akun dan kategori terkait
        $account = Account::findOrFail($payment->account);
        $category = Category::findOrFail($payment->category);

        // Kembalikan jumlah lama ke nilai awal
        if ($payment->type == "CR") {
            $account->income -= $payment->amount;
            $account->balance -= $payment->amount;
        } elseif ($payment->type == "DR") {
            $account->expense -= $payment->amount;
            $account->balance += $payment->amount;
            $category->expense -= $payment->amount;
        }

        // Simpan perubahan pada akun dan kategori
        $account->save();
        $category->save();

        // Perbarui data pembayaran dengan data baru
        $payment->update($validated);

        // Terapkan perubahan baru pada akun dan kategori
        $account = Account::findOrFail($validated['account']);
        $category = Category::findOrFail($validated['category']);

        if ($validated['type'] == "CR") {
            $account->income += $validated['amount'];
            $account->balance += $validated['amount'];
        } elseif ($validated['type'] == "DR") {
            $account->expense += $validated['amount'];
            $account->balance -= $validated['amount'];
            $category->expense += $validated['amount'];
        }

        // Simpan perubahan akhir
        $account->save();
        $category->save();

        return response()->json([
            'id' => $payment->id,
            'message' => 'Payment updated successfully.',
        ], 200);
    }

    // Menghapus Payment
    public function delete($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();

        return response()->json(['message' => 'Payment successfully deleted']);
    }

    // Menghapus semua pembayaran
    public function deleteAll()
    {
        Payment::query()->delete();

        return response()->json(['message' => 'All payments have been deleted']);
    }
}
