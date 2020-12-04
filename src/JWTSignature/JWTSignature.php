<?php

namespace JWTSignature;

use JWTSignature\Helpers\Base64Url;

class JWTSignature
{
    protected
        $header = [
        "alg" => "SHA256",
        "typ" => "JWT"
    ],
        $payload = [],
        $sign = [],
        $key = '',
        $name = '';

    public function __construct()
    {
        if(JWT_SIGNATURE === null) {
            throw new \Exception('Not set signature');
        }

        $this->key = JWT_SIGNATURE;
    }

    public function sign(string $name = '', array $data = [])
    {
        $this->payload = $data;
        $this->payload['sub']  = md5($name);
        $this->payload['name'] = $name;
        $this->payload['iat'] = time();

        $header = Base64Url::encode(json_encode($this->header));

        $payload = Base64Url::encode(json_encode($this->payload));

        $string = "{$header}.{$payload}";

        $signature = Base64Url::encode(hash_hmac('SHA256', $string, $this->key, true));

        $hash = "{$string}.{$signature}";

        return $hash;
    }

    public function validate($jwt_token)
    {
        if (empty($jwt_token)) {
            return ['status' => false, 'msg' => 'JWT is empty'];
        }

        $explode = explode('.', $jwt_token);

        $signature = Base64Url::decode($explode[2]);
        $string  = "{$explode[0]}.{$explode[1]}";

        if (hash_equals($signature, hash_hmac('SHA256', $string, $this->key, true))) {
            $header  = json_decode(Base64Url::decode($explode[0]), true);
            $payload = json_decode(Base64Url::decode($explode[1]), true);

            $timestamp = intval($payload['iat']) + (60 * 5);

            if (time() > $timestamp) {
                return ['status' => false, 'msg' => 'Timestamp invalid.'];
            }

            return [
                'status'  => true,
                'header'  => $header,
                'payload' => $payload,
            ];
        }

        return ['status' => false, 'msg' => 'Signature not valid.'];
    }
}
