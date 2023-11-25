<main id="main" class="main">

    <div class="pagetitle">
        <h1>Hasil Pengujian Dengan KNN</h1>
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
                                <a href="<?= base_url('admin/berita_uji') ?>" class="btn btn-md btn-warning float-end mt-2">Data Berita Uji</a>
                                <h5 class="card-title">Data Berita</h5>
                                <!-- <form class="row g-3" method="post" action="<?= base_url('admin/berita_uji_act') ?>"> -->
                                <div class="col-12">
                                    <label for="inputNanme4" class="form-label">Judul</label>
                                    <input type="text" class="form-control" name="judul" value="<?= $beritauji->judul ?>" readonly>
                                    <?= form_error('judul', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-12">
                                    <label for="inputEmail4" class="form-label">Link</label>
                                    <input type="text" class="form-control" name="link" value="<?= $beritauji->link ?>" readonly>
                                    <?= form_error('link', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <!-- <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div> -->
                                <!-- </form> -->
                            </div>
                        </div>
                    </div><!-- End Reports -->

                </div>
            </div><!-- End Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Hasil Perhitungan</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Bobot</td>
                                            <td>Klasifikasi</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($hasilknn->result() as $h) :
                                            switch ($h->klasifikasi) {
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
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $h->bobot ?></td>
                                                <td><?= $klasifikasi ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
                            </div>
                        </div>
                    </div><!-- End Reports -->

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>

</main><!-- End #main -->