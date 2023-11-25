<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function index()
    {
        // echo password_hash('admin', PASSWORD_DEFAULT);
        $this->load->view('login');
    }

    public function auth()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Username', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $cekUsername = $this->db->get_where('users', ['username' => $username]);
            if (!empty($cekUsername->row())) {
                $dataUser = $cekUsername->row();
                $cekPassword = password_verify($password, $dataUser->password);
                if ($cekPassword) {
                    $session = [
                        'username' => $dataUser->username,
                        'nama' => $dataUser->nama,
                        'status' => 'login'
                    ];
                    $this->session->set_userdata($session);
                    redirect('admin');
                } else {
                    $this->session->set_flashdata('error', 'Username atau password salah');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('error', 'Akun tidak ditemukan');
                redirect('login');
            }
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('beranda');
    }
}
