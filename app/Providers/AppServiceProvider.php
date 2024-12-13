<?php

namespace App\Providers;

use App\Repositories\ApiAuthRepository;
use App\Repositories\ApiAuthRepositoryInterface;
use App\Repositories\ApiCinemaRepository;
use App\Repositories\ApiCinemaRepositoryInterface;
use App\Repositories\ApiMovieCommentRepository;
use App\Repositories\ApiMovieCommentRepositoryInterface;
use App\Repositories\ApiMovieDiscoverRepository;
use App\Repositories\ApiMovieDiscoverRepositoryInterface;
use App\Repositories\ApiMovieRatingRepository;
use App\Repositories\ApiMovieRatingRepositoryInterface;
use App\Repositories\ApiMovieRepository;
use App\Repositories\ApiMovieRepositoryInterface;
use App\Repositories\ApiPaymentRepository;
use App\Repositories\ApiPaymentRepositoryInterface;
use App\Repositories\ApiProductRepository;
use App\Repositories\ApiProductRepositoryInterface;
use App\Repositories\ApiSessionRepository;
use App\Repositories\ApiSessionRepositoryInterface;
use App\Repositories\ApiUserRepository;
use App\Repositories\ApiUserRepositoryInterface;
use App\Repositories\CinemaRepository;
use App\Repositories\CinemaRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\DashboardRepositoryInterface;
use App\Repositories\GoogleAuthRepository;
use App\Repositories\GoogleAuthRepositoryInterface;
use App\Repositories\HallRepository;
use App\Repositories\HallRepositoryInterface;
use App\Repositories\MovieRepository;
use App\Repositories\MovieRepositoryInterface;
use App\Repositories\PDFRepository;
use App\Repositories\PDFRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\PurchaseRepository;
use App\Repositories\PurchaseRepositoryInterface;
use App\Repositories\QRCodeRepository;
use App\Repositories\QRCodeRepositoryInterface;
use App\Repositories\SessionRepository;
use App\Repositories\SessionRepositoryInterface;
use App\Repositories\SessionSlotRepository;
use App\Repositories\SessionSlotRepositoryInterface;
use App\Repositories\UserActivityRepository;
use App\Repositories\UserActivityRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CinemaRepositoryInterface::class, CinemaRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(HallRepositoryInterface::class, HallRepository::class);
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(PDFRepositoryInterface::class, PDFRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ApiAuthRepositoryInterface::class, ApiAuthRepository::class);
        $this->app->bind(GoogleAuthRepositoryInterface::class, GoogleAuthRepository::class);
        $this->app->bind(QRCodeRepositoryInterface::class, QRCodeRepository::class);
        $this->app->bind(ApiCinemaRepositoryInterface::class, ApiCinemaRepository::class);
        $this->app->bind(ApiMovieCommentRepositoryInterface::class, ApiMovieCommentRepository::class);
        $this->app->bind(ApiMovieRepositoryInterface::class, ApiMovieRepository::class);
        $this->app->bind(ApiMovieDiscoverRepositoryInterface::class, ApiMovieDiscoverRepository::class);
        $this->app->bind(ApiMovieRatingRepositoryInterface::class, ApiMovieRatingRepository::class);
        $this->app->bind(ApiPaymentRepositoryInterface::class, ApiPaymentRepository::class);
        $this->app->bind(ApiProductRepositoryInterface::class, ApiProductRepository::class);
        $this->app->bind(ApiSessionRepositoryInterface::class, ApiSessionRepository::class);
        $this->app->bind(ApiUserRepositoryInterface::class, ApiUserRepository::class);
        $this->app->bind(PurchaseRepositoryInterface::class, PurchaseRepository::class);
        $this->app->bind(SessionSlotRepositoryInterface::class, SessionSlotRepository::class);
        $this->app->bind(UserActivityRepositoryInterface::class, UserActivityRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
