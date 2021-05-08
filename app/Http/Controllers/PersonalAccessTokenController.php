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

use App\Http\Requests\PersonalAccessTokenRequest;
use App\Jobs\CreatePersonalAccessToken;
use App\Jobs\RevokePersonalAccessToken;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PersonalAccessTokenController extends Controller
{
    public function index(User $user): View
    {
        $tokens = $user->tokens()->latest()->get();

        return view('user.personal_access_tokens.show')
            ->with([
                'user' => $user,
                'tokens' => $tokens,
            ]);
    }

    public function create(User $user): View
    {
        return view('user.personal_access_tokens.create')
            ->with([
                'user' => $user,
            ]);
    }

    public function store(PersonalAccessTokenRequest $request): RedirectResponse
    {
        $user = $request->requestedUser();

        $plainTextToken = $this->dispatchNow(CreatePersonalAccessToken::fromRequest($request));

        return redirect()->route('users.tokens.index', $user)
            ->with([
                'newTokenName' => $request->name(),
                'newPlainTextToken' => $plainTextToken,
                'success' => __('user/personal_access_token.created'),
            ]);
    }

    public function destroy(PersonalAccessToken $token): RedirectResponse
    {
        $user = $token->relatedUser();

        $this->dispatchNow(new RevokePersonalAccessToken($token));

        return redirect()->route('users.tokens.index', $user)
            ->withSuccess(__('user/personal_access_token.revoked'));
    }
}
