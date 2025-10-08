<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }
        h1 {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .start-btn {
            background: #3498db;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        .start-btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 2em;
            }
            p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Emotional Intelligence (EQ) Test</h1>
        <p>Discover your emotional intelligence by taking our comprehensive EQ test. Understand your self-awareness, empathy, and emotional regulation skills to enhance your personal and professional relationships.</p>
        <button class="start-btn" onclick="window.location.href='quiz.php'">Start Test</button>
    </div>
</body>
</html>
