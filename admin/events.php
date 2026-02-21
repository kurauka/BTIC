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

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header"
            style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1 class="section-title">Event Logistics</h1>
                <p class="section-subtitle">Coordinate and track upcoming club gatherings and workshops.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-calendar-event-line"></i> Schedule New Event
            </button>
        </header>

        <?php if (isset($_GET['msg'])): ?>
            <div class="glass-card"
                style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-color: var(--teal); background: rgba(0, 201, 167, 0.05); color: var(--teal); display: flex; align-items: center; gap: 0.75rem;">
                <i class="ri-checkbox-circle-line"></i>
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th width="120">Timeline</th>
                        <th>Event Details</th>
                        <th>Venue / Location</th>
                        <th width="160">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td data-label="Timeline">
                                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                                    <div
                                        style="font-weight: 800; font-family: 'Syne', sans-serif; color: var(--accent); font-size: 1.25rem; line-height: 1;">
                                        <?php echo date('d', strtotime($event['event_date'])); ?>
                                    </div>
                                    <div
                                        style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--muted); letter-spacing: 0.05em;">
                                        <?php echo date('M Y', strtotime($event['event_date'])); ?>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Event Details">
                                <div style="font-weight: 700; font-size: 1.1rem; color: var(--white);">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
                                    <?php echo substr(htmlspecialchars($event['description']), 0, 80) . '...'; ?>
                                </div>
                            </td>
                            <td data-label="Venue">
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--muted);">
                                    <i class="ri-map-pin-2-line" style="color: var(--accent)"></i>
                                    <span><?php echo htmlspecialchars($event['location']); ?></span>
                                </div>
                            </td>
                            <td data-label="Control">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="#" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 10px;"
                                        title="Edit"><i class="ri-pencil-line"></i></a>
                                    <a href="events.php?delete=<?php echo $event['id']; ?>" class="btn"
                                        style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                        onclick="return confirm('Are you sure?')" title="Delete"><i
                                            class="ri-delete-bin-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Event Modal -->
<style>
    .modal-content-glass {
        width: 100%;
        max-width: 550px;
    }
</style>

<div class="modal-overlay" id="eventModal">
    <div class="glass-card modal-content-glass">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.5rem;">Schedule Engagement</h3>
            <button onclick="closeModal()"
                style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.5rem;">
                <i class="ri-close-circle-line"></i>
            </button>
        </header>

        <form method="POST">
            <input type="hidden" name="add_event" value="1">
            <div class="form-group">
                <label class="form-label">Event Nomenclature</label>
                <input type="text" name="title" class="form-input" required
                    placeholder="e.g. Annual Tech Symposium 2026">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Engagement Date</label>
                    <input type="date" name="event_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Venue Location</label>
                    <input type="text" name="location" class="form-input" required placeholder="Academy Innovation Hub">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Operational Brief</label>
                <textarea name="description" class="form-input" rows="4" required
                    placeholder="Outline the event objectives, speakers, and target audience..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Commit Event Schedule</button>
            </div>
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