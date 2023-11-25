<main id="main" class="main">

    <div class="pagetitle">
        <h1>Hasil Uji Algoritma KNN (Euclidean Distance)</h1>
        <br>
        <!-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav> -->
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h5 class="card-title">Form Hasil Klasifikasi</h5>
                                <!-- <form class="row g-3" method="post" action="<?= base_url('admin/berita_uji_act') ?>"> -->
                                <div class="col-12">
                                    <label for="inputEmail4" class="form-label">Link</label>
                                    <input type="text" class="form-control" name="link" value="<?= $beritauji->link ?>" readonly>
                                    <?= form_error('link', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Judul</label>
                                    <input type="text" class="form-control" name="judul" value="<?= $beritauji->judul ?>" readonly>
                                    <?= form_error('judul', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-12">
                                    <label for="inputEmail4" class="form-label">Isi Berita</label>
                                    <textarea name="" id="" class="form-control" cols="30" rows="10"><?= $beritauji->isi ?></textarea>
                                    <?= form_error('link', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <br>
                                <?php
                                switch ($beritauji->klasifikasi) {
                                    case '1':
                                        $klasifikasi = 'Positif';
                                        break;
                                    case '2':
                                        $klasifikasi = 'Netral';
                                        break;
                                    case '3':
                                        $klasifikasi = 'Negatif';
                                        break;

                                    default:
                                        $klasifikasi = 'Netral';
                                        break;
                                }
                                ?>
                                <p>Berdasarkan Hasil Perhitungan Di Atas Maka Berita Tersebut di atas masuk kelasifikasi : <strong><?= $klasifikasi ?></strong></p>
                                <br>
                                <div class="text-center">
                                    <a href="<?= base_url('beranda/ujiberita') ?>" class="btn btn-primary">Uji Berita Lain</a>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Reports -->

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>

</main><!-- End #main -->