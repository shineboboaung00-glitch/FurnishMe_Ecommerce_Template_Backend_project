<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/contact.php';

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable 
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$message_object = new Contact($db);
$messages = $message_object->read();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - FurnishMe Admin</title>

    <!-- Global CSS Link -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Messages Page Specific CSS Link -->
    <link rel="stylesheet" href="../css/messages.css">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body class="admin-body">

    <!-- 1. Sidebar Panel -->
    <?php include_once(__DIR__ . '/../components/dashboard_sidebar.php'); ?>

    <!-- 2. Main Content Area -->
    <main class="main-content">

        <!-- Dashboard Header -->
        <?php include_once(__DIR__ . '/../components/dashboard_header.php'); ?>

        <!-- Dashboard Body Area -->
        <div class="dashboard-body">

            <!-- Welcome Title -->
            <div class="welcome-header_product">
                <h1>Customer Messages</h1>
            </div>

            <!-- Filter / Tabs Container -->
            <div class="filter-card messages">
                <div class="tab-card">
                    <button class="tab-btn active">All Messages</button>
                    <button class="tab-btn">Unread</button>
                    <button class="tab-btn">Starred</button>
                </div>
            </div>

            <!-- Messages Table Section -->
            <div class="orders-table-container">

                <div class="orders-table">
                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Name</div>
                        <div class="col col-customer">Phone Number</div>
                        <div class="col col-total">Email</div>
                        <div class="col col-items">Message</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($messages && $messages->rowCount() > 0):
                        while ($row = $messages->fetch(PDO::FETCH_ASSOC)):
                            $id = $row['id'];
                            $raw_name = $row['name'] ?? '';
                            $raw_phone = $row['phone'] ?? '';
                            $raw_email = $row['email'] ?? '';
                            $raw_message = $row['message'] ?? '';

                            $name = htmlspecialchars($raw_name, ENT_QUOTES, 'UTF-8');
                            $phone = htmlspecialchars($raw_phone, ENT_QUOTES, 'UTF-8');
                            $email = htmlspecialchars($raw_email, ENT_QUOTES, 'UTF-8');
                            $message = htmlspecialchars($raw_message, ENT_QUOTES, 'UTF-8');

                            // View Data Payload
                            $view_payload = json_encode([
                                'name'    => $raw_name,
                                'email'   => $raw_email,
                                'phone'   => $raw_phone,
                                'message' => $raw_message
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

                            // Reply Modal Config
                            $contact_reply_config = json_encode([
                                'module'  => 'contact',
                                'action'  => 'reply',
                                'title'   => 'Reply to ' . $raw_name,
                                'fields'  => [
                                    ['name' => 'to_email', 'label' => 'To Email', 'type' => 'text', 'readonly' => true],
                                    ['name' => 'subject', 'label' => 'Subject', 'type' => 'text', 'placeholder' => 'Enter email subject'],
                                    ['name' => 'reply_message', 'label' => 'Your Reply', 'type' => 'textarea', 'placeholder' => 'Type your reply message here...']
                                ],
                                'data'    => [
                                    'id'       => (string)$id,
                                    'to_email' => $raw_email,
                                    'subject'  => 'Re: Response from FurnishMe Support'
                                ]
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

                            // Delete Modal Config
                            $contact_delete_config = json_encode([
                                'module'  => 'contact',
                                'action'  => 'delete',
                                'title'   => 'Delete Message',
                                'message' => 'Are you sure you want to delete this message from ' . $raw_name . '?',
                                'data'    => ['id' => (string)$id]
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                    ?>
                            <!-- Message Row -->
                            <div class="orders-row">

                                <!-- 1. Name -->
                                <div class="col col-id">
                                    <h3><?php echo $name; ?></h3>
                                </div>

                                <!-- 2. Phone -->
                                <div class="col col-customer"><?php echo $phone; ?></div>

                                <!-- 3. Email -->
                                <div class="col col-total email-col-no-transform"><?php echo $email; ?></div>

                                <!-- 4. Message Preview -->
                                <div class="col col-items">
                                    <p><?php echo (mb_strlen($message) > 40) ? mb_substr($message, 0, 40) . '...' : $message; ?></p>
                                </div>

                                <!-- 5. Action Buttons -->
                                <div class="col col-action action-buttons-flex">
                                    <!-- Custom View Modal Button -->
                                    <button onclick='viewMessage(<?php echo htmlspecialchars($view_payload, ENT_QUOTES, "UTF-8"); ?>)' class="btn btn-view-custom" title="Read Message">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>

                                    <?php if ($isAdmin): ?>
                                        <!-- Reply Button -->
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($contact_reply_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn btn-reply-custom" title="Reply Message">
                                            <i class="fa-solid fa-reply"></i> Reply
                                        </button>

                                        <!-- Delete Button -->
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($contact_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn btn-delete-custom" title="Delete Message">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No messages found!</p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <!-- Custom Read-Only Message View Modal -->
    <div id="messageViewModal" class="custom-modal-backdrop" style="display: none;">
        <div class="custom-modal-card">
            <div class="custom-modal-header">
                <h2>Message Details</h2>
                <button class="custom-modal-close" onclick="closeMessageViewModal()">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="msg-detail-item">
                    <div class="msg-detail-label">Sender Name</div>
                    <div class="msg-detail-value" id="viewSenderName"></div>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div class="msg-detail-item" style="flex: 1;">
                        <div class="msg-detail-label">Email</div>
                        <div class="msg-detail-value" id="viewSenderEmail"></div>
                    </div>
                    <div class="msg-detail-item" style="flex: 1;">
                        <div class="msg-detail-label">Phone</div>
                        <div class="msg-detail-value" id="viewSenderPhone"></div>
                    </div>
                </div>
                <div class="msg-detail-item">
                    <div class="msg-detail-label">Message</div>
                    <div class="msg-content-box" id="viewMessageContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Form Modal Container -->
    <?php include_once(__DIR__ . '/../components/dynamic_form.php'); ?>

    <!-- JS Files -->
    <script src="../js/script.js"></script>

</body>

</html>