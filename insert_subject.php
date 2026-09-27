<?php
require '../config.php'; // your PDO connection
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

$branch_id = 1; // Change if inserting for another branch

$subjects = [
    "1" => [
        ["code" => "DCOS 101", "name" => "Communication Skill - I", "type" => "THEORY"],
        ["code" => "DCOM 102", "name" => "Applied Maths-I", "type" => "THEORY"],
        ["code" => "DEE 103", "name" => "Electrical and Electronics Engg.", "type" => "THEORY"],
        ["code" => "DME 104", "name" => "Elements of Mechanical Engg.", "type" => "THEORY"],
        ["code" => "DCO 105", "name" => "Fundamental of Computers", "type" => "THEORY"],
        ["code" => "DEE 113", "name" => "Electrical and Electronics Lab", "type" => "PRACTICAL"],
        ["code" => "DME 116", "name" => "Workshop Practice", "type" => "PRACTICAL"],
        ["code" => "DME 117", "name" => "Engineering Drawing", "type" => "PRACTICAL"],
        ["code" => "DCO 115", "name" => "P.C.Software Lab", "type" => "PRACTICAL"]
    ],
    "2" => [
        ["code" => "DCOM 201", "name" => "Applied Maths-II", "type" => "THEORY"],
        ["code" => "DCOP 202", "name" => "Applied Physics", "type" => "THEORY"],
        ["code" => "DEL 203", "name" => "Electronics Devices", "type" => "THEORY"],
        ["code" => "DCOC 204", "name" => "Engineering Chemistry", "type" => "THEORY"],
        ["code" => "DCO 205", "name" => "Programming in C", "type" => "THEORY"],
        ["code" => "DCOP 212", "name" => "Physics Lab", "type" => "PRACTICAL"],
        ["code" => "DEL 213", "name" => "Electronics Lab", "type" => "PRACTICAL"],
        ["code" => "DCOC 214", "name" => "Chemistry Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 215", "name" => "C Programming Lab", "type" => "PRACTICAL"]
    ],
    "3" => [
        ["code" => "DCO 301", "name" => "Computer Oriented Numerical Methods", "type" => "THEORY"],
        ["code" => "DCO 302", "name" => "Object Oriented Programming", "type" => "THEORY"],
        ["code" => "DEE 303", "name" => "Signals & Systems", "type" => "THEORY"],
        ["code" => "DCO 304", "name" => "Computer Architecture", "type" => "THEORY"],
        ["code" => "DEL 306", "name" => "Digital Electronics", "type" => "THEORY"],
        ["code" => "DCO 312", "name" => "OOP Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 314", "name" => "Computer Workshop", "type" => "PRACTICAL"],
        ["code" => "DCO 315", "name" => "Computer System & Maintenance", "type" => "PRACTICAL"],
        ["code" => "DEL 316", "name" => "Digital Electronics Lab", "type" => "PRACTICAL"]
    ],
    "4" => [
        ["code" => "DCOS 401", "name" => "Communication Skills - II", "type" => "THEORY"],
        ["code" => "DCO 402", "name" => "Database Management System", "type" => "THEORY"],
        ["code" => "DCO 403", "name" => "Operating System", "type" => "THEORY"],
        ["code" => "DCO 404", "name" => "Data Structures", "type" => "THEORY"],
        ["code" => "DEL 405", "name" => "Microprocessor & Microcontroller", "type" => "THEORY"],
        ["code" => "DCO 412", "name" => "DBMS Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 413", "name" => "Operating System Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 414", "name" => "Data Structures Lab", "type" => "PRACTICAL"],
        ["code" => "DEL 415", "name" => "Microprocessor Programming", "type" => "PRACTICAL"]
    ],
    "5" => [
        ["code" => "DCO 501", "name" => "Computer Graphics", "type" => "THEORY"],
        ["code" => "DCO 502", "name" => "Web Technology", "type" => "THEORY"],
        ["code" => "DCO 503", "name" => "Data Communication & Networks", "type" => "THEORY"],
        ["code" => "DCO 504", "name" => "Software Engineering", "type" => "THEORY"],
        ["code" => "DCO 505", "name" => "Java Programming", "type" => "THEORY"],
        ["code" => "DCO 511", "name" => "Graphics & Multimedia Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 512", "name" => "Web Technology Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 513", "name" => "Computer Networks Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 515", "name" => "Java Programming Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 520", "name" => "Minor Project", "type" => "PRACTICAL"]
    ],
    "6" => [
        ["code" => "DCO 601", "name" => "Advanced RDBMS", "type" => "THEORY"],
        ["code" => "DCO 602", "name" => "Visual Programming", "type" => "THEORY"],
        ["code" => "DCO 603", "name" => "Information Security & Cyber Law", "type" => "THEORY"],
        ["code" => "DCO 604", "name" => "Elective I", "type" => "THEORY"],
        ["code" => "DCO 608", "name" => "ICT Management & Entrepreneurship", "type" => "THEORY"],
        ["code" => "DCO 611", "name" => "Advanced RDBMS Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 612", "name" => "Visual Programming Lab", "type" => "PRACTICAL"],
        ["code" => "DCO 620", "name" => "Major Project", "type" => "PRACTICAL"],
        ["code" => "DCO 630", "name" => "Industrial Training & Visits", "type" => "PRACTICAL"]
    ]
];

// Insert into DB
$stmt = $pdo->prepare("INSERT INTO subjects (branch_id, semester, code, name, type) VALUES (?, ?, ?, ?, ?)");
$count = 0;

foreach ($subjects as $sem => $subjectList) {
    foreach ($subjectList as $subj) {
        $stmt->execute([$branch_id, $sem, $subj['code'], $subj['name'], $subj['type']]);
        $count++;
    }
}

echo "<h3 style='color: green;'>$count subjects inserted successfully for branch_id = $branch_id</h3>";
