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
    $stmt = $db->prepare("SELECT PointsHistory.id,PointsHistory.reason, PointsHistory.user_id, PointsHistory.created, points_change, Users.username, Scores.score as score FROM PointsHistory JOIN Users on PointsHistory.user_id = Users.id JOIN Scores on PointsHistory.user_id = Scores.user_id where PointsHistory.id = :id");
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
            <h2>Stats</h2>        
                <table>
                <tr>
                    <th>Username</th>
                    <th><?php safer_echo($r["username"]); ?></th>
                </tr>
                <tr>
                    <td>User ID</td>
                    <td><?php safer_echo($r["user_id"]); ?></td>
                </tr>
                <tr>
                    <td>Points Changed</td>
                    <td><?php safer_echo($r["points_change"]); ?></td>
                </tr>
                <tr>
                    <td>Reason</td>
                    <td><?php safer_echo($r["reason"]); ?></td>
                </tr>
                <tr>
                    <td>Change Date</td>
                    <td><?php safer_echo($r["created"]); ?></td>
                </tr>
                </table>

            </body>
        </html>
            
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
