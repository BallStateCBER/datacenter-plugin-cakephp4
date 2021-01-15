<?php
declare(strict_types=1);

namespace DataCenter\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Class AppController
 *
 * @package DataCenter\Controller
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class AppController extends Controller
{
    // A list of actions that unauthenticated users can access
    public const ALLOW = [];

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
            $this->loadComponent('Authentication.Authentication');
            $this->loadComponent('Authorization.Authorization');

            $this->Authentication->allowUnauthenticated(static::ALLOW);
        }
    }
}
