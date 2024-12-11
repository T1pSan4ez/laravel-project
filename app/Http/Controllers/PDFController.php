<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function index()
    {
        return view('admin.pdf-generator');
    }

    public function preview(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isUser = $request->input('is_user');

        $query = Purchase::query()->with('items.sessionSlot.session.movie');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($isUser === 'user') {
            $query->whereNotNull('user_id');
        } elseif ($isUser === 'guest') {
            $query->whereNull('user_id');
        }

        $purchases = $query->get();

        return response()->json(['purchases' => $purchases]);
    }

    public function generatePDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isUser = $request->input('is_user');

        $query = Purchase::query()->with('items.sessionSlot.session.movie');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($isUser === 'user') {
            $query->whereNotNull('user_id');
        } elseif ($isUser === 'guest') {
            $query->whereNull('user_id');
        }


        $purchases = $query->get();

        $totalEarnings = $purchases->flatMap->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });


        $data = [
            'title' => 'Purchase Report',
            'date' => now()->format('m/d/Y'),
            'purchases' => $purchases,
            'totalEarnings' => $totalEarnings,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isUser' => $isUser,
        ];

        $pdf = Pdf::loadView('pdf.admin-report', $data);

        return response($pdf->stream('admin-report.pdf'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
