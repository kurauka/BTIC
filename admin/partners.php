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
    $stmt = $conn->prepare("DELETE FROM partners WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: partners.php?msg=Partner deleted successfully");
    exit;
}

// Handle Add Partner
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_partner'])) {
    $name = $_POST['name'];
    $website_url = $_POST['website_url'];
    $logo_url = $_POST['logo_url']; // Text input for now

    $stmt = $conn->prepare("INSERT INTO partners (name, website_url, logo_url, display_order) VALUES (:name, :website, :logo, 0)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':website', $website_url);
    $stmt->bindParam(':logo', $logo_url);

    if ($stmt->execute()) {
        header("Location: partners.php?msg=Partner added successfully");
        exit;
    } else {
        $error = "Failed to add partner.";
    }
}

// Fetch Partners
$partners = $conn->query("SELECT * FROM partners ORDER BY display_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header"
            style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1 class="section-title">Strategic Alliances</h1>
                <p class="section-subtitle">Manage our ecosystem of industry collaborators and sponsors.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-hand-heart-line"></i> Onboard Partner
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
                        <th width="120">Organizational Logo</th>
                        <th>Partner Identity</th>
                        <th>Digital Presence</th>
                        <th width="140">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $p): ?>
                        <tr>
                            <td data-label="Logo">
                                <div
                                    style="width: 100px; height: 50px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); border-radius: 12px; display: flex; align-items: center; justify-content: center; padding: 0.75rem; overflow: hidden;">
                                    <?php if ($p['logo_url']): ?>
                                        <img src="<?php echo htmlspecialchars($p['logo_url']); ?>"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain; filter: grayscale(1) brightness(2) contrast(0.5); transition: all 0.3s ease;"
                                            onmouseover="this.style.filter='grayscale(0) brightness(1) contrast(1)'"
                                            onmouseout="this.style.filter='grayscale(1) brightness(2) contrast(0.5)'">
                                    <?php else: ?>
                                        <i class="ri-shield-user-line"
                                            style="color: var(--muted); opacity: 0.3; font-size: 1.5rem;"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Identity">
                                <div style="font-weight: 700; color: var(--white); font-size: 1.1rem;">
                                    <?php echo htmlspecialchars($p['name']); ?></div>
                            </td>
                            <td data-label="Presence">
                                <a href="<?php echo htmlspecialchars($p['website_url']); ?>" target="_blank"
                                    class="nav-link"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; background: none; border: none; padding: 0; font-size: 0.9rem; color: var(--accent); opacity: 0.8;">
                                    <span>Visit Repository</span>
                                    <i class="ri-external-link-line"></i>
                                </a>
                            </td>
                            <td data-label="Control">
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="#" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 10px;"
                                        title="Edit Alliance"><i class="ri-settings-4-line"></i></a>
                                    <a href="partners.php?delete=<?php echo $p['id']; ?>" class="btn"
                                        style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                        onclick="return confirm('Terminate this strategic alliance?')" title="Delete"><i
                                            class="ri-close-circle-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Onboarding Modal -->
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
        max-width: 500px;
        transform: scale(0.9);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active .modal-content-glass {
        transform: scale(1);
    }
</style>

<div class="modal-overlay" id="partnerModal">
    <div class="glass-card modal-content-glass">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.5rem;">Alliance Onboarding</h3>
            <button onclick="closeModal()"
                style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.5rem;">
                <i class="ri-close-circle-line"></i>
            </button>
        </header>

        <form method="POST">
            <input type="hidden" name="add_partner" value="1">
            <div class="form-group">
                <label class="form-label">Organizational Name</label>
                <input type="text" name="name" class="form-input" required placeholder="e.g. Maritime Logix Corp">
            </div>

            <div class="form-group">
                <label class="form-label">Visual Asset (Logo URL)</label>
                <input type="text" name="logo_url" class="form-input"
                    placeholder="https://assets.logos.com/partner.svg">
            </div>

            <div class="form-group">
                <label class="form-label">Digital Presence URL</label>
                <input type="text" name="website_url" class="form-input" placeholder="https://corporate.maritime.com"
                    value="#">
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Commit Alliance</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('partnerModal').classList.add('active'); }
    function closeModal() { document.getElementById('partnerModal').classList.remove('active'); }
</script>
</body>

</html>
