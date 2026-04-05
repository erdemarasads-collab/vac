<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - HGS System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css?v=<?= time() ?>">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <span><em>HGS</em> Admin</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="?page=dashboard" class="nav-item <?= $page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Ana Sayfa</span>
                </a>
                <a href="?page=users" class="nav-item <?= $page === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Loglar</span>
                </a>
                <a href="?page=online" class="nav-item <?= $page === 'online' ? 'active' : '' ?>">
                    <i class="fas fa-circle" style="color: #2ecc71; font-size: 0.6rem;"></i>
                    <span>Canlı Takip</span>
                    <span class="badge" id="onlineCount">0</span>
                </a>
                <a href="?page=settings" class="nav-item <?= $page === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>Ayarlar</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="?logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Çıkış Yap</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-right">
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span>Admin</span>
                    </div>
                </div>
            </header>
            
            <div class="content">
                <?php include "pages/{$page}.php"; ?>
            </div>
        </main>
    </div>
    
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
        
        // Online count güncelle
        async function updateOnlineCount() {
            try {
                const response = await fetch('/api.php?action=online_count');
                const data = await response.json();
                if (data.success) {
                    document.getElementById('onlineCount').textContent = data.count;
                }
            } catch(e) {
                console.error('Online count error:', e);
            }
        }
        
        updateOnlineCount();
        setInterval(updateOnlineCount, 3000);
    </script>
</body>
</html>
