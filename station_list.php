<?php
include 'db_connect.php';

$train_no = $_POST['train_no'] ?? '';

if (!$train_no) {
    echo "<p style='text-align:center;'>Please enter a valid train number.</p>";
    exit;
}

// Image map for restaurant icons
$imageMap = [
    'Tandoori Express' => 'images/restaurants/tandoori_express.png',
    'Shree Pure Veg' => 'images/restaurants/shree_pure_veg.png',
    'Royal Kitchen' => 'images/restaurants/royal_kitchen.png',
    'Biryani House' => 'images/restaurants/biryani_house.png',
    'Food Junction' => 'images/restaurants/food_junction.png',
    'Prayagraj Delight' => 'images/restaurants/prayagraj_delight.png'
];

// Fetch station + restaurant data
$stmt = $pdo->prepare("
    SELECT ts.station_code, ts.station_name, r.name AS restaurant_name, r.id AS restaurant_id, t.train_name
    FROM train_stations ts
    LEFT JOIN restaurants r ON ts.station_code = r.station_code
    JOIN trains t ON ts.train_no = t.train_no
    WHERE ts.train_no = :train_no
    ORDER BY ts.stop_order ASC
");
$stmt->execute(['train_no' => $train_no]);
$stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$stations) {
    echo "<p style='text-align:center;'>No stations found for Train Number: " . htmlspecialchars($train_no) . "</p>";
    exit;
}

$train_name = $stations[0]['train_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stations and Restaurants for Train <?php echo htmlspecialchars($train_no); ?></title>
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .flip-card {
            background-color: transparent;
            width: 250px;
            height: 320px;
            perspective: 1000px;
        }
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 1px solid #ccc;
            border-radius: 10px;
            backface-visibility: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 20px;
        }
        .flip-card-front {
            background-color: #f1f1f1;
        }
        .flip-card-back {
            background-color: #ff6f61;
            color: white;
            transform: rotateY(180deg);
        }
        .btn {
            padding: 10px 15px;
            background-color: white;
            color: #ff6f61;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        img.restaurant-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Stations and Restaurants for Train: <?php echo htmlspecialchars($train_name . " (" . $train_no . ")"); ?></h2>

<div class="card-container">
<?php
foreach ($stations as $row) {
    echo "<div class='flip-card'>
            <div class='flip-card-inner'>
                <div class='flip-card-front'>";
    
    if ($row['restaurant_name'] && isset($imageMap[$row['restaurant_name']])) {
        $imgPath = htmlspecialchars($imageMap[$row['restaurant_name']]);
        echo "<img src='$imgPath' alt='Restaurant Icon' class='restaurant-img'>";
    }

    echo "<h3>" . htmlspecialchars($row['station_name']) . "</h3>
          <p>Code: " . htmlspecialchars($row['station_code']) . "</p>";
    
    if ($row['restaurant_name']) {
        echo "<p>Restaurant Available</p>";
    } else {
        echo "<p>No Restaurant</p>";
    }

    echo "</div>
                <div class='flip-card-back'>";

    if ($row['restaurant_name']) {
        echo "<h4>" . htmlspecialchars($row['restaurant_name']) . "</h4>
              <form action='menu.php' method='GET'>
                <input type='hidden' name='rid' value='" . htmlspecialchars($row['restaurant_id']) . "'>
                <button type='submit' class='btn'>View Menu</button>
              </form>";
    } else {
        echo "<p>No restaurant details</p>";
    }

    echo "</div>
            </div>
          </div>";
}
?>
</div>

</body>
</html>
