<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Pesantren Nurul Ulum</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Mendefinisikan warna utama agar mudah diganti */
        :root {
            --primary-color: #143D2B; /* Warna hijau tua yang lebih elegan */
            --accent-color: #ffffff;
        }

        .footer {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .footer h6 {
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        /* Styling untuk garis aksen di bawah judul */
        .footer .heading-garis {
            border: none;
            height: 2px;
            width: 50px;
            background-color: var(--accent-color);
            opacity: 0.8;
        }
        
        /* Styling untuk daftar kontak */
        .footer .list-kontak {
            list-style-type: none;
            padding-left: 0;
        }
        .footer .list-kontak li {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }
        .footer .list-kontak i {
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease, padding-left 0.3s ease;
        }

        .footer a:hover {
            color: #fff;
            padding-left: 5px; /* Efek hover yang lebih menarik */
        }
        
        .footer .copyright {
            background-color: rgba(0, 0, 0, 0.2);
            font-size: 0.85rem;
        }
        .footer .copyright a {
            font-weight: 600;
        }
        .footer .copyright a:hover {
            padding-left: 0;
        }
    </style>
</head>
<body>

    <footer class="footer pt-5 bg-success">
        <div class="container text-center text-md-start">
            <div class="row gy-4">
                
                <div class="col-lg-4 col-md-6 mx-auto mb-4">
                    <h6 class="fw-bold">Pesantren Nurul Ulum</h6>
                    <hr class="heading-garis mt-2 mb-4 d-inline-block mx-auto mx-md-0">
                    <div class="ratio ratio-16x9 rounded overflow-hidden">
                      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3948.7969400122624!2d113.69892927495286!3d-8.223165082536712!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6971d7d26293f%3A0xfca93c5b50270c83!2sYayasan%20Pendidikan%20dan%20Pondok%20Pesantren%20Nurul%20Ulum!5e0!3m2!1sen!2sid!4v1752330652034!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mx-auto mb-4">
                    <h6 class="fw-bold">Tautan</h6>
                    <hr class="heading-garis mt-2 mb-4 d-inline-block mx-auto mx-md-0">
                    <p><a href="#unit-pendidikan">Unit Pendidikan</a></p>
                    <p><a href="#!">Pendaftaran</a></p>
                    <p><a href="/berita">Berita</a></p>
                </div>
                
                <div class="col-lg-4 col-md-12 mx-auto mb-4">
                    <h6 class="fw-bold">Kontak</h6>
                    <hr class="heading-garis mt-2 mb-4 d-inline-block mx-auto mx-md-0">
                    <ul class="list-kontak">
                        <li>
                            <i class="bi bi-envelope-fill me-3"></i>
                            <a href="mailto:info@nurululum.ac.id">info@nurululum.ac.id</a>
                        </li>
                        <li>
                          <a href="https://wa.me/6282223333841" target="_blank">
                              <i class="bi bi-whatsapp me-3"></i>
                              <span>Pondok Putra: 0822-2333-3841</span>
                          </a>
                      </li>
                      <li>
                          <a href="https://wa.me/6282223333842" target="_blank">
                              <i class="bi bi-whatsapp me-3"></i>
                              <span>Pondok Putri: 0822-2333-3842</span>
                          </a>
                      </li>
                      <li>
                          <a href="https://wa.me/6282223333843" target="_blank">
                              <i class="bi bi-whatsapp me-3"></i>
                              <span>Klinik INSAN Medika: 0822-2333-3843</span>
                          </a>
                      </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="text-center p-3 copyright">
            © 2025 Copyright:
            <a href="#">Pesantren Nurul Ulum</a>
        </div>
    </footer>

</body>
</html>