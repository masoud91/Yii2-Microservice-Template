<?php

namespace micro\components\exceptions;
use yii\base\UserException;


class UnknownException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'خطای نامشخص';
    }

    protected $message = 'متاسفانه خطایی در هنگام اجرای درخواست شما به وجود آمد، لطفا زمان دیگری تلاش کنید.';
    protected $code = 503;
}
