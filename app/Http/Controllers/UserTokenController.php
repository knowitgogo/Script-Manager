<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\TokenService;
use Illuminate\Validation\Rule;

class UserTokenController extends BaseController
{
    public function index(Request $request, TokenService $tokenService)
    {
        $tokens = $tokenService->paginateForUser(Auth::user());

        if ($tokens->lastPage() > 0 && $tokens->currentPage() > $tokens->lastPage()) {
            $lastPageUrl = $request->fullUrlWithQuery(['page' => $tokens->lastPage()]);
            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => $lastPageUrl,
                    'message' => __('messages.requested_page_not_found'),
                ], 404);
            }
            return redirect($lastPageUrl)->with('error', __('messages.requested_page_not_found'));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'tokens' => $tokens
            ]);
        }

        // Keep the old ajax logic just in case it's used elsewhere
        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.tokens.partials.table_and_pagination', compact('tokens'))->render(),
            ]);
        }

        return view('user.tokens.index', compact('tokens'));
    }

    public function create(Request $request, TokenService $tokenService)
    {
        $tokens = $tokenService->paginateForUser(Auth::user());

        if ($request->expectsJson()) {
            return response()->json([
                'tokens' => $tokens,
                'showGenerate' => true
            ]);
        }

        if ($tokens->lastPage() > 0 && $tokens->currentPage() > $tokens->lastPage()) {
            return redirect()->route('token.generate', array_merge(
                request()->query(),
                ['page' => $tokens->lastPage()]
            ))->with('error', __('messages.requested_page_not_found'));
        }

        return view('user.tokens.index', compact('tokens'))->with('showGenerate', true);
    }

    public function jqueryGenerate()
    {
        return view('user.jquery-token');
    }

    public function store(Request $request, TokenService $tokenService)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tokens')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
            'expiry' => 'nullable|string|in:never,7_days,30_days,60_days,90_days,custom',
            'custom_date' => 'nullable|date|after:today',
        ], [
            'name.unique' => 'that token name is already exist give different name',
            'custom_date.after' => 'Custom expiration date must be in the future.',
        ]);

        $tokenValue = Str::random(16);

        $expiresAt = null;
        if (!empty($validated['expiry']) && $validated['expiry'] !== 'never') {
            switch ($validated['expiry']) {
                case '7_days': $expiresAt = now()->addDays(7); break;
                case '30_days': $expiresAt = now()->addDays(30); break;
                case '60_days': $expiresAt = now()->addDays(60); break;
                case '90_days': $expiresAt = now()->addDays(90); break;
                case 'custom':
                    if (!empty($validated['custom_date'])) {
                        $expiresAt = \Carbon\Carbon::parse($validated['custom_date'])->endOfDay();
                    }
                    break;
            }
        }

        $tokenService->createForUser(Auth::user(), [
            'name' => $validated['name'],
            'token' => $tokenValue,
            'expires_at' => $expiresAt,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('messages.token_generated_for', ['name' => $validated['name']]),
                'data' => [
                    'name' => $validated['name'],
                    'token' => $tokenValue,
                ],
            ], 201);
        }

        return redirect()
            ->route('tokens.index')
            ->with('success', __('messages.token_generated_for', ['name' => $validated['name']]))
            ->with('token', $tokenValue)
            ->with('token_name', $validated['name']);
    }

    public function edit(Request $request, Token $token)
    {
        $this->authorizeToken($token);

        if ($request->expectsJson()) {
            return response()->json(['token' => $token]);
        }

        return view('user.tokens.edit', compact('token'));
    }

    public function update(Request $request, Token $token, TokenService $tokenService)
    {
        $this->authorizeToken($token);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tokens')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($token->id)
            ],
        ], [
            'name.unique' => 'that token name is already exist give different name'
        ]);

        $tokenService->updateName($token, $validated['name']);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Token updated successfully.', 'token' => $token]);
        }

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token updated successfully.');
    }

    public function destroy(Request $request, Token $token, TokenService $tokenService)
    {
        $this->authorizeToken($token);
        $tokenService->delete($token);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Token deleted successfully.']);
        }

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token deleted successfully.');
    }

    public function disable(Request $request, Token $token, TokenService $tokenService)
    {
        $this->authorizeToken($token);

        $tokenService->toggleDisabled($token);

        $message = $token->disabled ? 'Token disabled.' : 'Token enabled.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'token' => $token]);
        }

        return redirect()
            ->route('tokens.index')
            ->with('success', $message);
    }

    protected function authorizeToken(Token $token): void
    {
        if ($token->user_id !== Auth::id()) {
            abort(404);
        }
    }
}
