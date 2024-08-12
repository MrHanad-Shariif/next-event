<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM Events WHERE organizer_id = :user_id";
$stmt = oci_parse($conn, $query);
oci_bind_by_name($stmt, ':user_id', $user_id);
oci_execute($stmt);

include 'header.php';
?>
<h2 class="my-4">Dashboard</h2>
<p class="welcome-message">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
<h3 class="my-4">Upcoming Events</h3>
<div class="row">
    <?php while ($row = oci_fetch_assoc($stmt)): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['EVENT_NAME']); ?></h5>
                    <p class="card-text">Date: <?php echo htmlspecialchars($row['EVENT_DATE']); ?></p>
                    <a href="edit_event.php?event_id=<?php echo $row['EVENT_ID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_event.php?event_id=<?php echo $row['EVENT_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php include 'footer.php'; ?>
