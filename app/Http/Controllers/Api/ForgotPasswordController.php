<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\codePassword;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

public function ForgotPassword(Request $request)
    {

        $request->validate([
            'contact' => 'required|max:10',
        ]);

        //$contactAdmin = $request->contactAdmin;
        // Check if the phone number is present in the database
        $Admin = Utilisateur::where('contact', $request->contact)->first();
        if (!$Admin) {
            return response()->json([
                'success' => false,
                'message' => 'votre numero est inconnu'
            ],);
        }
        // Generate a random confirmation code
        // $verificationCode = rand(100000, 999999);
        $verificationCode = mt_rand(1000, 9999);
        $number = $Admin->contact;
        $name = $Admin->nomAdmin;
        $contact = "225$number";

        $code = codePassword::where('code', $verificationCode)->first();

        $verifyIsOk = codePassword::firstOrCreate(
            [
                'contact' => $contact,
                'code' => $verificationCode,
            ]
        );

        // Send the request to the VavaSMS API
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://vavasms.com/api/v1/text/single",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=ibnmassoudkouakou@gmail.com&password=imRu@KZfF@JB5SM&sender_id=KOFASANTE&phone=$contact&message=Votre code de validation est: $verificationCode",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Content-Type: application/x-www-form-urlencoded",
                "Host: vavasms.com"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $responseData=json_decode($response,true);//convertir lea response json
        if ($err) {
             echo "cURL Error #:" . $err;

        } else {
            if($responseData['code'] == 0) {
                    // Checks whether the column is empty or not"
                if (($code->code) == Null) {
                    // Inserts the new "$verification" value in the "codeConfirm" column
                    $code->code = $verificationCode;
                    $code->save();
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'le code a ete envoyer avec suces',
                            'code'=>$verificationCode
                        ],
                        200
                    );
                } else {
                    $code->code = Null;
                    $code->save();
                    // Inserts the new "$verification" value in the "codeConfirm" column
                    $code->code = $verificationCode;
                    $code->save();
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'le code de confirmation a ete bien envoyer',
                            'code'=>$verificationCode
                        ],
                        200
                    );
                }

            }elseif($responseData['code'] == 903){
                    return response()->json([
                        'success' => false,
                        'message' => 'le format ou numero  incorrecte '
                    ],);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => "erreur de serveur",
                    'code'=>$verificationCode
                ],);
            }

        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
