<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    @php
        use App\Helpers\PermissionHelper;
        $userRole = session('user_role', null);
    @endphp

    <nav id="sidebar">
        <div class="sidebar-header">
            <h3 class="mb-0">
                <i class="bi bi-shop"></i>
                <span class="ms-2">GROSSISTE</span>
            </h3>
        </div>

        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                {{-- =============== SECTION ACHATS =============== --}}
                @if(PermissionHelper::hasModuleAccess($userRole, 'achats') || PermissionHelper::hasModuleAccess($userRole, 'all'))
                <div class="menu-divider"></div>
                <li class="menu-title">Achats</li>
                
                {{-- PROFORMA FOURNISSEUR --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur'))
                <li class="has-submenu {{ request()->is('proforma-fournisseur*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-file-earmark-check"></i>
                        <span class="menu-text">Demande d'achat</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('proforma-fournisseur*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur', 'create'))
                        <a href="{{ route('proforma-fournisseur.create') }}" class="submenu-item {{ request()->is('proforma-fournisseur/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur', 'list'))
                        <a href="{{ route('proforma-fournisseur.list') }}" class="submenu-item {{ request()->is('proforma-fournisseur/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- BON DE COMMANDE --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande'))
                <li class="has-submenu {{ request()->is('bon-commande*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span class="menu-text">Bon de Commande</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('bon-commande*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande', 'create'))
                        <a href="{{ route('bon-commande.create') }}" class="submenu-item {{ request()->is('bon-commande/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande', 'list'))
                        <a href="{{ route('bon-commande.list') }}" class="submenu-item {{ request()->is('bon-commande/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- FACTURE FOURNISSEUR --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'facture-fournisseur'))
                <li class="has-submenu {{ request()->is('facture-fournisseur*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-receipt"></i>
                        <span class="menu-text">Facture Fournisseur</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('facture-fournisseur*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'facture-fournisseur', 'create'))
                        <a href="{{ route('facture-fournisseur.create') }}" class="submenu-item {{ request()->is('facture-fournisseur/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'facture-fournisseur', 'list'))
                        <a href="{{ route('facture-fournisseur.list') }}" class="submenu-item {{ request()->is('facture-fournisseur/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- BON DE RECEPTION --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception'))
                <li class="has-submenu {{ request()->is('bon-reception*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-file-earmark-check"></i>
                        <span class="menu-text">Bon de Réception</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('bon-reception*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception', 'create'))
                        <a href="{{ route('bon-reception.create') }}" class="submenu-item {{ request()->is('bon-reception/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception', 'list'))
                        <a href="{{ route('bon-reception.list') }}" class="submenu-item {{ request()->is('bon-reception/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                {{-- =============== SECTION TIERS =============== --}}
                @if(PermissionHelper::hasModuleAccess($userRole, 'tiers') || PermissionHelper::hasModuleAccess($userRole, 'vente') || PermissionHelper::hasModuleAccess($userRole, 'all'))
                <div class="menu-divider"></div>
                <li class="menu-title">Tiers</li>
                
                {{-- CLIENTS --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'clients'))
                <li class="has-submenu {{ request()->is('clients*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-people"></i>
                        <span class="menu-text">Clients</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('clients*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'clients', 'create'))
                        <a href="{{ route('clients.create') }}" class="submenu-item {{ request()->is('clients/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'clients', 'list'))
                        <a href="{{ route('clients.list') }}" class="submenu-item {{ request()->is('clients/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- FOURNISSEURS --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs'))
                <li class="has-submenu {{ request()->is('fournisseurs*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Fournisseurs</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('fournisseurs*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs', 'create'))
                        <a href="{{ route('fournisseurs.create') }}" class="submenu-item {{ request()->is('fournisseurs/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs', 'list'))
                        <a href="{{ route('fournisseurs.list') }}" class="submenu-item {{ request()->is('fournisseurs/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                {{-- =============== SECTION PRODUITS =============== --}}
                @if(PermissionHelper::hasModuleAccess($userRole, 'produits') || PermissionHelper::hasModuleAccess($userRole, 'stock') || PermissionHelper::hasModuleAccess($userRole, 'all'))
                <div class="menu-divider"></div>
                <li class="menu-title">Produits</li>
                
                {{-- ARTICLES --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'articles'))
                <li class="has-submenu {{ request()->is('articles*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-box"></i>
                        <span class="menu-text">Articles</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('articles*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'articles', 'create'))
                        <a href="{{ route('articles.create') }}" class="submenu-item {{ request()->is('articles/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'articles', 'list'))
                        <a href="{{ route('articles.list') }}" class="submenu-item {{ request()->is('articles/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- MOUVEMENT DE STOCK --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock'))
                <li class="has-submenu {{ request()->is('mvt-stock*') || request()->is('stock*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-arrow-left-right"></i>
                        <span class="menu-text">Gestion Stock</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('mvt-stock*') || request()->is('stock*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'create'))
                        <a href="{{ route('mvt-stock.create') }}" class="submenu-item {{ request()->is('mvt-stock/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'list'))
                        <a href="{{ route('mvt-stock.list') }}" class="submenu-item {{ request()->is('mvt-stock/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'details'))
                        <a href="{{ route('stock.details') }}" class="submenu-item {{ request()->is('stock/details') ? 'active' : '' }}">
                            <i class="bi bi-list-check"></i>
                            <span>Détails</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'etat'))
                        <a href="{{ route('stock.etat') }}" class="submenu-item {{ request()->is('stock/etat') || request()->is('stock*') ? 'active' : '' }}">
                            <i class="bi bi-boxes"></i>
                            <span>Etat</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- CATEGORIES --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'categories'))
                <li class="has-submenu {{ request()->is('categories*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-tag"></i>
                        <span class="menu-text">Catégories</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('categories*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'categories', 'create'))
                        <a href="{{ route('categories.create') }}" class="submenu-item {{ request()->is('categories/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'categories', 'list'))
                        <a href="{{ route('categories.list') }}" class="submenu-item {{ request()->is('categories/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- UNITES --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'unites'))
                <li class="has-submenu {{ request()->is('unites*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-rulers"></i>
                        <span class="menu-text">Unités</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('unites*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'unites', 'create'))
                        <a href="{{ route('unites.create') }}" class="submenu-item {{ request()->is('unites/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'unites', 'list'))
                        <a href="{{ route('unites.list') }}" class="submenu-item {{ request()->is('unites/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                {{-- =============== SECTION MAGASIN =============== --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'magasin'))
                <div class="menu-divider"></div>
                <li class="menu-title">Magasin</li>
                
                <li class="has-submenu {{ request()->is('magasin*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-shop-window"></i>
                        <span class="menu-text">Magasin</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('magasin*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'magasin', 'create'))
                        <a href="{{ route('magasin.create') }}" class="submenu-item {{ request()->is('magasin/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'magasin', 'list'))
                        <a href="{{ route('magasin.list') }}" class="submenu-item {{ request()->is('magasin/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'magasin', 'carte'))
                        <a href="{{ route('magasin.carte') }}" class="submenu-item {{ request()->is('magasin/carte') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt"></i>
                            <span>Voir Carte</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- =============== SECTION FINANCE =============== --}}
                @if(PermissionHelper::hasModuleAccess($userRole, 'finance') || PermissionHelper::hasModuleAccess($userRole, 'all'))
                <div class="menu-divider"></div>
                <li class="menu-title">Finance</li>
                
                {{-- CAISSE --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'caisse'))
                <li class="has-submenu {{ request()->is('caisse*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-safe2"></i>
                        <span class="menu-text">Caisse</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('caisse*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'caisse', 'create'))
                        <a href="{{ route('caisse.create') }}" class="submenu-item {{ request()->is('caisse/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'caisse', 'list'))
                        <a href="{{ route('caisse.list') }}" class="submenu-item {{ request()->is('caisse/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                {{-- MOUVEMENTS CAISSE --}}
                @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse'))
                <li class="has-submenu {{ request()->is('mvt-caisse*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <i class="bi bi-arrow-left-right"></i>
                        <span class="menu-text">Mouvements</span>
                    </a>
                    <div class="sidebar-submenu" style="{{ request()->is('mvt-caisse*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse', 'create'))
                        <a href="{{ route('mvt-caisse.create') }}" class="submenu-item {{ request()->is('mvt-caisse/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse', 'list'))
                        <a href="{{ route('mvt-caisse.list') }}" class="submenu-item {{ request()->is('mvt-caisse/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse', 'etat'))
                        <a href="{{ route('mvt-caisse.etat') }}" class="submenu-item {{ request()->is('mvt-caisse/etat*') ? 'active' : '' }}">
                            <i class="bi bi-graph-up"></i>
                            <span>Etat</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                <div class="menu-divider"></div>
            </ul>
        </div>

        <div class="sidebar-footer">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li>
                    <a href="{{ url('/aide') }}" class="{{ request()->is('aide*') ? 'active' : '' }}" title="Aide et support">
                        <i class="bi bi-question-circle"></i>
                        <span class="menu-text">Aide</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="handleLogout()" title="Se déconnecter">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="menu-text">Déconnexion</span>
                    </a>
                </li>
            </ul>
            <div>
                <p style="margin-bottom: 2px; font-weight: 600;">Gestion AVS</p>
                <p style="font-size: 0.65rem; opacity: 0.7;">v1.0.0</p>
                <p style="font-size: 0.65rem; margin-top: 5px; opacity: 0.6;">&copy; {{ date('Y') }}</p>
            </div>
        </div>
    </nav>
    
    <div id="content">
        <button type="button" id="sidebarCollapse" class="d-lg-none">
            <i class="bi bi-list"></i>
        </button>
        <nav class="navbar navbar-expand-lg navbar-light navbar-top">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center">
                    <span class="navbar-text">
                        <i class="bi bi-calendar-check me-2"></i>
                        {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="ms-2 d-none d-md-block">
                                <div class="fw-bold" id="navbar-user-name">{{ session('user_email', 'Utilisateur') }}</div>
                                <div class="small text-muted" id="navbar-user-role">{{ $userRole ?? 'Non défini' }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ url('/profil') }}">
                                <i class="bi bi-person me-2"></i> Mon profil
                            </a>
                            <a class="dropdown-item" href="{{ url('/parametres') }}">
                                <i class="bi bi-gear me-2"></i> Paramètres
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="handleLogout()">
                                <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="container-fluid px-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1 fw-bold">@yield('title')</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        @yield('header-buttons')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4">
                        <h5 class="alert-heading">
                            <i class="bi bi-exclamation-triangle"></i> Veuillez corriger les erreurs suivantes :
                        </h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </main>

        <footer class="border-top py-3 mt-4">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span class="text-muted">© {{ date('Y') }} Gestion AVS</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="text-muted small">
                            <i class="bi bi-cpu me-1"></i>
                            Mémoire: {{ number_format(memory_get_usage() / 1024 / 1024, 2) }} MB
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Gestion de la déconnexion
        async function handleLogout() {
            const token = localStorage.getItem('jwt_token');
            
            try {
                if (token) {
                    await fetch('/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                        }
                    });
                }
            } catch (error) {
                console.error('Erreur logout:', error);
            }
            
            // Nettoyer le localStorage
            localStorage.removeItem('jwt_token');
            localStorage.removeItem('user');
            
            // Rediriger vers login
            window.location.href = '/login';
        }

        // Vérifier l'authentification au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('jwt_token');
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            
            if (!token) {
                window.location.href = '/login';
                return;
            }
            
            // Mettre à jour les infos utilisateur dans la navbar
            if (user.email) {
                const userNameEl = document.getElementById('navbar-user-name');
                const userRoleEl = document.getElementById('navbar-user-role');
                if (userNameEl) userNameEl.textContent = user.email;
                if (userRoleEl) userRoleEl.textContent = user.role || 'Non défini';
            }
        });

        // Toggle submenu
        document.querySelectorAll('.toggle-submenu').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.has-submenu');
                const submenu = parent.querySelector('.sidebar-submenu');

                document.querySelectorAll('.has-submenu').forEach(function(menu) {
                    if (menu !== parent) {
                        menu.classList.remove('active');
                        menu.querySelector('.sidebar-submenu').style.display = 'none';
                    }
                });

                if (submenu.style.display === 'none' || !submenu.style.display) {
                    submenu.style.display = 'block';
                    parent.classList.add('active');
                } else {
                    submenu.style.display = 'none';
                    parent.classList.add('active');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.has-submenu.active').forEach(function(menu) {
                const submenu = menu.querySelector('.sidebar-submenu');
                if (submenu && submenu.style.display !== 'block') {
                    submenu.style.display = 'block';
                }
            });
        });

        const sidebar = document.getElementById('sidebar');
        sidebar.addEventListener('wheel', function(e) {
            const scrollTop = sidebar.scrollTop;
            const scrollHeight = sidebar.scrollHeight;
            const clientHeight = sidebar.clientHeight;

            if (scrollHeight > clientHeight) {
                e.stopPropagation();
            }
        }, { passive: false });

        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x');
        });

        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');
                const toggleBtn = document.getElementById('sidebarCollapse');

                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                    const icon = toggleBtn.querySelector('i');
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            }
        });

        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        function updateTime() {
            const now = new Date();
            const timeElement = document.querySelector('.navbar-text');
            if (timeElement) {
                const formattedTime = now.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                timeElement.innerHTML = `<i class="bi bi-calendar-check me-2"></i>${formattedTime}`;
            }
        }

        setInterval(updateTime, 60000);
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
        });
    </script>

    @stack('scripts')
</body>

</html>
