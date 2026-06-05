<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use function Termwind\render;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Vue de connexion
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Vue d'inscription
        // Fortify::registerView(function () {
        //     return view('auth.register');
        // });

        // Vue "Mot de passe oublié"
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        // Vue "Réinitialiser le mot de passe"
        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        // Vue de vérification d'email (optionnelle)
        // Fortify::verifyEmailView(function () {
        //     return view('auth.verify-email');
        // });

        // Vue de confirmation de mot de passe (optionnelle)
        // Fortify::confirmPasswordView(function () {
        //     return view('auth.confirm-password');
        // });

        // Vue d'authentification à deux facteurs (optionnelle)
        // Fortify::twoFactorChallengeView(function () {
        //     return view('auth.two-factor-challenge');
        // });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
