<?php

//Declare variables for name, age, course, and hobbies.
$name = "Russel Benedict F. Alforque";
$age = 20;
$course = "Bachelor of Science in Information Systems";
$hobbies = ["Playing Games", "Watching Movies", "Listening to Music"];
$message;
//Uses control structures (if-else) to display a message based on the age group:
if ($age < 13)
    $message = "You are a child. Enjoy learning and playing.";
//Below 13: "You are a child. Enjoy learning and playing."
else if ($age <= 19)
    $message = "You are a teenager. Explore your interests and build your skills!";
//13-19: "You are a teenager. Explore your interests and build your skills!"
else if ($age <= 25)
    $message = "You are a young adult. Prepare for your career and future.";
//20-25: "You are a young adult. Prepare for your career and future."
else if ($age > 25)
    $message = "You are an adult. Keep growing and contributing!";
//Above 25: "You are an adult. Keep growing and contributing!"
?>

<!-- Output a styled HTML page that -->
<!-- Displays the profile information using embedded PHP -->
<!-- Lists hobbies using a loop (foreach) -->
<!-- Applies basic CSS styling (inline or internal) -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($name); ?>! :D</h1>
        <p>Age: <?php echo htmlspecialchars($age); ?></p>
        <p>Course: <?php echo htmlspecialchars($course); ?></p>
        <p>Message: <?php echo htmlspecialchars($message); ?></p>
        <p>Hobbies: </p>
        <ul>
            <?php foreach ($hobbies as $hobby)
                echo '<li>' . htmlspecialchars($hobby) . '</li>'; ?>
        </ul>
    </div>
</body>

</html>