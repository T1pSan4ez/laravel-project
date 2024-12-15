<?php

namespace App\Http\Controllers;

use App\Interfaces\PDFRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    protected $pdfRepository;

    public function __construct(PDFRepositoryInterface $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    public function index()
    {
        $movies = $this->pdfRepository->getMoviesWithSessions();
        $cities = $this->pdfRepository->getCitiesWithCinemas();
        return view('admin.pdf-generator', compact('movies', 'cities'));
    }

    public function preview(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'is_user', 'movie_id', 'city_id']);
        $purchases = $this->pdfRepository->getFilteredPurchases($filters);

        return response()->json(['purchases' => $purchases]);
    }

    public function generatePDF(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'is_user', 'movie_id', 'city_id']);
        $purchases = $this->pdfRepository->getFilteredPurchases($filters);

        $totalEarnings = $this->pdfRepository->calculateTotalEarnings($purchases);

        $selectedMovie = $filters['movie_id']
            ? $this->pdfRepository->getMovieTitleById($filters['movie_id'])
            : $this->pdfRepository->getMoviesWithSessions()->implode(', ');

        $selectedCity = $filters['city_id']
            ? $this->pdfRepository->getCityNameById($filters['city_id'])
            : $this->pdfRepository->getCitiesWithCinemas()->implode(', ');

        $data = [
            'title' => 'Purchase Report',
            'date' => now()->format('m/d/Y'),
            'purchases' => $purchases,
            'totalEarnings' => $totalEarnings,
            'startDate' => $filters['start_date'] ?? null,
            'endDate' => $filters['end_date'] ?? null,
            'isUser' => $filters['is_user'] ?? null,
            'selectedMovie' => $selectedMovie,
            'selectedCity' => $selectedCity,
        ];

        $pdf = Pdf::loadView('pdf.admin-report', $data);

        return response($pdf->stream('admin-report.pdf'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
