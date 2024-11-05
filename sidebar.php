<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg bg-purple main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg	collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
            <li>
                <form class="form-inline mr-auto">
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="assets/img/user-8.png"
                    class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title"><?php echo $_SESSION['username']; ?></div>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2 bg-gray">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="assets/img/logo.png" class="header-logo" style="height: 70px;" />
                <span class="logo-name small" style="font-size: 14px;">Spot Engineers</span>
            </a>

        </div>
        <ul class="sidebar-menu">
            <li class="menu-header"></li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="dashboard.php">Sales</a></li>
                    <li><a class="nav-link" href="dashboard-service.php">Service</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="briefcase"></i><span>Master</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="supplier.php">Supplier </a></li>
                    <li><a class="nav-link" href="items.php">Spares Part</a></li>
                    <li><a class="nav-link" href="customer.php">Customer</a></li>
                    <li><a class="nav-link" href="work-master.php">Work Schedule</a></li>
                    <li><a class="nav-link" href="employee.php">Employee</a></li>
                </ul>
            </li>


            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="package"></i><span>Inventory</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="purchase.php">Purchase (Inward) </a></li>
                    <li><a class="nav-link" href="purchases.php">Purchase Return</a></li>
                    <li><a class="nav-link" href="sales.php">Sales (Billing)</a></li>
                    <li><a class="nav-link" href="sales_all.php">Sales Return(Edit)</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="cpu"></i><span>Service</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="job-entry.php">Job Entry</a></li>
                    <!-- <li><a class="nav-link" href="spare-issue.php">Spare Issue</a></li>
                    <li><a class="nav-link" href="spare-return.php">Spare Return</a></li> -->
                </ul>
            </li>




            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>HR Management</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="attendance.php">Attendance</a></li>

                </ul>
            </li>




            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="file-text"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="sales_report.php">Sales Register</a></li>
                    <li><a class="nav-link" href="purchase-report.php">Purchase Register</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="http://www.infotrackin.com/its/" target="_blank" class="nav-link"><i data-feather="headphones"></i><span>Support </span></a>
            </li>
        </ul>
    </aside>
</div>