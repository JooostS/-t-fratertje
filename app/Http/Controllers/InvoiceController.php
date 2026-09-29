<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        return view('invoices.index', [
            'invoices' => Invoice::with('member')->latest('issued_on')->latest('id')->paginate(20),
            'totalsByYear' => Invoice::query()
                ->selectRaw(
                    'year, SUM(amount) as netto, SUM(CASE WHEN type = ? THEN amount ELSE 0 END) as contributie, SUM(CASE WHEN type = ? THEN amount ELSE 0 END) as restitutie',
                    [Invoice::CONTRIBUTION, Invoice::REFUND]
                )
                ->groupBy('year')
                ->orderByDesc('year')
                ->get(),
        ]);
    }
}
