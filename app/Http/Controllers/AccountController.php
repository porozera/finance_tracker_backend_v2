<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Method untuk membuat akun baru
    public function create(Request $request)
    {
        // Validate the request if necessary
        $request->validate([
            'name' => 'required|string|max:255',
            'holderName' => 'required|string|max:255',
            'accountNumber' => 'required|string|max:255',
            'icon' => 'required|integer',
            'color' => 'required|integer',
            'isDefault' => 'required|boolean',
        ]);
    
        // Create a new account
        $account = Account::create([
            'name' => $request->name,
            'holderName' => $request->holderName,
            'accountNumber' => $request->accountNumber,
            'icon' => $request->icon,
            'color' => $request->color,
            'isDefault' => $request->isDefault,
        ]);
    
        // Return response with the created account's ID or relevant information
        return response()->json([
            'id' => $account->id,
            'message' => 'Account created successfully.',
        ], 201);
    }
    
    // Method untuk mendapatkan semua akun
    public function index()
    {
        $accounts = Account::all();
        return response()->json($accounts);
    }

    // Method untuk mendapatkan akun dengan summary pengeluaran dan pemasukan
    public function getSummary()
    {
        $accounts = Account::with(['payments' => function($query) {
            $query->where('type', 'DR')->orWhere('type', 'CR');
        }])->get();

        $accounts = $accounts->map(function($account) {
            $income = $account->payments->where('type', 'CR')->sum('amount');
            $expense = $account->payments->where('type', 'DR')->sum('amount');
            $account->income = $income;
            $account->expense = $expense;
            $account->balance = $income - $expense;
            return $account;
        });

        return response()->json($accounts);
    }

    // Method untuk mengupdate akun
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $account->update($request->all());

        return response()->json(['message' => 'Account updated successfully', 'account' => $account]);
    }

    // Method untuk menghapus akun
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        // Menghapus pembayaran terkait akun
        $account->payments()->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }

    // Method untuk menghapus semua akun
    public function destroyAll()
    {
        Account::query()->delete();
        return response()->json(['message' => 'All accounts deleted successfully']);
    }
}
