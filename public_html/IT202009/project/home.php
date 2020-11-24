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


$stmt = $db->prepare("SELECT id, score, user_id, FROM Scores ORDER BY id desc WHERE id = :id LIMIT 10");
$stmt->execute([":id" => get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result) {
    $score = $result["score"];
}
else {
//else for $isValid, though don't need to put anything here since the specific failure will output the message
}

?>
<form method="POST">
    <label>View Player's Score History</label>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Score:</div>
                        <div><?php safer_echo($result["score"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php");