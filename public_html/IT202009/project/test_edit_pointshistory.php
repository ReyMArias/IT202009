
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$points_change = $_POST["points_change"];
	if ($points_change <= 0) {
		$points_change = null;
	}
	$reason = $_POST["reason"];
	$user = get_user_id();
	$db = getDB();
	if(isset($id)){
		$stmt = $db->prepare("UPDATE PointHistory set points_change=:points_change, reason=:reason where id=:id");
		$r = $stmt->execute([
			":points_change"=>$points_change,
			"reason"=>$reason,
			":id"=>$id
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM PointHistory where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);	
}

$db = getDB();
$stmt = $db->prepare("SELECT score FROM Scores AND user_id from User LIMIT 10");
$r = $stmt->execute();
$eggs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
    <h3>Edit Scores</h3>
    <form method="POST">
	<div>User: <?php safer_echo($result["user_id"]); ?></div>
        <label>Increase/Decrease Points:</label>
        <input type="number" min="-100" max="100" name="points_change" value="<?php echo $result["points_change"]; ?>"/>
        <input type="submit" name="save" value="Update"/>
    </form>

<?php require(__DIR__ . "/partials/flash.php");