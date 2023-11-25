<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Data</h1>
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
                                <a href="<?= base_url('admin/berita_latih') ?>" class="btn btn-md btn-warning float-end mt-2">Data Berita Latih</a>
                                <h5 class="card-title">Form Edit Data</h5>
                                <form class="row g-3" method="post" action="<?= base_url('admin/berita_latih_update') ?>">
                                    <div class="col-12">
                                        <label for="inputNanme4" class="form-label">Judul</label>
                                        <input type="hidden" value="<?= $berita->id ?>" name="id">
                                        <input type="text" class="form-control" name="judul" value="<?= set_value('judul', $berita->judul) ?>">
                                        <?= form_error('judul', '<small class="text-danger">', '</small>') ?>
                                    </div>
                                    <div class="col-12">
                                        <label for="inputEmail4" class="form-label">Link</label>
                                        <input type="text" class="form-control" name="link" value="<?= set_value('link', $berita->link) ?>">
                                        <?= form_error('link', '<small class="text-danger">', '</small>') ?>
                                    </div>
                                    <div class="col-12">
                                        <label for="inputPassword4" class="form-label">Klasifikasi</label>
                                        <select name="klasifikasi" class="form-control">
                                            <option value="1" <?= $berita->klasifikasi == 1 ? "selected" : "" ?>>Positif</option>
                                            <option value="2" <?= $berita->klasifikasi == 2 ? "selected" : "" ?>>Netral</option>
                                            <option value="3" <?= $berita->klasifikasi == 3 ? "selected" : "" ?>>Negatif</option>
                                        </select>
                                        <?= form_error('klasifikasi', '<small class="text-danger">', '</small>') ?>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- End Reports -->

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>

</main><!-- End #main -->