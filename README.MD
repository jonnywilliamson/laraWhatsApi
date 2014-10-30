# WhatsApi Integrated with Laravel

There are a number of WhatsAPI forks out there at the moment that allow the user to send messages via Whatsapp with PHP. However all of them are quite cumbersome when it comes to writing the code necessary to make it all work.

A lot of us are now using Laravel in our PHP projects and this project attempts to take the wonderful readability of Laravel's syntax and apply it to the WhatsApi code. 

Laravel does all the grunt work in setting up and maintaining the connection with Whatsapp and also allows you to use **multiple** accounts if you have them!


##Ok enough! - just show me an example!
Ok ok, wouldn't it be nice if we could do this in our projects at ANY stage:

```php
$message = "Hello $user->name and welcome to our website";
$user = User::find(1);

WA::sendMessage($user->phoneNum, $message);
```

and have a message instantly sent? Instead of all this:

```php
$user = User::find(1);

// Initializing client
$localizationService = new LocalizationService();
$phone = new Phone($user->phoneNum); // your phone number with international prefix
$localizationService->injectPhoneProperties($phone);
$identity = new Identity();
$identity->setNickname($user->nickName); // your name
$identity->setIdentityToken($user->token);    // your token
$identity->setPassword($user->password); // your password
$identity->setPhone($phone);
$client = new Client($identity);
$client->setChallengeDataFilepath(__DIR__ . '/data/nextChallenge.dat');
$client->connect();
$client->login();

$number = ''; // number to send message
// Sending composing notification (simulating typing)
$client->send(new Action\ChatState($number, Action\ChatState::STATE_COMPOSING));
$client->send(new Action\ChatState($number, Action\ChatState::STATE_PAUSED));
$messagetext = "Hello $user->name and welcome to our website"
$message = new Action\MessageText($identity->getNickname(), $number);
$message->setBody($messageText);
$client->send($message);
```

Well I thought so anyway, and so I made this package!

This project can be integrated with an existing Laravel Installation. The following instructions show you how to get it up and running from absolute scratch. 

**If you already have Laravel running, just jump in at the appropiate part instead.**

##Prerequisites!

This is the beginning of the project. 

At the moment you **must** know your whatsapp's password and identity string for this to work. If you do NOT have these details, you will need to acquire them either by using [Wart](https://github.com/shirioko/WART), or by using one of these php libraries manually:[WhatsAPI-Official](https://github.com/mgp25/WhatsAPI-Official) / [TmvWhatsApi](https://github.com/thomasvargiu/TmvWhatsApi)

At a later stage a registration feature using this package might become available.

##Installation

1. Assuming you already have composer installed on your system - install a new Laravel Project into ```whatsapidemoproject``` folder:

        composer create-project laravel/laravel whatsapidemoproject --prefer-dist

2. Ensure that you set your webserver to use ```whatsapidemoproject/public``` as it's webroot. If you visit http://localhost (or whatever domain name you are using) you should now see a welcome to laravel message. If you have problems, check out the official documents on the [Laravel Home Page](http://laravel.com/docs/4.2/quick "Laravel Installation").  A quick alternative is to use the `php artisan serve` command

3. Now change into your new ```whatsapiprojectdemoproject``` folder

        cd whatsapiprojectdemo

4. Require this laraWhatsApi package.

        composer require williamson/larawhatsapi:dev-master --update-no-dev


5. Now we need to create the config file that will allow the user to very easily add all their numbers/names/password/identities.

        php artisan config:publish williamson/larawhatsapi

6. Finally, we need to tell Laravel that there is a LaraWhatsApi ServiceProvider to be used when Laravel starts up. In the project folder, edit the `app/config/app.php` file. Find the end of the providers array, it should look like this:

        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider'

    and add the last line to make it look like this:

        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider',
        'Williamson\Larawhatsapi\LaraWhatsapiServiceProvider'


##Configuration
Now everything has been installed, you just need to add your Whatsapp account details into the config file. 

There will now be a personal config file created for you in ```whatsapidemoproject/app/config/packages/williamson/larawhatsapi```.

Open this file and edit the details with your account info. 

Don't forget that ```nextChallengeDir``` MUST be writable by the webserver otherwise you will get errors.

Once saved, you're good to use the API!

##Which Fork?

In the config file you will see an option for fork. There are two leading php whatsapi forks out there at the moment, [WhatsAPI-Official](https://github.com/mgp25/WhatsAPI-Official) and [TmvWhatsApi](https://github.com/thomasvargiu/TmvWhatsApi). Both of them have pros and cons.

**WhatsAPI-Official** gets updated more often, but the code is very messy and hard to maintain.

**TmvWhatsApi** doesn't get updated as often, but the code is very well structured and laid out. As time progresses and more features are added, I believe this will be a solid foundation to use for a php version of whatsapi.

However the choice is yours, by changing the fork option in your config file, you will be able to pick which library is being used in the background to make all the magic happen.


##Usage
Lets make a simple example.

Open, the routes file ```whatsapidemoproject/app/routes.php```. Lets add a new route: 
        
        Route::get("demo", function()
        {
            
            echo "I'm sending a message via Whatsapi!";
            WA::sendMessage('353xxxxxxxx', "My boring Hello World Text!");
            
        });

Obviously edit the phone number to one you wish to receive the message on. Save the file. We're good to go!

You can now access the demo and send the message simply by visiting: [http://localhost/demo](http://localhost/demo)