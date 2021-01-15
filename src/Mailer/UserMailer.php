<?php
declare(strict_types=1);

namespace DataCenter\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\Mailer\Message;
use Cake\Routing\Router;

class UserMailer extends Mailer
{
    /**
     * Defines the "reset password" email
     *
     * @param \DataCenter\Model\Entity\User $user User entity
     * @return void
     */
    public function resetPassword($user)
    {
        $siteTitle = Configure::read('DataCenter.siteTitle');
        $this
            ->setTo($user->email)
            ->setSubject("$siteTitle - Reset password")
            ->setEmailFormat(Message::MESSAGE_BOTH)
            ->setViewVars([
                'url' => Router::url([
                    'controller' => 'Users',
                    'action' => 'resetPassword',
                    'token' => $user->token,
                    '_full' => true,
                ]),
            ]);

        $this
            ->viewBuilder()
            ->setLayout('DataCenter.default')
            ->setTemplate('DataCenter.reset_password');
    }
}
