<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ReportServiceInterface;
use App\Http\Middleware\PartnerCompanyAuthorization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PartnerCompanyController extends Controller
{
    protected $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $reportData = $this->reportService->index();
        return view('api.report', $reportData);
    }

    public function generateToken(Request $request)
{
    // Validate request data
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $account = User::where('email', $validated['email'])->first();

    if (!$account) {
        return response()->json(['error' => 'User not found.'], 404);
    }

    if (!Hash::check($validated['password'], $account->password)) {
        return response()->json(['error' => 'Invalid credentials.'], 401);
    }

    if ($account->email !== 'company@company.com') {
        return response()->json(['error' => 'Unauthorized.'], 403);
    }

    $account->tokens()->delete();

    $token = $account->createToken('PartnerCompanyToken')->plainTextToken;

    return response()->json(['token' => $token]);
}


    
}
