<?php

namespace App\Http\Controllers\Api;

//use Illuminate\Http\Request;
//use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;
use Qcloud\Sms\SmsSingleSender;


class VerificationCodesController extends Controller
{public function store(VerificationCodeRequest $request, SmsSingleSender $qcloudSms)
    {
        $phone = $request->phone;
        $contryCode = "86";
        $templateId = "123613";

//        $smsSign = "锋手机器人";
        $opeTimes = 10;

        if(!app()->environment('production')) {
            $code = '123456';
        }
        else {
            // 生成6位随机数，左侧补0
            $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);
            $params = [$code,$opeTimes];

            try {
                $result = $qcloudSms->sendWithParam($contryCode, $phone, $templateId, $params);
                $rsp = json_decode($result);
                return $this->response->array([$rsp]);

            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
            }
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存验证码 10分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}



