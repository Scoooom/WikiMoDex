<?php
ini_set('mysql.connect_timeout', 300);
ini_set('default_socket_timeout', 300); 

define('GID',json_encode(["gid1339035316585107546"=>1]));
function allowed_guid($gid) {
  $allowed = json_decode(GID);
  $tag = "gid".$gid;
  return isset($allowed->$tag);
}
define('CID',json_encode(["cid1388985958371688448"=>1,"cid1381010953671540876"=>1,"cid1365402438969983066"=>1]));

function allowed_cid($cid) {
  $allowed = json_decode(CID);
  $tag = "cid".$cid;
  return isset($allowed->$tag);
}
/**
 * Example Bot with Discord-PHP
 *
 * When a User says "ping", the Bot will reply "pong"
 *
 * Getting a User message content requries the Message Content Privileged Intent
 * @link http://dis.gd/mcfaq
 *
 * Run this example bot from main directory using command:
 * php examples/ping.php
 */
define('ADB','voidGlitch');
define('hardPath','/var/www/void.scooom.xyz/Classes/');

require 'vendor/autoload.php';
require_once(hardPath.'/functions.php');

function statBar($value) {
	$rounded = round($value/5) * 5;
	$div5 = ($rounded/5);
	$remain = 20 - $div5;
	$k = '';
	while($div5 > 0) {
		$k .= '=';
		$div5--;
	}while($remain > 0) {
		$k .= '-';
		$remain--;
	}
	return $k;
}
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\Builders\MessageBuilder;

$token = trim(file_get_contents("/var/www/void.scooom.xyz/token.txt"));
// Create a $discord BOT
$discord = new Discord([
    'token' => $token, // Put your Bot token here from https://discord.com/developers/applications/
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT // Required to get message content, enable it on https://discord.com/developers/applications/
]);

// When the Bot is ready
$discord->on('init', function (Discord $discord) {

    // Listen for messages
    $discord->on('message', function (Message $message, Discord $discord) {

        // If message is from a bot
        if ($message->author->bot) {
            // Do nothing
            return;
        }

		$tmp = explode(" ",$message->content);
        $command = $tmp[0];
        unset($tmp[0]);
        $msg = implode(" ",$tmp);

        // If message is "ping"
        if ($command == '!glitch') {
			if (!allowed_guid($message->guild_id)) {
				$message->reply("This server is not authoried to use this bot!");
				return;
			}
            // Reply with "pong"
            try {
			  $tpl = <<<end
			# [{%name%} (Rating: {%rating%})](<https://void.scooom.xyz/g:{%name%}:{%id%}.html>)
## Created By [{%by%}](<https://void.scooom.xyz/u:{%by%}.html>)
### Typing: `{%typeOne%}` / `{%typeTwo%}`
### Abilties
`{%ab1%}`
-# `{%ab1Desc%}`
`{%ab2%}`
-# `{%ab2Desc%}`
Hidden: `{%abHA%}`
-# `{%abHADesc%}`
### Stats
`{%statBarHP%}` HP: {%hpValue%} 
`{%statBarAtk%}` Attack: {%atkValue%} 
`{%statBarDef%}` Defense: {%defValue%} 
`{%statBarSpAtk%}` Special Attack: {%spAtkValue%} 
`{%statBarSpDef%}` Special Defence: {%spDefValue%} 
`{%statBarSpd%}` Speed: {%spdValue%} 
BST: {%bstValue%} 
-# Rivals: {%rivals%}
end;
              $a = \Glitches\Glitch::LoadBy(["name"=>$msg])[0];

              $img = $a->front;

              $user = new \Users\Users($a->created_by);
              $maker = $user->username;
			  $tpl = str_replace('{%name%}',trim($a->name),$tpl);
			  $tpl = str_replace('{%id%}',$a->id,$tpl);
			  $tpl = str_replace('{%by%}',$maker,$tpl);
			  $tpl = str_replace('{%typeOne%}',$a->getTypeOneEn(),$tpl);
			  $tpl = str_replace('{%typeTwo%}',$a->getTypeTwoEn(),$tpl);
			  $tpl = str_replace('{%rivals%}',$a->getRivals(true),$tpl);
			  
			  $abilityOne = $a->getAbilityOne();
			  $abilityTwo = $a->getAbilityTwo();
  			  $rating = $a->getRating();
  			  $abilityHA = $a->getAbilityHA();

			  $ogStats = $a->getOGStats();
			  $ogBST = $ogStats[0]['value'] + $ogStats[1]['value'] + $ogStats[2]['value'] + $ogStats[3]['value'] + $ogStats[4]['value'] + $ogStats[5]['value'];
			  $boostedStats = $a->adjustStats($ogStats,$a->calculateTotalIncrease($ogBST));
              //$tpl .= print_r($boostedStats,1);

			  // Stats
			  $tpl = str_replace('{%statBarHP%}',\statBar($boostedStats[0]['percent']),$tpl);
			  $tpl = str_replace('{%hpValue%}',($boostedStats[0]['value']),$tpl);
			  
			  $tpl = str_replace('{%statBarAtk%}',\statBar($boostedStats[1]['percent']),$tpl);
			  $tpl = str_replace('{%atkValue%}',($boostedStats[1]['value']),$tpl);
			  
			  $tpl = str_replace('{%statBarDef%}',\statBar($boostedStats[2]['percent']),$tpl);
			  $tpl = str_replace('{%defValue%}',($boostedStats[2]['value']),$tpl);
			  
			  $tpl = str_replace('{%statBarSpAtk%}',\statBar($boostedStats[3]['percent']),$tpl);
			  $tpl = str_replace('{%spAtkValue%}',($boostedStats[3]['value']),$tpl);
			  
			  $tpl = str_replace('{%statBarSpDef%}',\statBar($boostedStats[4]['percent']),$tpl);
			  $tpl = str_replace('{%spDefValue%}',($boostedStats[4]['value']),$tpl);
			  
			  $tpl = str_replace('{%statBarSpd%}',\statBar($boostedStats[5]['percent']),$tpl);
			  $tpl = str_replace('{%spdValue%}',($boostedStats[5]['value']),$tpl);
				
			  $newBST = $boostedStats[0]['value'] + $boostedStats[1]['value'] + $boostedStats[2]['value'] + $boostedStats[3]['value'] + $boostedStats[4]['value'] + $boostedStats[5]['value'];
			  $tpl = str_replace('{%bstValue%}',($newBST),$tpl);

			  $rating = $a->getRating();
			  $tpl = str_replace('{%rating%}',($rating),$tpl);


			  $tpl = str_replace('{%ab1%}',$abilityOne['name'],$tpl);
			  $tpl = str_replace('{%ab1Desc%}',$abilityOne['desc'],$tpl);
			  $tpl = str_replace('{%ab2%}',$abilityTwo['name'],$tpl);
			  $tpl = str_replace('{%ab2Desc%}',$abilityTwo['desc'],$tpl);
			  $tpl = str_replace('{%abHA%}',$abilityHA['name'],$tpl);
			  $tpl = str_replace('{%abHADesc%}',$abilityHA['desc'],$tpl);



              $img = base64_decode(str_replace("data:image/png;base64,","",$img));

              $img2 = base64_decode(str_replace("data:image/png;base64,","",$a->back));
              $msg = "Rivals: ".$a->getRivals(1)."\n\nCreated By: `".$maker.'`';

              $builder = MessageBuilder::new();
              $builder->setContent($tpl);
              $builder->addFileFromContent($a->name."Front.png",$img);
              $builder->addFileFromContent($a->name."Back.png",$img2);
              file_put_contents(hardPath.'/../bot/log',$msg);
              $message->reply($builder);
            } catch (\Exceptions\ItemNotFound $e) {
              $message->reply("The GlitchDex was unable to locate `".$msg."`!");
            }
		} else if ($command == "!core") {
			if (!allowed_guid($message->guild_id)) {
			  $message->reply("This server is not authoried to use this bot!");
			  return;
			} else {
				$msg = strtolower($msg);
			  $core = \Glitches\BuiltIn::LoadCore($msg);
			  if (!$core) 
			    $message->reply("The CoreDexs was unable to locate `".$msg."`!");
			  else {

			  $tpl = <<<end
			# [{%name%}](<https://void.scooom.xyz/core:{%name%}.html>)
### Typing: `{%typeOne%}` / `{%typeTwo%}`
### Abilties
`{%ab1%}`
-# `{%ab1Desc%}`
`{%ab2%}`
-# `{%ab2Desc%}`
Hidden: `{%abHA%}`
-# `{%abHADesc%}`
### Stats
`{%statBarHP%}` HP: {%hpValue%} 
`{%statBarAtk%}` Attack: {%atkValue%} 
`{%statBarDef%}` Defense: {%defValue%} 
`{%statBarSpAtk%}` Special Attack: {%spAtkValue%} 
`{%statBarSpDef%}` Special Defence: {%spDefValue%} 
`{%statBarSpd%}` Speed: {%spdValue%} 
BST: {%bstValue%} 
end;

              $img = $a->front;

			  $tpl = str_replace('{%name%}',trim($core->name),$tpl);
			  $tpl = str_replace('{%id%}',$a->id,$tpl);
			  $tpl = str_replace('{%by%}',$maker,$tpl);
			  $tpl = str_replace('{%typeOne%}',\Glitches\Glitch::getTypeEn($core->type1),$tpl);
			  $tpl = str_replace('{%typeTwo%}',\Glitches\Glitch::getTypeEn($core->type2),$tpl);
			 
			  $hpPer = floor(($core->hp / 255)*100);
$atkPer = floor(($core->atk / 255)*100);
$defPer = floor(($core->def / 255)*100);
$spatkPer = floor(($core->spatk / 255)*100);
$spdefPer = floor(($core->spdef / 255)*100);
$spdPer = floor(($core->spd / 255)*100);
			  // Stats
			  $tpl = str_replace('{%statBarHP%}',\statBar($hpPer),$tpl);
			  $tpl = str_replace('{%hpValue%}',($core->hp),$tpl);
			  
			  $tpl = str_replace('{%statBarAtk%}',\statBar($atkPer),$tpl);
			  $tpl = str_replace('{%atkValue%}',($core->atk),$tpl);
			  
			  $tpl = str_replace('{%statBarDef%}',\statBar($defPer),$tpl);
			  $tpl = str_replace('{%defValue%}',($core->def),$tpl);
			  
			  $tpl = str_replace('{%statBarSpAtk%}',\statBar($spatkPer),$tpl);
			  $tpl = str_replace('{%spAtkValue%}',($core->spatk),$tpl);
			  
			  $tpl = str_replace('{%statBarSpDef%}',\statBar($spdefPer),$tpl);
			  $tpl = str_replace('{%spDefValue%}',($core->spdef),$tpl);
			  
			  $tpl = str_replace('{%statBarSpd%}',\statBar($spdPer),$tpl);
			  $tpl = str_replace('{%spdValue%}',($core->spd),$tpl);
				
			  $tpl = str_replace('{%bstValue%}',($core->bst),$tpl);



			  $tpl = str_replace('{%ab1%}',$core->ab1->name,$tpl);
			  $tpl = str_replace('{%ab1Desc%}',$core->ab1->description,$tpl);
			  $tpl = str_replace('{%ab2%}',$core->ab2->name,$tpl);
			  $tpl = str_replace('{%ab2Desc%}',$core->ab2->description,$tpl);
			  $tpl = str_replace('{%abHA%}',$core->ha->name,$tpl);
			  $tpl = str_replace('{%abHADesc%}',$core->ha->description,$tpl);



              $img = file_get_contents(GPATH.strtolower($core->name).".png");

              $img2 = file_get_contents(GPATH.strtolower($core->name)."_back.png");

              $builder = MessageBuilder::new();
              $builder->setContent($tpl);
              $builder->addFileFromContent($a->name."Front.png",$img);
              $builder->addFileFromContent($a->name."Back.png",$img2);
//              file_put_contents(hardPath.'/../bot/log',$msg);
              $message->reply($builder);
            
			
			/* */;
			}
			}

		} else if ($command == "!smitty") {
			if (!allowed_guid($message->guild_id)) {
			  $message->reply("This server is not authoried to use this bot!");
			  return;
			}
			if (!allowed_cid($message->channel_id)) {
				$message->reply("This channel is not authoried to use this command! Please use <#1381010953671540876>");
			} else {
			  $msg = strtolower($msg);
			  $core = \Glitches\BuiltIn::LoadSmitty($msg);
			  if (!$core) $core = \Glitches\BuiltIn::LoadSmittyForm($msg);
			  if (!$core) 
			    $message->reply("The CoreDexs was unable to locate `".$msg."`!");
			  else {

			  $tpl = <<<end
			# [{%name%}](<https://void.scooom.xyz/smitty:{%name%}.html>) - {%%code%%}
### Typing: `{%typeOne%}` / `{%typeTwo%}`
### Abilties
`{%ab1%}`
-# `{%ab1Desc%}`
`{%ab2%}`
-# `{%ab2Desc%}`
Hidden: `{%abHA%}`
-# `{%abHADesc%}`
### Stats
`{%statBarHP%}` HP: {%hpValue%} 
`{%statBarAtk%}` Attack: {%atkValue%} 
`{%statBarDef%}` Defense: {%defValue%} 
`{%statBarSpAtk%}` Special Attack: {%spAtkValue%} 
`{%statBarSpDef%}` Special Defence: {%spDefValue%} 
`{%statBarSpd%}` Speed: {%spdValue%} 
BST: {%bstValue%} 
end;

              $img = $a->front;
                          $code = `/usr/local/bin/getUniCode {$core->name} | gawk -F"'" '{print $4}'`;
			  $tpl = str_replace('{%name%}',trim($core->name),$tpl);
			  $tpl = str_replace('{%id%}',$a->id,$tpl);
			  $tpl = str_replace('{%by%}',$maker,$tpl);
			  $tpl = str_replace('{%typeOne%}',\Glitches\Glitch::getTypeEn($core->type1),$tpl);
			  $tpl = str_replace('{%typeTwo%}',\Glitches\Glitch::getTypeEn($core->type2),$tpl);
			 
			  $hpPer = floor(($core->hp / 255)*100);
$atkPer = floor(($core->atk / 255)*100);
$defPer = floor(($core->def / 255)*100);
$spatkPer = floor(($core->spatk / 255)*100);
$spdefPer = floor(($core->spdef / 255)*100);
$spdPer = floor(($core->spd / 255)*100);
			  // Stats
			  $tpl = str_replace('{%statBarHP%}',\statBar($hpPer),$tpl);
			  $tpl = str_replace('{%hpValue%}',($core->hp),$tpl);
                          $tpl = str_replace('{%%code%%}',$code,$tpl);

			  $tpl = str_replace('{%statBarAtk%}',\statBar($atkPer),$tpl);
			  $tpl = str_replace('{%atkValue%}',($core->atk),$tpl);
			  
			  $tpl = str_replace('{%statBarDef%}',\statBar($defPer),$tpl);
			  $tpl = str_replace('{%defValue%}',($core->def),$tpl);
			  
			  $tpl = str_replace('{%statBarSpAtk%}',\statBar($spatkPer),$tpl);
			  $tpl = str_replace('{%spAtkValue%}',($core->spatk),$tpl);
			  
			  $tpl = str_replace('{%statBarSpDef%}',\statBar($spdefPer),$tpl);
			  $tpl = str_replace('{%spDefValue%}',($core->spdef),$tpl);
			  
			  $tpl = str_replace('{%statBarSpd%}',\statBar($spdPer),$tpl);
			  $tpl = str_replace('{%spdValue%}',($core->spd),$tpl);
				
			  $tpl = str_replace('{%bstValue%}',($core->bst),$tpl);



			  $tpl = str_replace('{%ab1%}',$core->ab1->name,$tpl);
			  $tpl = str_replace('{%ab1Desc%}',$core->ab1->description,$tpl);
			  $tpl = str_replace('{%ab2%}',$core->ab2->name,$tpl);
			  $tpl = str_replace('{%ab2Desc%}',$core->ab2->description,$tpl);
			  $tpl = str_replace('{%abHA%}',$core->ha->name,$tpl);
			  $tpl = str_replace('{%abHADesc%}',$core->ha->description,$tpl);



              $img = file_get_contents(GPATH.strtolower($core->name).".png");

              $img2 = file_get_contents(GPATH.strtolower($core->name)."_back.png");

              $builder = MessageBuilder::new();
              $builder->setContent($tpl);
              $builder->addFileFromContent($a->name."Front.png",$img);
              $builder->addFileFromContent($a->name."Back.png",$img2);
//              file_put_contents(hardPath.'/../bot/log',$msg);
              $message->reply($builder);
            
			
			/* */;
			}
			}

		} else if ($command == "!auth") {
			if (!allowed_cid($message->channel_id)) {
				$message->reply("You may not authorize in this channel!");
				return;
			}
            try {
				$user = \Users\Users::LoadBy(["user_id"=>$message->author->id])[0];
				if ($user->smitty) {
					$message->reply("You are already authorized to see SMITTY forms!");
					return;
				} else {
					$user->smitty = true;
					$user->Save();
					$message->reply("You have been authorized to see SMITTY forms!");
					return;
				}
			} catch (\Exceptions\ItemNotFound $e) {
			    $message->reply("User Not Found! Please login at the [WikiMoDex](https://void.scooom.xyz/index.html) before attempting to Authorize!");
				return;
			}
		}

    });

});

// Start the Bot (must be at the bottom)
$discord->run();
