<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Purchase::with('items');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $purchases = $query->get();

        $totalEarnings = $purchases->flatMap->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Разделение на группы User и Guest
        $userPurchases = $purchases->whereNotNull('user_id');
        $guestPurchases = $purchases->whereNull('user_id');

        return view('admin.pdf-generator', compact('userPurchases', 'guestPurchases', 'totalEarnings', 'startDate', 'endDate'));
    }

    public function generatePDF(Request $request)
    {
        $selectedPurchases = Purchase::with('items')
            ->whereIn('id', $request->input('selected_purchases', []))
            ->get();

        $data = [
            'title' => 'Admin Report',
            'date' => date('m/d/Y'),
            'purchases' => $selectedPurchases,
            'totalEarnings' => $selectedPurchases->flatMap->items->sum(function ($item) {
                return $item->quantity * $item->price;
            }),
        ];

        $pdf = Pdf::loadView('pdf.admin-report', $data);

        return response($pdf->stream('admin-report.pdf'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
