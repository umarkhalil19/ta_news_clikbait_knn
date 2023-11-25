<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') != 'login') {
            redirect('login');
        }
    }

    public function index()
    {
        // $this->load->view('welcome_message');
        $this->load->view('admin/templates/header');
        $this->load->view('admin/index');
        $this->load->view('admin/templates/footer');
    }

    public function kata()
    {
        // $data['kata'] = $this->db->get('kata');
        $query = "SELECT * FROM `kata` where LENGTH(kata) = 1";
        $data['kata'] = $this->db->query($query);
        $this->load->view('admin/templates/header');
        $this->load->view('admin/kata', $data);
        $this->load->view('admin/templates/footer');
    }

    public function set_stopword($id = 0)
    {
        if ($id == 0) {
            redirect('admin/kata');
        } else {
            $kata = $this->db->select('kata')->from('kata')->where(['id' => $id])->get()->row();
            $stopword = [
                'kata' => $kata->kata
            ];
            $this->db->insert('stopword', $stopword);
            $this->db->delete('kata', ['id' => $id]);
            $this->db->delete('kata_latih', ['kata_id' => $id]);
            $this->db->delete('bobot_kata_latih', ['kata_id' => $id]);
            redirect('admin/kata');
        }
    }

    public function berita_latih()
    {
        $data['berita'] = $this->db->get('berita_latih');
        $this->load->view('admin/templates/header');
        $this->load->view('admin/berita_latih', $data);
        $this->load->view('admin/templates/footer');
    }

    public function berita_latih_add()
    {
        $this->load->view('admin/templates/header');
        $this->load->view('admin/berita_latih_add');
        $this->load->view('admin/templates/footer');
    }

    public function berita_latih_act()
    {
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('link', 'Link', 'required|trim');
        // $this->form_validation->set_rule('', '', '')
        if ($this->form_validation->run() == FALSE) {
            $this->berita_latih_add();
        } else {
            $berita = [
                'judul' => $this->input->post('judul'),
                'link' => $this->input->post('link'),
                'klasifikasi' => $this->input->post('klasifikasi')
            ];
            $this->db->insert('berita_latih', $berita);
            $beritaId = $this->db->insert_id();
            redirect('algoritma/getDataLatih/' . $beritaId);
        }
    }

    public function berita_latih_detail($id = 0)
    {
        if ($id == 0) {
            redirect('admin/berita_latih');
        } else {
            $data['berita'] = $this->db->get_where('berita_latih', ['id' => $id]);
            $data['kata'] = $this->db->select('k.kata,kl.frekuensi')
                ->from('kata_latih kl')
                ->join('kata k', 'k.id=kl.kata_id')
                ->where(['berita_id' => $id])
                ->get();
            $this->load->view('admin/templates/header');
            $this->load->view('admin/berita_latih_detail', $data);
            $this->load->view('admin/templates/footer');
        }
    }

    function berita_latih_edit($id = 0)
    {
        if ($id == 0 || $id == '') {
            redirect('admin/berita_latih');
        } else {
            $data['berita'] = $this->db->get_where('berita_latih', ['id' => $id])->row();
            $this->load->view('admin/templates/header');
            $this->load->view('admin/berita_latih_edit', $data);
            $this->load->view('admin/templates/footer');
        }
    }

    function berita_latih_update()
    {
        $id = $this->input->post('id');
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('link', 'Link', 'required|trim');
        // $this->form_validation->set_rule('', '', '')
        if ($this->form_validation->run() == FALSE) {
            $this->berita_latih_add();
        } else {
            $berita = [
                'judul' => $this->input->post('judul'),
                'link' => $this->input->post('link'),
                'klasifikasi' => $this->input->post('klasifikasi')
            ];
            $this->db->update('berita_latih', $berita, ['id' => $id]);
            redirect('admin/berita_latih');
        }
    }

    function berita_latih_del($id = 0)
    {
        if ($id == 0 || $id == '') {
            redirect('admin/berita_latih');
        } else {
            $this->db->delete('berita_latih', ['id' => $id]);
            $this->db->delete('kata_latih', ['berita_id' => $id]);
            $this->db->delete('bobot_kata_latih', ['berita_id' => $id]);
            redirect('admin/berita_latih');
        }
    }

    public function berita_uji()
    {
        $data['berita'] = $this->db->get('berita_uji');
        $this->load->view('admin/templates/header');
        $this->load->view('admin/berita_uji', $data);
        $this->load->view('admin/templates/footer');
    }

    public function berita_uji_add()
    {
        $this->load->view('admin/templates/header');
        $this->load->view('admin/berita_uji_add');
        $this->load->view('admin/templates/footer');
    }

    function berita_uji_act()
    {
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('link', 'Link', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $this->berita_uji_add();
        } else {
            $berita = [
                'judul' => $this->input->post('judul'),
                'link' => $this->input->post('link'),
            ];
            $this->db->insert('berita_uji', $berita);
            $beritaId = $this->db->insert_id();
            redirect('algoritma/getDataUji/' . $beritaId);
        }
    }

    function hitungBobot()
    {
        $this->load->view('admin/templates/header');
        $this->load->view('admin/hitung_bobot');
        $this->load->view('admin/templates/footer');
    }

    function keyword()
    {
        $data['keyword'] = $this->db->get('keyword');
        $this->load->view('admin/templates/header');
        $this->load->view('admin/keyword', $data);
        $this->load->view('admin/templates/footer');
    }

    function keyword_add()
    {
        $this->load->view('admin/templates/header');
        $this->load->view('admin/keyword_add');
        $this->load->view('admin/templates/footer');
    }

    function keyword_act()
    {
        $this->form_validation->set_rules('kata', 'Kata Kunci', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->keyword_add();
        } else {
            $keyword = [
                'kata' => $this->input->post('kata'),
            ];
            $this->db->insert('keyword', $keyword);
            redirect('admin/keyword');
        }
    }

    function keyword_delete($id = 0)
    {
        if ($id == 0 || $id == '') {
            redirect('admin/keyword');
        } else {
            $this->db->delete('keyword', ['id' => $id]);
            redirect('admin/keyword');
        }
    }
    function cekDataLatih()
    {
        $datalatih = $this->db->select('*')->from('berita_latih')->get()->result();
        $dataLatihError = [];
        foreach ($datalatih as $d) {
            $kataLatih = $this->db->get_where('kata_latih', ['berita_id' => $d->id])->num_rows();
            if ($kataLatih === 1) {
                array_push($dataLatihError, $d->id);
            }
        }
        $data['berita'] = $datalatih;
        $data['error'] = $dataLatihError;
        $this->load->view('admin/templates/header');
        $this->load->view('admin/berita_latih_error', $data);
        $this->load->view('admin/templates/footer');
    }
}
