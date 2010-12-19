<html>
<head>
    <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
    <link href="/css/form-styles.css" rel="stylesheet">
</head>
<body>
    <h1>Report Quiz</h1>

    <?php if (isset($_GET['id'])):?>
    <p>Quiz ID: <?php echo $_GET['id'];?></p>
    <form action="report-quiz.php" method="post">
        <label><p>Reason for Reporting Quiz</p></label>
        <select name="reason">
            <option value="none">-- Please Select --</option>
            <option value="inappropriate-content">Inappropriate Content</option>
            <option value="incorrect-content">Incorrect Content</option>
            <option value="broken-quiz">Broken Quiz</option>
            <option value="spam">Spam</option>
            <option value="other">Other - please specify</option>
        </select><br />
        <label><p>Additional Details</p></label><br/>
        <textarea name="details" cols="40" rows="6" style="margin-left: 200px;"
                  placeholder="Please outline any further details you wish to let us know about this case"></textarea>
        <br />
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
        <button type="submit">Submit Case</button>
    </form>

    <?php elseif (isset($_POST['id'])):?>
        <?php
            $quizId  = $_POST['id'];
            $reason  = $_POST['reason'];
            $details = $_POST['details'];
        ?>

        <h2>Thank you</h2>
        <p>Your report has been submitted, and an admin will check this quiz as soon as possible and take appropriate action</p>
        <br />
        <code>
            -------------------------------------
            <br />
            QUIZ ID: <?php echo $quizId; ?><br />
            REASON : <?php echo $reason; ?><br />
            DETAILS: <?php echo $details;?><br />
            --------------------------------------
        </code>
    <?php endif; ?>

</body>
</html>