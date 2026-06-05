<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - IFAPME</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">

    @stack('styles')
</head>
<body>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">I</div>
            <span class="logo-text">IFAPME</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Accueil</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('complaints.index') }}" class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Mes Plaintes</span>
                    @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('complaints.create') }}" class="nav-link {{ request()->routeIs('complaints.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nouvelle Plainte</span>
                </a>
            </li>

            <li class="nav-divider"></li>

            <li class="nav-item">
                <a href="{{ route('history') }}" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Historique</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                        <span class="badge">{{ $unreadNotifications }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('help') }}" class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}">
                    <i class="bi bi-question-circle"></i>
                    <span>Aide</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info-compact">
            <div class="user-avatar-small">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-details-small">
                <div class="user-name-small">{{ Auth::user()->name }}</div>
                <div class="user-role-small">Utilisateur</div>
            </div>
        </div>
    </div>
</aside>

<!-- Main Content Area -->
<div class="main-wrapper">
    <!-- Top Header -->
    <header class="top-header">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="breadcrumb">
                @yield('breadcrumb')
            </div>
        </div>

        <div class="header-right">
            <!-- Search -->
            <div class="header-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Rechercher..." class="search-input">
            </div>

            <!-- Notifications -->
            <div class="header-item dropdown">
                <button class="icon-button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                        <span class="notification-dot"></span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <li class="dropdown-header">
                        <strong>Notifications</strong>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="notification-item">
                                <i class="bi bi-check-circle text-success"></i>
                                <div>
                                    <strong>Plainte traitée</strong>
                                    <small>Votre plainte #1234 a été résolue</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="notification-item">
                                <i class="bi bi-chat-left-text text-primary"></i>
                                <div>
                                    <strong>Nouveau message</strong>
                                    <small>Réponse à votre plainte #1235</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="{{ route('notifications') }}">Voir toutes les notifications</a></li>
                </ul>
            </div>

            <!-- User Profile -->
            <div class="header-item dropdown">
                <button class="user-button" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <strong>{{ Auth::user()->name }}</strong>
                        <small class="d-block text-muted">{{ Auth::user()->email }}</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i> Mon Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('settings') }}">
                            <i class="bi bi-gear"></i> Paramètres
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="main-content">
        @yield('content')
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.querySelector('.main-wrapper').classList.toggle('sidebar-collapsed');
    });

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');

        if (window.innerWidth <= 768) {
            if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.add('collapsed');
            }
        }
    });
</script>

@stack('scripts')
</body>
</html>
