<?php

$trip_id = $_GET['trip_id'];

session_start();

$error =
    $_SESSION['invite_error'] ?? '';

unset($_SESSION['invite_error']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Invite Member</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{
    background:#081028;
    color:white;
}

.box{
    max-width:600px;
    margin:auto;
    margin-top:100px;
    background:#111c3a;
    padding:40px;
    border-radius:20px;
}

</style>

</head>

<body>

<div class="container">

<div class="box">

<h1 class="mb-4">
Invite Member
</h1>

<?php if($error): ?>

<div class="alert alert-danger">
    <?= $error ?>
</div>

<?php endif; ?>

<form
method="POST"
action="../../controllers/TripMemberController.php?action=invite">

<input
type="hidden"
name="trip_id"
value="<?= $trip_id ?>">

<div class="mb-4">

<label class="form-label">
Member Email
</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<button class="btn btn-success w-100">
Send Invitation
</button>

</form>

</div>

</div>

</body>
</html>