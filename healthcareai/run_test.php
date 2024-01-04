<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exec('python3 test.py', $output, $returnCode);

    if ($returnCode === 0) {
        echo "Test.py executed successfully";
    } else {
        echo "Failed to execute test.py";
    }
} else {
    echo "Invalid request method.";
}
?>
