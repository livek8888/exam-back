<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class PersonalAccessTokens extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'personal_access_tokens';
    protected $primaryKey = 'id';

    protected $casts = [
        'id' => 'int',
        'tokenable_id' => 'int',
        'error_code' => 'int',
        'is_old_password' => 'boolean',
    ];

    protected $dates = [
        'last_used_at',
        'created_at',
        'updated_at',
    ];

    protected $visible = [
        'token',
        'is_old_password',
    ];

    protected $fillable = [
        'id',
        'tokenable_type',
        'tokenable_id',
        'os',
        'device',
        'agent',
        'token',
        'abilities',
        'last_used_at',
        'created_at',
        'updated_at',
        'is_old_password',
        'iat',//토큰 payload 담는 용도 (실제 DB 컬럼은 없음)
        'exp',//토큰 payload 담는 용도 (실제 DB 컬럼은 없음)
        'iss',//토큰 payload 담는 용도 (실제 DB 컬럼은 없음)
        'uid',//토큰 payload 담는 용도 (실제 DB 컬럼은 없음)
        'error',//공통.. response error message
        'error_code',//공통 response http status code
    ];

    //jwt 발행 전 row 먼저 생성
    public function beforeGenerateToken(User $user, string $os, string $device, string $agent): PersonalAccessTokens
    {
        $this->token = $user->account;
        $this->tokenable_id = $user->id;
        $this->tokenable_type = User::class;
        $this->os = $os;
        $this->device = $device;
        $this->agent = $agent;
        $this->abilities = '[*]';

        return $this->updateOrCreate(
            ['tokenable_id' => $user->id, 'device' => $device, 'os' => $os],
            ['agent' => $agent, 'tokenable_type' => User::class, 'abilities' => '[*]']
        );
    }

    //토큰 데이터 가져오기
    public function getTokenData()
    {
        return $this->where('token', $this->token)->find($this->uid);
    }
}
