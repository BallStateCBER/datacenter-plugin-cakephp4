<?php
declare(strict_types=1);

namespace DataCenter\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\I18n\Time;
use Cake\Utility\Security;
use DataCenter\Mailer\UserMailer;

/**
 * Users Controller
 *
 * @property \DataCenter\Model\Table\UsersTable $Users
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /** @var string[] Login-related actions that are accessible to the public */
    public const AUTH_ACTIONS = [
        'login',
        'logout',
        'requestResetPassword',
        'resetPassword',
    ];

    /**
     * Initialization hook method
     *
     * @throws \Exception
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        if (Configure::read('DataCenter.auth.enabled')) {
            $allowedActions = self::AUTH_ACTIONS;
            $controllerClass = 'App\Controller\UsersController';
            if (defined("$controllerClass::ALLOW")) {
                $allowedActions = array_merge($allowedActions, $controllerClass::ALLOW);
            }
            $this->Authentication->allowUnauthenticated($allowedActions);
        }
    }

    /**
     * Login page
     *
     * @return \Cake\Http\Response
     */
    public function login(): Response
    {
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/';

            return $this->redirect($target);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Invalid email or password');
        }

        $this->set('pageTitle', 'Log in');

        return $this->render('DataCenter.Users/login');
    }

    /**
     * Logout redirect
     *
     * @return \Cake\Http\Response
     */
    public function logout(): Response
    {
        $logoutRedirect = $this->Authentication->logout() ?? Configure::read('DataCenter.auth.logoutRedirect');
        $logoutRedirect = $logoutRedirect ?? '/';
        $this->Flash->success('You have been logged out');

        return $this->redirect($logoutRedirect);
    }

    /**
     * Sends the user an email with a password-resetting link
     *
     * @return \Cake\Http\Response
     */
    public function requestResetPassword(): Response
    {
        $template = 'DataCenter.Users/request_reset_password';
        $user = $this->Users->newEmptyEntity();
        $this->set([
            'pageTitle' => 'Request Password Reset',
            'user' => $user,
        ]);

        if (!$this->getRequest()->is('post')) {
            return $this->render($template);
        }

        $email = $this->request->getData('email');
        /** @var \DataCenter\Model\Entity\User $user */
        $user = $this->Users->findByEmail($email)->first();
        if (!$user) {
            $this->Flash->error('Email address not found');

            return $this->render($template);
        }

        // Update token
        $expiration = new Time('now');
        $day = 86400;
        $user->token_expires = $expiration->addSeconds($day);
        $user->token = bin2hex(Security::randomBytes(16));
        if (!$this->Users->save($user)) {
            $this->Flash->error('An error prevented your password reset token from being generated');

            return $this->render($template);
        }

        (new UserMailer())->send('resetPassword', [$user]);
        $this->Flash->success('Please check your email for a password reset message');

        return $this->render($template);
    }

    /**
     * Sends the user an email with a password-resetting link
     *
     * @param string $token Password-reset token
     * @return null|\Cake\Http\Response
     */
    public function resetPassword(string $token): ?Response
    {
        $loginUrl = Configure::read('DataCenter.auth.loginUrl');

        /** @var \DataCenter\Model\Entity\User $user */
        $user = $this->Users->findByToken($token)->first();
        if (!$user) {
            $this->Flash->error('The provided password reset token is invalid or has already been used.');

            return $this->redirect($loginUrl);
        }

        $expired = $user->token_expires->wasWithinLast('1 day');
        if ($expired) {
            $this->Flash->error(
                'The provided password reset token has expired. ' .
                'Click on the "Reset Password" link to receive a new link to reset your password.'
            );

            return $this->redirect($loginUrl);
        }

        $data = $this->request->getData();
        $data['password'] = $data['new_password'];
        $user = $this->Users->patchEntity($user, $data, [
            'fields' => [
                'new_password',
                'password',
                'password_confirm',
            ],
        ]);

        if ($this->Users->save($user)) {
            $this->Flash->success('Your password has been successfully updated. You may now log in.');

            return $this->redirect($loginUrl);
        }

        $this->set([
            'pageTitle' => 'Reset Password',
            'user' => $user,
        ]);

        return $this->render('DataCenter.Users/reset_password');
    }
}
