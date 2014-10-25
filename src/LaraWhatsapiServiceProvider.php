<?php namespace Williamson\Larawhatsapi;

use App;
use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Entity\Phone;
use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Service\LocalizationService;
use Tmv\WhatsApi\Event\MessageReceivedEvent;
use Tmv\WhatsApi\Message\Received;
use WhatsProt;
use Zend\EventManager\Event;

class LaraWhatsapiServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    public function boot()
    {
        $this->package('williamson/larawhatsapi', null, __DIR__);

        $loader  = AliasLoader::getInstance();
        $aliases = Config::get('app.aliases');
        if (empty($aliases['WA']))
        {
            $loader->alias('WA', 'Williamson\Larawhatsapi\Facades\LaraWhatsapiFacade');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Set up how the create the Identity when one is asked to be created
        $this->app->bindShared('Tmv\WhatsApi\Entity\Identity', function ()
        {
            //Setup Account details.
            $account   = Config::get("larawhatsapi::useAccount");
            $nickName  = Config::get("larawhatsapi::accounts.$account.nickName");
            $number    = Config::get("larawhatsapi::accounts.$account.number");
            $password  = Config::get("larawhatsapi::accounts.$account.password");
            $userIdent = Config::get("larawhatsapi::accounts.$account.identity");

            // Initializing client
            // Creating a service to retrieve phone info
            $localizationService = new LocalizationService();

            // Creating a phone object...
            $phone = new Phone($number);
            // Injecting phone properties
            $localizationService->injectPhoneProperties($phone);
            // Creating identity
            $identity = new Identity();
            $identity->setPhone($phone)
                ->setNickname($nickName)
                ->setPassword($password)
                ->setIdentityToken($userIdent);

            return $identity;
        });


        //Set up how the create TMV's Client Object when one is asked to be created (which needs the Identity)
        $this->app->bindShared('Tmv\WhatsApi\Client', function ()
        {
            $debug             = Config::get("larawhatsapi::debug");
            $account           = Config::get("larawhatsapi::useAccount");
            $number            = Config::get("larawhatsapi::accounts.$account.number");
            $nextChallengeFile = Config::get("larawhatsapi::nextChallengeDir") . "/" . $number . "-NextChallenge.dat";

            $identity = App::make('Tmv\WhatsApi\Entity\Identity');
            // Initializing client
            $client = new Client($identity);
            $client->setChallengeDataFilepath($nextChallengeFile);


            // Attaching events...
            //TODO I don't want to attach events here, but this is just for demo.
            $client->getEventManager()->attach(
                'onMessageReceived',
                function (MessageReceivedEvent $e)
                {
                    $message = $e->getMessage();
                    echo str_repeat('-', 80) . PHP_EOL;
                    echo '** MESSAGE RECEIVED **' . PHP_EOL;
                    echo sprintf('From: %s', $message->getFrom()) . PHP_EOL;
                    if ($message->isFromGroup())
                    {
                        echo sprintf('Group: %s', $message->getGroupId()) . PHP_EOL;
                    }
                    echo sprintf('Date: %s', $message->getDateTime()->format('Y-m-d H:i:s')) . PHP_EOL;

                    if ($message instanceof Received\MessageText)
                    {
                        echo PHP_EOL;
                        echo sprintf('%s', $message->getBody()) . PHP_EOL;
                    } elseif ($message instanceof Received\MessageMedia)
                    {
                        echo sprintf('Type: %s', $message->getMedia()->getType()) . PHP_EOL;
                    }
                    echo str_repeat('-', 80) . PHP_EOL;
                }
            );


            // Debug events
            if ($debug)
            {
                $client->getEventManager()->attach(
                    'node.received',
                    function (Event $e)
                    {
                        $node = $e->getParam('node');
                        echo sprintf("\n--- Node received:\n%s\n", $node);
                    }
                );
                $client->getEventManager()->attach(
                    'node.send.pre',
                    function (Event $e)
                    {
                        $node = $e->getParam('node');
                        echo sprintf("\n--- Sending Node:\n%s\n", $node);
                    }
                );
            }

            return $client;
        });


        //Which concret implementation will we use when an SMSInterface is asked for? User can pick in the config file.
        $this->app->bindShared('Williamson\Larawhatsapi\Repository\SMSMessageInterface', function ()
        {
            $fork = strtoupper(Config::get('larawhatsapi::fork'));
            switch ($fork)
            {
                case ($fork == 'MGP25'):
                    return App::make('Williamson\Larawhatsapi\Clients\LaraWhatsapiMGP25Client');
                    break;
                default:
                    return App::make('Williamson\Larawhatsapi\Clients\LaraWhatsapiTMVClient');
                    break;
            }
        });


        //Set up how the create the WhatsProt object when using MGP25 fork
        $this->app->bindShared('WhatsProt', function ()
        {
            //Setup Account details.
            $debug     = Config::get("larawhatsapi::debug");
            $account   = Config::get("larawhatsapi::useAccount");
            $nickName  = Config::get("larawhatsapi::accounts.$account.nickName");
            $number    = Config::get("larawhatsapi::accounts.$account.number");
            $userIdent = Config::get("larawhatsapi::accounts.$account.identity");
            $nextChallengeFile = Config::get("larawhatsapi::nextChallengeDir") . "/" . $number . "-NextChallenge.dat";

            $whatsProt =  new WhatsProt($number, $userIdent, $nickName, $debug);
            $whatsProt->setChallengeName($nextChallengeFile);

            return $whatsProt;
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}