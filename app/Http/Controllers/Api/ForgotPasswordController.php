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

    $Admin = Utilisateur::where('contact', $request->contact)->first();
    if (!$Admin) {
        return response()->json([
            'success' => false,
            'message' => 'Votre numéro est inconnu'
        ]);
    }

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
    $responseData = json_decode($response, true);

    if ($err) {
        return response()->json([
            'success' => false,
            'message' => "Erreur cURL : $err"
        ]);
    } else {
        if ($responseData['code'] == 0) {
            if (($code->code) == Null) {
                $code->code = $verificationCode;
                $code->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Le code a été envoyé avec succès',
                    'code' => [
                        'code' => $verificationCode,
                        'contact' => $contact,
                        'message' => 'Le code a été envoyé avec succès'
                    ]
                ]);
            } else {
                $code->code = Null;
                $code->save();
                $code->code = $verificationCode;
                $code->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Le code de confirmation a été bien envoyé',
                    'code' => [
                        'code' => $verificationCode,
                        'contact' => $contact,
                        'message' => 'Le code de confirmation a été bien envoyé'
                    ]
                ]);
            }
        } elseif ($responseData['code'] == 903) {
            return response()->json([
                'success' => false,
                'message' => 'Le format ou le numéro est incorrect'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Erreur de serveur",
                'code' => [
                    'code' => $verificationCode,
                    'contact' => $contact,
                    'message' => "Erreur de serveur"
                ]
            ]);
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
