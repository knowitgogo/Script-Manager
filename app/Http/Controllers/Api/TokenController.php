<?php

namespace App\Http\Controllers\Api;

use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class TokenController extends BaseController
{
    public function __construct(private readonly TokenService $tokenService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('tokens')->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    })
                ],
            ], [
                'name.unique' => 'This token already exists.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => __('messages.failed'),
                'errors' => $e->errors(),
            ], 422);
        }

        $tokenValue = Str::random(16);

        $token = $this->tokenService->createForUser(Auth::user(), [
            'name' => $validated['name'],
            'token' => $tokenValue,
        ]);

        return response()->json([
            'message' => __('messages.success'),
            'data' => [
                'id' => $token->id,
                'name' => $token->name,
                'token' => $token->token,
                'disabled' => (bool) $token->disabled,
                'created_at' => $token->created_at?->toIso8601String(),
            ],
        ], 201);
    }

    public function index(): JsonResponse
    {
        $tokens = $this->tokenService->getForUser(Auth::user());

        return response()->json([
            'data' => $tokens->map(fn ($token) => [
                'id' => $token->id,
                'name' => $token->name,
                'token' => $token->token,
                'disabled' => (bool) $token->disabled,
                'created_at' => $token->created_at?->toIso8601String(),
            ]),
        ]);
    }
}
