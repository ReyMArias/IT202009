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
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT PointsHistory.id,PointsHistory.reason, Users.username, Scores.score FROM PointsHistory JOIN Users on PointsHistory.user_id = Users.id LEFT JOIN Scores PointsHistory.user_id = Score.user_id where PointsHistory.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
    <h3>View Scores</h3>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-body">
            <div>
                <p>Stats</p>
                <div>Score: <?php safer_echo($result["score"]); ?></div>
                <div>Reason of Change: <?php safer_echo($result["reason"]); ?></div>
                <div>Owned by: <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
