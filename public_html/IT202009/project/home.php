<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
    <p>Welcome, <?php echo $email; ?></p>

<?php
$db = getDB();
$results = [];
$today = date("Y/m/d h:i:s")
$week = strtotime("-1 Weeks");
$month = strtotime("-1 Months");



$stmt = $db->prepare("SELECT id, score, user_id FROM Scores WHERE Scores.user_id = :id ORDER BY score DESC LIMIT 10");
$stmt->execute([":id" => get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


<form method="POST">
    <label>Last 10 Scores</label>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $results): ?>
                <div class="list-group-item">
                    <div>
                        <div>Score:</div>
                        <div><?php safer_echo($results["score"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>



$stmt = $db->prepare("SELECT TOP 10 score FROM Scores ORDER BY score");
$stmt->execute([":id" => get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form method="POST">
    <label>Top 10 Weekly</label>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $results): ?>
                <div class="list-group-item">
                    <div>
                        <div>Score:</div>
                        <div><?php safer_echo($results["score"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php");