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
    $stmt = $conn->prepare("DELETE FROM organizers WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: organizers.php?msg=Organizer deleted successfully");
    exit;
}

// Handle Add Organizer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_organizer'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $bio = $_POST['bio'];
    $image_path = '';

    // Handle File Upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "../uploads/organizers/";
        $file_extension = strtolower(pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Check if image file is a actual image
        $check = getimagesize($_FILES["image_file"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
                $image_path = 'uploads/organizers/' . $new_filename;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO organizers (name, role, image_url, bio, display_order) VALUES (:name, :role, :image, :bio, 0)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':image', $image_path);
        $stmt->bindParam(':bio', $bio);

        if ($stmt->execute()) {
            header("Location: organizers.php?msg=Organizer added successfully");
            exit;
        } else {
            $error = "Failed to add organizer.";
        }
    }
}

// Fetch Organizers
$organizers = $conn->query("SELECT * FROM organizers ORDER BY display_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header"
            style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1 class="section-title">Leadership Council</h1>
                <p class="section-subtitle">Manage the dedicated individuals steering the club's vision.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-user-add-line"></i> Appoint Member
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
                        <th width="80">Avatar</th>
                        <th>Professional Identity</th>
                        <th>Functional Role</th>
                        <th>Biography</th>
                        <th width="140">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organizers as $org): ?>
                        <tr>
                            <td data-label="Avatar">
                                <div
                                    style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 2px solid var(--border); background: var(--bg-deep);">
                                    <?php if ($org['image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($org['image_url']); ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div
                                            style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--muted);">
                                            <i class="ri-user-3-line" style="font-size: 1.2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Identity">
                                <div style="font-weight: 700; color: var(--white);">
                                    <?php echo htmlspecialchars($org['name']); ?>
                                </div>
                            </td>
                            <td data-label="Role">
                                <span class="badge badge-accent"
                                    style="text-transform: uppercase; letter-spacing: 0.05em;"><?php echo htmlspecialchars($org['role']); ?></span>
                            </td>
                            <td data-label="Biography" style="color: var(--muted); font-size: 0.9rem; line-height: 1.5;">
                                <?php echo substr(htmlspecialchars($org['bio']), 0, 90) . '...'; ?>
                            </td>
                            <td data-label="Control">
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="#" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 10px;"
                                        title="Edit Profile"><i class="ri-edit-2-line"></i></a>
                                    <a href="organizers.php?delete=<?php echo $org['id']; ?>" class="btn"
                                        style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                        onclick="return confirm('Remove this member from the leadership council?')"
                                        title="Remove"><i class="ri-user-unfollow-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Appointment Modal -->
<style>
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(15px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.4s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-content-glass {
        width: 100%;
        max-width: 550px;
        transform: scale(0.9);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active .modal-content-glass {
        transform: scale(1);
    }
</style>

<div class="modal-overlay" id="orgModal">
    <div class="glass-card modal-content-glass">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.5rem;">Leadership Appointment</h3>
            <button onclick="closeModal()"
                style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.5rem;">
                <i class="ri-close-circle-line"></i>
            </button>
        </header>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add_organizer" value="1">
            <div class="form-group">
                <label class="form-label">Full Nomenclature</label>
                <input type="text" name="name" class="form-input" required placeholder="Dr. Alexander Wright">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Council Designation</label>
                    <input type="text" name="role" class="form-input" required
                        placeholder="Club Principal / Lead Mentor">
                </div>
                <div class="form-group">
                    <label class="form-label">Profile Image (Upload)</label>
                    <input type="file" name="image_file" class="form-input" accept="image/*" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Professional Brief</label>
                <textarea name="bio" class="form-input" rows="4"
                    placeholder="Outline professional background and club responsibilities..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Confirm Appointment</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('orgModal').classList.add('active'); }
    function closeModal() { document.getElementById('orgModal').classList.remove('active'); }
</script>
</body>

</html>