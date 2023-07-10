<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlyCut Landing Page — #2 PSD To HTML Coversion</title>

    <!-- Favicon -->

    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png" />
    <link rel="manifest" href="images/favicon/site.webmanifest" />
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5" />
    <link rel="shortcut icon" href="images/favicon/favicon.ico" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="msapplication-config" content="images/favicon/browserconfig.xml" />
    <meta name="theme-color" content="#ffffff" />

    <!-- End: Favicon -->

    <!-- Required Social Preview MetaTags -->

    <!-- Primary Meta Tags -->
    <meta name="title" content="FlyCut Landing Page — #2 PSD To HTML Coversion" />
    <meta name="description" content="FlyCut is an open source single page App Landing page website PSD to HTML template. This is my #2 PSD To HTML Conversion. Best of Luck To Me ☻" />

    <!-- LATO — Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet" />
    <!-- End: LATO — Google Font -->

    <!-- Main CSS File -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/grid.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" >
    <!-- End: Main CSS File -->
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <!-- Preloader End -->
    <!-- Header Section -->
    <header id="home">
        <nav>
            <div class="navbar-logo">
                <a href="#" class="logo">FlyCut</a>
            </div>
            <div id="navbar">
                <ul>
                    <li><a href="#home">HOME</a></li>
                    <li><a href="#features">FEATURES</a></li>
                    <li><a href="#download">DOWNLOAD</a></li>
                </ul>
            </div>
            <div id="mobileNav">
                <span onclick="openNav()">&#9776;</span>
            </div>
            <div id="myNav" class="mobileNavOverlay">
                <div class="overlay-content">
                    <a href="javascript:void()" class="close-btn" onclick="closeNav()">&times;</a>
                    <a href="#home" onclick="closeNav()">HOME</a>
                    <a href="#features" onclick="closeNav()">FEATURES</a>
                    <a href="#testimonial" onclick="closeNav()">TESTIMONIAL</a>
                    <a href="#howto" onclick="closeNav()">HOW TO</a>
                    <a href="#download" onclick="closeNav()">DOWNLOAD</a>
                </div>
            </div>
        </nav>
        <div id="hero">
            <div class="row">
                <div class="col span_1_of_2">
                    <div class="hero-description">
                        <h1>
                            Simple, fast <br />
                            & stylish.
                        </h1>
                        <p>
                            <span class="bold-text">FlyCut</span>, your way to get the best haircut
                        </p>

                        <div>
                            <a href="https://expo.dev/artifacts/eas/xjwJgTyboG8rXqFoV2sAK7.apk" class="btn" style="margin-right: 1.6rem;"><span class="download-btn">Download Now</span></a>
                            <a href="https://expo.dev/artifacts/eas/rkPUiQHTE2WGHKPW8ipnr1.apk" class="btn" style="margin-right: 1.6rem;"><span class="download-btn">Barber's app</span></a>
                        </div>
                    </div>
                </div>
                <div class="col span_1_of_2">
                    <img class="hero-img" src="images2/1.png" alt="" />
                </div>
            </div>
        </div>
        <div class="shape-divider">
            <div class="custom-shape-divider-bottom-1603385849">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M1200 120L0 16.48 0 0 1200 0 1200 120z" class="shape-fill"></path>
                </svg>
            </div>
        </div>
        <button onclick="topFunction()" id="scrollUp" title="Go to top">
            <svg width="1.5rem" height="1.5rem" viewBox="0 0 16 16" class="bi bi-arrow-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
            </svg>
        </button>
    </header>
    <!-- End: Header Section -->

    <!-- Features Section -->
    <section id="features">
        <div class="row">
            <div class="col span_1_of_2">
                <div class="features-images">
                    <img class="features_1" src="images/features1.png" alt="" />
                </div>
            </div>
            <div class="col span_1_of_2">
                <div class="section-description features-description col1">
                    <h2 class="stylish_heading">
                        Find Nearby <br /> Barbers <span class="red_dot">.</span>
                    </h2>
                    <p class="little-description">
                        Find the nearest barbershop to you, view their location on the map, view their ratings and provided services, and make a reservation with them!
                    </p>
                </div>
            </div>
        </div>
        <div class="row second-features-row">
            <div class="col span_1_of_2">
                <div class="section-description features-description">
                    <h2 class="stylish_heading">
                        Manage your barbershop <span class="red_dot">.</span>
                    </h2>
                    <p class="little-description">
                        Add your barbershop to our system, put it on the map, add your barbers, customize your services and get more customers to know you!
                    </p>
                </div>
            </div>
            <div class="col span_1_of_2">
                <div class="features-images">
                    <img class="features_3" src="images/features2.png" alt="" />
                </div>
            </div>
        </div>
    </section>
    <!-- End: Features Section -->

    <!-- Download The App Section -->
    <section id="download">
        <div class="row">
            <img class="app-logo" src="images/app_logo.png" alt="" />
            <h2 class="stylish_heading">
                Download the app <span class="red_dot">.</span>
            </h2>
            <p class="">
                App coming soon on Google Play
            </p>

            <a href="javascript:void()">
                <img src="images/appstore.png" alt="" /></a>
        </div>
    </section>
    <!-- End: Download The App Section -->

    <!-- Footer Section -->
    <section id="footer">
        <div class="links">
            <ul>
                <li><a href="#home">HOME</a></li>
                <li><a href="#features">FEATURES</a></li>
                <li><a href="#download">DOWNLOAD</a></li>
            </ul>
        </div>
    </section>
    <!-- End: Footer Section -->

    <!-- Required JavaScript Files & CDN -->
    <!-- JQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Main JS Files -->
    <script src="resources/js/essential.js"></script>
    <script src="resources/js/jquery.nice-select.min.js"></script>
    <script src="resources/js/main.js"></script>
    <script src="{{ asset('js/essential.js') }}" defer></script>
    <script src="{{ asset('js/jquery.nice-select.min.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
</body>

</html>