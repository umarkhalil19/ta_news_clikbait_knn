<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Algoritma extends CI_Controller
{
    public function getDataLatih($id = 0, $status = 0)
    {
        // code python untuk mengambil data dalam bentuk kata
        $stat = "latih";
        $command = escapeshellcmd('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\text_preprocessing.py ' . $id . ' ' . $stat);
        // $command = escapeshellcmd('python D:\laragon\www\ta_rini_python_code\text_preprocessing.py ' . $id . ' ' . $stat);
        $data = exec($command);

        // merubah text menjadi array dengan menghapus tanda kurung siku ']' pada data
        $array = explode('],', $data);

        //menyimpan parameter id ke variabel
        $beritaId = $id;

        // mengambil semua data kata yang sudah ada pada database dan merubah bentuknya menjadi array
        $kata = $this->db->get('kata');
        if (!empty($kata->result())) {
            $arrayKata = [];
            foreach ($kata->result() as $k) {
                array_push($arrayKata, $k->kata);
                array_push($arrayKata, $k->id);
                // $arrayKata[$k->id] = $k->kata;
            }
        }

        //mengambil data kata stopword dari database
        $stopword = $this->db->get('stopword');
        $arrayStopword = [];
        foreach ($stopword->result() as $s) {
            array_push($arrayStopword, $s->kata);
        }

        // melakukan penyimpanan untuk setiap data yang ada dalam $array
        for ($i = 0; $i < count($array); $i++) {
            //menghilangkan semua tanda banca yang tidak butuhkan
            $kataFrekuensi = str_replace(['[', ']', '"'], '', $array[$i]);
            // memisahkan antara kata dan frekuensinya
            $arrayKF = explode(",", $kataFrekuensi);
            // cek apakah kata yang muncul pada data adalah stopword atau bukan
            if (!in_array(trim($arrayKF[0]), $arrayStopword)) {
                //cek apakah sudah ada kata atau belum di dalam tabel kata
                if (empty($kata->result())) {
                    $insert = [
                        'kata' => trim($arrayKF[0])
                    ];
                    // meyimpan data kata baru ke database
                    $this->db->insert('kata', $insert);
                    // mengambil id dari kata baru yang di tambah ke database
                    $lastId = $this->db->insert_id();
                    $kata_latih = [
                        'kata_id' => $lastId,
                        'berita_id' => $beritaId,
                        // 'kata' => $arrayKF[0],
                        'frekuensi' => trim($arrayKF[1])
                    ];
                    // menyimpan data kata latih ke database
                    $this->db->insert('kata_latih', $kata_latih);
                } else {
                    // mencari kata dalam kata latih yang sudah di input ke database
                    $key = array_search(trim($arrayKF[0]), $arrayKata);
                    //jika ditemukan
                    if (!empty($key)) {
                        // ambil index untuk mengambil bobot
                        $index = (int)($key) + 1;
                        $insertKata = [
                            'kata_id' => $arrayKata[$index],
                            'berita_id' => $beritaId,
                            // 'kata' => $arrayKF[0],
                            'frekuensi' => trim($arrayKF[1])
                        ];
                        // menyimpan data kata latih ke database
                        $this->db->insert('kata_latih', $insertKata);
                    } else {
                        $insert = [
                            'kata' => trim($arrayKF[0])
                        ];
                        // meyimpan data kata baru ke database
                        $this->db->insert('kata', $insert);
                        // mengambil id dari kata baru yang di tambah ke database
                        $lastId = $this->db->insert_id();
                        $kata_latih = [
                            'kata_id' => $lastId,
                            'berita_id' => $beritaId,
                            // 'kata' => $arrayKF[0],
                            'frekuensi' => trim($arrayKF[1])
                        ];
                        // menyimpan data kata latih ke database
                        $this->db->insert('kata_latih', $kata_latih);
                    }
                }
            }
        }
        if ($status == 0) {
            redirect('admin/berita_latih');
        } else {
            redirect('admin/cekDataLatih');
        }
    }

    public function getDataUji($id = 0)
    {
        // code python untuk mengambil data dalam bentuk kata
        $stat = "uji";
        $command = escapeshellcmd('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\text_preprocessing.py ' . $id . ' ' . $stat);

        // $command = escapeshellcmd('python D:\laragon\www\ta_rini_python_code\text_preprocessing.py ' . $id . ' ' . $stat);
        $data = exec($command);

        // merubah text menjadi array dengan menghapus tanda kurung siku ']' pada data
        $array = explode('],', $data);

        //menyimpan parameter id ke variabel
        $beritaId = $id;

        //mengambil data kata stopword dari database
        $stopword = $this->db->get('stopword');
        $arrayStopword = [];
        foreach ($stopword->result() as $s) {
            array_push($arrayStopword, $s->kata);
        }

        for ($i = 0; $i < count($array); $i++) {
            //menghilangkan semua tanda banca yang tidak butuhkan
            $kataFrekuensi = str_replace(['[', ']', '"'], '', $array[$i]);
            // memisahkan antara kata dan frekuensinya
            $arrayKF = explode(",", $kataFrekuensi);
            // cek apakah kata yang muncul pada data adalah stopword atau bukan
            if (!in_array(trim($arrayKF[0]), $arrayStopword)) {
                $kataUji = [
                    'kata' => trim($arrayKF[0]),
                    'berita_id' => $beritaId,
                    'frekuensi' => trim($arrayKF[1])
                ];
                $this->db->insert('kata_uji', $kataUji);
            }
        }
        if ($this->session->userdata('status') == 'login') {
            redirect('admin/berita_uji');
        } else {
            $this->knn($id);
        }
    }

    public function hitungBobot()
    {
        // hapus data dan reset kondisi di tabel bobot_kata_latih
        $this->db->query('TRUNCATE TABLE bobot_kata_latih');

        // jumlah berita latih yang ada di database
        $jumlahBerita = $this->db->select('COUNT(id) as jumlah')->from('berita_latih')->get()->row();

        //algortima untuk menghitung total kemunculan kata per berita latih
        $arrayJumlahKataPerDokumen = [];
        $jumlahKataPerDokumen = $this->db->query('select SUM(frekuensi) as nilai,berita_id from kata_latih group by berita_id');
        foreach ($jumlahKataPerDokumen->result() as $jkpd) {
            array_push($arrayJumlahKataPerDokumen, $jkpd->berita_id);
            array_push($arrayJumlahKataPerDokumen, $jkpd->nilai);
        }

        // algoritma untuk mencari total kemunculan kata pada keseluruan dokumen 
        $arrayKataLatih = [];
        $kataLatih = $this->db->select('k.kata,kl.*')
            ->from('kata_latih kl')
            ->join('kata k', 'k.id=kl.kata_id')
            ->get();
        foreach ($kataLatih->result() as $kL) {
            array_push($arrayKataLatih, $kL->kata);
        }
        $jumlahKataDokumen = array_count_values($arrayKataLatih); /* Menghitung Kemunculan kata dalam dokumen */

        //algoritma untuk menghitung bobot setiap kata latih dan menyimpan datanya ke database
        foreach ($kataLatih->result() as $kL) {
            $idJumlahKata = array_search($kL->berita_id, $arrayJumlahKataPerDokumen) + 1;
            // $TF = $kL->frekuensi / $arrayJumlahKataPerDokumen[$idJumlahKata];
            $TF = $kL->frekuensi;
            $IDF =  log10($jumlahBerita->jumlah / $jumlahKataDokumen[$kL->kata]);
            $bobot = [
                'kata_id' => $kL->kata_id,
                'berita_id' => $kL->berita_id,
                'bobot' => $TF * $IDF,
            ];
            $this->db->insert('bobot_kata_latih', $bobot);
        }
        redirect('admin/berita_uji');
    }

    public function knn($id = 0)
    {
        if ($id == 0) {
            redirect('admin');
        } else {
            //menghapus hasil klasifikasi sebelumnnya dari tabel hasil_knn
            $this->db->query('TRUNCATE TABLE hasil_knn');

            // array untuk menyimpan id berita latih
            $arrayIdBeritaLatih = [];

            // mengambil data berita latih dan merubahnya manjadi array biasa
            $idBeritaLatih = $this->db->select('*')->from('berita_latih')->get()->result_array();
            foreach ($idBeritaLatih as $iBL) {
                array_push($arrayIdBeritaLatih, $iBL['id']);
            }

            //jumlah data latih yang ada
            $jumlahBeritaLatih = count($arrayIdBeritaLatih);

            // jumlah kata dalam berita uji
            $jumlahKata = $this->db->query("SELECT SUM(frekuensi) as nilai FROM kata_uji WHERE berita_id=$id")->row();

            // mengambil semua data kata latih
            $arrayKataBeritaLatih = [];
            $bobotLatih = $this->db->select('bkl.*,k.kata')
                ->from('bobot_kata_latih bkl')
                ->join('kata k', 'k.id=bkl.kata_id', 'LEFT')
                ->get()
                ->result();
            foreach ($bobotLatih as $bL) {
                array_push($arrayKataBeritaLatih, $bL->kata);
            }

            //kemunculan setiap kata pada semua dokumen
            $bobotKataDiDokumen = array_count_values($arrayKataBeritaLatih);

            // mengambil semua data kata uji
            $kataUji = $this->db->get_where('kata_uji', ['berita_id' => $id])->result();

            // mengambil bobot untuk setiap kata latih per dokumen
            $arrayBobotPerDokumen = [];
            for ($i = 0; $i < count($arrayIdBeritaLatih); $i++) {
                $arrayBobot = [];
                foreach ($bobotLatih as $bL) {
                    if ($arrayIdBeritaLatih[$i] == $bL->berita_id) {
                        array_push($arrayBobot, $bL->kata);
                        array_push($arrayBobot, $bL->bobot);
                    }
                }
                array_push($arrayBobotPerDokumen, $arrayBobot);
            }

            // melakukan perhitungan euclidean distance
            for ($i = 0; $i < count($arrayBobotPerDokumen); $i++) {
                $hasilBobot = 0;
                foreach ($kataUji as $kU) {
                    if (in_array($kU->kata, $arrayBobotPerDokumen[$i])) {
                        $indexBobot = array_search($kU->kata, $arrayBobotPerDokumen[$i]) + 1;
                        $bobotlatih =  $arrayBobotPerDokumen[$i][$indexBobot];
                        // $bobot = pow($bobotlatih - (($kU['frekuensi'] / $jumlahKata) * (log10($jumlahBeritaLatih / ($bobotKataDiDokumen[$kU->kata] + 1)))), 2);
                        // $bobotUji = ($kU->frekuensi / $jumlahKata->nilai) * (log10($jumlahBeritaLatih / ($bobotKataDiDokumen[$kU->kata] + 1)));
                        $bobotUji = ($kU->frekuensi) * (log10($jumlahBeritaLatih / ($bobotKataDiDokumen[$kU->kata] + 1)));
                        $bobot = pow(($bobotlatih - $bobotUji), 2);
                    } else {
                        $bobot = 0;
                    }
                    $hasilBobot = $hasilBobot + $bobot;
                }
                $knn = [
                    'uji_id' => $id,
                    'latih_id' => $idBeritaLatih[$i]['id'],
                    'bobot' => sqrt($hasilBobot),
                    'klasifikasi' => $idBeritaLatih[$i]['klasifikasi'],
                ];
                $this->db->insert('hasil_knn', $knn);
            }
            // echo "<pre>";
            // print_r($);
            // echo "</pre>";
            $klasifikasi = $this->db->query("SELECT * FROM hasil_knn WHERE uji_id=$id ORDER BY bobot DESC LIMIT 4");
            $arrayKlasifikasi = [];
            foreach ($klasifikasi->result() as $k) {
                array_push($arrayKlasifikasi, $k->klasifikasi);
            }
            $arrayHasilKlasifikasi = array_count_values($arrayKlasifikasi);
            $arrayRsort = $arrayHasilKlasifikasi;
            rsort($arrayRsort);
            // rsort($arrayHasilKlasifikasi);
            // $klasifikasiKnn = array_key_first($arrayHasilKlasifikasi);
            $klasifikasiKnn = array_search($arrayRsort[0], $arrayHasilKlasifikasi);
            $data['hasilknn'] = $klasifikasi;
            $this->db->update('berita_uji', ['klasifikasi' => $klasifikasiKnn], ['id' => $id]);
            $data['beritauji'] = $this->db->get_where('berita_uji', ['id' => $id])->row();
            if ($this->session->userdata('status') == 'login') {
                $this->load->view('admin/templates/header');
                $this->load->view('admin/berita_uji_hasil', $data);
                $this->load->view('admin/templates/footer');
            } else {
                $this->load->view('beranda/templates/header');
                $this->load->view('beranda/hasil_uji_berita', $data);
                $this->load->view('beranda/templates/footer');
            }
        }
    }
}
