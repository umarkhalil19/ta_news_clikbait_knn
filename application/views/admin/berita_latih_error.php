<main id="main" class="main">

    <div class="pagetitle">
        <h1>Berita</h1>
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
                                <a href="<?= base_url('admin/berita_latih_add') ?>" class="btn btn-md btn-primary float-end mt-2">Tambah Data</a>
                                <h5 class="card-title">Data Berita Latih</h5>

                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Judul</th>
                                            <!-- <th scope="col">Isi</th> -->
                                            <th scope="col">Link</th>
                                            <th scope="col">Klasifikasi</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($berita as $b) :
                                            $cek = in_array($b->id,$error);
                                            switch ($b->klasifikasi) {
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
                                        <?php
                                            if ($cek == 1) {
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $b->judul ?></td>
                                                <td><?= $b->link ?></td>
                                                <td><?= $klasifikasi ?></td>
                                                <td>
                                                    <a href="<?= base_url('algoritma/getDataLatih/' . $b->id.'/1') ?>" class="btn btn-sm btn-info" title="Detail"><i class="bi bi-arrow-repeat"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>
                        </div>
                    </div><!-- End Reports -->

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>

</main><!-- End #main -->