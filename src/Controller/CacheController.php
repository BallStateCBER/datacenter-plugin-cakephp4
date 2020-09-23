<?php
declare(strict_types=1);

namespace DataCenter\Controller;

use Cake\Cache\Cache;

/**
 * Cache Controller
 */
class CacheController extends AppController
{
    /**
     * Clears the entire cache and redirects to the home page
     *
     * @return \Cake\Http\Response
     */
    public function clear()
    {
        Cache::clearAll();
        return $this->redirect('/');
    }
}
