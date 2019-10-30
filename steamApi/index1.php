<?php
    require ('steamauth/steamauth.php');
	# You would uncomment the line beneath to make it refresh the data every time the page is loaded
	 unset($_SESSION['steam_uptodate']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>page</title>
</head>
<body>
<?php
if(!isset($_SESSION['steamid'])) {
    echo "welcome guest! please login<br><br>";
    loginbutton(); //login button
    
}  else {
    include ('steamauth/userInfo.php');
    //Protected content
    include "SteamPlayer.php";

// Must set SteamApi key
SteamPlayer::$API_KEY = '95EBE10AAE07E922DC933C5960A433A5';

// Find player by identifier

    echo "<b>Welcome back " . $steamprofile['personaname'] . "<b></br>";
    echo "Your real name is " . $steamprofile['realname'] . "</br>";
    echo "Created: ".date("Y-m-d\TH:i:s\Z", $steamprofile['timecreated']) . "</br>";
    echo "Current state : " . $steamprofile['personastate']  . "</br>";
    echo "Profile url : " . $steamprofile['profileurl'] . "</br>";
    echo "ID is" . $steamprofile['steamid'] . "</br>";
    echo "here is your avatar: </br>" . '<img style="border:3px solid red" src="'.$steamprofile['avatarfull'].'" title="" alt="" /><br>'; // Display their avatar!

    $steamID = $steamprofile['steamid'];
    $player = SteamPlayer::Create($steamID);
    echo 'private profile: '.($player->isPrivate() ? 'true' : 'false')."<br/>\r\n";
    echo 'countrycode: '.$player->countryCode()."<br/>\r\n";
    echo 'localitycode: '.$player->localityCode()."<br/>\r\n";
    echo 'loccityid: '.$player->loccityid."<br/>\r\n";
    echo 'is playing: '. $player->isPlaying()."<br/>\r\n";
    echo 'game name: '. $player->gameName()."<br/>\r\n";
    echo 'game id: '. $player->gameId()."<br/>\r\n";
    
// Get friend list of current user
$friendsSteamPlayersCollection = $player->Friends();
echo 'count friends: '.$friendsSteamPlayersCollection->count().'<hr/>';


############################	 Showing each friend 	##########################

foreach($friendsSteamPlayersCollection->get() as $friend) {
	echo $friend->nickName().'<br/>';
}

echo '<hr/>';


##################    Get friends which are living in RU and PE 	########################

$newCollection = $friendsSteamPlayersCollection->country(['RU', 'PE']);
foreach($newCollection->get() as $friend) {
	echo $friend->nickName().' | '.$friend->countryCode().'<br/>';
}

echo '<hr/>';


######################### Get friends which are playing in any of games and status is not away	##################

$newCollection = $friendsSteamPlayersCollection->statusNot(SteamPlayer::STATUS_AWAY)->isPlaying();
foreach($newCollection->get() as $friend) {
	echo $friend->nickName().' | '.$friend->gameName().'<br/>';
}



    logoutbutton();
}    
?>  
</body>
</html>