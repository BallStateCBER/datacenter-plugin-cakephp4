# Authentication / Authorization setup

To use this plugin's auth setup, do the following.

## Configuration
Enable the auth setup in your configuration data:
1. Copy this plugin's `config/datacenter.php` configuration file to the app's `config` directory
2. Update the app's copy of the file so that the `DataCenter.auth.enabled` value is `true`

## Application
Add to `src/Application.php`:
```php
use DataCenter\Plugin as DataCenterPlugin;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends BaseApplication implements
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
```

```php
/**
 * Returns the authorization service
 *
 * @param \Psr\Http\Message\ServerRequestInterface $request Server request
 * @return \Authorization\AuthorizationServiceInterface
 */
public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
{
    return DataCenterPlugin::getAuthorizationService($request);
}

/**
 * Returns a service provider instance.
 *
 * @param \Psr\Http\Message\ServerRequestInterface $request Request
 * @return \Authentication\AuthenticationServiceInterface
 */
public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
{
    return DataCenterPlugin::getAuthenticationService($request);
}
```


## Controllers
- Have `UsersController` extend `DataCenter\Controller\UsersController`
 - In each controller with publicly-accessible actions, list those action names in a class constant called `ALLOW`, e.g.
   ```php
   public const ALLOW = ['index', 'view'];
   ```

## User model
- Have the `User` class extend `DataCenter\Model\Entity\User`
- Have the `UsersTable` class extend `DataCenter\Model\Table\UsersTable`
