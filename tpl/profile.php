<?php
$user = \Discord2\User::getUsername();
if (isset($_GET['username'])) $user = $_GET['username'];
$u = \Users\Users::getUser($user);
$img = $u->getAvatarURL();
$likes = 0; // Temp, TODO: implement liking users and mods
$likes = \Ratings\UserLikes::get($u->id);
//die(code(print_r($likes,1)));
$uploaded = $u->getUploadCount(); // Temp, TODO: implement uploaded total
$uploadedText = "$uploaded";
if ($uploaded == 0) {
	$uploadedText = "None";
}
$creatorID = $u->id;
$name = $u->username;
$join = date("F j, Y",$u->join_date);
$lastLogin = date("F j, Y, g:i a",$u->last_login);
$__output = <<<end
<section style="background-color: #eee;">


    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="{$img}" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3">{$name}</h5>
end;

$likeForm = <<<end
			  <form action="/uLike:{$creatorID}.html" method="post" /><input type="hidden" name="returnURL" value="{$_SERVER['REQUEST_URI']}" />
			    <input  type="submit" data-mdb-button-init  data-mdb-ripple-init value="Like" class="btn btn-success" />
			  </form>
			  &nbsp;
end;

$unlikeForm = <<<end

			  <form action="/uRLike:{$creatorID}.html" method="post"  /><input type="hidden" name="returnURL" value="{$_SERVER['REQUEST_URI']}" />
			    <input  type="submit" data-mdb-button-init data-mdb-ripple-init value="Remove Like" class="btn btn-success" />
			  </form>
			  &nbsp;
end;



if (\Discord2\User::isLoggedIn()) {
	$curUser = \Users\Users::getUser();

	$__output .= '			<div class="d-flex justify-content-center mb-2">';
	if ($curUser->likesUser($u->id)) {
		$__output .= $unlikeForm;
	} else {
		$__output .= $likeForm;
	}
	
	$__output .= "</div>";
}
$__output .= <<<end
          </div>
        </div>
        
      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Discord User</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">{$name}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Likes</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">{$likes}</p>
              </div>
            </div>
            <hr>
 
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Uploaded Glitches</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">{$uploadedText}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Join Date</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">{$join}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Last Login</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">{$lastLogin}</p>
              </div>
            </div>
			
          </div>
        </div>
        
      </div>
    </div>
  </div>
</section>

end;


$k = <<<end
<tr>
      <th scope="row"><img src="%%front%%" alt="avatar"
              class="rounded-circle img-fluid" style="width: 64px;"></th>
      <td>%%name%%</td>
      <td>%%rating%%</td>
      <td>%%ogMon%%</td>
      <td><img src="/img/types/%%tOne%%.png" /></td>
      <td><img src="/img/types/%%tTwo%%.png" /></td>
	  <td><form action="/g:%%lname%%:%%id%%.html"><input class="form-control" type="submit" value="View"  /></form></td>
    </tr>
end;

try {
	$glitches = \Glitches\Glitch::LoadBy(["created_by"=>$u->id]);
	$__output .= <<<end
<table class="table table-striped table-dark" id="galleryMons">
  <thead>
    <tr>
      <th scope="col">Sprite</th>
      <th scope="col">Name</th>
	  <th scope="col">Rating</th>
	  <th scope="col">Base Pokemon</th>
      <th scope="col">Primary Type</th>
	  <th scope="col">Secondary Type</th>
	  <th scope="col">View</th>
    </tr>
  </thead>
  <tbody>
end;
	foreach($glitches as $glitch) {
		$mon2 = json_decode($glitch->json_data);
		$user = new \Users\Users($glitch->created_by);
		$typeOne = $mon2->primaryType;
		$typeTwo = $mon2->secondaryType;

		$ogMon = \Pokemon\Pokemon::getMon($mon2->speciesId);
                $tmp = str_replace("%%lname%%",str_replace(" ","",$glitch->name),$k);
		$tmp = str_replace("%%name%%",trim($glitch->name),$tmp);

		$tmp = str_replace("%%front%%",$glitch->front,$tmp);
		$tmp = str_replace("%%rating%%",$glitch->getRating(),$tmp);
		$tmp = str_replace("%%tOne%%",$typeOne,$tmp);
                $tmp = str_replace("%%id%%",$glitch->id,$tmp);

		$tmp = str_replace("%%tTwo%%",$typeTwo,$tmp);
		$tmp = str_replace("%%ogMon%%",ucwords(str_replace("-",' ',$ogMon->name)),$tmp);
		$__output .= $tmp;
	}
	$__output .= <<<end
</tbody>
</table>

<script type="text/javascript">
/* */
  $(document).ready(function() {
    $("#galleryMons").DataTable({
	  "paging": 1,
	  "stateSave": 0,
	  "searching": 1,
      "order": [[ 2, "desc" ]]
    });
  });
/* */
</script>  
<style type="text/css">
table.dataTable tbody tr {
    background-color: #343a40;
}
</style>
end;
} catch  (\Exceptions\ItemNotFound $e) {}
