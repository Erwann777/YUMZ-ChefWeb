<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', 'in:customer,cooker'],
            'country'  => ['required', 'in:ID,SG,MY'],
        ]);

        $country  = $request->country;
        $currency = User::getCurrencyFromCountry($country);
        $initialBalance = User::getInitialWalletBalance($country);

        $user = DB::transaction(function () use ($request, $country, $currency, $initialBalance) {
            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'role'           => $request->role,
                'country'        => $country,
                'currency'       => $currency,
                'wallet_balance' => $initialBalance,
            ]);

            // Record initial wallet credit transaction
            WalletTransaction::create([
                'user_id'        => $user->id,
                'type'           => 'credit',
                'amount'         => $initialBalance,
                'currency'       => $currency,
                'reference_type' => 'initial_credit',
                'description'    => '🎁 Saldo awal virtual wallet (Simulasi)',
            ]);

            return $user;
        });

        Auth::login($user);

        ActivityLog::log(
            'register',
            "{$user->name} registered as {$user->role} from {$user->getCountryName()} ({$user->currency})",
            $user->id, null, $request->ip()
        );

        return redirect()->intended($this->redirectByRole($user));
    }

    /**
     * Get the redirect path based on user role.
     */
    protected function redirectByRole(User $user): string
    {
        return match ($user->role) {
            'admin'  => route('admin.dashboard'),
            'cooker' => route('cooker.dashboard'),
            default  => route('dashboard'),
        };
    }
}
