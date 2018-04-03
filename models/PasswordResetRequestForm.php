<?php

namespace app\models;

use app\components\Utils;
use app\controllers\SiteController;
use app\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => \Yii::t('app', 'There is no user with such email.'),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('app', 'Email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save(false, ['password_reset_token'])) {
                $mail = Utils::newMailer();
                $mail->addAddress($user->email);
                $mail->setFrom(\Yii::$app->params['senderEmail']);
                $mail->Subject = \Yii::t('app', 'Password reset for VACH');
                $mail->Body = \Yii::$app->view->renderFile('@app/mail/passwordResetToken.php', [
                    'user' => $user,
                ]);

                return $mail->send();
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return false;
    }

}
