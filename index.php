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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
<div class="container">
        <h1 class="mt-5 text-center " id="index">Empresa X</h1>
        <form method="POST" action="" class="mt-4 form-container">
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>

        <div class="cards-container">
            <?php foreach ($results as $r) : ?>
                <div class="employee-info mt-4 border p-3">
                    <h3>Employee: <?php echo $r['username'] ?> </h3>
                    <p>Clock in: <?php echo $r['entry'] ?> </p>
                    <p>Clock out: <?php echo $r['leaving'] ?> </p>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
        if (isset($error)) {
            echo "<p class='error-msg mt-4'>" . $error . "</p>";
        }
        ?>
    </div>
</body>

</html>
