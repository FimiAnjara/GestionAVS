<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    @php
        use App\Helpers\PermissionHelper;
        $userRole = session('user_role');
    @endphp

    <nav id="sidebar">
        <div class="sidebar-header text-center">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 100px; max-width: 300px;">
        </div>

        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <!-- DASHBOARD GLOBAL - Directeur Général uniquement -->
                @if($userRole === 'Directeur Général')
                <li>
                    <a href="{{ route('dashboard.global') }}" class="{{ request()->is('dashboard/global*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span class="menu-text">Dashboard Global</span>
                    </a>
                </li>
                @endif

                <!-- SECTION ACHATS -->
                @if(PermissionHelper::hasModuleAccess($userRole, 'achats'))
                <div class="menu-divider"></div>
                <li class="menu-title">Achats</li>

                <!-- PROFORMA FOURNISSEUR MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur'))
                <li class="has-submenu {{ request()->is('proforma-fournisseur*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-file-earmark-check"></i>
                        <span class="menu-text">Demande d'achat</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('proforma-fournisseur*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur', 'create'))
                        <a href="{{ route('proforma-fournisseur.create') }}"
                            class="submenu-item {{ request()->is('proforma-fournisseur/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma-fournisseur', 'list'))
                        <a href="{{ route('proforma-fournisseur.list') }}"
                            class="submenu-item {{ request()->is('proforma-fournisseur/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- BON DE COMMANDE MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande'))
                <li class="has-submenu {{ request()->is('bon-commande*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span class="menu-text">Bon de Commande</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('bon-commande*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande', 'create'))
                        <a href="{{ route('bon-commande.create') }}"
                            class="submenu-item {{ request()->is('bon-commande/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-commande', 'list'))
                        <a href="{{ route('bon-commande.list') }}"
                            class="submenu-item {{ request()->is('bon-commande/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- FACTURE FOURNISSEUR MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'facture-fournisseur'))
                <li class="has-submenu {{ request()->is('facture-fournisseur*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-receipt"></i>
                        <span class="menu-text">Facture Fournisseur</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('facture-fournisseur*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'facture-fournisseur', 'create'))
                        <a href="{{ route('facture-fournisseur.create') }}"
                            class="submenu-item {{ request()->is('facture-fournisseur/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'facture-fournisseur', 'list'))
                        <a href="{{ route('facture-fournisseur.list') }}"
                            class="submenu-item {{ request()->is('facture-fournisseur/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- BON DE RECEPTION MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception'))
                <li class="has-submenu {{ request()->is('bon-reception*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-file-earmark-check"></i>
                        <span class="menu-text">Bon de Réception</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('bon-reception*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception', 'create'))
                        <a href="{{ route('bon-reception.create') }}"
                            class="submenu-item {{ request()->is('bon-reception/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-reception', 'list'))
                        <a href="{{ route('bon-reception.list') }}"
                            class="submenu-item {{ request()->is('bon-reception/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                <!-- SECTION VENTES -->
                @if(PermissionHelper::hasModuleAccess($userRole, 'ventes'))
                <div class="menu-divider"></div>
                <li class="menu-title">Ventes</li>

                <!-- PROFORMA CLIENT MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'proforma'))
                <li class="has-submenu {{ request()->is('proforma*') && !request()->is('proforma-fournisseur*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="menu-text">Proforma Client</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('proforma*') && !request()->is('proforma-fournisseur*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma', 'create'))
                        <a href="{{ route('proforma.create') }}"
                            class="submenu-item {{ request()->is('proforma/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'proforma', 'list'))
                        <a href="{{ route('proforma.list') }}"
                            class="submenu-item {{ request()->is('proforma/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- COMMANDE CLIENT MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'commande'))
                <li class="has-submenu {{ request()->is('commande*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-cart"></i>
                        <span class="menu-text">Commande Client</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('commande*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'commande', 'create'))
                        <a href="{{ route('commande.create') }}"
                            class="submenu-item {{ request()->is('commande/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'commande', 'list'))
                        <a href="{{ route('commande.list') }}"
                            class="submenu-item {{ request()->is('commande/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- BON DE LIVRAISON MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'bon-livraison'))
                <li class="has-submenu {{ request()->is('bon-livraison*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-truck"></i>
                        <span class="menu-text">Bon de Livraison</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('bon-livraison*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-livraison', 'create'))
                        <a href="{{ route('bon-livraison.create') }}"
                            class="submenu-item {{ request()->is('bon-livraison/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'bon-livraison', 'list'))
                        <a href="{{ route('bon-livraison.list') }}"
                            class="submenu-item {{ request()->is('bon-livraison/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                <!-- SECTION TIERS -->
                @if(PermissionHelper::hasModuleAccess($userRole, 'tiers'))
                <div class="menu-divider"></div>

                <li class="menu-title">Organigramme</li>
                <!-- GROUPE MENU -->
                <li class="has-submenu {{ request()->is('groupe*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-diagram-3"></i>
                        <span class="menu-text">Groupes</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('groupe*') ? 'display: block;' : 'display: none;' }}">
                        <a href="{{ route('groupe.create') }}"
                            class="submenu-item {{ request()->is('groupe/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        <a href="{{ route('groupe.list') }}"
                            class="submenu-item {{ request()->is('groupe/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                    </div>
                </li>

                <!-- ENTITE MENU -->
                <li class="has-submenu {{ request()->is('entite*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-grid-3x3"></i>
                        <span class="menu-text">Entités</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('entite*') ? 'display: block;' : 'display: none;' }}">
                        <a href="{{ route('entite.create') }}"
                            class="submenu-item {{ request()->is('entite/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        <a href="{{ route('entite.list') }}"
                            class="submenu-item {{ request()->is('entite/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                    </div>
                </li>

                <!-- SITE MENU -->
                <li class="has-submenu {{ request()->is('site*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-geo-alt"></i>
                        <span class="menu-text">Sites</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('site*') ? 'display: block;' : 'display: none;' }}">
                        <a href="{{ route('site.create') }}"
                            class="submenu-item {{ request()->is('site/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        <a href="{{ route('site.list') }}"
                            class="submenu-item {{ request()->is('site/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                    </div>
                </li>

                <!-- MAGASIN MENU (Organigramme) -->
                <li class="has-submenu {{ request()->is('magasin*') && !request()->is('magasin/carte') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-shop-window"></i>
                        <span class="menu-text">Magasins</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('magasin*') && !request()->is('magasin/carte') ? 'display: block;' : 'display: none;' }}">
                        <a href="{{ route('magasin.create') }}"
                            class="submenu-item {{ request()->is('magasin/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        <a href="{{ route('magasin.list') }}"
                            class="submenu-item {{ request()->is('magasin/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                    </div>
                </li>

                <!-- CARTE MENU -->
                <li>
                    <a href="{{ route('magasin.carte') }}" class="{{ request()->is('magasin/carte') ? 'active' : '' }}">
                        <i class="bi bi-map"></i>
                        <span class="menu-text">Carte</span>
                    </a>
                </li>
                <div class="menu-divider"></div>
                <li class="menu-title">Tiers</li>

                <!-- CLIENT MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'clients'))
                <li class="has-submenu {{ request()->is('clients*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-people"></i>
                        <span class="menu-text">Clients</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('clients*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'clients', 'create'))
                        <a href="{{ route('clients.create') }}"
                            class="submenu-item {{ request()->is('clients/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'clients', 'list'))
                        <a href="{{ route('clients.list') }}"
                            class="submenu-item {{ request()->is('clients/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- FOURNISSEUR MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs'))
                <li class="has-submenu {{ request()->is('fournisseurs*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Fournisseurs</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('fournisseurs*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs', 'create'))
                        <a href="{{ route('fournisseurs.create') }}"
                            class="submenu-item {{ request()->is('fournisseurs/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'fournisseurs', 'list'))
                        <a href="{{ route('fournisseurs.list') }}"
                            class="submenu-item {{ request()->is('fournisseurs/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                <!-- SECTION PRODUITS -->
                @if(PermissionHelper::hasModuleAccess($userRole, 'produits'))
                <div class="menu-divider"></div>
                <li class="menu-title">Produits</li>

                <!-- ARTICLE MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'articles'))
                <li class="has-submenu {{ request()->is('articles*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-box"></i>
                        <span class="menu-text">Articles</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('articles*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'articles', 'create'))
                        <a href="{{ route('articles.create') }}"
                            class="submenu-item {{ request()->is('articles/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'articles', 'list'))
                        <a href="{{ route('articles.list') }}"
                            class="submenu-item {{ request()->is('articles/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- CATEGORIE MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'categories'))
                <li class="has-submenu {{ request()->is('categories*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-tag"></i>
                        <span class="menu-text">Catégories</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('categories*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'categories', 'create'))
                        <a href="{{ route('categories.create') }}"
                            class="submenu-item {{ request()->is('categories/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'categories', 'list'))
                        <a href="{{ route('categories.list') }}"
                            class="submenu-item {{ request()->is('categories/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- UNITE MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'unites'))
                <li class="has-submenu {{ request()->is('unites*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-rulers"></i>
                        <span class="menu-text">Unités</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('unites*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'unites', 'create'))
                        <a href="{{ route('unites.create') }}"
                            class="submenu-item {{ request()->is('unites/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'unites', 'list'))
                        <a href="{{ route('unites.list') }}"
                            class="submenu-item {{ request()->is('unites/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif
                @endif

                <!-- SECTION STOCK -->
                @if(PermissionHelper::hasModuleAccess($userRole, 'stock'))
                <div class="menu-divider"></div>
                <li class="menu-title">Stock</li>

                <!-- MOUVEMENT DE STOCK MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock'))
                <li class="has-submenu {{ request()->is('mvt-stock*') || request()->is('stock*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-arrow-left-right"></i>
                        <span class="menu-text">Gestion Stock</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('mvt-stock*') || request()->is('stock*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'create'))
                        <a href="{{ route('mvt-stock.create') }}"
                            class="submenu-item {{ request()->is('mvt-stock/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-stock', 'list'))
                        <a href="{{ route('mvt-stock.list') }}"
                            class="submenu-item {{ request()->is('mvt-stock/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        <a href="{{ route('stock.details') }}"
                            class="submenu-item {{ request()->is('stock/details') ? 'active' : '' }}">
                            <i class="bi bi-list-check"></i>
                            <span>Détails</span>
                        </a>
                        <a href="{{ route('stock.etat') }}"
                            class="submenu-item {{ request()->is('stock/etat') || request()->is('stock*') ? 'active' : '' }}">
                            <i class="bi bi-boxes"></i>
                            <span>Etat</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- TYPE EVALUATION STOCK MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'type-evaluation-stock'))
                <li class="has-submenu {{ request()->is('type-evaluation-stock*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-calculator"></i>
                        <span class="menu-text">Évaluation Stock</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('type-evaluation-stock*') ? 'display: block;' : 'display: none;' }}">
                        <a href="{{ route('type-evaluation-stock.create') }}"
                            class="submenu-item {{ request()->is('type-evaluation-stock/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Ajout</span>
                        </a>
                        <a href="{{ route('type-evaluation-stock.list') }}"
                            class="submenu-item {{ request()->is('type-evaluation-stock/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                    </div>
                </li>
                @endif
                @endif

                <!-- SECTION FINANCE -->
                @if(PermissionHelper::hasModuleAccess($userRole, 'finance'))
                <div class="menu-divider"></div>
                <li class="menu-title">Finance</li>

                <!-- CAISSE MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'caisse'))
                <li class="has-submenu {{ request()->is('caisse*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-safe2"></i>
                        <span class="menu-text">Caisse</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('caisse*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'caisse', 'create'))
                        <a href="{{ route('caisse.create') }}"
                            class="submenu-item {{ request()->is('caisse/create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'caisse', 'list'))
                        <a href="{{ route('caisse.list') }}"
                            class="submenu-item {{ request()->is('caisse/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                <!-- MOUVEMENTS CAISSE MENU -->
                @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse'))
                <li class="has-submenu {{ request()->is('mvt-caisse*') ? 'active' : '' }}">
                    <a href="#" class="toggle-submenu">
                        <i class="bi bi-arrow-left-right"></i>
                        <span class="menu-text">Mouvements</span>
                    </a>
                    <div class="sidebar-submenu"
                        style="{{ request()->is('mvt-caisse*') ? 'display: block;' : 'display: none;' }}">
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse', 'create'))
                        <a href="{{ route('mvt-caisse.create') }}"
                            class="submenu-item {{ request()->is('mvt-caisse/create') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Saisie</span>
                        </a>
                        @endif
                        @if(PermissionHelper::hasMenuAccess($userRole, 'mvt-caisse', 'list'))
                        <a href="{{ route('mvt-caisse.list') }}"
                            class="submenu-item {{ request()->is('mvt-caisse/list') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Liste</span>
                        </a>
                        <a href="{{ route('mvt-caisse.etat') }}"
                            class="submenu-item {{ request()->is('mvt-caisse/etat*') ? 'active' : '' }}">
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
                    <a href="{{ url('/aide') }}" class="{{ request()->is('aide*') ? 'active' : '' }}"
                        title="Aide et support">
                        <i class="bi bi-question-circle"></i>
                        <span class="menu-text">Aide</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="event.preventDefault(); logout();" title="Se déconnecter">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="menu-text">Déconnexion</span>
                    </a>
                </li>
            </ul>
            <div>
                <p style="margin-bottom: 2px; font-weight: 600;">GestionAVS</p>
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
                <div class="d-flex align-items-center gap-3">
                    <!-- Affichage du magasin actuel -->
                    @if(session('user_magasin_nom'))
                    <div class="d-none d-md-flex align-items-center text-muted">
                        <i class="bi bi-shop me-1"></i>
                        <small>{{ session('user_magasin_nom') }}</small>
                    </div>
                    @endif
                    
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="ms-2 d-none d-md-block">
                                <div class="fw-bold">{{ session('user_email', 'Utilisateur') }}</div>
                                <div class="small text-muted">{{ $userRole ?? 'Non défini' }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">
                                <small class="text-muted">Connecté en tant que</small>
                                <div class="fw-bold">{{ session('user_email', 'Utilisateur') }}</div>
                                <span class="badge bg-primary">{{ $userRole ?? 'Non défini' }}</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header">
                                <small class="text-muted">
                                    <i class="bi bi-building me-1"></i> {{ session('user_entite_nom', 'Non défini') }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i> {{ session('user_site_nom', 'Non défini') }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-shop me-1"></i> {{ session('user_magasin_nom', 'Non défini') }}
                                </small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/profil') }}">
                                <i class="bi bi-person me-2"></i> Mon profil
                            </a>
                            <a class="dropdown-item" href="{{ url('/parametres') }}">
                                <i class="bi bi-gear me-2"></i> Paramètres
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); logout();">
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
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                        class="text-decoration-none">Accueil</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        @yield('header-buttons')
                    </div>
                </div>
                @yield('content')

                <!-- Affichage des erreurs de validation -->
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

        <!-- Pied de page -->
        <footer class="border-top py-3 mt-4">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span class="text-muted">
                            © {{ date('Y') }} GestionAVS
                        </span>
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

    @include('partials.delete-modal')

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts Sidebar -->
    <script>
        // Fonction de déconnexion
        function logout() {
            localStorage.removeItem('jwt_token');
            fetch('/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).finally(() => {
                window.location.href = '/login';
            });
        }

        // Vérifier l'authentification
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('jwt_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
        });

        // Toggle submenu
        document.querySelectorAll('.toggle-submenu').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.has-submenu');
                const submenu = parent.querySelector('.sidebar-submenu');

                // Close other submenus
                document.querySelectorAll('.has-submenu').forEach(function(menu) {
                    if (menu !== parent) {
                        menu.classList.remove('active');
                        menu.querySelector('.sidebar-submenu').style.display = 'none';
                    }
                });

                // Toggle current submenu
                if (submenu.style.display === 'none' || !submenu.style.display) {
                    submenu.style.display = 'block';
                    parent.classList.add('active');
                } else {
                    submenu.style.display = 'none';
                    parent.classList.remove('active');
                }
            });
        });

        // On page load, open active submenus
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.has-submenu.active').forEach(function(menu) {
                const submenu = menu.querySelector('.sidebar-submenu');
                if (submenu && submenu.style.display !== 'block') {
                    submenu.style.display = 'block';
                }
            });
        });

        // Prevent scroll propagation from sidebar to main
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.addEventListener('wheel', function(e) {
                const scrollTop = sidebar.scrollTop;
                const scrollHeight = sidebar.scrollHeight;
                const clientHeight = sidebar.clientHeight;

                if (scrollHeight > clientHeight) {
                    e.stopPropagation();
                }
            }, { passive: false });
        }

        // Toggle sidebar on mobile
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x');
        });

        // Close sidebar when clicking outside on mobile
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

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Update time in real-time
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

        // Update time every minute
        setInterval(updateTime, 60000);

        // Run on load
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();

            // Unified Delete Confirmation Modal Handler
            const deleteModal = document.getElementById('deleteConfirmModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const url = button.getAttribute('data-bs-url');
                    const itemName = button.getAttribute('data-bs-item');

                    const form = deleteModal.querySelector('#deleteConfirmForm');
                    const nameContainer = deleteModal.querySelector('#deleteItemName');

                    if (form) form.action = url;
                    if (nameContainer) nameContainer.textContent = itemName || 'cet élément';
                });
            }
        });
    </script>

    <!-- Additional scripts -->
    @stack('scripts')
</body>

</html>
