<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM Users WHERE username = :username AND password = :password";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':username', $username);
    oci_bind_by_name($stmt, ':password', $password);
    oci_execute($stmt);

    if ($row = oci_fetch_assoc($stmt)) {
        $_SESSION['username'] = $row['USERNAME'];
        $_SESSION['user_id'] = $row['USER_ID'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }

    oci_free_statement($stmt);
}

oci_close($conn);
include 'header.php';
?>
<h2 class="my-4">Login</h2>
<form method="post" class="needs-validation" novalidate>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="form-group mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
        <div class="invalid-feedback">Please enter your username.</div>
    </div>
    <div class="form-group mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="invalid-feedback">Please enter your password.</div>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
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
