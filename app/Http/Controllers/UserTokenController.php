<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserTokenController extends BaseController
{
    public function index()
    {
        $tokens = Auth::user()->tokens()->latest()->get();

        return view('user.tokens.index', compact('tokens'));
    }

    public function create()
    {
        return view('user.token');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tokenValue = Str::random(64);

        Auth::user()->tokens()->create([
            'name' => $request->name,
            'token' => $tokenValue,
        ]);

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token created successfully.')
            ->with('token', $tokenValue);
    }

    public function edit(Token $token)
    {
        $this->authorizeToken($token);

        return view('user.tokens.edit', compact('token'));
    }

    public function update(Request $request, Token $token)
    {
        $this->authorizeToken($token);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token->name = $request->name;
        $token->save();

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token updated successfully.');
    }

    public function destroy(Token $token)
    {
        $this->authorizeToken($token);

        $token->delete();

        return redirect()
            ->route('tokens.index')
            ->with('success', 'Token deleted successfully.');
    }

    protected function authorizeToken(Token $token): void
    {
        if ($token->user_id !== Auth::id()) {
            abort(404);
        }
    }
}
