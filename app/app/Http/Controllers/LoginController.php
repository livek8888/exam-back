<?php

namespace App\Http\Controllers;

use App\Dto\LoginDto;
use App\Exceptions\ApiException;
use App\Factory\RequestDtoFactory;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $dto = new LoginDto(
            $request->account,
            $request->password,
        );

        $matchedUser = User::where('account', $dto->getAccount())
                    ->first();

        if (!isset($matchedUser->id)) {
            throw new ApiException('not found user', 404);
        }

        if (!Hash::check($dto->getPassword(), $matchedUser->password)) {
            throw new ApiException('not found user', 404);
        }
        return $matchedUser;
    }
}
