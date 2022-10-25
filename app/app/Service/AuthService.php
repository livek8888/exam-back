<?php

namespace App\Service;

use App\Models\PersonalAccessTokens;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use ReallySimpleJWT\Token;

class AuthService
{
    public function __construct()
    {
        $this->agent = app(AgentHelper::class);
    }

    public function generateToken(User $user): PersonalAccessTokens
    {
        DB::beginTransaction();

        $token_result = (new PersonalAccessTokens())->beforeGenerateToken(
            $user,
            $this->agent->getOsType(),
            $this->agent->getDevice(),
            $this->agent->getUserAgent()
        );
        if (!isset($token_result->id)) {
            DB::rollBack();
            // throw new DatabaseInsertFailedException(__('auth.failed_generate_token'));
        }
        //토큰발급
        $now = time();
        //발급일자
        $iat = $now;
        //access_token 만료일자
        $exp = $now + config('jwt.ttl');
        $payload = [
            'iat' => $iat, // 생성시간
            'exp' => $exp, //유효시간 (+1일)
            'iss' => config('app.url'),
            'uid' => $token_result->id,
        ];
        $access_token = Token::customPayload($payload, env('JWT_SECRET'));

        if ($access_token == '') {
            DB::rollBack();
            // throw new DatabaseInsertFailedException(__('auth.failed_generate_token'));
        }
        //발급받은 토큰을 token row에 업데이트 처리함
        $token_result->token = $access_token;
        $result = $token_result->update();
        if (!$result) {
            DB::rollBack();
            // throw new DatabaseInsertFailedException(__('auth.failed_generate_token'));
        }
        DB::commit();
        //예전 비밀번호 (mysql PASSWORD()) 함수로 생성된 비번은 변경을 위해 response에 같이 구분값을 넣어서 보내줌
        $token_result->is_old_password = $user->is_old_password;
        return $token_result;
    }

    public function deleteToken(string $token): bool
    {
        if ($token == '') {
            return false;
        }

        try {
            if ($this->isValidateToken($token)) {
                $payload = $this->getTokenPayloads($token);
                //디비체크해야함
                $token_data = PersonalAccessTokens::where('token', $token)->find($payload['uid']);
                $result = 0;
                if (!isset($token_data->id)) {
                    return false;
                }
                return $token_data->delete();
            }
        } catch (Exception $e) {
            return false;
        }
    }

    //토큰 유효성 검사
    private function isValidateToken(string $token): bool
    {
        return Token::validate($token, env('JWT_SECRET'));
    }


    //유효성 검사 후 payload model 에 set.
    private function getTokenPayloads(string $token): array
    {
        return Token::getPayload($token, env('JWT_SECRET'));
    }
}
