<?php namespace Williamson\Larawhatsapi\Repository;

interface SMSMessageInterface {

    /**
     * Get the WhatsAPI object so you can make manual commands
     * @return mixed
     */
    public function getWhatsApi();

    /**
     * Used to send a manual connect request.
     * @return mixed
     */
    public function manualConnect();
    public function manualDisconnect();
    public function manualLogin($password);
    public function sendMessage($to, $message);
    public function sendMessageAudio($to, $mediaURI);
    public function sendMessageImage($to, $mediaURI, $caption = '');
    public function sendMessageVideo($to, $mediaURI, $caption = '');
    public function sendMessageLocation($to, $longitude, $latitude, $locationName);
    public function sendBroadcastMessage(array $to, $message);
    public function sendBroadcastAudio(array $to, $mediaURI);
    public function sendBroadcastImage(array $to, $mediaURI, $caption = '');
    public function sendBroadcastVideo(array $to, $mediaURI, $caption = '');
    public function sendBroadcastLocation(array $to, $longitude, $latitude, $locationName);
    public function sendGroupChatCreate($subject, array $participants);
    public function sendGroupsChatDelete($groupID);
    public function sendSetGroupSubject($groupID, $subject);
    public function sendGroupParticipantAdd($groupID, array $memberIDs);
    public function sendGroupParticipantRemove($groupID, array $memberIDs);
}