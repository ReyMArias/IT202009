<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT PointsHistory.id,points_change,reason, PointsHistory.user_id, Users.username FROM PointsHistory JOIN Users on PointsHistory.user_id = Users.id WHERE Users.username like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
        <html>
            <head>
            <style>
            table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }
            td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }
            tr:nth-child(even) {
            background-color: #dddddd;
            }
            </style>
            </head>
            <body>
            <h2>Points History</h2>        
            <?php foreach ($results as $r): ?>
                <table>
                <tr>
                    <th>Username</th>
                    <th>Points Change</th>
                    <th>Reason</th>
                </tr>
                <tr>
                    <td><?php safer_echo($r["username"]); ?></td>
                    <td><?php safer_echo($r["points_change"]); ?></td>
                    <td><?php safer_echo($r["reason"]); ?></td>
                </tr>
                </table>

            </body>
        </html>
                
                
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>