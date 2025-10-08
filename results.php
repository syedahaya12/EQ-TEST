<?php
session_start();
require_once 'db.php';

$scores = ['self_awareness' => 0, 'empathy' => 0, 'emotional_regulation' => 0];
$total_score = 0;

try {
    if (isset($_SESSION['responses']) && !empty($_SESSION['responses'])) {
        foreach ($_SESSION['responses'] as $question_id => $answer) {
            $answer = strtolower(trim($answer));
            if (!in_array($answer, ['a', 'b', 'c', 'd'])) {
                continue;
            }
            $score_column = "score_$answer";
            $stmt = $pdo->prepare("SELECT $score_column AS score, category FROM questions WHERE id = ?");
            $stmt->execute([$question_id]);
            $result = $stmt->fetch();
            if ($result) {
                $scores[$result['category']] += $result['score'];
                $total_score += $result['score'];
            }
        }

        $session_id = session_id();
        $stmt = $pdo->prepare("INSERT INTO user_results (session_id, total_score, self_awareness_score, empathy_score, emotional_regulation_score) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$session_id, $total_score, $scores['self_awareness'], $scores['empathy'], $scores['emotional_regulation']]);
    } else {
        $error = "No responses found. Please take the test again.";
    }
} catch (PDOException $e) {
    $error = "Error processing results: " . $e->getMessage();
}

function getFeedback($score, $category) {
    $max_score = 16; // 4 questions per category, max 4 points each
    if ($score >= $max_score * 0.8) {
        return "Excellent $category skills! You demonstrate strong abilities in this area.";
    } elseif ($score >= $max_score * 0.5) {
        return "Good $category skills. Consider practicing more to enhance your proficiency.";
    } else {
        return "Your $category skills could use improvement. Try focusing on this area.";
    }
}

session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Results</title>
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
        .results-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 90%;
            text-align: center;
        }
        h2 {
            font-size: 2.2em;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .score {
            font-size: 3em;
            color: #3498db;
            margin: 20px 0;
        }
        .feedback {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .error {
            color: #e74c3c;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .btn {
            background: #3498db;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            margin: 10px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        @media (max-width: 600px) {
            .results-container {
                padding: 20px;
            }
            h2 {
                font-size: 1.8em;
            }
            .score {
                font-size: 2.5em;
            }
            .feedback {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="results-container">
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <button class="btn" onclick="window.location.href='index.php'">Retake Test</button>
        <?php else: ?>
            <h2>Your Emotional Intelligence Results</h2>
            <div class="score"><?php echo $total_score; ?>/48</div>
            <div class="feedback">
                <p><strong>Self-Awareness:</strong> <?php echo getFeedback($scores['self_awareness'], 'self-awareness'); ?></p>
                <p><strong>Empathy:</strong> <?php echo getFeedback($scores['empathy'], 'empathy'); ?></p>
                <p><strong>Emotional Regulation:</strong> <?php echo getFeedback($scores['emotional_regulation'], 'emotional regulation'); ?></p>
            </div>
            <button class="btn" onclick="window.location.href='index.php'">Retake Test</button>
            <button class="btn" onclick="alert('Share your results: EQ Score <?php echo $total_score; ?>/48')">Share Results</button>
        <?php endif; ?>
    </div>
</body>
</html>
