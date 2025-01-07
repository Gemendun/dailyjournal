<?php
// Memulai session
session_start();

// Menyertakan file koneksi
include "koneksi.php";

//check jika sudah ada user yang login arahkan ke halaman admin
if (isset($_SESSION['username'])) { 
	header("location:admin.php"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['users']; // Pastikan nama input sesuai dengan ini
    $password = md5($_POST['password']); // Enkripsi password dengan md5

    // Prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT username FROM user WHERE username=? AND password=?");

    // Parameter binding
    $stmt->bind_param("ss", $username, $password);

    // Eksekusi query
    $stmt->execute();
    
    // Menampung hasil query
    $hasil = $stmt->get_result();

    // Ambil baris hasil
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    // Jika ada hasil cocok
    if (!empty($row)) {
        $_SESSION['username'] = $row['username']; // Set session
        header("location:admin.php"); // Redirect ke halaman admin
    } else {
        header("location:login.php"); // Redirect ke login jika gagal
    }

    // Menutup koneksi
    $stmt->close();
    $conn->close();
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <label for="user">Username:</label>
        <input type="text" id="username" name="users" required><br><br>
        <label for="pass">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

<?php
}
?>
