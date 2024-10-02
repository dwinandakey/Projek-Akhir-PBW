<?php
session_start();

// Include the database configuration file
include './config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: my-account.php');
    exit;
}

// Get the user's information from the database
$stmt = $conn->prepare("SELECT nama, email, username, alamat, telp FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Display the user's profile information
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - My Profile</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="./styles/user-profile.css">
</head>

<body>
    <?php include 'assets/navbar.php'; ?>
    <main>
        <div class="container">
            <h1>My Profile</h1>
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="./images/logo.png" alt="Profile Picture">
                </div>
                <div class="profile-info">
                    <label for="nama">Name:</label>
                    <p><?php echo $user_data['nama']; ?></p>

                    <label for="email">Email:</label>
                    <p><?php echo $user_data['email']; ?></p>

                    <label for="username">Username:</label>
                    <p><?php echo $user_data['username']; ?></p>

                    <label for="alamat">Address:</label>
                    <p><?php echo $user_data['alamat']; ?></p>

                    <label for="telp">Phone:</label>
                    <p><?php echo $user_data['telp']; ?></p>
                </div>
            </div>
        </div>
    </main>
    <?php include 'assets/footer.php'; ?>
</body>

</html>