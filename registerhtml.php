<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Business Signup Page</title>
  <link rel="stylesheet" href="business.css">
</head>
<body>
  <div class="signup-container">
    <h1>Signup as Business</h1>
    <form action="register.php" method="post">
      <div class="form-group">
        <label for="restaurant-name">Restaurant Name:</label>
        <input type="text" id="restaurant-name" name="restaurant-name" required>
      </div>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
      </div>
      <div class="form-group">
        <label for="Type">Type:</label>
        <select id="type" name="type">
          <option value="Select">Select</option>
          <option value="Veg">Veg</option>
          <option value="Non_Veg">Non_Veg</option>
          <option value="Both">Both</option>
        </select>
      </div>
      <div class="form-group">
        <label for="Location">Location</label>
        <input type="location" id="location" name="location" required>
      </div>
      <button type="submit">Signup</button>
    </form>
  </div>
  <footer>
    <p>&copy; 2023 BookMyPlate. All rights reserved.</p>
  </footer>
</body>
</html>
