<?php

namespace App\Http\Controllers;

use App\Models\FundTransaction;
use App\Services\FundService;
use Illuminate\Http\Request;

class FundController extends Controller
{
    protected FundService $fundService;

    public function __construct(FundService $fundService)
    {
        $this->fundService = $fundService;
    }

    /**
     * Fund monitoring page.
     */
    public function index(Request $request)
    {
        $this->authorize('manage-dues');

        $summary = $this->fundService->getSummary();

        $query = FundTransaction::with('recorder')->latest('transacted_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('date_from')) {
            $query->where('transacted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('transacted_at', '<=', $request->date_to.' 23:59:59');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.fund.index', compact('summary', 'transactions'));
    }

    /**
     * Store a manual transaction.
     */
    public function storeTransaction(Request $request)
    {
        $this->authorize('manage-dues');

        $validated = $request->validate([
            'type' => 'required|in:inflow,outflow',
            'category' => 'required|in:manual_adjustment,donation,other',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
            'transacted_at' => 'nullable|date',
        ]);

        $this->fundService->recordManualTransaction($validated, auth()->user());

        return redirect()->route('admin.fund.index')->with('success', 'Transaction recorded successfully.');
    }

    /**
     * Export transactions to CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('manage-dues');

        $query = FundTransaction::with('recorder')->latest('transacted_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $transactions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="fund-transactions-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reference', 'Date', 'Type', 'Category', 'Amount', 'Description', 'Notes', 'Recorded By']);

            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->reference_number,
                    $txn->transacted_at->format('Y-m-d H:i'),
                    $txn->type,
                    FundTransaction::CATEGORIES[$txn->category] ?? $txn->category,
                    $txn->amount,
                    $txn->description,
                    $txn->notes,
                    $txn->recorder->name ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
