<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function try($id = 0)
	{
		// echo shell_exec("python F:\Pribadi\Tugas_Akhir\ta_rini\text_preprocessing.py");
		// $data =  shell_exec('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\text_preprocessing.py ' . $id);
		$stat = "status";
		$command = escapeshellcmd('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\text_preprocessing.py ' . $id . ' ' . $stat);
		$data = exec($command);
		// echo exec('pwd');
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		$array = explode('],', $data);
		echo "<pre>";
		print_r($array);
		echo "</pre>";
		die;
		$beritaId = $id;
		$kata = $this->db->get('kata');
		if (!empty($kata->result())) {
			$arrayKata = [];
			foreach ($kata->result() as $k) {
				array_push($arrayKata, $k->kata);
				array_push($arrayKata, $k->id);
			}
		}
		// $insertKata = [];
		for ($i = 0; $i < count($array); $i++) {
			// echo "<pre>";
			// print_r(trim(str_replace(['[', ']', '"'], '', $array[$i])));
			// echo "</pre>";
			$kataFrekuensi = str_replace(['[', ']', '"'], '', $array[$i]);
			$arrayKF = explode(",", $kataFrekuensi);
			if (empty($kata->result())) {
				$insert = [
					'kata' => trim($arrayKF[0])
				];
				// $this->db->insert('kata', $insert);
				// $lastId = $this->db->insert_id();
				$kata_latih = [
					// 'kata_id' => $lastId,
					'berita_id' => $beritaId,
					'kata' => $arrayKF[0],
					'frekuensi' => trim($arrayKF[1])
				];
				// $this->db->insert('kata_latih', $kata_latih);
			} else {
				$key = array_search(trim($arrayKF[0]), $arrayKata);
				if (!empty($key)) {
					$index = (int)($key) + 1;
					$insertKata = [
						// 'kata_id' => $arrayKata[$index],
						'berita_id' => $beritaId,
						'kata' => $arrayKF[0],
						'frekuensi' => trim($arrayKF[1])
					];
					// echo "<pre>";
					// print_r($insertKata);
					// echo "</pre>";
					// $this->db->insert('kata_latih', $insertKata);
				} else {
					$insert = [
						'kata' => trim($arrayKF[0])
					];
					// $this->db->insert('kata', $insert);
					// $lastId = $this->db->insert_id();
					$kata_latih = [
						// 'kata_id' => $lastId,
						'berita_id' => $beritaId,
						'kata' => $arrayKF[0],
						'frekuensi' => trim($arrayKF[1])
					];
					// $this->db->insert('kata_latih', $kata_latih);
					echo "<pre>";
					print_r($kata_latih);
					echo "</pre>";
				}
			}

			// echo "<pre>";
			// print_r($arrayKF);
			// echo "</pre>";
		}
		// echo "<pre>";
		// print_r($arrayKata);
		// echo "</pre>";
		// redirect('admin/berita_latih');
	}

	public function hitung_bobot()
	{
		$jumlahKata = $this->db->select('COUNT(id) as jumlah')->from('kata')->get()->row();
		$jumalahBerita = $this->db->select('COUNT(id) as jumlah')->from('berita_latih')->get()->row();
		// print_r($jumalahBerita->jumlah);
		// echo '<br>';
		// print_r($jumlahKata->jumlah);

		$kata = $this->db->get('kata');
		$arrayKataLatih = [];
		// $kataLatih = $this->db->get('kata_latih');
		$kataLatih = $this->db->select('k.kata,kl.*')
			->from('kata_latih kl')
			->join('kata k', 'k.id=kl.kata_id')
			// ->where(['berita_id' => $id])
			->get();
		foreach ($kataLatih->result() as $kL) {
			array_push($arrayKataLatih, $kL->kata);
		}

		// echo "<pre>";
		// print_r($arrayKataLatih);
		// echo "</pre>";

		$jumlahKataDokumen = array_count_values($arrayKataLatih); /* Menghitung Kemunculan kata dalam dokumen */

		$arrayBobot = [];


		foreach ($kataLatih->result() as $kL) {
			$TF = $kL->frekuensi / $jumlahKata->jumlah;
			$IDF =  log10($jumalahBerita->jumlah / $jumlahKataDokumen[$kL->kata]);
			$bobot = [
				// 'kata_id' => $kL->kata_id,
				'kata' => $kL->kata,
				'berita_id' => $kL->berita_id,
				'bobot' => $TF * $IDF,
				// 'frekuensi' => $kL->frekuensi,
				// 'dokumen' => $jumlahKataDokumen[$kL->kata]
			];
			array_push($arrayBobot, $bobot);
			$this->db->insert('bobot_kata_latih', $bobot);
		}

		// redirect('admin');

		// echo "<pre>";
		// print_r($arrayBobot);
		// echo "</pre>";

		// foreach ($kata->result() as $k) {
		// 	$nilai = array_coun
		// 	echo $nilai;
		// }
	}

	public function knn($id = 0)
	{
		if ($id == 0) {
			redirect('admin');
		} else {
			// array untuk menyimpan id berita latih
			$arrayIdBeritaLatih = [];

			// mengambil data berita latih dan merubahnya manjadi array biasa
			$idBeritaLatih = $this->db->select('*')->from('berita_latih')->get()->result();
			foreach ($idBeritaLatih as $iBL) {
				array_push($arrayIdBeritaLatih, $iBL->id);
			}

			//jumlah data latih yang ada
			$jumlahBeritaLatih = count($arrayIdBeritaLatih);

			// jumlah kata dalam berita uji
			$jumlahKata = $this->db->query("SELECT SUM(frekuensi) as nilai FROM kata_latih WHERE berita_id=$id")->row();

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
			$kataUji = $this->db->select('kl.*,k.kata')
				->from('kata_latih kl')
				->join('kata k', 'k.id=kl.kata_id', 'LEFT')
				->where(['kl.berita_id' => $id])
				->get()
				->result();

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
						$bobotUji = ($kU->frekuensi / $jumlahKata->nilai) * (log10($jumlahBeritaLatih / ($bobotKataDiDokumen[$kU->kata] + 1)));
						$bobot = pow(($bobotlatih - $bobotUji), 2);
					} else {
						$bobot = 0;
					}
					$hasilBobot = $hasilBobot + $bobot;
				}
				echo "<pre>";
				print_r(sqrt($hasilBobot));
				echo "</pre>";
			}
		}
	}

	public function hitung_bobot_v2()
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
			$TF = $kL->frekuensi / $arrayJumlahKataPerDokumen[$idJumlahKata];
			$IDF =  log10($jumlahBerita->jumlah / $jumlahKataDokumen[$kL->kata]);
			$bobot = [
				'kata_id' => $kL->kata_id,
				'berita_id' => $kL->berita_id,
				'bobot' => $TF * $IDF,
			];
			// $this->db->insert('bobot_kata_latih', $bobot);
		}
	}

	function testKode()
	{
		$id = "id";
		$stat = "status";
		$command = escapeshellcmd('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\text.py ' . $id . ' ' . $stat);
		// $data = exec("python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\text.py"m,);
		$data = exec($command);
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}
