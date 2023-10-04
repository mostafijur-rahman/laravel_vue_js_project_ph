<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Auth;
use Illuminate\Http\JsonResponse;
use Browser;
use Location;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = 'dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // for keep login activity
    protected function authenticated(Request $request, $user)
    {
        activity()
            ->by($user)
            ->withProperties(
                ['attributes' => [
                    'ip' => request()->ip(), 
                    'browser' => Browser::detect(),
                    // 'location' => Location::get(request()->ip())
                    ]
                ],

            )
            ->tap(function(Activity $activity) {
                $activity->causer_type = 'App\Models\User';
                $activity->causer_id = Auth::user()->id;
            })
            ->log('login');
    }
    
    public function logout(Request $request)
    {
        // activity log
        if(Auth::user() && Auth::user()->id){
            activity()->withProperties(
                ['attributes' => [
                    'ip' => request()->ip(), 
                    'browser' => Browser::detect(),
                    // 'location' => Location::get(request()->ip())
                    ]
                ]
            )
            ->tap(function(Activity $activity) {
                $activity->causer_type = 'App\Models\User';
                $activity->causer_id = Auth::user()->id;
            })
            ->log('logout');
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    public function username()
    {
        return 'phone';
    }
}
