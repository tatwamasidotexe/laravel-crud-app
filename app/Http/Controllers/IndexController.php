<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class IndexController extends Controller
{
    public function fetch(Request $request) {
        try {    
            $userData = DB::table('userdetails')
                        ->select('u_id', 'username', 'dob', 'phone_no', 'email', 'u_address', 'gender', 'state_id', 'country_id', 'hobbies', 'img_url')
                        ->get();

            $data = [];

            foreach($userData as $user) {
                $user->img_url = asset("assets/uploads/" . basename($user->img_url));

                $stateAndCountry = DB::table('states')
                        ->join('countries', 'states.country_id', '=', 'countries.country_id')
                        ->where('states.state_id', $user->state_id)
                        ->where('countries.country_id', $user->country_id)
                        ->select('states.state_name', 'countries.country_name')
                        ->first();

                if ($stateAndCountry) {
                    $user->state_name = $stateAndCountry->state_name;
                    $user->country_name = $stateAndCountry->country_name;
                } else {
                    return response()->json([
                        "status" => "error",
                        "message" => "Error fetching state and country names."
                    ]);
                }

                $data[] = $user;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Error fetching data from the database.",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function login(Request $request) {
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'success',
                'message' => 'User login successful',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.',
        ], 401);
    }
}