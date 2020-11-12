<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<h3>Create Point History</h3>
    <form method="POST">
        <label>Points Change</label>
        <input type="number" min="1" name="pointchange"/>
		<label>Reason</label>
        <input name="name" max="60" placeholder="reason"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$pointchange = $_POST["pointchange"];
	$reason = $_POST["reason"];
	$user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO PointsHistory (pointchange, reason, user_id) VALUES(:pointchange, :reason, :user)");
	$r = $stmt->execute([
		":pointchange"=>$pointchange,
		":reason"=>$reason,
		":user"=>$user
	]);
	if($r){
		flash("Created successfully with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}
}
?>
<?php require(__DIR__ . "/partials/flash.php");
