<?php

namespace App\Factory;

use App\Models\User;
use DavidHoeck\LaravelJsonMapper\JsonMapper;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use JsonMapper_Exception;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\HttpFoundation\ParameterBag;

class RequestDtoFactory
{
    public static function create($dto, Request|FormRequest|array $request, array $add_params = [])
    {
        try {
            //dto 에 추가한 파라미터가 있으면 같이 머지 해줌
            if (is_array($request)) {
                $req = $request;
            } else {
                $req = $request->all();
            }

            $param = [];
            foreach ($req as $k => $v) {
                $param[$k] = !is_null($v) ? $v : '';
            }

            foreach ($add_params as $k => $v) {
                $param[$k] = !is_null($v) ? $v : '';
            }

            $mapper = new JsonMapper();
            $mapper->bIgnoreVisibility = true;

            return $mapper->map(
                new ParameterBag($param),
                (new ReflectionClass($dto))->newInstanceWithoutConstructor()
            );
        } catch (Exception $exception) {
        }
    }
}
