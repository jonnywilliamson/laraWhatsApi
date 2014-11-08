<?php

class TMVWhatapiEvents extends AllEvents
{

    /**
     * This is a list of all current events. Uncomment the ones you wish to listen to.
     * Every event that is uncommented - should then have a function below.
     * @var array
     */
    public $activeEvents = array(
//        'onMessageComposing',
//        'onMessagePaused',
//        'onGetGroupsResult',
//        'onGetGroupInfoResult',
//        'onGroupParticipantAdded',
//        'onGroupParticipantRemoved',
//        'onReceiptServer',
//        'onReceiptClient',
//        'action.send.pre',
//        'action.send.post',
//        'node.send.pre',
//        'node.send.post',
//        'node.received'
    );

    public function onConnect($mynumber, $socket)
    {
        echo "<p>WooHoo!, Phone number $mynumber connected successfully!</p>";
    }

    public function onDisconnect($mynumber, $socket)
    {
        echo "<p>Booo!, Phone number $mynumber is disconnected!</p>";
    }

}