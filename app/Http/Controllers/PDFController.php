<?php
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Movie;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function index()
    {
        $movies = Movie::whereHas('sessions')->pluck('title', 'id');
        $cities = City::whereHas('cinemas.halls.sessions')->orderBy('name')->pluck('name', 'id');
        return view('admin.pdf-generator', compact('movies', 'cities'));
    }

    public function preview(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isUser = $request->input('is_user');
        $movieId = $request->input('movie_id');
        $cityId = $request->input('city_id');

        $query = Purchase::query()->with('items.sessionSlot.session.movie');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($isUser === 'user') {
            $query->whereNotNull('user_id');
        } elseif ($isUser === 'guest') {
            $query->whereNull('user_id');
        }

        if ($movieId) {
            $query->whereHas('items.sessionSlot.session.movie', function ($movieQuery) use ($movieId) {
                $movieQuery->where('id', $movieId);
            });
        }

        if ($cityId) {
            $query->whereHas('items.sessionSlot.session.hall.cinema.city', function ($cityQuery) use ($cityId) {
                $cityQuery->where('id', $cityId);
            });
        }

        $purchases = $query->get();

        return response()->json(['purchases' => $purchases]);
    }


    public function generatePDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isUser = $request->input('is_user');
        $movieId = $request->input('movie_id');
        $cityId = $request->input('city_id');

        $query = Purchase::query()->with('items.sessionSlot.session.movie');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($isUser === 'user') {
            $query->whereNotNull('user_id');
        } elseif ($isUser === 'guest') {
            $query->whereNull('user_id');
        }

        if ($movieId) {
            $query->whereHas('items.sessionSlot.session.movie', function ($movieQuery) use ($movieId) {
                $movieQuery->where('id', $movieId);
            });
        }

        if ($cityId) {
            $query->whereHas('items.sessionSlot.session.hall.cinema.city', function ($cityQuery) use ($cityId) {
                $cityQuery->where('id', $cityId);
            });
        }

        $purchases = $query->get();

        $totalEarnings = $purchases->flatMap->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $selectedMovie = $movieId ? Movie::find($movieId)->title : 'All Movies';
        $selectedCity = $cityId ? City::find($cityId)->name : 'All Cities';

        $data = [
            'title' => 'Purchase Report',
            'date' => now()->format('m/d/Y'),
            'purchases' => $purchases,
            'totalEarnings' => $totalEarnings,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isUser' => $isUser,
            'selectedMovie' => $selectedMovie,
            'selectedCity' => $selectedCity,
        ];

        $pdf = Pdf::loadView('pdf.admin-report', $data);

        return response($pdf->stream('admin-report.pdf'), 200)
            ->header('Content-Type', 'application/pdf');
    }

}
