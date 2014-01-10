<!DOCTYPE html>
<html  lang="en">
<head>
<title>Asterisk Call Status</title>
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-latest.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

<script> 
var auto_refresh = setInterval(
function()
{
var status = $("#status").val();
$('#status').load('status.php', {status:status});
}, 1000);
</script>

<script>
$(document).ready(function () {
$('#status').load('status.php');
});
</script>

</head>
<body>
<div class="container">
<h2>Active Call List</h2>
<div class="row">
<div class="span3"></div>
<div class="span6" id="status"></div>
<div class="span3"></div>
</div>

</div>

</body>
</html>
