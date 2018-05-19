<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Api\SocialAuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        // 目前只支持微信 判断是不是微信
        if (!in_array($type, ['weixin'])) {
            return $this->response->errorBadRequest();
        }

        // 这是调用 socialiteproviders的方法  https://socialiteproviders.github.io/
        $driver = \Socialite::driver($type);

        try {
            // code 是客户端要得到的授权码
            if ($code = $request->code) {
                // 通过code 得到 access_token  和 openID
                $response = $driver->getAccessTokenResponse($code);
                // 好像下面这一句可以不用的，也成功了
                $driver->setOpenId($response['openid']);
                $token = array_get($response, 'access_token');
            } else {
                // 或者客户端自己得到了 access_token  和 openID
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 没有用户，默认创建一个用户
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;
        }

        return $this->response->array(['token' => $user->id]);
    }


}
