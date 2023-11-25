<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kata</h1>
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
                                <h5 class="card-title">Data Kata</h5>

                                <!-- Table with stripped rows -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kata</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($kata->result() as $k) :
                                        ?>
                                            <tr>
                                                <td><?= $k->id ?></td>
                                                <td><?= $k->kata ?></td>
                                                <td>
                                                    <a href="<?= base_url('admin/set_stopword/' . $k->id) ?>" class="btn btn-sm btn-info" title="Jadikan Stopword" onclick="return confirm('Kata akan merubah menjadi Stopword, Apakah Anda Yakin?')"><i class="bi bi-arrow-right-square"></i></a>
                                                </td>
                                            </tr>
                                        <?php
                                        endforeach;
                                        ?>
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