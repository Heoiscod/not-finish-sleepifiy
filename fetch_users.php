<?php
include "db.php";

$sql = "SELECT id, user_id, firstname, lastname, email, username, account_type FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <thead>
              <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Account Type</th>
              </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['user_id']) . "</td>
                <td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['account_type']) . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "No registered users found.";
}

$conn->close();
?>
