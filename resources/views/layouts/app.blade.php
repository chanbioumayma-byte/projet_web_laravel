<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Accueil') — {{ config('app.name', 'ShopLaravel') }}</title>

    {{-- Bootstrap 5 CSS via CDN (pas besoin de Node.js) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --dark: #0f172a;
            --accent: #f59e0b;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        /* Navbar */
        .navbar-main {
            background: var(--dark);
            box-shadow: 0 2px 12px rgba(0, 0, 0, .3);
        }

        .navbar-main .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -.5px;
        }

        .navbar-main .nav-link {
            color: #cbd5e1 !important;
            font-weight: 500;
            transition: color .2s;
        }

        .navbar-main .nav-link:hover,
        .navbar-main .nav-link.active {
            color: var(--accent) !important;
        }

        .navbar-main .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .15);
        }

        /* Panier badge */
        .cart-badge {
            background: #ef4444;
            color: white;
            font-size: .65rem;
            padding: 2px 6px;
            border-radius: 99px;
            vertical-align: super;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, .07);
            transition: box-shadow .2s, transform .2s;
        }

        .card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, .13);
            transform: translateY(-2px);
        }

        .card-img-top {
            border-radius: 14px 14px 0 0;
        }

        /* Boutons */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-accent {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* Alertes flottantes */
        .alerts-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            width: 340px;
        }

        /* Product image placeholder */
        .img-placeholder {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #94a3b8;
        }

        /* Stars */
        .stars {
            color: var(--accent);
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: #94a3b8;
        }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            border-radius: 18px;
            color: white;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar navbar-expand-lg navbar-main sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-bag-heart-fill text-warning"></i> ShopLaravel
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                            href="{{ route('products.index') }}">
                            <i class="bi bi-grid-3x3-gap"></i> Catalogue
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-lg-center gap-1">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm ms-2 px-3" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> S'inscrire
                        </a>
                    </li>
                    @else
                    {{-- Panier --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}"
                            href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3"></i>
                            @php $cartCount = count(session('cart', [])); @endphp
                            @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                            Panier
                        </a>
                    </li>

                    {{-- Commandes --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                            href="{{ route('orders.index') }}">
                            <i class="bi bi-bag-check"></i> Commandes
                        </a>
                    </li>

                    {{-- Dropdown utilisateur --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <span class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center"
                                style="width:32px;height:32px;font-size:.8rem;font-weight:700;color:#1e3a5f;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li class="px-3 py-2 text-muted small">{{ Auth::user()->email }}</li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear me-2"></i> Mon profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('products.create') }}">
                                    <i class="bi bi-plus-square me-2"></i> Vendre un produit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-clock-history me-2"></i> Mes commandes
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- ===== ALERTES FLOTTANTES ===== --}}
    <div class="alerts-container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <main class="container py-5">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">
                        <i class="bi bi-bag-heart-fill text-warning"></i> ShopLaravel
                    </h5>
                    <p class="small">Plateforme e-commerce développée avec Laravel dans le cadre du DS2 — Programmation Web 2.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-white mb-3">Navigation</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Accueil</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-secondary text-decoration-none">Catalogue</a></li>
                        @auth
                        <li><a href="{{ route('cart.index') }}" class="text-secondary text-decoration-none">Mon panier</a></li>
                        <li><a href="{{ route('orders.index') }}" class="text-secondary text-decoration-none">Mes commandes</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-white mb-3">Technologies</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-danger">Laravel</span>
                        <span class="badge bg-primary">PHP</span>
                        <span class="badge bg-secondary">MySQL</span>
                        <span class="badge bg-dark border">Blade</span>
                        <span class="badge bg-info text-dark">Bootstrap 5</span>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center small mb-0">© {{ date('Y') }} ShopLaravel — DS2 Programmation Web 2</p>
        </div>
    </footer>

    {{-- Bootstrap JS via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Auto-fermeture des alertes après 4s --}}
    <script>
        setTimeout(function() {
            document.querySelectorAll('.alerts-container .alert').forEach(function(el) {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            });
        }, 4000);
    </script>

    @stack('scripts')
</body>

</html>