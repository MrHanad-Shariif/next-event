<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $sql = "BEGIN AddUser(:username, :password, :email); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':username', $username);
    oci_bind_by_name($stmt, ':password', $password);
    oci_bind_by_name($stmt, ':email', $email);

    if (oci_execute($stmt)) {
        echo "<div class='alert alert-success'>Registration successful. <a href='login.php'>Click here to login</a></div>";
    } else {
        $e = oci_error($stmt);
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e['message']) . "</div>";
    }

    oci_free_statement($stmt);
}

oci_close($conn);
?>
<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="my-4 text-center">Register</h2>
        <form method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div class="invalid-feedback">Please enter your username.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="invalid-feedback">Please enter your password.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</div>
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
