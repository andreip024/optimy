<?php
$db_writer_host = getenv("DB_HOST");
$db_reader_host = getenv("DB_READER_HOST");
$db_username = getenv("DB_USER");
$db_password = getenv("DB_PASS");
$db_database = getenv("DB_NAME");

$writer_conn = new mysqli($db_writer_host, $db_username, $db_password, $db_database);
$reader_conn = new mysqli($db_reader_host, $db_username, $db_password, $db_database);

if ($writer_conn->connect_error) {
    die("Connection failed: " . $writer_conn->connect_error);
}

$tableCheckQuery = "SHOW TABLES LIKE 'test'";
$tableCheckResult = $reader_conn->query($tableCheckQuery);

if ($tableCheckResult->num_rows === 0) {

    $createQuery = "
        CREATE TABLE test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50)
        )
    ";

    if ($writer_conn->query($createQuery) === TRUE) {
        echo "Table 'test' created successfully.<br>";
        $writer_conn->query("INSERT INTO test (name) VALUES ('Hello Optimy!')");
    } else {
        echo "Error creating table: " . $writer_conn->error;
    }
} 

$result = $reader_conn->query("SELECT name FROM test");

if ($result && $row = $result->fetch_assoc()) {
    echo $row['name'];
} else {
    echo "No data found.";
}

$writer_conn->close();
$reader_conn->close();

?>
