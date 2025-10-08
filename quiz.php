<?php
session_start();
require_once 'db.php';

$_SESSION['responses'] = $_SESSION['responses'] ?? [];
$_SESSION['current_question'] = $_SESSION['current_question'] ?? 0;

try {
    $stmt = $pdo->query("SELECT * FROM questions");
    $questions = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error loading questions: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = $_POST['answer'] ?? '';
    $question_id = $_POST['question_id'] ?? 0;
    
    if ($answer && $question_id) {
        $_SESSION['responses'][$question_id] = $answer;
        $_SESSION['current_question']++;
        
        if ($_SESSION['current_question'] >= count($questions)) {
            echo '<script>window.location.href="results.php";</script>';
            exit;
        }
    }
}

$current_question = $_SESSION['current_question'];
$question = $questions[$current_question] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Quiz</title>
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
            min-height: 100vh;
        }
        .quiz-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 90%;
        }
        h2 {
            font-size: 1.8em;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .option {
            background: #f1f2f6;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .option:hover {
            background: #dfe4ea;
        }
        .option input {
            display: none;
        }
        .option label {
            cursor: pointer;
            font-size: 1.1em;
            display: block;
            width: 100%;
        }
        .option input:checked + label {
            background: #3498db;
            color: #fff;
            border-radius: 10px;
            padding: 15px;
        }
        .submit-btn {
            background: #3498db;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .submit-btn:hover {
            background: #2980b9;
        }
        @media (max-width: 600px) {
            .quiz-container {
                padding: 20px;
            }
            h2 {
                font-size: 1.5em;
            }
            .option label {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <?php if ($question): ?>
            <h2>Question <?php echo $current_question + 1; ?> of <?php echo count($questions); ?></h2>
            <p><?php echo htmlspecialchars($question['question_text']); ?></p>
            <form method="POST" id="quizForm">
                <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                <div class="options">
                    <div class="option">
                        <input type="radio" name="answer" id="option_a" value="a" required>
                        <label for="option_a"><?php echo htmlspecialchars($question['option_a']); ?></label>
                    </div>
                    <div class="option">
                        <input type="radio" name="answer" id="option_b" value="b" required>
                        <label for="option_b"><?php echo htmlspecialchars($question['option_b']); ?></label>
                    </div>
                    <div class="option">
                        <input type="radio" name="answer" id="option_c" value="c" required>
                        <label for="option_c"><?php echo htmlspecialchars($question['option_c']); ?></label>
                    </div>
                    <div class="option">
                        <input type="radio" name="answer" id="option_d" value="d" required>
                        <label for="option_d"><?php echo htmlspecialchars($question['option_d']); ?></label>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Next</button>
            </form>
        <?php else: ?>
            <p>No more questions available.</p>
            <button class="submit-btn" onclick="window.location.href='results.php'">View Results</button>
        <?php endif; ?>
    </div>
    <script>
        document.querySelectorAll('.option').forEach(option => {
            option.addEventListener('click', (e) => {
                const input = option.querySelector('input');
                input.checked = true;
                document.querySelectorAll('.option').forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
            });
        });
    </script>
</body>
</html>
