<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Facturenlijst met zoeken (lidnaam of kweeknummer), filter op soort en jaar, plus jaartotalen.
     */
    public function index(Request $request): View
    {
        return view('invoices.index', [
            'invoices' => Invoice::with('member')
                ->search($request->query('q'))
                ->when(in_array($request->query('type'), [Invoice::CONTRIBUTION, Invoice::REFUND], true), fn ($query) => $query->where('type', $request->query('type')))
                ->when($request->integer('year'), fn ($query, $year) => $query->where('year', $year))
                ->latest('issued_on')->latest('id')
                ->paginate(20)
                ->withQueryString(),
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

    /**
     * Downloadt één factuur (of creditnota) als PDF.
     */
    public function pdf(Invoice $invoice)
    {
        $invoice->load('member.address');

        return Pdf::loadView('invoices.pdf', ['invoice' => $invoice])
            ->download($invoice->number.'.pdf');
    }
}
