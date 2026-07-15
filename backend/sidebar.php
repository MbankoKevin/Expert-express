<!-- Modern CSS overrides to match the SaaS App aesthetics -->
<style>
    .left-sidebar {
        background-color: #090d16; /* Deeper midnight background for richer contrast */
        min-height: 100vh;
        width: 260px;
        position: fixed;
        top: 0;
        left: 0;
        box-shadow: 4px 0 30px rgba(0, 0, 0, 0.25);
        border-right: 1px solid rgba(255, 255, 255, 0.04);
        z-index: 1000;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .scroll-sidebar {
        padding: 24px 14px; /* Slightly tighter padding for modern breathing room */
        display: flex;
        flex-direction: column;
        height: calc(100vh - 48px);
        justify-content: space-between;
    }

    .sidebar-brand {
        padding: 4px 12px 24px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
    }

    .sidebar-brand-text {
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* Small Section Header Titles */
    .sidebar-nav .nav-label {
        color: #475569; /* Balanced slate tone */
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 20px 12px 8px 12px;
        display: block;
    }

    /* Remove natural spacing on the very first label */
    .sidebar-nav ul > .nav-label:first-child {
        padding-top: 0;
    }

    .sidebar-nav ul li {
        margin-bottom: 2px; /* Tighter list arrangement */
        position: relative;
    }

    .sidebar-nav ul li a {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.875rem; /* Clean 14px styling standard */
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }

    .sidebar-nav ul li a i {
        font-size: 1rem;
        width: 20px;
        margin-right: 12px;
        color: #475569;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* --- Dynamic Hover State --- */
    .sidebar-nav ul li a:hover {
        background-color: rgba(255, 255, 255, 0.03);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .sidebar-nav ul li a:hover i {
        color: #38bdf8; /* Vibrant Sky Accent */
    }

    /* --- Clean Active Link Indicator --- */
    .sidebar-nav ul li.active a {
        background-color: rgba(56, 189, 248, 0.08); /* Transparent accent color */
        color: #ffffff;
        font-weight: 600;
    }

    .sidebar-nav ul li.active a i {
        color: #38bdf8;
    }

    /* Smooth CSS left boundary indicator pill */
    .sidebar-nav ul li.active::before {
        content: '';
        position: absolute;
        left: -14px; /* Flush against the sidebar boundary */
        top: 20%;
        height: 60%;
        width: 4px;
        background-color: #38bdf8;
        border-radius: 0 4px 4px 0;
    }
</style>

<!-- Left Sidebar -->
<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        
        <div>
            <!-- Brand Header -->
            <div class="sidebar-brand">
                <span class="sidebar-brand-text">📦 Cargo Express</span>
            </div>

            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-label">Core Matrix</li>
                    
                    <!-- Added active class to the dynamic root dashboard for reference -->
                    <li class="active"> 
                        <a href="home.php" aria-expanded="false">
                            <i class="fa fa-tachometer"></i><span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-label">User Directories</li>
                    <li> 
                        <a href="add_user.php" aria-expanded="false">
                            <i class="fa fa-user-plus"></i><span>New User</span>
                        </a>
                    </li>
                    <li> 
                        <a href="view_user.php" aria-expanded="false">
                            <i class="fa fa-users"></i><span>View Users</span>
                        </a>
                    </li>
                    
                    <li class="nav-label">Logistics & Freight</li>
                    <li> 
                        <a href="new_tracking.php" aria-expanded="false">
                            <i class="fa fa-plus-circle"></i><span>New Tracking</span>
                        </a>
                    </li>
                    <li> 
                        <a href="view_tracking.php" aria-expanded="false">
                            <i class="fa fa-cube"></i><span>View Tracking</span>
                        </a>
                    </li>
                    
                    <li class="nav-label">Communications</li>
                    <li> 
                        <a href="mail.php" aria-expanded="false">
                            <i class="fa fa-envelope-o"></i><span>Mail Jk</span>
                        </a>
                    </li>
                    <li> 
                        <a href="editmap.php" aria-expanded="false">
                            <i class="fa fa-map-marker"></i><span>Edit Map</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        
    </div>
    <!-- End Sidebar scroll-->
</div>