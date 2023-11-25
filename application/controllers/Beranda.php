<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends CI_Controller
{
    public function index()
    {
        // echo password_hash('admin', PASSWORD_DEFAULT);
        $this->load->view('beranda/templates/header');
        $this->load->view('beranda/index');
        $this->load->view('beranda/templates/footer');
    }

    public function ujiBerita()
    {
        $this->load->view('beranda/templates/header');
        $this->load->view('beranda/uji_berita');
        $this->load->view('beranda/templates/footer');
    }

    public function ujiBeritaAct()
    {
        $this->form_validation->set_rules('link', 'Link Berita', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->ujiBerita();
        } else {
            $link = $this->input->post('link');
            $command_judul = escapeshellcmd('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\judul.py ' . $link);
            $command_isi = escapeshellcmd('python F:\Pribadi\Tugas_Akhir\ta_rini_python_code\isi.py ' . $link);
            $judul = exec($command_judul);
            $isi = exec($command_isi);
            $berita = [
                'judul' => $judul,
                'isi' => $isi,
                'link' => $this->input->post('link'),
            ];
            $kataKunci = $this->db->get('keyword');
            $foundKey = 0;
            foreach ($kataKunci->result() as $key) {
                if (preg_match("/$key->kata/", $isi, $matches, PREG_OFFSET_CAPTURE) === 1) {
                    $foundKey++;
                }
            }

            if ($foundKey == 0) {
                return $this->notMatch($berita);
            }

            $this->db->insert('berita_uji', $berita);
            $beritaId = $this->db->insert_id();
            redirect('algoritma/getDataUji/' . $beritaId);
        }
    }

    public function notMatch($berita = [])
    {
        $data['berita'] = $berita;
        $this->load->view('beranda/templates/header');
        $this->load->view('beranda/berita_not_match', $data);
        $this->load->view('beranda/templates/footer');
    }
}
