<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\TokenService;

class UserTokenController extends BaseController
{
    public function index(Request $request, TokenService $tokenService)
    {
        $tokens = $tokenService->paginateForUser(Auth::user());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.tokens.partials.table_and_pagination', compact('tokens'))->render(),
            ]);
        }

        return view('user.tokens.index', compact('tokens'));
    }

    public function create(TokenService $tokenService)
    {
        $tokens = $tokenService->paginateForUser(Auth::user());

        return view('user.tokens.index', compact('tokens'))->with('showGenerate', true);
    }

    public function jqueryGenerate()
    {
        return view('user.jquery-token');
    }

    public function store(Request $request, TokenService $tokenService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tokenValue = Str::random(64);

        $tokenService->createForUser(Auth::user(), [
            'name' => $validated['name'],
            'token' => $tokenValue,
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

    public function edit(Token $token)
    {
        $this->authorizeToken($token);

        return view('user.tokens.edit', compact('token'));
    }

    public function update(Request $request, Token $token, TokenService $tokenService)
    {
        $this->authorizeToken($token);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tokenService->updateName($token, $validated['name']);

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token updated successfully.');
    }

    public function destroy(Token $token, TokenService $tokenService)
    {
        $this->authorizeToken($token);
        $tokenService->delete($token);

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token deleted successfully.');
    }

    public function disable(Token $token, TokenService $tokenService)
    {
        $this->authorizeToken($token);

        $tokenService->toggleDisabled($token);

        $message = $token->disabled ? 'Token disabled.' : 'Token enabled.';

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
