<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];

    $query = "BEGIN AddEvent(:event_name, TO_DATE(:event_date, 'YYYY-MM-DD'), :organizer_id); END;";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':event_name', $event_name);
    oci_bind_by_name($stmt, ':event_date', $event_date);
    oci_bind_by_name($stmt, ':organizer_id', $_SESSION['user_id']);

    if (oci_execute($stmt)) {
        echo "<div class='alert alert-success'>Event created successfully.</div>";
    } else {
        $e = oci_error($stmt);
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e['message']) . "</div>";
    }

    oci_free_statement($stmt);
}

oci_close($conn);
include 'header.php';
?>
<h2 class="my-4">Create Event</h2>
<form method="post" class="needs-validation" novalidate>
    <div class="form-group mb-3">
        <label for="event_name" class="form-label">Event Name:</label>
        <input type="text" class="form-control" id="event_name" name="event_name" required>
        <div class="invalid-feedback">Please enter the event name.</div>
    </div>
    <div class="form-group mb-3">
        <label for="event_date" class="form-label">Event Date:</label>
        <input type="date" class="form-control" id="event_date" name="event_date" required>
        <div class="invalid-feedback">Please enter the event date.</div>
    </div>
    <button type="submit" class="btn btn-primary">Create Event</button>
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
