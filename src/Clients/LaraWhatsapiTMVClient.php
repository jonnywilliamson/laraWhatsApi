<?php namespace Williamson\Larawhatsapi\Clients;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Action\ChatState;
use Tmv\WhatsApi\Message\Action\MessageText;
use Williamson\Larawhatsapi\Repository\SMSMessageInterface;

class LaraWhatsapiTMVClient implements SMSMessageInterface {

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

    public function __destruct()
    {
        $this->client->disconnect();
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

    public function getWhatsApi()
    {
        return $this->client;
    }

    /**
     * Used to send a manual connect request.
     * @return mixed
     */
    public function manualConnect()
    {
        $this->client->connect();
    }

    public function manualDisconnect()
    {
        $this->client->disconnect();
    }

    public function manualLogin($password)
    {
        $this->client->login();
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
    }

    public function sendMessageAudio($to, $mediaURI)
    {
        // TODO: Implement sendMessageAudio() method.
    }

    public function sendMessageImage($to, $mediaURI, $caption = '')
    {
        // TODO: Implement sendMessageImage() method.
    }

    public function sendMessageVideo($to, $mediaURI, $caption = '')
    {
        // TODO: Implement sendMessageVideo() method.
    }

    public function sendMessageLocation($to, $longitude, $latitude, $locationName)
    {
        // TODO: Implement sendMessageLocation() method.
    }

    public function sendBroadcastMessage(array $to, $message)
    {
        // TODO: Implement sendBroadcastMessage() method.
    }

    public function sendBroadcastAudio(array $to, $mediaURI)
    {
        // TODO: Implement sendBroadcastAudio() method.
    }

    public function sendBroadcastImage(array $to, $mediaURI, $caption = '')
    {
        // TODO: Implement sendBroadcastImage() method.
    }

    public function sendBroadcastVideo(array $to, $mediaURI, $caption = '')
    {
        // TODO: Implement sendBroadcastVideo() method.
    }

    public function sendBroadcastLocation(array $to, $longitude, $latitude, $locationName)
    {
        // TODO: Implement sendBroadcastLocation() method.
    }

    public function sendGroupChatCreate($subject, array $participants)
    {
        // TODO: Implement sendGroupChatCreate() method.
    }

    public function sendGroupsChatDelete($groupID)
    {
        // TODO: Implement sendGroupsChatDelete() method.
    }

    public function sendSetGroupSubject($groupID, $subject)
    {
        // TODO: Implement sendSetGroupSubject() method.
    }

    public function sendGroupParticipantAdd($groupID, array $memberIDs)
    {
        // TODO: Implement sendGroupParticipantAdd() method.
    }

    public function sendGroupParticipantRemove($groupID, array $memberIDs)
    {
        // TODO: Implement sendGroupParticipantRemove() method.
    }
}