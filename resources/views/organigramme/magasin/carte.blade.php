@extends('layouts.app')

@section('title', 'Carte des Magasins')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css" />
    <style>
        #map {
            height: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .map-legend {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
        }

        .map-legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-right: 8px;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        .marker-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .popup-content {
            font-size: 14px;
            min-width: 200px;
        }

        .popup-content strong {
            display: block;
            font-size: 16px;
            color: #1a73e8;
            margin-bottom: 8px;
        }

        .popup-item {
            margin: 5px 0;
            line-height: 1.6;
        }

        .popup-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            color: white;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .leaflet-popup-content {
            width: auto !important;
            padding: 10px;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            min-width: 250px;
        }

        .summary-card {
            background: linear-gradient(135deg, #1a73e8 0%, #0052cc 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card h6 {
            font-size: 12px;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .summary-card .number {
            font-size: 28px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <!-- Résumé des Magasins et Valeur Stock -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <h6><i class="bi bi-shop me-2"></i>Total des Magasins</h6>
                <div class="number">{{ $totalMagasins }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <h6><i class="bi bi-geo-alt me-2"></i>Magasins Affichés</h6>
                <div class="number">{{ $magasinsAffiches }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="summary-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <h6><i class="bi bi-currency-dollar me-2"></i>Valeur Totale du Stock</h6>
                <div class="number">{{ number_format($totalStockGlobal, 0, ',', ' ') }} Ar</div>
            </div>
        </div>
    </div>

    <!-- Filtres de Recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('magasin.carte') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Filtre Groupe -->
                    <div class="col-lg-3 col-md-6">
                        <label for="id_groupe" class="form-label">Groupe</label>
                        <select class="form-select" id="id_groupe" name="id_groupe">
                            <option value="">Tous les groupes</option>
                            @foreach($groupes as $groupe)
                                <option value="{{ $groupe->id_groupe }}" {{ request('id_groupe') == $groupe->id_groupe ? 'selected' : '' }}>
                                    {{ $groupe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Entité -->
                    <div class="col-lg-3 col-md-6">
                        <label for="id_entite" class="form-label">Entité</label>
                        <select class="form-select" id="id_entite" name="id_entite">
                            <option value="">Toutes les entités</option>
                            @foreach($entites as $entite)
                                <option value="{{ $entite->id_entite }}" 
                                    data-groupe="{{ $entite->id_groupe }}"
                                    data-couleur="{{ $entite->code_couleur }}"
                                    {{ request('id_entite') == $entite->id_entite ? 'selected' : '' }}>
                                    {{ $entite->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Site -->
                    <div class="col-lg-3 col-md-6">
                        <label for="id_site" class="form-label">Site</label>
                        <select class="form-select" id="id_site" name="id_site">
                            <option value="">Tous les sites</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id_site }}" 
                                    data-entite="{{ $site->id_entite }}"
                                    {{ request('id_site') == $site->id_site ? 'selected' : '' }}>
                                    {{ $site->localisation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Nom -->
                    <div class="col-lg-3 col-md-6">
                        <label for="nom" class="form-label">Nom du Magasin</label>
                        <input type="text" class="form-control" id="nom" name="nom"
                            placeholder="Chercher par nom..." value="{{ request('nom') }}">
                    </div>

                    <!-- Mode d'affichage -->
                    <div class="col-lg-6">
                        <label class="form-label">Mode d'affichage</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="view_mode" id="mode_magasin" value="magasin" checked>
                            <label class="btn btn-outline-primary" for="mode_magasin">
                                <i class="bi bi-shop me-2"></i>Vue Magasins (Points)
                            </label>

                            <input type="radio" class="btn-check" name="view_mode" id="mode_site" value="site">
                            <label class="btn btn-outline-primary" for="mode_site">
                                <i class="bi bi-pentagon me-2"></i>Vue Sites (Polygones)
                            </label>
                        </div>
                    </div>
                    
                    <!-- Option Évaluation Stock -->
                    <div class="col-lg-6">
                        <label class="form-label">Options d'affichage</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_stock_value" checked>
                            <label class="form-check-label" for="show_stock_value">
                                <i class="bi bi-currency-dollar me-1"></i>Afficher l'évaluation de stock
                            </label>
                        </div>
                        <small class="text-muted">Affiche la valeur du stock dans les popups et marque le magasin avec la plus grande valeur</small>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Rechercher
                            </button>
                            <a href="{{ route('magasin.carte') }}" class="btn btn-secondary" title="Réinitialiser">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Carte -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-geo-alt me-2"></i>Carte des Magasins
            </h6>
        </div>
        <div class="card-body p-3">
            <div id="map"></div>
        </div>
    </div>

    <!-- Classement par Valeur de Stock (déplacé en bas) -->
    <div class="card border-0 shadow-sm mt-4" id="stock-stats-section" style="display: block;">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-trophy me-2"></i>Classement par Valeur de Stock
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Top Groupe -->
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2 mb-1 text-muted small">Top Groupe</h6>
                        @if($topGroupe)
                            <strong class="d-block">{{ $topGroupe['nom'] }}</strong>
                            <span class="badge bg-primary mt-1">{{ number_format($topGroupe['valeur'], 0, ',', ' ') }} Ar</span>
                        @else
                            <span class="text-muted small">Aucune donnée</span>
                        @endif
                    </div>
                </div>
                
                <!-- Top Entité -->
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <i class="bi bi-diagram-3 text-success" style="font-size: 2rem;"></i>
                        <h6 class="mt-2 mb-1 text-muted small">Top Entité</h6>
                        @if($topEntite)
                            <strong class="d-block">{{ $topEntite['nom'] }}</strong>
                            <span class="badge bg-success mt-1">{{ number_format($topEntite['valeur'], 0, ',', ' ') }} Ar</span>
                        @else
                            <span class="text-muted small">Aucune donnée</span>
                        @endif
                    </div>
                </div>
                
                <!-- Top Site -->
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <i class="bi bi-geo text-warning" style="font-size: 2rem;"></i>
                        <h6 class="mt-2 mb-1 text-muted small">Top Site</h6>
                        @if($topSite)
                            <strong class="d-block">{{ $topSite['nom'] }}</strong>
                            <span class="badge bg-warning text-dark mt-1">{{ number_format($topSite['valeur'], 0, ',', ' ') }} Ar</span>
                        @else
                            <span class="text-muted small">Aucune donnée</span>
                        @endif
                    </div>
                </div>
                
                <!-- Top Magasin -->
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <i class="bi bi-shop text-danger" style="font-size: 2rem;"></i>
                        <h6 class="mt-2 mb-1 text-muted small">Top Magasin</h6>
                        @if($topMagasin)
                            <strong class="d-block">{{ $topMagasin['nom'] }}</strong>
                            <span class="badge bg-danger mt-1">{{ number_format($topMagasin['valeur_stock'], 0, ',', ' ') }} Ar</span>
                        @else
                            <span class="text-muted small">Aucune donnée</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Détails des Agrégations -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <h6 class="small text-muted mb-2"><i class="bi bi-building me-1"></i>Par Groupe</h6>
                    <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                        @forelse($stockParGroupe as $id => $data)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                                <span class="small">{{ $data['nom'] }}</span>
                                <span class="badge bg-light text-dark">{{ number_format($data['valeur'], 0, ',', ' ') }} Ar</span>
                            </div>
                        @empty
                            <div class="text-muted small">Aucune donnée</div>
                        @endforelse
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h6 class="small text-muted mb-2"><i class="bi bi-diagram-3 me-1"></i>Par Entité</h6>
                    <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                        @forelse($stockParEntite as $id => $data)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                                <span class="small">{{ $data['nom'] }}</span>
                                <span class="badge bg-light text-dark">{{ number_format($data['valeur'], 0, ',', ' ') }} Ar</span>
                            </div>
                        @empty
                            <div class="text-muted small">Aucune donnée</div>
                        @endforelse
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h6 class="small text-muted mb-2"><i class="bi bi-geo me-1"></i>Par Site</h6>
                    <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                        @forelse($stockParSite as $id => $data)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                                <span class="small">{{ $data['nom'] }}</span>
                                <span class="badge bg-light text-dark">{{ number_format($data['valeur'], 0, ',', ' ') }} Ar</span>
                            </div>
                        @empty
                            <div class="text-muted small">Aucune donnée</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const defaultCenter = [-18.8792, 47.5079];
            const locations = @json($locations);
            
            const map = L.map('map', {
                zoom: 6,
                center: defaultCenter
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            const markersGroup = L.featureGroup();
            const polygonsGroup = L.featureGroup();
            
            /**
             * Algorithme de calcul de l'enveloppe convexe (Convex Hull)
             * Algorithme de Monotone Chain (Andrew) - O(n log n)
             */
            function getConvexHull(points) {
                if (points.length <= 2) return points;

                // Trier par x (longitude) puis y (latitude)
                points.sort((a, b) => a[1] !== b[1] ? a[1] - b[1] : a[0] - b[0]);

                const crossProduct = (o, a, b) => (a[1] - o[1]) * (b[0] - o[0]) - (a[0] - o[0]) * (b[1] - o[1]);

                // Coquille inférieure
                const lower = [];
                for (const p of points) {
                    while (lower.length >= 2 && crossProduct(lower[lower.length - 2], lower[lower.length - 1], p) <= 0) {
                        lower.pop();
                    }
                    lower.push(p);
                }

                // Coquille supérieure
                const upper = [];
                for (let i = points.length - 1; i >= 0; i--) {
                    const p = points[i];
                    while (upper.length >= 2 && crossProduct(upper[upper.length - 2], upper[upper.length - 1], p) <= 0) {
                        upper.pop();
                    }
                    upper.push(p);
                }

                upper.pop();
                lower.pop();
                return lower.concat(upper);
            }

            function renderMarkers() {
                markersGroup.clearLayers();
                const showStockValue = document.getElementById('show_stock_value').checked;
                const topMagasinId = @json($topMagasin ? $topMagasin['id'] : null);
                
                locations.forEach(location => {
                    if (location.latitude && location.longitude) {
                        const color = location.code_couleur || '#1a73e8';
                        const isTopMagasin = showStockValue && location.id === topMagasinId;
                        
                        // Marqueur principal
                        const markerHtml = `<div class="marker-icon" style="background-color: ${color};"><i class="bi bi-shop"></i></div>`;

                        const customMarker = L.divIcon({
                            html: markerHtml,
                            className: 'custom-marker',
                            iconSize: [34, 34],
                            iconAnchor: [17, 34],
                            popupAnchor: [0, -34]
                        });

                        let popupContent = `
                            <div class="popup-content">
                                <strong><a href="{{ url("magasin") }}/${location.id}">${location.nom}</a></strong>
                                ${location.entite ? `<span class="popup-badge" style="background-color: ${color};">${location.entite}</span>` : ''}
                                ${isTopMagasin ? '<div class="popup-item"><span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill me-1"></i>Top Magasin</span></div>' : ''}
                                <div class="popup-item"><i class="bi bi-diagram-3 me-1"></i><small>${location.groupe || '-'}</small></div>
                                <div class="popup-item"><i class="bi bi-geo-alt me-1"></i><small>${location.site_nom || '-'}</small></div>`;
                        
                        if (showStockValue && location.valeur_stock !== undefined) {
                            popupContent += `<div class="popup-item mt-3 pt-2 border-top">
                                <span class="text-muted d-block small mb-1">Valeur dans le stock :</span>
                                <strong class="text-success fs-5">${location.valeur_stock.toLocaleString('fr-FR')} Ar</strong>
                            </div>`;
                        }
                        
                        popupContent += `
                                <div class="popup-item mt-2">
                                    <a href="https://maps.google.com/?q=${location.latitude},${location.longitude}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-link-45deg"></i> Google Maps
                                    </a>
                                </div>
                            </div>`;

                        const marker = L.marker([location.latitude, location.longitude], { icon: customMarker })
                            .bindPopup(popupContent);
                        
                        markersGroup.addLayer(marker);
                        
                        // Ajouter une icône couronne à côté du marqueur pour le top magasin
                        if (isTopMagasin) {
                            const crownHtml = `<div style="text-align: center; font-size: 20px; color: #ffc107; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"><i class="bi bi-trophy-fill"></i></div>`;
                            const crownIcon = L.divIcon({
                                html: crownHtml,
                                className: 'crown-marker',
                                iconSize: [24, 24],
                                iconAnchor: [-10, 20]  // Décalé vers la gauche et légèrement en haut
                            });
                            const crownMarker = L.marker([location.latitude, location.longitude], { icon: crownIcon });
                            markersGroup.addLayer(crownMarker);
                        }
                    }
                });
            }

            function renderPolygons() {
                polygonsGroup.clearLayers();
                
                // Grouper par site
                const sites = {};
                const showStockValue = document.getElementById('show_stock_value').checked;
                const topSiteId = @json($topSite ? $topSite['id'] : null);

                locations.forEach(loc => {
                    if (!loc.id_site) return;
                    if (!sites[loc.id_site]) {
                        sites[loc.id_site] = {
                            id: loc.id_site,
                            nom: loc.site_nom,
                            entite: loc.entite,
                            couleur: loc.code_couleur || '#1a73e8',
                            points: [],
                            valeur_totale: 0
                        };
                    }
                    sites[loc.id_site].points.push([loc.latitude, loc.longitude]);
                    sites[loc.id_site].valeur_totale += loc.valeur_stock || 0;
                });

                for (const siteId in sites) {
                    const site = sites[siteId];
                    if (site.points.length === 0) continue;

                    let layer;
                    if (site.points.length === 1) {
                        // Un seul magasin : un cercle ou un marqueur special
                        layer = L.circle(site.points[0], {
                            radius: 500,
                            color: site.couleur,
                            fillColor: site.couleur,
                            fillOpacity: 0.4
                        });
                    } else if (site.points.length === 2) {
                        // Deux magasins : une ligne epaisse
                        layer = L.polyline(site.points, {
                            color: site.couleur,
                            weight: 8,
                            opacity: 0.6
                        });
                    } else {
                        // Plus de deux : Polygone Convex Hull
                        const hullPoints = getConvexHull(site.points);
                        layer = L.polygon(hullPoints, {
                            color: site.couleur,
                            fillColor: site.couleur,
                            fillOpacity: 0.35,
                            weight: 3
                        });
                    }

                    const isTopSite = showStockValue && site.id === topSiteId;
                    
                    let popupContent = `
                        <div class="popup-content">
                            <strong style="color: ${site.couleur}">${site.nom}</strong>
                            <span class="popup-badge" style="background-color: ${site.couleur}">${site.entite}</span>
                            ${isTopSite ? '<div class="popup-item mb-2"><span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill me-1"></i>Top Site</span></div>' : ''}
                            <div class="popup-item"><i class="bi bi-shop me-1"></i>${site.points.length} magasin(s)</div>`;
                    
                    if (showStockValue) {
                        popupContent += `<div class="popup-item mt-3 pt-2 border-top">
                            <span class="text-muted d-block small mb-1">Valeur dans le stock :</span>
                            <strong class="text-success fs-5">${site.valeur_totale.toLocaleString('fr-FR')} Ar</strong>
                        </div>`;
                    }
                    
                    popupContent += `</div>`;
                    layer.bindPopup(popupContent);

                    polygonsGroup.addLayer(layer);

                    // Ajouter l'icône trophée pour le top site
                    if (isTopSite && site.points.length > 0) {
                        const crownHtml = `<div style="text-align: center; font-size: 20px; color: #ffc107; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"><i class="bi bi-trophy-fill"></i></div>`;
                        const crownIcon = L.divIcon({
                            html: crownHtml,
                            className: 'crown-marker',
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });
                        const crownMarker = L.marker(site.points[0], { icon: crownIcon });
                        polygonsGroup.addLayer(crownMarker);
                    }
                }
            }

            function updateView() {
                const mode = document.querySelector('input[name="view_mode"]:checked').value;
                
                if (mode === 'magasin') {
                    map.removeLayer(polygonsGroup);
                    markersGroup.addTo(map);
                    if (markersGroup.getLayers().length > 0) {
                        map.fitBounds(markersGroup.getBounds().pad(0.1));
                    }
                } else {
                    map.removeLayer(markersGroup);
                    polygonsGroup.addTo(map);
                    if (polygonsGroup.getLayers().length > 0) {
                        map.fitBounds(polygonsGroup.getBounds().pad(0.1));
                    }
                }
            }

            // Initialisation
            renderMarkers();
            renderPolygons();
            updateView();

            // Event Listeners
            document.querySelectorAll('input[name="view_mode"]').forEach(radio => {
                radio.addEventListener('change', updateView);
            });
            
            // Event listener pour le toggle d'évaluation de stock
            document.getElementById('show_stock_value').addEventListener('change', function() {
                // Re-render les marqueurs et polygones avec ou sans valeurs de stock
                renderMarkers();
                renderPolygons();
                
                // Afficher/masquer la section de classement
                const statsSection = document.getElementById('stock-stats-section');
                if (statsSection) {
                    statsSection.style.display = this.checked ? 'block' : 'none';
                }
            });

            // Filtre Select dynamiques (existant)
            const groupeSelect = document.getElementById('id_groupe');
            const entiteSelect = document.getElementById('id_entite');
            const siteSelect = document.getElementById('id_site');

            function filterSelectOptions(select, filterAttr, filterValue) {
                const options = select.querySelectorAll('option');
                const currentValue = select.value;
                let hasValidSelection = false;

                options.forEach(option => {
                    if (option.value === '') {
                        option.style.display = '';
                        return;
                    }
                    
                    const attrValue = option.getAttribute(filterAttr);
                    if (!filterValue || attrValue === filterValue) {
                        option.style.display = '';
                        if (option.value === currentValue) hasValidSelection = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (!hasValidSelection && currentValue !== '') {
                    select.value = '';
                }
            }

            groupeSelect.addEventListener('change', function() {
                filterSelectOptions(entiteSelect, 'data-groupe', this.value);
                entiteSelect.dispatchEvent(new Event('change'));
            });

            entiteSelect.addEventListener('change', function() {
                filterSelectOptions(siteSelect, 'data-entite', this.value);
            });

            if (groupeSelect.value) filterSelectOptions(entiteSelect, 'data-groupe', groupeSelect.value);
            if (entiteSelect.value) filterSelectOptions(siteSelect, 'data-entite', entiteSelect.value);

            // Mise à jour de la légende
            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'map-legend');
                const entiteCouleurs = {};
                locations.forEach(l => { if(l.entite) entiteCouleurs[l.entite] = l.code_couleur; });

                let legendHtml = '<strong class="d-block mb-2">Légende par Entité</strong>';
                for (const [entite, couleur] of Object.entries(entiteCouleurs)) {
                    legendHtml += `<div class="map-legend-item"><div class="legend-color" style="background-color: ${couleur};"></div><small>${entite}</small></div>`;
                }
                div.innerHTML = legendHtml || '<small>Aucune donnée</small>';
                return div;
            };
            legend.addTo(map);
        });
    </script>
@endpush
