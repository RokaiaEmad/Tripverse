<?php
// $link comes from controller
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<title>Invitation Link</title>

<style>

body{
    background:#081028;
    color:white;
    font-family:Arial;
}

.box{
    max-width:700px;
    margin:auto;
    margin-top:120px;
    background:#111c3a;
    padding:40px;
    border-radius:24px;
}

.copy-input{
    background:#0f172a;
    border:none;
    color:white;
}

.copy-input:focus{
    background:#0f172a;
    color:white;
    box-shadow:none;
}

</style>

</head>

<body>

<div class="container">

<div class="box">

<h1 class="mb-4">
Invitation Link Created
</h1>

<p class="text-secondary mb-4">
Copy this link and send it to your friend
to join the trip.
</p>

<div class="input-group mb-4">

<input
type="text"
class="form-control copy-input"
id="inviteLink"
value="<?= $link ?>"
readonly>

<button
class="btn btn-success"
onclick="copyLink()">

Copy

</button>

</div>

<a
href="javascript:history.back()"
class="btn btn-outline-light">

Back

</a>

</div>

</div>

<script>

function copyLink(){

    const input =
        document.getElementById('inviteLink');

    input.select();

    input.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(input.value);

    alert('Invitation link copied!');
}

</script>

</body>

</html>