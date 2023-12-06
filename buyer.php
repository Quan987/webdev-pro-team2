<?php
    session_start();
	if(empty($_SESSION['user_auth'])) {
        header('Location: ./session_destroy_buyer.php');
        exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <div class="welcome-note" id="welcomeNote"></div>
		
        <!-- <input type="text" id="searchInput" class="search-bar" placeholder="Search...">
        <button onclick="search()">Search</button> -->
		<form action="buyer.php" method="post">
			<input type="text" id="search" name="search" class="search-bar" placeholder="Search...">
			<br>
			<label for="minBed">Minimum number of bedrooms</label> 
			<select name="minBed" id="minBed">
				<option value=0 selected>0</option>
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
			</select>
			<br>
			<label for="minBath">Minimum number of bathrooms</label> 
			<select name="minBath" id="minBath">
				<option value=0 selected>0</option>
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
			</select>
			<br>
			<label for="minPrice">Minimum price:</label> 
			<select name="minPrice" id="minPrice">
				<option value=0 selected>0</option>
				<option value=100000>$100,000</option>
				<option value=200000>$200,000</option>
				<option value=300000>$300,000</option>
				<option value=400000>$400,000</option>
				<option value=500000>$500,000</option>
				<option value=600000>$600,000</option>
				<option value=700000>$700,000</option>
				<option value=800000>$800,000</option>
				<option value=900000>$900,000</option>
				<option value=1000000>$1,000,000</option>
			</select>
			<label for="maxPrice">Maximum price:</label>
			<select name="maxPrice" id="maxPrice">
				<option value=0>0</option>
				<option value=100000>$100,000</option>
				<option value=200000>$200,000</option>
				<option value=300000>$300,000</option>
				<option value=400000>$400,000</option>
				<option value=500000>$500,000</option>
				<option value=600000>$600,000</option>
				<option value=700000>$700,000</option>
				<option value=800000>$800,000</option>
				<option value=900000>$900,000</option>
				<option value=1000000>$1,000,000</option>
				<option value=2000000>$2,000,000</option>
				<option value=3000000>$3,000,000</option>
				<option value=4000000>$4,000,000</option>
				<option value=5000000>$5,000,000</option>
				<option value=6000000>$6,000,000</option>
				<option value=7000000>$7,000,000</option>
				<option value=8000000>$8,000,000</option>
				<option value=9000000>$9,000,000</option>
				<option value=10000000>$10,000,000</option>
				<option value=1000000000000 selected>No limit</option>
			</select>
			<br>
			<label for="wishFilter">Wishlisted only:</label>
			<input type="checkbox" value="yes" name="wishFilter"></input>
			<br><br>
			<input type="submit" value = "Search">
		</form>
        <div class="search-results" id="searchResults">
			<?php
				$minBed = (int)$_POST["minBed"];
				$minBath = (float)$_POST["minBath"];
				$minPrice =(float) $_POST["minPrice"];
				$maxPrice = (float) $_POST["maxPrice"];
				$searchBar = $_POST["search"];
				$db = getDB();
				if(isset($_POST["search"]) and !isset($_POST["wishFilter"]))
				{
					$sql="SELECT * FROM Card WHERE addr LIKE '%?%' price > ? and price < ? and beds > ? and baths > ? ";
					$statement = $db->prepare($sql);
    				$statement->bind_param("siiii", $searchBar, $minPrice, $maxPrice, $minBed, $minBath);
    				$statement->execute();
					$intermediate = $statement->get_result();
				}
				else if(isset($_POST["search"]) and isset($_POST["wishFilter"]))
				{
					$sql="SELECT * FROM Card WHERE addr LIKE '%?%' price > ? and price < ? and beds > ? and baths > ?";
					//Adding implementation to include wishlisted value later.
					$statement = $db->prepare($sql);
    				$statement->bind_param("siiii", $searchBar, $minPrice, $maxPrice, $minBed, $minBath);
    				$statement->execute();
					$intermediate = $statement->get_result();
				}
				else if(isset($_POST["wishFilter"]))
				{
					$sql="SELECT * FROM Card WHERE price > ? and price < ? and beds > ? and baths > ?";
					//Adding implementation to include wishlisted value later using EXIST in the select statement to check the 2nd table
					$statement = $db->prepare($sql);
    				$statement->bind_param("iiii", $minPrice, $maxPrice, $minBed, $minBath);
    				$statement->execute();
					$intermediate = $statement->get_result();
				}
				else
				{
					$sql="SELECT * FROM Card WHERE price > ? and price < ? and beds > ? and baths > ?";
					$statement = $db->prepare($sql);
    				$statement->bind_param("iiii", $minPrice, $maxPrice, $minBed, $minBath);
    				$statement->execute();
					$intermediate = $statement->get_result();
				}
				while($result -> fetch_assoc($intermediate))
				{
					$seller = $result["seller"];
					$addr = $result["addr"];
					$age = $result["age"];
					$price = $result["price"];
					$img = $result["img"];
					$beds = $result["bed"];
					$baths = $result["baths"];
					$garage = $result["garage"];
					$area = $result["areaL"] * $result["areaW"];
					?>
					<div class="property-card">
						<img src="<?=$img?>" alt="Property Image" style="width:100%;">
						<h3>Sold by <?=$seller?></h3>
						<p>Location: <?=$addr?></p>
						<p>Price: <?=$price?></p>
						<!-- The stuff in here should be saved for when the user clicks on the card
						<p>//$beds  bedrooms, //$baths bathrooms, =//$garage garage</p>
						<p>Area: //$area square feet</p>
						Implement wishlist later -->
					</div>
				<?php}
				$db->close();
			?>
        </div>
		<!--  Just commenting this out for now, I keep it here so we can keep the uh template for addToWishList and stuff
		<div class="property-card" onclick="viewPropertyDetails(1)">
		<img src="property-image.jpg" alt="Property Image" style="width: 100%;">
		<h3>Property Name</h3>
		<p>Location: Atlanta, GA</p>
		<p>Price: $1,000,000</p>
		<button onclick="addToWishlist(1)">Add to Wishlist</button>
        </div>
		
		<div class="property-card" onclick="viewPropertyDetails(2)">
		<img src="property-image2.jpg" alt="Property Image" style="width: 100%;">
		<h3>Property Name</h3>
		<p>Location: Buckhead, GA</p>
		<p>Price: $800,000</p>
		<button onclick="addToWishlist(2)">Add to Wishlist</button>
        </div>
		
		<div class="property-card" onclick="viewPropertyDetails(3)">
		<img src="property-image3.jpg" alt="Property Image" style="width: 100%;">
		<h3>Property Name</h3>
		<p>Location: Macon, GA</p>
		<p>Price: $1,500,000</p>
		<button onclick="addToWishlist(3)">Add to Wishlist</button>
        </div>
		
		<div class="property-card" onclick="viewPropertyDetails(4)">
		<img src="property-image4.jpg" alt="Property Image" style="width: 100%;">
		<h3>Property Name</h3>
		<p>Location: Alpharetta, GA</p>
		<p>Price: $2,000,000</p>
		<button onclick="addToWishlist(4)">Add to Wishlist</button>
        </div> -->
    </div>


	
	 <!-- <script src="myscripts.js"></script> -->
</body>
</html>
