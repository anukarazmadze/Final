<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

       // Retrieve the user by email
    $account = User::where('email', $credentials['email'])->first();

    if (!$account) {
       throw new NotFoundHttpException();
    }

    if (!Hash::check($credentials['password'], $account->password)) {
        throw new NotFoundHttpException();
    }

    if ($account->email !== 'company@company.com') {
        return response()->json(['error' => 'Unauthorized.'], 403);
    }

    $account->tokens()->delete();

    $token = $account->createToken('PartnerCompanyToken')->plainTextToken;

    return response()->json(['token' => $token]);
    }
}
