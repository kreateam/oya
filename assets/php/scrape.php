<?php




require 'vendor/autoload.php';
use InstagramScraper\Instagram;

define('HASHTAG', "osloby");
define('HASHFILE', __DIR__ . "/data/hashtag-".HASHTAG.".json");


define('DB', 'hashtag');
define('DB_HOST', 'localhost');
define('DB_USER', 'kreateam');
define('DB_PASS', 'Quolvalek0');

$reply = array("DEBUG" => array());
$tags = array();


// $medias = Instagram::getMediasByTag(HASHTAG, 100);

// echo json_encode($medias);

  class DBException extends Exception {};

define('DEFAULT_THUMBNAIL', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC)');

$DEFAULTS = array(
  "limit" => 20,
  "latestTime" => 0,
  "batchSize" => 30
  );

if (!file_exists(HASHFILE)) {
  $SETTINGS = json_decode(file_get_contents(HASHFILE), true);  
}
else {
  $SETTINGS = $DEFAULTS;
  file_put_contents(HASHFILE, json_encode($SETTINGS, JSON_PRETTY_PRINT));
}

  

  $reply['DEBUG'][] = "loaded settings: ";
  $reply['DEBUG'][] = json_encode($SETTINGS);


  function getTagId($tag = "apøyablikk") {

  }


  function getSelectedMedia($tag = "apøyablikk", $limit = 20) {
    global $reply, $tags;

    $tagId = isset($tags[$tag]) ? $tags[$tag] : null;
    if (!$tag || !$tagId) {
      $reply['DEBUG'][] = "error: no tag or tagId";
      return false;
    }

    $result = array();
    $succeeded = 0;
    $failed = 0;
    $posts = Instagram::getMediasByTag($tag, $limit);
    if ($posts && count($posts)) {
      try {
        $mysqli = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB);
        if ($mysqli->connect_errno) {
          throw new DBException(__FUNCTION__ . "," . __LINE__ . ", Connection error ({$mysqli->connect_errno}): " . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare("INSERT IGNORE into instagram (code, instaId, ownerId, ownerName, tagId, time, isvideo, url, caption, json) VALUES(?,?,?,?,?,?,?,?,?,?);");
// INSERT INTO myCity (Name, CountryCode, District) VALUES (?,?,?)
        foreach ($posts as $item) {
          // print($item->id."|".$item->caption ."\n");
          $owner = Instagram::getAccountById($item->ownerId);
          $username = "Unknown";
          if ($owner && isset($owner->username)) {
            var_dump($owner);
            $username = $owner->username;
          }
          
          $isvideo = ($item->type == "video");
          $url = "";
          $json = json_encode($item, JSON_PRETTY_PRINT);
          if ($isvideo ){
            $url = $item->videoStandardResolutionUrl;
          } 
          else {
            $url = $item->imageStandardResolutionUrl;
          }
          $stmt->bind_param("siisiiisss", $item->code, $item->id, $item->ownerId, $username, $tagId, $item->createdTime, $isvideo, $url, $item->caption, $json);
          $success = $stmt->execute();
          if ($success) {
            $succeeded++;
          }
          else {
            $failed++;
          }
        }
        $stmt->close();
        $result['succeeded'] = $succeeded;
        $result['failed'] = $failed;
      }
      catch (Exception $e) {
        $reply['DEBUG'][] = get_class($e) . ": " . $e->getMessage();
      }
    }
    else {
      return false;
    }
    return $result;
  }




  function getTagMedia($tag = "apøyablikk", $limit = 20) {
    global $reply, $tags;

    $tagId = isset($tags[$tag]) ? $tags[$tag] : null;
    if (!$tag || !$tagId) {
      return false;
    }

    $result = array();
    $succeeded = 0;
    $failed = 0;
    $posts = Instagram::getMediasByTag("apøyablikk", $limit);
    if ($posts && count($posts)) {
      try {
        $mysqli = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB);
        if ($mysqli->connect_errno) {
          throw new DBException(__FUNCTION__ . "," . __LINE__ . ", Connection error ({$mysqli->connect_errno}): " . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare("INSERT IGNORE into instagram (code, instaId, ownerId, ownerName, tagId, time, isvideo, url, thumbnail, caption, json) VALUES(?,?,?,?,?,?,?,?,?,?,?);");
// INSERT INTO myCity (Name, CountryCode, District) VALUES (?,?,?)
        foreach ($posts as $item) {
          //print($item->id."|".$item->caption ."\n");
          $owner = Instagram::getAccountById($item->ownerId);

          $username = "Unknown";
          if ($owner && isset($owner->username)) {
            var_dump($owner);
            $username = $owner->username;
          }

          $isvideo = ($item->type == "video");
          $url = "";
          $json = json_encode($item, JSON_PRETTY_PRINT);
          if ($isvideo ){
            $url = $item->videoStandardResolutionUrl;
            $thumbnail = isset($item->imageThumbnailUrl) ? $item->imageThumbnailUrl : DEFAULT_THUMBNAIL;
          } 
          else {
            $url = $item->imageStandardResolutionUrl;
            $thumbnail = $item->imageThumbnailUrl;
          }
          $stmt->bind_param("siisiiissss", $item->code, $item->id, $item->ownerId, $username, $tagId, $item->createdTime, $isvideo, $url, $thumbnail, $item->caption, $json);
          $success = $stmt->execute();
          if ($success) {
            $succeeded++;
            if ($item->isAd) {
              print("ADVERTISEMENT: " . $item->id);
            }
          }
          else {
            print("ERROR: " . $item->id);
            $failed++;
          }
        }
        $stmt->close();
        $result['succeeded'] = $succeeded;
        $result['failed'] = $failed;
      }
      catch (Exception $e) {
        $reply['DEBUG'][] = get_class($e) . ": " . $e->getMessage();
        $reply['error'] = get_class($e) . ": " . $e->getMessage();
      }
    }
    else {
      return false;
    }
    return $result;
  }


  function getActiveHashtags() {
    global $reply, $tags;

    $ACTIVE = 1;

    $result = array();

    try {

      $mysqli = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB);

      if ($mysqli->connect_errno) {
        throw new DBException(__FUNCTION__ . "," . __LINE__ . ", Connection error ({$mysqli->connect_errno}): " . $mysqli->connect_error);
      }

      $reply['DEBUG'][] = "Looking for active hashtags.";
      $stmt = $mysqli->prepare("SELECT id, tag, service, active, update_interval, updated FROM tags WHERE active = ?;");

      if (!$stmt) {
        $reply['DEBUG'][] = "Invalid SQL statement returned from mysqli::prepare().";
        $reply['error'] = "MySQL error (" . $mysqli->errno . ") : " . $mysqli->error;
        $reply['DEBUG'][] = $reply['error'];
        throw new DBException(__FUNCTION__ . "," . __LINE__ . ", " . $reply['error']);
      }

      $stmt->bind_param('i', $ACTIVE);

      if ($stmt->execute()) {
        $stmt->bind_result($dbid, $dbtag, $dbservice, $dbactive, $dbupdateInterval, $dbupdated);
        if ($stmt->fetch()) {
          array_push($result, array("id" => $dbid, "tag" => $dbtag, "service" => $dbservice, "active" => $dbactive, "updateInterval" => $dbupdateInterval, "updated" => $dbupdated));
          $tags[$dbtag] = $dbid;
          $reply['DEBUG'][] = __FUNCTION__ . "," . __LINE__ . "() -> SQL statement successfully executed.";
          $reply['DEBUG'][] = "dbid". "|" ."dbtag". "|" ."dbservice". "|" ."dbactive". "|" ."dbupdateInterval". "|" ."dbupdated";
          $reply['DEBUG'][] = $dbid. "|" .$dbtag. "|" .$dbservice. "|" .$dbactive. "|" .$dbupdateInterval. "|" .$dbupdated;
        }
        else {
          $reply['DEBUG'][] = __FUNCTION__ . "," . __LINE__ . "() -> stmt->fetch() NOT successful, returning empty result!";
        }
      }
      else {
        $reply['error'] = "MySQL error (" . $stmt->errno . ") : " . $stmt->error;
        $reply['DEBUG'][] = $reply['error'];
        $reply['DEBUG'][] = __FUNCTION__ . "," . __LINE__ . "() -> SQL statement NOT successfully executed.";
        throw new DBException(__FUNCTION__ . "," . __LINE__ . ", " . $reply['error']);
      }
    }
    catch (Exception $e) {
      $reply['error'] = get_class($e) . ": " . $e->getMessage();
      return false;
    }    
    return $result;

  }




$activeTags = getActiveHashtags();
$tagCount = count($activeTags);
$reply['activeTags'] = $activeTags;

if ($tagCount) {
  foreach ($activeTags as $item) {
    $updatedCount = getTagMedia($item['tag'], 100);
  }
}

$reply['tags'] = $tags;

// save settings
file_put_contents(HASHFILE, json_encode($SETTINGS, JSON_PRETTY_PRINT));
if (isset($reply['error'])) {
  print($reply['error']."\n");
}

// print("Result, $tagCount tag(s): \n");
// print("-----------------------------------\n");
// print(json_encode($reply, JSON_PRETTY_PRINT));
// print("\n");

?>
