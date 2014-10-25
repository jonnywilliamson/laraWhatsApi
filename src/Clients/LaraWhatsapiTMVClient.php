<?php namespace Williamson\Larawhatsapi\Clients;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Action\ChatState;
use Tmv\WhatsApi\Message\Action\MessageText;
use Williamson\Larawhatsapi\Repository\SMSMessageInterface;

class LaraWhatsapiTMVClient implements SMSMessageInterface{

    /**
     * @var Client
     */
    protected $client;
    /**
     * @var MessageText
     */
    protected $message;
    /**
     * @var ChatState
     */
    protected $chatState;

    /**
     * @param Client      $client
     * @param MessageText $message
     * @param ChatState   $chatState
     */
    public function __construct(Client $client, MessageText $message, ChatState $chatState)
    {
        $this->client    = $client;
        $this->message   = $message;
        $this->chatState = $chatState;
    }

    public function sendMessage($toNumber, $message)
    {
        $this->connectAndLogin();
        $this->simulateTypingStart($toNumber);

        $this->message->setFromName($this->client->getIdentity()->getNickname());
        $this->message->setTo($toNumber);
        $this->message->setBody($message);

        $this->simulateTypingEnd();

        // Sending message...
        $this->client->send($this->message);

        $this->logoutAndDisconnect();
    }

    public function checkForNewMessages()
    {
        $this->connectAndLogin();
        $this->client->pollMessages();
        $this->logoutAndDisconnect();
    }

    protected function connectAndLogin()
    {
        $this->client->connect();
        $this->client->login();
    }

    /**
     * @param $toNumber
     */
    protected function simulateTypingStart($toNumber)
    {
        // Sending composing notification (simulating typing)
        $this->chatState->setTo($toNumber)->setState(ChatState::STATE_COMPOSING);
        $this->client->send($this->chatState);
    }

    protected function simulateTypingEnd()
    {
        // Sending paused notification (typing end)
        $this->chatState->setState(ChatState::STATE_PAUSED);
        $this->client->send($this->chatState);
    }

    protected function logoutAndDisconnect()
    {
        $this->client->disconnect();
    }
}