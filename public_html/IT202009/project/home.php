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
$weekly = [];
$monthly = [];

$stmt = $db->prepare("SELECT score FROM Scores ORDER BY score DESC LIMIT 10");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT score FROM Scores WHERE created BETWEEN '2020-11-22 23:05:55' AND '2020-11-22 23:06:02' ORDER BY score DESC LIMIT 10");
$stmt->execute();
$weekly = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT score FROM Scores WHERE created BETWEEN '2020-10-22 23:05:55' AND '2020-11-22 23:06:02' ORDER BY score DESC LIMIT 10");
$stmt->execute();
$monthly = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<form method="POST">
    <label>Top Scores</label>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $result): ?>
                <div class="list-group-item">
                    <div>
                        <div>Top Scores:</div>
                        <div><?php safer_echo($result["score"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


    <?php foreach ($weekly as $w): ?>
                <div class="list-group-item">
                    <div>
                        <div>Weekly Top Scores:</div>
                        <div><?php safer_echo($w["score"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach ($monthly as $m): ?>
                <div class="list-group-item">
                    <div>
                        <div>Monthly Top Scores:</div>
                        <div><?php safer_echo($m["score"]); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>

</div>
<?php require(__DIR__ . "/partials/flash.php");