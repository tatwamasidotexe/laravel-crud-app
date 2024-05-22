<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use \Mpdf\Mpdf as PDF; 

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

            $salt = strtolower(substr($name, 0, 3)) . implode("", explode("-", $dob));

            // Hashing the password
            $password = Hash::make($salt);

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

    public function update(Request $request, $u_id) {
        $user = User::find($u_id);

        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'User not found.'
            ]);
        }

        $user->username = $request->input('username');
        $user->dob = $request->input('dob');
        $user->phone_no = $request->input('phone_no');
        $user->email = strtolower($request->input('email'));
        $user->u_address = $request->input('u_address');
        $user->gender = $request->input('gender');
        $user->country_id = $request->input('country_id');
        $user->state_id = $request->input('state_id');
        $user->hobbies = $request->input('hobbies');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imgurl = rand() . "." . $image->getClientOriginalExtension();
            $path = public_path('assets/uploads/' . $imgurl);
            $image->move(public_path('assets/uploads'), $imgurl);

            // Delete old image if exists
            if ($user->img_url) {
                $oldImagePath = public_path('assets/uploads/' . $user->img_url);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $user->img_url = $imgurl;
        }

        // Save user
        $user->save();
        return response()->json([
            'status' => 'success', 
            'message' => 'Data updated successfully.'
        ]);
    }

    public function delete(Request $request, $u_id){
        
        $user = User::find($u_id);
        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "User not found."
            ]);
        }

        $img_url = $user->img_url;

        DB::beginTransaction();
        try {
            if ($img_url) {
                $image_path = public_path('assets/uploads/' . basename($img_url));
                if (File::exists($image_path)) {
                    File::delete($image_path);
                } else {
                    return response()->json([
                        "status" => "error",
                        "message" => "File could not be found at " . $image_path,
                    ]);
                }
            }
            $user->delete();
            DB::commit();

            return response()->json([
                "status" => "success",
                "message" => "Data deleted successfully.",
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => "error",
                "message" => "Error deleting data: " . $e->getMessage(),
            ]);
        }
    }

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
                'redirectURL' => route("dashboard"),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.',
        ], 401);
    }

    // public function download(Request $request, $u_id) {
        
    //     $username = (User::find($u_id))->username;

    //     if (!$username) {
    //         return response()->json([
    //             'status' => 'error', 
    //             'message' => 'User not found.'
    //         ]);
    //     }

    //     $documentFileName = "DegreeCert" . $u_id . ".pdf";
    
    //     // Render the Blade view to HTML
    //     $htmlContent = view('certificate', compact('u_id', 'username'))->render();
    
    //     // Create the mPDF document
    //     $document = new \Mpdf\Mpdf([
    //         'mode' => 'utf-8',
    //         'format' => 'A4-L',
    //         'margin_header' => '3',
    //         'margin_top' => '20',
    //         'margin_bottom' => '20',
    //         'margin_footer' => '2',
    //     ]);
    
    //     // Write the HTML content to the PDF
    //     $document->WriteHTML($htmlContent);
    
    //     // Save the PDF to the storage
    //     $filePath = 'public/' . $documentFileName;
    //     Storage::put($filePath, $document->Output('', 'I'));
    
    //     // Generate the URL to the file
    //     $fileUrl = Storage::url($filePath);

        // $document->OutputHttpDownload($documentFileName);
    
        // Send the PDF to the browser for download
        // return response($document->Output($documentFileName, 'D'))->header('Content-Type', 'application/pdf');
    
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'File generated successfully',
    //         'file_url' => $fileUrl
    //     ]);
    // }

    public function download(Request $request, $u_id) {
        try {    
            $user = User::find($u_id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ]);
            }
    
            $username = $user->username;
    
            // Use public_path() to get the absolute path for images
            $ximLogo = public_path('assets/images/XIM_University_Logo.png');
            $deanSign = public_path('assets/images/deanSign.png');
            $registrarSign = public_path('assets/images/registrarSign.png');
            $vcSign = public_path('assets/images/VCSign.png');

            $document = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
            ]);
            // $abfont = $document->SetFont('aboriginalsans');
            $dejavufont = $document->SetFont('dejavusanscondensed');
    
            $htmlContent = '
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body {
                            width: 100%;
                            height: 100%;
                            margin: 0;
                            padding: 10px;
                            font-family: Arial, sans-serif;
                            box-sizing: border-box;
                            overflow-x: hidden;
                            background: #f0f0f0;
                        }
                        .my-0 {
                            margin-top: 0;
                            margin-bottom: 0;
                        }
                        table {
                            background: rgb(241,255,231);
                            background: radial-gradient(circle, rgba(241,255,231,1) 0%, rgba(255,244,246,1) 35%, rgba(233,238,250,1) 100%);
                            width: 100%; 
                            border-collapse: collapse; 
                            border: 20px solid #1d3d70;
                        }
                        tr {
                            box-sizing: border-box;
                        }
                        .border-bottom td {
                            border-bottom: 1px solid black;
                        }
                        .header-row {
                            background: rgb(217,255,190);
                            background: linear-gradient(90deg, rgba(217,255,190,0.7904411764705882) 0%, rgba(255,240,242,1) 0%, rgba(223,231,250,1) 100%);
                        }
                        .logoCol {
                            text-align: center;
                            width: 5%;
                            padding-top: 40px;
                            padding-bottom: 40px;
                        } 
                        .headerTextCol {
                            text-align: center;
                            width: 90%;
                            font-family: "Times New Roman", Times, serif;
                            padding-top: 40px;
                            padding-bottom: 40px;
                        }
                        .degCertNumCol {
                            font-size: 20px; 
                            vertical-align: bottom;
                            width: 5%;
                            padding-left: 20px;
                            padding-bottom: 20px;
                        }
                        .certBody td {
                            padding-top: 90px;
                            padding-bottom: 50px;
                            width: 100%; 
                            text-align: center;
                            font-family: Georgia, serif;
                        }

                        .certBody td span {
                            font-family: "Brush Script MT", cursive;
                        }

                        .signatures td {
                            padding-top: 40px;
                            padding-bottom: 60px;
                            font-family: "Times New Roman", Times, serif;
                        }
                        .signatures img {
                            width: 300px;
                            height: auto;
                        }
                        .signatures h4 {
                            margin-top: 0;
                        }
                        .signatures td {
                            font-size: 30px;
                        }
                    </style>
                </head>
                <body>
                    <table>
                        <tr class="border-bottom header-row">
                            <td align="center" class="logoCol">
                                <img width="230px" height="230px" src="' . $ximLogo . '" alt="ximLogo">
                            </td>
                            <td class="headerTextCol">
                                <h1 style="font-size: 50px;">XIM UNIVERSITY</h1>
                                <p style="font-size: 30px;">(Established under the Xavier University, Odisha (Amendment) Act 2021)<br><br></p>
                                <h3 style="margin-top: 40px; font-size: 30px;">School of Computer Science and Engineering<br>Bhubaneswar</h3>
                            </td>
                            <td class="degCertNumCol">
                                <p class="my-0">XIM20240427UCSE200'.$u_id.'UB</p>
                            </td>  
                        </tr>
                        <tr class="certBody" style="width: 100%;">
                            <td colspan="3">
                                <p style="font-size: 30px;"><i>The Governing Board hereby certifies that<br><br></i></p>
                                <h1 style="font-size: 60px; margin-top: 20px !important;"><i>' . $username . '<br></i></h1>
                                <p style="font-size: 30px;"><i>
                                <br>Class 2020-2024<br><br>
                                    on the successful completion of all the requirements and on the<br>
                                    recommendation of the faculty is awarded the Degree of<br><br>
                                </i></p>
                                <h1 style="font-size: 60px;"><i>B.Tech in Computer Science & Engineering (Hons.)</i></h1>
                                <p style="font-size: 30px;"><i><br>
                                    with all its rights and privileges.
                                </i></p>
                                <p style="font-size: 30px;"><i>
                                    Given in Bhubaneswar, Odisha, India on 27th April 2024.
                                </i></p>
                            </td>
                        </tr>
                        <tr class="signatures">
                            <td style="text-align: center;">
                                <img src="' . $deanSign . '" alt="Dean Sign">
                                <h4>Dean (Academics)</h4>
                            </td>
                            <td style="text-align: center;">    
                                <img src="'. $registrarSign .'" alt="Registrar Sign">
                                <h4>Registrar</h4>
                            </td>
                            <td style="text-align: center;">
                                <img src="' . $vcSign . '" alt="VC Sign">
                                <h4>Vice-Chancellor</h4>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>
            ';
    
            // Set the watermark image
            $document->SetWatermarkImage($ximLogo, 0.17, '', [101, 70]);
            $document->showWatermarkImage = true;
    
            // Write the HTML content to the PDF
            $document->WriteHTML($htmlContent);
    
            // Set the PDF file name
            $documentFileName = "DegreeCert" . $u_id . ".pdf";
    
            // Return the PDF as a response
            return response($document->Output($documentFileName, 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$documentFileName.'"');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

}