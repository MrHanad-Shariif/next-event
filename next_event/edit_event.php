<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];

        $query = "UPDATE Events SET event_name = :event_name, event_date = TO_DATE(:event_date, 'YYYY-MM-DD') WHERE event_id = :event_id";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':event_name', $event_name);
        oci_bind_by_name($stmt, ':event_date', $event_date);
        oci_bind_by_name($stmt, ':event_id', $event_id);

        if (oci_execute($stmt)) {
            echo "<div class='alert alert-success'>Event updated successfully.</div>";
        } else {
            $e = oci_error($stmt);
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e['message']) . "</div>";
        }
    } else {
        $query = "SELECT * FROM Events WHERE event_id = :event_id";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':event_id', $event_id);
        oci_execute($stmt);
        $event = oci_fetch_assoc($stmt);
    }

    oci_free_statement($stmt);
    oci_close($conn);
} else {
    header("Location: dashboard.php");
    exit();
}

include 'header.php';
?>
<h2 class="my-4">Edit Event</h2>
<form method="post" class="needs-validation" novalidate>
    <div class="form-group mb-3">
        <label for="event_name" class="form-label">Event Name:</label>
        <input type="text" class="form-control" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['EVENT_NAME']); ?>" required>
        <div class="invalid-feedback">Please enter the event name.</div>
    </div>
    <div class="form-group mb-3">
        <label for="event_date" class="form-label">Event Date:</label>
        <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['EVENT_DATE']); ?>" required>
        <div class="invalid-feedback">Please enter the event date.</div>
    </div>
    <button type="submit" class="btn btn-primary">Update Event</button>
</form>
<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
<?php include 'footer.php'; ?>
