<style>
    .navbar {
        background-color: #0d47a1;
        padding: 10px 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        box-shadow: 0 4px 8px rgba(62, 172, 250, 0.8);
        font-family: Arial, sans-serif;
        position: relative;
    }

    .navbar-left {
        font-size: 20px;
        font-weight: bold;
        color: white;
        text-shadow: 1px 1px 2px black;
    }

    .navbar-toggle {
        display: none;
        font-size: 26px;
        cursor: pointer;
        color: white;
    }

    .navbar-right {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .navbar-right a {
        color: #ffffff !important;
        margin-left: 20px;
        text-decoration: none;
        font-weight: bold;
        font-size: 20px;
        transition: color 0.3s;
        position: relative;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.6);
    }

    .navbar-right a::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #ffffff;
        transition: width 0.3s ease;
    }

    .navbar-right a:hover::after {
        width: 100%;
    }

    .navbar-right a:hover {
        color: #eeeeee;
    }

    /* Responsiveness */
    @media screen and (max-width: 768px) {
        .navbar-right {
            display: none;
            flex-direction: column;
            width: 100%;
            background-color: #0d47a1;
            margin-top: 10px;
        }

        .navbar-right a {
            margin: 10px 0;
            text-align: center;
        }

        .navbar-toggle {
            display: block;
        }

        .navbar-right.active {
            display: flex;
        }
    }
</style>

<div class="navbar">
    <div class="navbar-left">
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?> (<?= $_SESSION['role'] ?>)</p>
    </div>
    <div class="navbar-toggle" onclick="toggleNavbar()">☰</div>
    <div class="navbar-right" id="navbarRight">
        <a href="<?= ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'supervisor') ? 'dashboard.php' : 'index.php' ?>">🏠 Dashboard</a>
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="dashboard_analitik.php">📊 Lihat Analitik</a>
        <?php endif; ?>
        <a href="profil.php">👤 Profil</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<script>
    function toggleNavbar() {
        const navbarRight = document.getElementById("navbarRight");
        navbarRight.classList.toggle("active");
    }
</script>
