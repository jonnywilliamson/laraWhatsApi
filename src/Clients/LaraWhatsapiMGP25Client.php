<?php namespace Williamson\Larawhatsapi\Clients;

use Config;
use WhatsProt;
use Williamson\Larawhatsapi\Repository\SMSMessageInterface;

class LaraWhatsapiMGP25Client implements SMSMessageInterface {

    protected $events;

    /**
     * @var string $password
     */
    protected $password;

    /**
     * @var WhatsProt
     */
    protected $whatsProt;

    /**
     * @param WhatsProt $whatsProt
     */
    public function __construct(WhatsProt $whatsProt)
    {
        $this->whatsProt = $whatsProt;

        $account        = Config::get("larawhatsapi::useAccount");
        $this->password = Config::get("larawhatsapi::accounts.$account.password");
    }

    public function __destruct()
    {
        $this->whatsProt->disconnect();
    }

    public function getWhatsApi()
    {
        return $this->whatsProt;
    }

    public function sendMessage($to, $message)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendMessageComposing($to);
        $this->whatsProt->sendMessage($to, $message);
    }

    protected function connectAndLogin()
    {
        $this->whatsProt->connect();
        $this->whatsProt->loginWithPassword($this->password);
    }

    /**
     *New comment
     */
    public function manualConnect()
    {
        $this->whatsProt->connect();
    }

    public function manualDisconnect()
    {
        $this->whatsProt->disconnect();
    }

    public function manualLogin($password)
    {
        $this->whatsProt->loginWithPassword($password);
    }

    public function sendMessageAudio($to, $mediaURI)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendMessageAudio($to, $mediaURI);
    }

    public function sendMessageImage($to, $mediaURI, $caption = '')
    {
        $this->connectAndLogin();
        $this->whatsProt->sendMessageImage($to, $mediaURI, false, 0, "", $caption);
    }

    public function sendMessageVideo($to, $mediaURI, $caption = '')
    {
        $this->connectAndLogin();
        $this->whatsProt->sendMessageVideo($to, $mediaURI, false, 0, '', $caption);
    }

    public function sendMessageLocation($to, $longitude, $latitude, $locationName)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendMessageLocation($to, $longitude, $latitude, $locationName);
    }

    public function sendBroadcastMessage(array $to, $message)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendBroadcastMessage($to, $message);
    }

    public function sendBroadcastAudio(array $to, $mediaURI)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendBroadcastAudio($to, $mediaURI);
    }

    public function sendBroadcastImage(array $to, $mediaURI, $caption = '')
    {
        $this->connectAndLogin();
        $this->whatsProt->sendBroadcastImage($to, $mediaURI, false, 0, '', $caption);
    }

    public function sendBroadcastVideo(array $to, $mediaURI, $caption = '')
    {
        $this->connectAndLogin();
        $this->whatsProt->sendBroadcastVideo($to, $mediaURI, false, 0, '', $caption);
    }

    public function sendBroadcastLocation(array $to, $longitude, $latitude, $locationName)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendBroadcastLocation($to, $longitude, $latitude, $locationName);
    }

    public function sendGroupChatCreate($subject, array $participants)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendGroupsChatCreate($subject, $participants);
    }

    public function sendGroupsChatDelete($groupID)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendGroupsChatEnd($groupID);
    }

    public function sendSetGroupSubject($groupID, $subject)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendSetGroupSubject($groupID, $subject);
    }

    public function sendGroupParticipantAdd($groupID, array $memberIDs)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendGroupsParticipantsAdd($groupID, $memberIDs);
    }

    public function sendGroupParticipantRemove($groupID, array $memberIDs)
    {
        $this->connectAndLogin();
        $this->whatsProt->sendGroupsParticipantsRemove($groupID, $memberIDs);
    }


//
//    /**
//     * Dynamically pass methods to the default connection.
//     *
//     * @param  string  $method
//     * @param  array   $parameters
//     * @return mixed
//     */
//    public static function __callStatic($method, $parameters)
//    {
//        return call_user_func_array(array(static::connection(), $method), $parameters);
//    }

}