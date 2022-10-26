<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Actions\CreatePersonalAccessTokenAction;
use App\Http\Requests\PersonalAccessTokenRequest;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PersonalAccessTokenController extends Controller
{
    public function index(User $user): View
    {
        $this->authorize('viewAny', [PersonalAccessToken::class, $user]);

        $tokens = $user->tokens()->latest()->get();

        return view('user.personal_access_tokens.show')
            ->with([
                'user' => $user,
                'tokens' => $tokens,
            ]);
    }

    public function create(User $user): View
    {
        $this->authorize('create', [PersonalAccessToken::class, $user]);

        return view('user.personal_access_tokens.create')
            ->with([
                'user' => $user,
            ]);
    }

    public function store(
        PersonalAccessTokenRequest $request,
        CreatePersonalAccessTokenAction $createPersonalAccessToken
    ): RedirectResponse {
        $user = $request->requestedUser();

        $this->authorize('create', [PersonalAccessToken::class, $user]);

        $plainTextToken = $createPersonalAccessToken(
            user: $user,
            name: $request->name()
        );

        return redirect()->route('users.tokens.index', $user)
            ->with('newTokenName', $request->name())
            ->with('newPlainTextToken', $plainTextToken)
            ->with('success', __('user/personal_access_token.created'));
    }

    public function destroy(PersonalAccessToken $token): RedirectResponse
    {
        $user = $token->relatedUser();

        $this->authorize('delete', [PersonalAccessToken::class, $user]);

        $token->delete();

        return redirect()->route('users.tokens.index', $user)
            ->withSuccess(__('user/personal_access_token.revoked'));
    }
}
