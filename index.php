<?php
include("connection.php");

if (isset($_POST["password"])) {
    try {

        $dni = $_POST["dni"];
        $password = $_POST["password"];
        $sql = "select * from user where dni = ? and password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $dni);
        $stmt->bindParam(2, $password);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $iduser = $result["id"];
            $name = $result["username"];
            session_start();
            $_SESSION["id"] = $iduser;
            $_SESSION["username"] = $name;
            header("Location: employee");
            exit();
        } else {
            $error = "Invalid Credentials";
        }
    } catch (Exception $e) {
        $error = "Error interno" . $e->getMessage();
    }
}

$sql2 = "SELECT u.username, c.entry, c.leaving FROM clock_in as c
inner join user as u 
on c.user_id = u.id limit 10";
$stm = $conn->prepare($sql2);
$stm->execute();
$results = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichajes</title>
</head>

<body>
    <h1>Empresa Gabimania</h1>
    <form method="POST" action="">
        <input type="text" name="dni" id="dni" placeholder="DNI">
        <input type="password" name="password" id="password" placeholder="Password">
        <input type="submit" value="Sing in">
    </form>
    <?php foreach ($results as $r) : ?>
        <h3>Employee: <?php echo $r['username'] ?> </h3>
        <p>Clock in: <?php echo $r['entry'] ?> </p>
        <p>Clock out: <?php echo $r['leaving'] ?> </p>

    <?php endforeach; ?>
    <?php
    if (isset($error)) {
        echo "<p class='error-msg'>" . $error . "</p>";
    }
    ?>
</body>

</html>