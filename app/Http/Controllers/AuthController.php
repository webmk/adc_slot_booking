<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Container;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request, UserLevelService $userLevelService)
    {
        $request->validate([
            'cpf_no'   => 'required|string',
            'password' => 'required|string',
        ]);

        $cpf = $request->cpf_no;
        $password = $request->password;
        $user = User::where('cpf_no', $cpf)->first();
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return $this->redirectAfterLogin($user);
        }

        if (!$user) {
            $connection = Container::getConnection('default');
            $record = $connection->query()->findBy('samaccountname', $cpf);
            $level = $userLevelService->getUserLevel($cpf)?->GR_Res?->Level;

            if (!$record) {
                return false;
            }
            $user = User::create(
                [
                    'cpf_no'           => $cpf,
                    'name'             => $record['cn'][0] ?? null,
                    'email'            => $record['mail'][0] ?? null,
                    'mobile'           => $record['telephonenumber'][0] ?? null,
                    'location'         => $record['physicaldeliveryofficename'][0] ?? null,
                    'level'            => $level ?? null,
                    'password'         => Hash::make($password),
                ]
            );

            Auth::login($user);
            $request->session()->regenerate();
            return $this->redirectAfterLogin($user);
        }

        return back()->withErrors(['cpf_no' => 'Invalid CPF or password.',])->withInput();
    }



    private function redirectAfterLogin(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('employee.index'));
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
