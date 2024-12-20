<?php
session_start(); // Start the session to manage user sessions

// Include database connection
include 'header.php';
// Login with Google
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $url = "https://oauth2.googleapis.com/token"; // URL to exchange code for token

    // Prepare data for token exchange
    $data = [
        'code' => $code,
        'client_id' => '656915444750-jencm5pb6chr7gri8547p9qrmp6iilqq.apps.googleusercontent.com', // Your Client ID
        'client_secret' => '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', // Your Client Secret
        'grant_type' => 'authorization_code',
        'redirect_uri' => ($_SERVER['HTTP_HOST'] == 'localhost') ? 
            'http://localhost/google_login.php' : 
            'https://farahkordmahalehho-ayg0crenf5cag7dv.westeurope-01.azurewebsites.net/google_login.php'
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context); // Use @ to suppress errors

    // Check for errors in the response
    if ($result === FALSE) {
        error_log("Token exchange error: " . print_r($http_response_header, true));
        die('Error occurred during token exchange.'); // Log error in production
    }

    $response = json_decode($result);

    // Check if access token was returned
    if (isset($response->access_token)) {
        $access_token = $response->access_token;

        // Fetch user information
        $url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $access_token;
        $result = @file_get_contents($url);

        if ($result === FALSE) {
            die('Error occurred while fetching user info.'); // Log error in production
        }

        $user_info = json_decode($result);

        // Validate user info response
        if (isset($user_info->email, $user_info->given_name, $user_info->id, $user_info->family_name)) {
            $email = $user_info->email;
            $firstname = $user_info->given_name;
            $lastname = $user_info->family_name;
            $google_id = $user_info->id;

            // Check if user already exists in the database using prepared statements
            $stmt = db_connect()->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email); // Bind the parameter
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // User exists, set session variables
                $_SESSION['user'] = $user;
                $_SESSION['success'] = "success";
                $_SESSION['message'] = "Welcome back " . htmlspecialchars($user['firstname']); // Sanitize output
                $_SESSION["loggedIn"] = $user['role'];
                $_SESSION["user_id"] = $user['id'];
            } else {
                // New user, insert into database using prepared statements
                $is_active = '1';
                $created = date('Y-m-d H:i:s');

                $stmt = db_connect()->prepare("INSERT INTO users (firstname, lastname, email, google_id, is_active, created) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $firstname, $lastname, $email, $google_id, $is_active, $created); // Bind the parameters
                if (!$stmt->execute()) {
                    die('Error occurred while creating user.'); // Log error in production
                }

                // Fetch the newly created user
                $stmt = db_connect()->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email); // Bind the parameter
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                $_SESSION['user'] = $user;
                $_SESSION['success'] = "success";
                $_SESSION['message'] = "Welcome " . htmlspecialchars($user['firstname']); // Sanitize output

                if ($user['is_active'] == 1) {
                    $_SESSION["loggedIn"] = $user['role'];
                    $_SESSION["user_id"] = $user['id'];
                }
            }

            // Redirect to the main page
            header("Location: index.php");
            exit();
        } else {
            die('Error: User info not available.'); // Log error in production
        }
    } else {
        die('Error: Access token not received.'); // Log error in production
    }
} else {
    // Display Google login button
    echo '<form action="https://accounts.google.com/o/oauth2/auth" method="get">
        <input type="hidden" name="response_type" value="code">
        <input type="hidden" name="client_id" value="656915444750-jencm5pb6chr7gri8547p9qrmp6iilqq.apps.googleusercontent.com">
        <input type="hidden" name="redirect_uri" value="'.($_SERVER['HTTP_HOST'] == 'localhost' ? "http://localhost/google_login.php" : "https://farahkordmahalehho-ayg0crenf5cag7dv.westeurope-01.azurewebsites.net/google_login.php").'">
        <input type="hidden" name="scope" value="https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile">
        <input type="hidden" name="approval_prompt" value="force">
        <input type="hidden" name="access_type" value="offline">
        <button type="submit">Login with Google</button>
    </form>';
}
