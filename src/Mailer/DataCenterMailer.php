<?php
declare(strict_types=1);

namespace DataCenter\Mailer;

use Cake\Mailer\Mailer;
use Cake\Mailer\Message;

class DataCenterMailer extends Mailer
{
    /**
     * DataCenterMailer constructor
     *
     * @param array|string|null $config Array of configs, or string to load configs from app.php
     */
    public function __construct($config = null)
    {
        parent::__construct($config);

        $this->setEmailFormat(Message::MESSAGE_BOTH);

        $this->viewBuilder()->setLayout('DataCenter.default');
    }
}
