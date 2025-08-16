<?php 
include 'auth_check.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Check Stations</title>
  <link rel="stylesheet" href="pnr.css" />
</head>
<body>

  <form action="station_list.php" method="POST">
    <label for="check_by">Check by:</label>
    <select id="check_by" name="check_by" required onchange="toggleInput()">
      <option value="pnr">PNR Number</option>
      <option value="train_no">Train Number</option>
    </select>

    <div id="pnr_input">
      <label for="pnr">Enter PNR Number:</label>
      <input type="text" id="pnr" name="pnr" pattern="[0-9]{10}" placeholder="10-digit PNR Number" />
    </div>

    <div id="train_no_input" style="display:none;">
      <label for="train_no">Enter Train Number:</label>
      <input type="text" id="train_no" name="train_no" pattern="[0-9]{5}" placeholder="5-digit Train Number" />
    </div>

    <button type="submit">Check Stations</button>
  </form>

<script>
function toggleInput() {
  const checkBy = document.getElementById('check_by').value;
  document.getElementById('pnr_input').style.display = checkBy === 'pnr' ? 'block' : 'none';
  document.getElementById('train_no_input').style.display = checkBy === 'train_no' ? 'block' : 'none';

  // Also set required attribute accordingly
  document.getElementById('pnr').required = (checkBy === 'pnr');
  document.getElementById('train_no').required = (checkBy === 'train_no');
}
</script>

</body>
</html>
