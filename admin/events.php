<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../db_connect.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: events.php?msg=Event deleted successfully");
    exit;
}

// Handle Add Event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $date = $_POST['event_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO events (title, event_date, location, description) VALUES (:title, :date, :location, :description)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        header("Location: events.php?msg=Event added successfully");
        exit;
    } else {
        $error = "Failed to add event.";
    }
}

// Fetch Events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<style>
    /* Reusing dashboard styles + specific ones */
    .dashboard-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 260px;
        background: rgba(5, 13, 26, 0.9);
        border-right: 1px solid var(--border);
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
    }

    .main-content {
        flex: 1;
        margin-left: 260px;
        padding: 2rem;
    }

    /* Sidebar Links (Shared) */
    .menu-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--muted);
        margin-bottom: 1rem;
        margin-top: 2rem;
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--white);
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
    }

    .menu-link:hover,
    .menu-link.active {
        background: rgba(0, 201, 167, 0.1);
        color: var(--teal);
    }

    /* Table & Forms */
    .content-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem;
        overflow: hidden;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .data-table th {
        text-align: left;
        padding: 1rem;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        font-weight: 600;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--white);
        vertical-align: middle;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .action-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.2s;
    }

    .btn-edit {
        background: rgba(56, 189, 248, 0.15);
        color: var(--sky);
    }

    .btn-delete {
        background: rgba(255, 77, 79, 0.15);
        color: var(--error);
    }

    .btn-edit:hover {
        background: rgba(56, 189, 248, 0.25);
    }

    .btn-delete:hover {
        background: rgba(255, 77, 79, 0.25);
    }

    /* Form */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: #0a1625;
        width: 100%;
        max-width: 500px;
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .close-modal {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 1.5rem;
        cursor: pointer;
    }
</style>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="login-logo" style="justify-content: flex-start; margin-bottom: 0;">
            <i class="ri-anchor-line" style="color:var(--teal)"></i> BTIC<span>.</span>
        </div>
        <div class="menu-label">Main</div>
        <a href="index.php" class="menu-link"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a href="projects.php" class="menu-link"><i class="ri-folder-line"></i> Projects</a>
        <a href="events.php" class="menu-link active"><i class="ri-calendar-event-line"></i> Events</a>
        <a href="messages.php" class="menu-link"><i class="ri-mail-line"></i> Messages</a>
        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Events</h1>
                <p style="color: var(--muted); margin-top: 0.5rem;">Schedule and manage events</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-add-line"></i> Add Event
            </button>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert"
                style="background: rgba(0,201,167,0.1); color: var(--teal); border: 1px solid rgba(0,201,167,0.2);">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                        <th>Location</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--teal);">
                                    <?php echo date('d M Y', strtotime($event['event_date'])); ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--muted);">
                                    <?php echo substr(htmlspecialchars($event['description']), 0, 50) . '...'; ?>
                                </div>
                            </td>
                            <td><span class="badge"><i class="ri-map-pin-line"></i>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </span></td>
                            <td>
                                <a href="#" class="action-btn btn-edit"><i class="ri-pencil-line"></i></a>
                                <a href="events.php?delete=<?php echo $event['id']; ?>" class="action-btn btn-delete"
                                    onclick="return confirm('Are you sure?')"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Event Modal -->
<div class="modal-overlay" id="eventModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Event</h3>
            <button class="close-modal" onclick="closeModal()"><i class="ri-close-line"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_event" value="1">
            <div class="form-group">
                <label class="form-label">Event Title</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="event_date" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-input" value="Bandari Maritime Academy">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Event</button>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('eventModal').classList.add('active'); }
    function closeModal() { document.getElementById('eventModal').classList.remove('active'); }

    // Auto-open modal if query param exists
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'add') {
        openModal();
    }
</script>
</body>

</html>