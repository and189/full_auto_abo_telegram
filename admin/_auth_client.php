<?php
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$InputChannel = 'https://t.me/joinchat/AAAAAEOzl0uIG6rC2xuqjQ';	// YOUR Telegram Chanel
$InputUser = '@username'; // aother test username

$Updates = $MadelineProto->channels->inviteToChannel(['silent' => false, 'channel' => $InputChannel, 'users' => [$InputUser, $InputUser], ]);
?>
