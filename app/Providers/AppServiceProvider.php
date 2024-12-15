<?php

namespace App\Providers;

use App\Interfaces\ApiAuthRepositoryInterface;
use App\Interfaces\ApiCinemaRepositoryInterface;
use App\Interfaces\ApiMovieCommentRepositoryInterface;
use App\Interfaces\ApiMovieDiscoverRepositoryInterface;
use App\Interfaces\ApiMovieRatingRepositoryInterface;
use App\Interfaces\ApiMovieRepositoryInterface;
use App\Interfaces\ApiPaymentRepositoryInterface;
use App\Interfaces\ApiProductRepositoryInterface;
use App\Interfaces\ApiSessionRepositoryInterface;
use App\Interfaces\ApiUserRepositoryInterface;
use App\Interfaces\CinemaRepositoryInterface;
use App\Interfaces\DashboardRepositoryInterface;
use App\Interfaces\GoogleAuthRepositoryInterface;
use App\Interfaces\HallRepositoryInterface;
use App\Interfaces\MovieRepositoryInterface;
use App\Interfaces\PDFRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\PurchaseRepositoryInterface;
use App\Interfaces\QRCodeRepositoryInterface;
use App\Interfaces\SessionRepositoryInterface;
use App\Interfaces\SessionSlotRepositoryInterface;
use App\Interfaces\UserActivityRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ApiAuthRepository;
use App\Repositories\ApiCinemaRepository;
use App\Repositories\ApiMovieCommentRepository;
use App\Repositories\ApiMovieDiscoverRepository;
use App\Repositories\ApiMovieRatingRepository;
use App\Repositories\ApiMovieRepository;
use App\Repositories\ApiPaymentRepository;
use App\Repositories\ApiProductRepository;
use App\Repositories\ApiSessionRepository;
use App\Repositories\ApiUserRepository;
use App\Repositories\CinemaRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\GoogleAuthRepository;
use App\Repositories\HallRepository;
use App\Repositories\MovieRepository;
use App\Repositories\PDFRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\QRCodeRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionSlotRepository;
use App\Repositories\UserActivityRepository;
use App\Repositories\UserRepository;
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
