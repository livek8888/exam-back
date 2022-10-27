<?php

namespace App\Http\Controllers;

use App\Dto\UserDto;
use App\Exceptions\ApiException;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function join(CreateUserRequest $request)
    {
        $dto = new UserDto(
            $request->account,
            $request->password,
            $request->name,
            $request->email,
        );

        $existsAccount = User::where('account', $dto->getAccount())
                    ->first();

        if (isset($existsAccount->id)) {
            throw new ApiException('account already exist', 409);
        }

        $existsEmail = User::where('email', $dto->getEmail())
                    ->first();

        if (isset($existsEmail->id)) {
            throw new ApiException('email already exist', 409);
        }

        $user = new User();
        $user->account = $dto->getAccount();
        $user->password = Hash::make($dto->getPassword());
        $user->name = $dto->getName();
        $user->email = $dto->getEmail();
        $user->save();
        return $user;
    }
}
