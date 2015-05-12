<?php namespace Williamson\Larawhatsapi\Clients;

use Config;
use WhatsProt;
use Williamson\Larawhatsapi\Repository\SMSMessageInterface;

class LaraWhatsapiMGP25Client implements SMSMessageInterface{

    /**
     * @var string $password
     */
    protected $password;

    /**
     * @var WhatsProt
     */
    protected $whatsProt;

    public $messages;

    /**
     * @param WhatsProt $whatsProt
     */
    public function __construct(WhatsProt $whatsProt)
    {
        $this->whatsProt = $whatsProt;
        $account   = Config::get("larawhatsapi::useAccount");
        $this->password = Config::get("larawhatsapi::accounts.$account.password");
    }

    public function sendMessage($to, $message)
    {
        $this->connectAndLogin();
        $this->whatsProt->eventManager()->bind('onGetMessage', array($this, 'processReceivedMessage'));
        $this->whatsProt->sendMessageComposing($to);
        $this->whatsProt->sendMessage($to, $message);
        $this->logoutAndDisconnect();
        if (!empty($this->messages))
            return $this->messages;
    }

    public function sendSync($r)
    {
        $this->connectAndLogin();
        $this->whatsProt->eventManager()->bind('onGetMessage', array($this, 'processReceivedMessage'));
        $this->whatsProt->sendSync($r);
        $this->logoutAndDisconnect();
        if (!empty($this->messages))
            return $this->messages;
    }

    public function checkForNewMessages()
    {
        $this->connectAndLogin();
        $this->whatsProt->eventManager()->bind('onGetMessage', array($this, 'processReceivedMessage'));
        $this->whatsProt->pollMessage();
        $this->logoutAndDisconnect();
        return $this->messages;
    }

    public function processReceivedMessage($phone, $from, $id, $type, $time, $name, $data = null)
    {
        $matches = null;
        $time = date('Y-m-d H:i:s', $time);
        if (preg_match('/\d*/', $from, $matches)) {
            $from = $matches[0];
        }
        $messages = array('phone' => $phone, 'from' => $from, 'id' => $id, 'type' => $type, 'time' => $time, 'name' => $name, 'data' => $data);
        $this->messages[] = $messages;
    }

    protected function connectAndLogin()
    {
        $this->whatsProt->connect();
        $this->whatsProt->loginWithPassword($this->password);
    }

    protected function logoutAndDisconnect()
    {
        $this->whatsProt->disconnect();
    }
}