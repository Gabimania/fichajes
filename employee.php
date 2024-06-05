<?php
session_start();
$iduser = $_SESSION["id"];
$name = $_SESSION["username"];
include("connection.php");
if (isset($_POST["clock"])) {
    $query = "SELECT * FROM clock_in where entry is not null and leaving is null and user_id= ? ";
    $sm = $conn->prepare($query);
    $sm->bindParam(1, $iduser);
    $sm->execute();
    $results = $sm->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) > 0) {
        echo "You have done the clock in before";
    } else {
        $sql = "INSERT INTO clock_in (entry, user_id) VALUES (NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $iduser);
        $stmt->execute();
        header("Location: index");
    }
}

if (isset($_POST["out"])) {
    try {
        $sqll = "SELECT * FROM fichajes.clock_in where entry is null and leaving is null and user_id = ?";
        $s = $conn->prepare($sqll);
        $s->bindParam(1, $iduser);
        $s->execute();
        $res = $s->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) > 0) {
            echo "Is is not allowed to sing in the clock out if there is not registered the clock in. You should speack with Human Resources Departement for trying to insert your clock in manually";
        } else {
            $sql1 = "SELECT * FROM fichajes.clock_in where leaving is null and user_id = ?";
            $st = $conn->prepare($sql1);
            $st->bindParam(1, $iduser);
            $st->execute();
            $re = $st->fetchAll(PDO::FETCH_ASSOC);
            if (count($re) > 0) {
                $sql2 = "UPDATE clock_in SET leaving = NOW() WHERE user_id = ?";
                $stm = $conn->prepare($sql2);
                $stm->bindParam(1, $iduser);
                $stm->execute();
                header("Location: index");
            } else {
                $error = "You have already checked out before";
            }
        }
    } catch (Exception $e) {
        $error = "Error interno" . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Welcome to X, <?php echo "<span>$name</span>" ?></h1>

        <div class="inline-forms-container mt-4">
            <h2>It's time to clock in?</h2>
            <form method="post" action="" class="inline-form">
                <button type="submit" class="btn btn-primary" name="clock">Clock in</button>
            </form>
            <h2>It's time to clock out?</h2>
            <form method="post" action="" class="inline-form">
                <button type="submit" class="btn btn-danger" name="out">Clock out</button>
            </form>
        </div>

        <a href="index" class="mt-4 btn btn-secondary">Volver</a>

        <?php
        if (isset($error)) {
            echo "<p class='error-msg mt-4'>" . $error . "</p>";
        }
        ?>
    </div>
</body>

</html>