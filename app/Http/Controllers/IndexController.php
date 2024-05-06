<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function insert(Request $request) {
        if ($request->isMethod('post')) {
            $name = $request->input('username');
            $dob = $request->input('dob');
            $phone = $request->input('phone_no');
            $email = strtolower($request->input('email'));
            $address = $request->input('u_address');
            $gender = $request->input('gender');
            $country_id = $request->input('country_id');
            $state_id = $request->input('state_id');
            $hobbies = $request->input('hobbies');

            // Constructing password from username first 3 chars and dob
            $salt = strtolower(substr($name, 0, 3)) . implode("", explode("-", $dob));

            // Hashing the password
            $password = Hash::make($salt);

            // Uploading image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imgurl = rand() . "." . $image->getClientOriginalExtension();
                $image->move(public_path('assets/uploads'), $imgurl);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Image file not found.']);
            }

            $user = new User();
            $user->username = $name;
            $user->dob = $dob;
            $user->phone_no = $phone;
            $user->email = $email;
            $user->u_address = $address;
            $user->gender = $gender;
            $user->country_id = $country_id;
            $user->state_id = $state_id;
            $user->hobbies = $hobbies;
            $user->img_url = $imgurl;
            $user->password = $password;

            // Save the user record
            $user->save();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data insertion successful.'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid request.']);
        }
    }

    public function update(Request $request, $u_id){}

    public function getCountries(Request $request) {
        try {    
            $countries = DB::table('countries')
                        ->select('country_id', 'country_name')
                        ->get();

            $data = [];

            foreach($countries as $country) {
                $data[] = $country;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Error fetching countries from the database.",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function getStates(Request $request, $country_id) {
        try {    

            $states = DB::table('states')
                        ->select('state_id', 'state_name')
                        ->where('country_id', $country_id)
                        ->get();

            $data = [];

            foreach($states as $state) {
                $data[] = $state;
            }

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Error fetching states from the database for country_id $country_id",
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