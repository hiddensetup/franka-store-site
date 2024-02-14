<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>CatWEBP Converter | Frankastore</title>
</head>

<body class="fadeIn">
    <div class="w-100 d-none bg-primary position-fixed form-check mt-1" style="bottom: 79px;z-index:1030">
        <input class="mx-1 form-check-input" style="min-width:20px; min-height:19px;" type="checkbox" id="selectAll">
        <label class="text-light form-check-label" style="vertical-align: middle;" for="selectAll">Select All</label>
    </div>
    <div class="container mt-5">
        <div id="welcome-message-container" class="container rounded-4">
            <div id="welcome-message" class="text-center p-4 rounded">
                <h1 class="mb-4 display-4 pt-5 "><i class="bi bi-cat"></i> CatWEBP Converter</h1>
                <p class="lead col-auto mx-auto">Unlock the full potential of your images with our state-of-the-art WEBP converter. This tool is designed to enhance your digital content.</p>
                <div class="row justify-content-center my-4">
                    <div class="col-md-6">
                        <p class="bg-primary badge">Key Features</p>
                        <ul class="list-group list-group-flush border rounded-4 shadow text-start">
                            <li class="list-group-item p-3">🌐 <b>Web-Friendly:</b> WEBP is the cool kid on the block, offering superior image quality with smaller file sizes for a smoother online experience.</li>
                            <li class="list-group-item p-3">⚡ <b>Speedy Conversions:</b> Drag, drop, and voilà! Watch your images transform in the blink of an eye.</li>
                            <li class="list-group-item p-3">🎨 <b>Image Excellence:</b> Whether you're a design maestro or just getting started, our converter ensures your visuals shine at their very best.</li>
                           
                        </ul>
                    </div>
                </div>
                <p class="lead mt-4 text-secondary col-sm-6 col-md-6  mx-auto"><strong>Ready to dive in? </strong> Let's make your images the stars of the digital show! Drop your files or hit the upload button to get started.</p>
            </div>
        </div>
        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <nav class="border-top navbar bg-body-tertiary fixed-bottom">
        <button class="btn btn-custom-secondary btn-lg border px-5 mx-auto rounded-4" onclick="downloadSelected()">
            <i class="bi bi-download"></i>
        </button>
        <div class="d-flex align-items-center mx-auto">
            <div id="conversion-counter" class="badge bg-dark rounded me-2"></div>
            <label for="fileSelector" class="btn btn-lg px-5 btn-custom-secondary rounded-4 border">
                <i class="bi bi-plus-lg"></i>
            </label>
            <input type="file" class="form-control visually-hidden" id="fileSelector" multiple>
        </div>
        <div class="dropup dropup-center me-2">
            <button class="rotate-gear btn border-0 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fs-4 bi bi-gear"></i>
            </button>
            <ul class="rounded-4 dropdown-menu dropdown-menu-end">
                <li>
                    <p class="ps-3 pe-2 mb-1" id="offcanvasNavbarLabel">
                        <i class="bi bi-person-circle"></i>
                        <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Frankastore'; ?>
                    </p>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <button type="" class="dropdown-item" name="">
                        <i class="bi bi-cash-stack"></i> Donate
                    </button>
                </li>
                <li>
                    <button class="dropdown-item" onclick="toggleTheme()">
                        <i id="theme-icon" class="bi bi-sun"></i> Theme
                    </button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-image-converter mx-2 my-5">
        <table id="imageTable" class="fadeIn align-middle table-hover table table-striped table-bordered" style="display: none;">
            <thead>
                <tr>
                    <th><i class="bi bi-card-image"></i> Image Preview</th>
                    <th class="d-none col-3 d-sm-table-cell"><i class="bi bi-archive"></i> Original Size</th>
                    <th class="col-3 d-none d-sm-table-cell"><i class="bi bi-check-circle"></i> Converted Size</th>
                    <th class="col-3"><i class="bi bi-arrow-down-circle"></i> Download Webp File</th>
                </tr>
            </thead>
            <tbody class="previews" id="imageTableBody"></tbody>
        </table>
        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.7.1/dist/jszip.min.js"></script>
    <script src="../js/theme.js"></script>
    <script src="../js/img-converter.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>