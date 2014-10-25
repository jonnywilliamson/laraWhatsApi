<?php namespace Williamson\Larawhatsapi\Repository;

interface SMSMessageInterface {
    public function sendMessage($to, $message);
    public function checkForNewMessages();
}