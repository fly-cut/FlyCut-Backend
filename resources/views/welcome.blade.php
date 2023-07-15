<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlyCut</title>

    <!-- Favicon -->

    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="images/favicon/apple-touch-icon.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="images/favicon/favicon-32x32.png"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />

    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="images/favicon/favicon-16x16.png"
    />
    <link rel="manifest" href="images/favicon/site.webmanifest" />
    <link
      rel="mask-icon"
      href="images/favicon/safari-pinned-tab.svg"
      color="#5bbad5"
    />
    <link rel="shortcut icon" href="images/favicon/favicon.ico" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta
      name="msapplication-config"
      content="images/favicon/browserconfig.xml"
    />
    <meta name="theme-color" content="#ffffff" />

    <!-- End: Favicon -->

    <!-- Required Social Preview MetaTags -->

    <!-- Primary Meta Tags -->
    <meta
      name="title"
      content="FlyCut"
    />
    <meta
      name="description"
      content="FlyCut is an open source single page App Landing page website PSD to HTML template."
    />

    <!-- LATO — Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400&display=swap"
      rel="stylesheet"
    />
    <!-- End: LATO — Google Font -->

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('css/grid.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
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
            <li><a href="#contact_us">CONTACT US</a></li>
            <li><a href="#demo">DEMO</a></li>
            <li><a href="#features">FEATURES</a></li>
            <li><a href="#download">DOWNLOAD</a></li>
          </ul>
        </div>
        <div id="mobileNav">
          <span onclick="openNav()">&#9776;</span>
        </div>
        <div id="myNav" class="mobileNavOverlay">
          <div class="overlay-content">
            <a href="javascript:void()" class="close-btn" onclick="closeNav()"
              >&times;</a
            >
            <a href="#home" onclick="closeNav()">HOME</a>
            <a href="#contact_us" onclick="closeNav()">CONTACT US</a>
            <a href="#demo" onclick="closeNav()">DEMO</a>
            <a href="#features" onclick="closeNav()">FEATURES</a>
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
                <span class="bold-text">FlyCut</span>, your way to get the best
                haircut
              </p>

              <div>
                <a
                  href="https://expo.dev/artifacts/eas/xjwJgTyboG8rXqFoV2sAK7.apk"
                  class="btn"
                  style="margin-right: 1.6rem"
                  ><span class="download-btn">Download Now</span></a
                >
                <a
                  href="https://expo.dev/artifacts/eas/rkPUiQHTE2WGHKPW8ipnr1.apk"
                  class="btn"
                  style="margin-right: 1.6rem"
                  ><span class="download-btn">Barber's app</span></a
                >
              </div>
            </div>
          </div>
          <div class="col span_1_of_2">
            <img
              class="hero-img"
              src="images2/1.png"
              alt=""
              style="margin-top: -5rem"
            />
          </div>
        </div>
      </div>
      <div class="shape-divider">
        <div class="custom-shape-divider-bottom-1603385849">
          <svg
            data-name="Layer 1"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1200 120"
            preserveAspectRatio="none"
          >
            <path
              d="M1200 120L0 16.48 0 0 1200 0 1200 120z"
              class="shape-fill"
            ></path>
          </svg>
        </div>
      </div>
      <button onclick="topFunction()" id="scrollUp" title="Go to top">
        <svg
          width="1.5rem"
          height="1.5rem"
          viewBox="0 0 16 16"
          class="bi bi-arrow-up"
          fill="currentColor"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"
          />
        </svg>
      </button>
    </header>
    <!-- End: Header Section -->

    <!-- Contact Us Section -->
    <section id="contact_us">
      <h2 class="stylish_heading" style="margin: 4rem 0">
        Team Members <span class="red_dot">.</span>
      </h2>
      <div class="grid-container">
        <div class="contact">
          <img src="images2/adel.jpg" alt="Person 1" />
          <p><strong>Adel Ashraf</strong></p>
          <a href="mailto:adelaboalanien@gmail.com" class="social_card_email"
            >adelaboalanien@gmail.com</a
          >
          <a href="tel:+201118080632" class="social_card_email">01118080632</a>
          <div style="margin-top: 0.4rem">
            <!-- Social media icons here -->
            <a
              href="https://github.com/AdelBenAshraf"
              target="_blank"
              class="contacts__social-icon"
              style="margin-right: 0.6rem"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 496 512"
              >
                <path
                  d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                />
              </svg>
            </a>
            <a
              href="https://www.linkedin.com/in/adelashraf/"
              target="_blank"
              class="contacts__social-icon"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 448 512"
                fill=" #0A66C2"
              >
                <path
                  d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"
                />
              </svg>
            </a>
          </div>
        </div>

        <div class="contact">
          <img src="images2/ammar.jpg" alt="Person 2" />
          <p><strong>Abdullah Ammar</strong></p>
          <a href="mailto:abdallahammar323@gmail.com" class="social_card_email"
            >abdallahammar323@gmail.com</a
          >
          <a href="tel:+201064668258" class="social_card_email">01064668258</a>
          <div style="margin-top: 0.4rem">
            <!-- Social media icons here -->
            <a
              href="https://github.com/Ammarr5"
              target="_blank"
              class="contacts__social-icon"
              style="margin-right: 0.6rem"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 496 512"
              >
                <path
                  d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                />
              </svg>
            </a>
            <a
              href="https://www.linkedin.com/in/ammarr5/"
              target="_blank"
              class="contacts__social-icon"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 448 512"
                fill=" #0A66C2"
              >
                <path
                  d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"
                />
              </svg>
            </a>
          </div>
        </div>

        <div class="contact">
          <img src="images2/zeyad.jpeg" alt="Person 3" />
          <p><strong>Zeyad Taher</strong></p>
          <a href="mailto:zeyadtaher16@gmail.com" class="social_card_email"
            >zeyadtaher16@gmail.com</a
          >
          <a href="tel:+201061400529" class="social_card_email">01061400529</a>
          <div style="margin-top: 0.4rem">
            <!-- Social media icons here -->
            <a
              href="https://github.com/Zeyad-Taher"
              target="_blank"
              class="contacts__social-icon"
              style="margin-right: 0.6rem"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 496 512"
              >
                <path
                  d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                />
              </svg>
            </a>
            <a
              href="https://www.linkedin.com/in/zeyad-taher-0323a8210/"
              target="_blank"
              class="contacts__social-icon"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 448 512"
                fill=" #0A66C2"
              >
                <path
                  d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"
                />
              </svg>
            </a>
          </div>
        </div>

        <div class="contact">
          <img src="images2/ziad.jpg" alt="Person 4" />
          <p><strong>Ziad AbdelMonem</strong></p>
          <a href="mailto:ziad.beda95@gmail.com" class="social_card_email"
            >ziad.beda95@gmail.com</a
          >
          <a href="tel:+201156595325" class="social_card_email">01156595325</a>
          <div style="margin-top: 0.4rem">
            <!-- Social media icons here -->
            <a
              href="https://github.com/ZIAD220"
              target="_blank"
              class="contacts__social-icon"
              style="margin-right: 0.6rem"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 496 512"
              >
                <path
                  d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                />
              </svg>
            </a>
            <a
              href="https://www.linkedin.com/in/ziad-abd-el-monem-bb3370241/"
              target="_blank"
              class="contacts__social-icon"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 448 512"
                fill=" #0A66C2"
              >
                <path
                  d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"
                />
              </svg>
            </a>
          </div>
        </div>

        <div class="contact">
          <img src="images2/kareem.jpg" alt="Person 5" />
          <p><strong>Kareem Galal</strong></p>
          <a href="mailto:kareemgalal1890@gmail.com" class="social_card_email"
            >kareemgalal1890@gmail.com</a
          >
          <a href="tel:+201032588720" class="social_card_email">01032588720</a>
          <div style="margin-top: 0.4rem">
            <!-- Social media icons here -->
            <a
              href="https://github.com/kareemgalall"
              target="_blank"
              class="contacts__social-icon"
              style="margin-right: 0.6rem"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 496 512"
              >
                <path
                  d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                />
              </svg>
            </a>
            <a
              href="https://www.linkedin.com/in/kareem-galall/"
              target="_blank"
              class="contacts__social-icon"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1em"
                viewBox="0 0 448 512"
                fill=" #0A66C2"
              >
                <path
                  d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"
                />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- End: Contsct us Section -->

    <!-- Demo Section -->
    <section id="demo">
      <div class="demo-video-container">
        <h2 class="stylish_heading" style="margin-bottom: 4rem">
          Watch a Demo <span class="red_dot">.</span>
        </h2>
        <iframe width="1120" height="630" src="https://www.youtube.com/embed/4L84fo1X_4s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        <!-- <video controls class="demo-video">
          <source src="{{URL::asset("images2/FlyCut_DemoV2.mp4")}}" type="video/mp4" />
        </video> -->
      </div>
    </section>
    <!-- End: Demo Section -->

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
              Find Nearby <br />
              Barbers <span class="red_dot">.</span>
            </h2>
            <p class="little-description">
              Find the nearest barbershop to you, view their location on the
              map, view their ratings and provided services, and make a
              reservation with them!
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
              Add your barbershop to our system, put it on the map, add your
              barbers, customize your services and get more customers to know
              you!
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
        <p class="">App coming soon on Google Play</p>

        <a href="javascript:void()">
          <img src="images2/playstore.png" alt="playstore image" width="300" height="200"
        /></a>
      </div>
    </section>
    <!-- End: Download The App Section -->

    <!-- Footer Section -->
    <section id="footer">
      <div class="links">
        <ul>
          <li><a href="#home">HOME</a></li>
          <li><a href="#contact_us">CONTACT US</a></li>
          <li><a href="#demo">DEMO</a></li>
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
    <script src="{{ asset('js/essential.js') }}" defer></script>
    <script src="{{ asset('js/jquery.nice-select.min.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
  </body>
</html>
