<?php
?>
<!DOCTYPE html>
<html lang="es-US" data-bs-theme="dark">

<head>
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CatPosting | Share Your Cat Stories</title>
    <meta name="robots" content="follow, index">
    <meta name="description" content="Share your cat stories and post random cat stuff with easy formatting on CatPosting. Join now to connect with other cat lovers!">
    <meta name="keywords" content="catposting, cat stories, share cat photos, cat lovers, cat community, cat memes">   
    <meta name="author" content="frankastore">
    <meta property="og:title" content="CatPosting | Share Your Cat Stories">
    <meta property="og:description" content="Share your cat stories and post random cat stuff with easy formatting on CatPosting. Join now to connect with other cat lovers!">
    <meta property="og:image" content="url-to-your-social-media-thumbnail-image.jpg">
    <meta property="og:url" content="https://catposting.franka.store">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="CatPosting | Share Your Cat Stories">
    <meta name="twitter:description" content="Share your cat stories and post random cat stuff with easy formatting on CatPosting. Join now to connect with other cat lovers!">
    <meta name="twitter:image" content="/apple-touch-icon.png">
    <link rel="icon" href="/apple-touch-icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="canonical" href="https://catposting.franka.store">
    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "Organization",
          "name": "CatPosting",
          "url": "https://catposting.franka.store",
          "logo": "url-to-your-logo.png",
          "description": "Share your cat stories and post random cat stuff with easy formatting on CatPosting. Join now to connect with other cat lovers!",
          "serviceType": "Social media platform",
          "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "bestRating": "5",
            "worstRating": "1",
            "ratingCount": "500"
          },
          "areaServed": {
            "@type": "Country",
            "name": "Global"
          },
          "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer service",
            "email": "fix@franka.store"
          },
          "offers": {
            "@type": "Offer",
            "price": "0.00",
            "priceCurrency": "USD",
            "availability": "http://schema.org/InStock",
            "seller": {
              "@type": "Organization",
              "name": "CatPosting"
            },
            "hoursAvailable": [
              {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": [
                  "Monday",
                  "Tuesday",
                  "Wednesday",
                  "Thursday",
                  "Friday",
                  "Saturday",
                  "Sunday"
                ],
                "opens": "00:00",
                "closes": "23:59"
              }
            ]
          }
        }
    </script>
</head>
<style>
    :root {
        --bs-success-rgb: #12d964;
        --bs-body-bg: #000;
        --bs-transparent-bg: black;
    }

    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: var(--bs-body-bg);
    }

    .modal-fullscreen {
        height: calc(100% - 50px);
        max-height: 100%;
        margin: 0px 0px 0px 0px;
        position: fixed;
        left: 0;
    }

    .text-success {
        color: var(--bs-success-rgb) !important;
    }

    .bg-custom {
        background-color: var(--bs-transparent-bg);
        backdrop-filter: blur(15px);
    }

  iframe{
      
        height:calc(100% - 50px)!important;
        width: 100%;
        border: none;
        display: block;
    }

    .modal {
        transition: transform 0.3s ease-out;
        transform: translate(0, 100%);
    }

    .modal.show {
        transform: translate(0, 0);
    }

    .z-index {
        z-index: 9999;
    }

    .navbar-toggler-icon {
        display: inline-block;
        width: 1.5em;
        height: 1.5em;
        vertical-align: middle;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100%;
    }

    .navbar-toggler:focus {
        color: white !important;
        box-shadow: none;
    }

    .rotate-gear:hover {
        transition: transform 0.3s ease-in-out !important;
        ;
    }

    .rotate-gear:active {
        transform: rotate(-360deg) !important;
    }

    .btn-close {
        background-image: url("data:image/svg+xml,%3Csvg width='60px' height='60px' viewBox='0 0 3.3 3.3' xmlns='http://www.w3.org/2000/svg'%3E%3Ctitle%3Eclose-circle-solid%3C/title%3E%3Cg id='Layer_2' data-name='Layer 2'%3E%3Cg id='invisible_box' data-name='invisible box'%3E%3Cpath width='48' height='48' fill='none' d='M0 0h3.3v3.3H0V0z'/%3E%3C/g%3E%3Cg id='icons_Q2' data-name='icons Q2'%3E%3Cpath fill='white' d='M1.65 0.138A1.513 1.513 0 1 0 3.163 1.65 1.506 1.506 0 0 0 1.65 0.138Zm0.571 1.891a0.144 0.144 0 0 1 0.028 0.186 0.138 0.138 0 0 1 -0.213 0.014l-0.385 -0.386 -0.385 0.385a0.138 0.138 0 0 1 -0.213 -0.014 0.144 0.144 0 0 1 0.028 -0.186l0.378 -0.378 -0.378 -0.378A0.152 0.152 0 0 1 1.052 1.085 0.138 0.138 0 0 1 1.265 1.07l0.385 0.385 0.385 -0.385a0.138 0.138 0 0 1 0.213 0.014 0.152 0.152 0 0 1 -0.028 0.186l-0.378 0.38Z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") !important;
        opacity: 1;
        border-radius: 30px;
        font-size: 50px;
    }

    .fadeIn:active {
        opacity: 0;
        animation: fadeInAnimation 1s ease-in-out forwards;
        transition: all 0.2s ease-out;
    }
</style>
<body>
    
    <nav class="w-100 z-index navbar position-fixed top-0 shadow navbar-expand-lg bg-custom" data-bs-target="#navbarNav" aria-controls="navbarNav">
            <div class="container-xl">
                <a class="navbar-brand" href="#">Franka.store®</a>
                <button class="navbar-toggler rounded-pill border-0 btn p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fadeIn color-white navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-3">
                       
                        <li data-bs-toggle="collapse" data-bs-target="#navbarNav" class="nav-item">
                            <a class="nav-link active" href="#" onclick="loadIframe('postore.php', this);"><span class="p-1 fw-light  text-light"><i class="bi bi-grid"></i></span> Catposting</a>
                        </li>
                        
                        
                        <li data-bs-toggle="collapse" data-bs-target="#navbarNav" class="nav-item">
                            <a class="nav-link" href="#" onclick="loadIframe('postore.php', this);"><span class="p-1 fw-light  text-light"><i class="bi bi-grid"></i></span> WEBP Image Converter</a>
                        </li>
                        
                          <li data-bs-toggle="collapse" data-bs-target="#navbarNav" class="nav-item">
                            <a class="nav-link" href="#" onclick="loadIframe('proforma.php', this);"><span class="p-1 fw-light  text-light"><i class="bi bi-grid"></i></span> Proforma Template</a>
                        </li>
                        
                        
                                                
                        <li data-bs-toggle="collapse" data-bs-target="#navbarNav" class="nav-item">
                            <a class="nav-link" href="#" onclick="loadIframe('messaging.php', this);"><span class="p-1 fw-light  text-light"><i class="bi bi-grid"></i></span> CatMessagingr</a>
                        </li>
                        
                        
                        <li data-bs-toggle="collapse" data-bs-target="#navbarNav" class="nav-item">
                            <a class="nav-link" href="#" onclick="loadIframe('home.php', this);"><span class="p-1 fw-light  text-light"><i class="bi bi-grid"></i></span> Home</a>
                        </li>
 

            

                    </ul>
                </div>
            </div>
        </nav>
        <div class="pt-5"></div>
  <iframe id="iframeContent" src="https://franka.store/app/php/postore.php" class="bg-body-tertiary iframe-class rounded-bottom-0" frameborder="0"></iframe>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAppModal();
        });

        function loadIframe(page, element) {
            updateIframeSource(page);
            updateLinkActiveClass(element);
            showAppModal();
        }

        function updateIframeSource(page) {
            document.getElementById('iframeContent').src = 'https://franka.store/app/php/' + page;
        }

        function updateLinkActiveClass(element) {
            const links = document.querySelectorAll('.navbar-nav .nav-link');
            links.forEach(link => link.classList.remove('active'));
            element.classList.add('active');
        }

        function showAppModal() {
            const existingModal = document.querySelector('.modal.show');
            if (!existingModal) {
                const myModal = new bootstrap.Modal(document.getElementById('appModal'));
                myModal.show();
            }
        }
    </script>
</body>

</html>