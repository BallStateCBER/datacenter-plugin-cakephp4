<?php

use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Cake\Core\Configure;
use Cake\Event\EventManager;

// Load config file(s)
try {
    Configure::load('DataCenter.datacenter');
    if (file_exists(ROOT . DS . 'config' . DS . 'datacenter.php')) {
        Configure::load('datacenter');
    }
} catch (Exception $e) {
    exit($e->getMessage() . "\n");
}

// Add Auth middleware
$config = Configure::read('DataCenter');
if ($config['auth']['enabled']) {
    EventManager::instance()->on(
        'Server.buildMiddleware',
        function ($event, $middleware) {
            $app = $event->getSubject()->getApp();
            try {
                $middleware
                    ->add(new AuthenticationMiddleware($app))
                    ->add(new AuthorizationMiddleware($app))
                    ->add(new RequestAuthorizationMiddleware());
            } catch (LogicException $exception) {
                exit($exception->getMessage() . "\n");
            }
        }
    );
}
