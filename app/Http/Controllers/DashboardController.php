<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {    
    public function index() {

        if (Auth::check()) {

            $userDetails = User::where('u_id', Auth::id())->first();
            
            if ($userDetails) {
                $userDetails->img_url = asset("assets/uploads/" . basename($userDetails->img_url));
                return view('dashboard')->with('userDetails', $userDetails);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User details not found.'
                ], 404);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function fetchData(Request $request) {
        if (Auth::check()) {
            $userDetails = User::where('u_id', Auth::id())->first();

            if ($userDetails) {
                $userDetails->img_url = asset("assets/uploads/" . basename($userDetails->img_url));
                
                $stateName = DB::table('states')
                    ->where('state_id', $userDetails->state_id)
                    ->value('state_name');

                $countryName = DB::table('countries')
                    ->where('country_id', $userDetails->country_id)
                    ->value('country_name');

                $userDetails->state_name = $stateName;
                $userDetails->country_name = $countryName;

                return response()->json($userDetails);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User details not found.'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.'
            ], 401);
        }
    }

    public function logOut() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'redirectURL' => route("login"),
        ]);
    }
}