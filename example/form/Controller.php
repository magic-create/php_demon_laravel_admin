<?php

namespace App\Http\Controllers\Example;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * 表单测试
     *
     * @return mixed
     *
     * @author    ComingDemon
     * @copyright 魔网天创信息科技
     */
    public function index()
    {
        if (DEMON_SUBMIT) {
            $data = arguer();
            $validator = validator($data, ['api' => 'required']);
            if ($validator->fails())
                return response($validator->errors()->toArray(), DEMON_CODE_PARAM);

            return response($data);
        }

        return view('view');
    }
}
