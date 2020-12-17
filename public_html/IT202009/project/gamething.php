<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<html>
<head>
<style>

#center{
width: 100%;
margin-left: auto;
margin-right: auto;
align-content:center;
text-align:center;
background-color: rgb(85, 16, 196);

height:100%;
padding:0px;
}
canvas, body{
padding: 0px;
margin:0px;
}
</style>
</head>
<body>
<div id="center">
<canvas id="canvas" width="400px" height="900px"></canvas>
</div>
<span id="endGame"></span>

<script>
// Arcade Shooter game

// Get a reference to the canvas DOM element
var canvas = document.getElementById('canvas');
// Get the canvas drawing context
var context = canvas.getContext('2d');

// Create an object representing a square on the canvas
function makeSquare(x, y, length, speed) {
  return {
    x: x,
    y: y,
    l: length,
    s: speed,
    draw: function() {
      context.fillRect(this.x, this.y, this.l, this.l);
    }
  };
}

// The ship the user controls
var ship = makeSquare(canvas.width / 2 - 25, canvas.height - 75, 50, 10);

// Flags to tracked which keys are pressed
var left = false;
var right = false;
var space = false;

// Is a bullet already on the canvas?
var shooting = false;
// The bulled shot from the ship
var bullet = makeSquare(0, 0, 10, 10);

// An array for quadros (in case there are more than one)
var quadros = [];

// Add an quadro object to the array
var enemyBaseSpeed = 2;
function makequadro() {
  var quadroSize = Math.round((Math.random() * 15)) + 15;
  var quadroX = Math.round(Math.random() * (canvas.width - quadroSize * 2)) + quadroSize;
  var quadroY = 0+ (quadroSize/2);
  var quadroSpeed = enemyBaseSpeed / 4;
  quadros.push(makeSquare(quadroX, quadroY, quadroSize, quadroSpeed));
}

// Check if number a is in the range b to c (exclusive)
function isWithin(a, b, c) {
  return (a > b && a < c);
}

// Return true if two squares a and b are colliding, false otherwise
function isColliding(a, b) {
  var result = false;
  if (isWithin(a.x, b.x, b.x + b.l) || isWithin(a.x + a.l, b.x, b.x + b.l)) {
    if (isWithin(a.y, b.y, b.y + b.l) || isWithin(a.y + a.l, b.y, b.y + b.l)) {
      result = true;
    }
  }
  return result;
}

// Track the user's score
var score = 0;
// The delay between quadros (in milliseconds)
var timeBetweenEnemies = 1000;
// ID to track the spawn timeout
var timeoutIdQuad = null;

// Show the game menu and instructions
function menu() {
  erase();
  context.fillStyle = '#000000';
  context.font = '36px Arial';
  context.textAlign = 'center';
  context.fillText('Shoot \'Em!', canvas.width / 2, canvas.height / 4);
  context.font = '24px Arial';
  context.fillText('Click to Start', canvas.width / 2, canvas.height / 2);
  context.font = '18px Arial';
  context.fillText('Left/Right to move, Space to shoot.', canvas.width / 2, (canvas.height / 4) * 3);
  // Start the game on a click
  canvas.addEventListener('click', startGame);
}

// Start the game
function startGame() {
	// Kick off the quadro spawn interval
  timeoutIdQuad = setInterval(makequadro, timeBetweenEnemies);
  // Make the first quadro
  setTimeout(makequadro, 1000);
  // Kick off the draw loop
  draw();
  // Stop listening for click events
  canvas.removeEventListener('click', startGame);
}

// Show the end game screen
function endGame() {
	// Stop the spawn interval
  clearInterval(timeoutIdQuad);
  // Show the final score
  erase();
  context.fillStyle = '#000000';
  context.font = '24px Arial';
  context.textAlign = 'center';
  context.fillText('Game Over. Final Score: ' + score, canvas.width / 2, canvas.height / 2);
  $score = score;
}

// Listen for keydown events
window.addEventListener('keydown', function(event) {
  event.preventDefault();
  if (event.keyCode === 37) { // LEFT
    left = true;
  }
  if (event.keyCode === 39) { // RIGHT
    right = true;
  }
  if (event.keyCode === 32) { // SPACE
    shoot();
  }
});

// Listen for keyup events
window.addEventListener('keyup', function(event) {
  event.preventDefault();
  if (event.keyCode === 37) { // LEFT
    left = false;
  }
  if (event.keyCode === 39) { // RIGHT
    right = false;
  }
});

// Clear the canvas
function erase() {
  context.fillStyle = '#FFFFFF';
  context.fillRect(0, 0, 400, 900);
}

// Shoot the bullet (if not already on screen)
function shoot() {
  if (!shooting) {
    shooting = true;
    bullet.x = ship.x + ship.l / 2;
    bullet.y = ship.y;
  }
}

// The main draw loop
function draw() {
  erase();
  var gameOver = false;
  // Move and draw the quadros
  quadros.forEach(function(quadro) {
    quadro.y += quadro.s;
    if (quadro.x > canvas.height) {
      gameOver = true;
    }
    context.fillStyle = '#00FF00';
    quadro.draw();
  });
  // Collide the ship with quadros
  quadros.forEach(function(quadro, i) {
    if (isColliding(quadro, ship)) {
      gameOver = true;
    }
  });
  // Move the ship
  if (right) {
    ship.x += ship.s;
  }
  if (left) {
    ship.x -= ship.s;
  }
  // Don't go out of bounds
  if (ship.x < 0) {
    ship.x = 0;
  }
  if (ship.x > canvas.width - ship.l) {
    ship.x = canvas.width - ship.l;
  }
  // Draw the ship
  context.fillStyle = '#FF0000';
  ship.draw();
  // Move and draw the bullet
  if (shooting) {
    // Move the bullet
    bullet.y -= bullet.s;
    // Collide the bullet with quadros
    quadros.forEach(function(quadro, i) {
      if (isColliding(bullet, quadro)) {
        quadros.splice(i, 1);
        score++;
        shooting = false;        
      }
    });
    // Collide with the wall
    if (bullet.y < 0) {
      shooting = false;
    }
    // Draw the bullet
    context.fillStyle = '#0000FF';
    bullet.draw();
  }
  // Draw the score
  context.fillStyle = '#000000';
  context.font = '24px Arial';
  context.textAlign = 'left';
  context.fillText('Score: ' + score, 1, 25)
  // End or continue the game

  
  if (gameOver) {
    endGame();
  } else {
    window.requestAnimationFrame(draw);
  }

  window.requestAnimationFrame(draw);
}

// Start the game
menu();
canvas.focus();
</script>
</body>
</html>

<?php
$db = getDB();

$query = "INSERT INTO Scores (user_id, score) VALUES(:uid, :score)";
$stmt = $db->prepare($query);
$params = [
    ":uid" => get_user_id(),
    ":score" => $score;
    ];
$r = $stmt->execute($params);

$stmt = $db->prepare("UPDATE Users set points = :points where id = :id");
$s = $stmt->execute([":points" => $points, ":id" => get_user_id()]);

$quory = "INSERT INTO PointsHistory (user_id, points_change, reason) VALUES(:uid, :points_change, :reason)";
$stmt = $db->prepare($quory);
$paroms = [
    ":uid" => get_user_id(),
    ":points_chane" => $score;
    ":reason" => "From game";
    ];
$p = $stmt->execute($paroms);
?>