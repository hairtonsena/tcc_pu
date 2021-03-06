<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comentario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('UTC');
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('manimaps/comentario_model');
        $this->load->model('manimaps/colaboracao_model');       
    }

    public function index() {
        redirect(base_url());
    }

    function verComentarios() {
        $idProblema = $_POST['idProblema'];

        $userLogado = FALSE;

        if ($this->session->userdata('local')) {
            $this->session->unset_userdata('local');
        }
        $dado = array(
            'problemaComentario' => $this->colaboracao_model->obterProblema($idProblema)->result(),
            'idProblema' => $idProblema,
        );
        if (($this->session->userdata('idCidadao')) && ($this->session->userdata('nomeCidadao')) && ($this->session->userdata('emailCidadao')) && ($this->session->userdata('senhaCidadao'))) {
            $userLogado = TRUE;


            $this->load->view('user_cidadao/comentario/formNovoComentario_view', $dado);
        } else {
            $this->load->view('user_cidadao/seguranca/linkLoginComentario_view', $dado);
        }

        $arr = array();
        $query = $this->comentario_model->obterComentarioPorColaboracao($idProblema)->result();
        foreach ($query as $qr) {
            $jaApoiei = 'nao';
            $jaReprovei = 'nao';
            if ($this->comentario_model->verificarUserApoioComentario($qr->idComentario, $this->session->userdata('idCidadao')) > 0) {
                $jaApoiei = 'sim';
            }

            if ($this->comentario_model->verificarUserReprovaComentario($qr->idComentario, $this->session->userdata('idCidadao')) > 0) {
                $jaReprovei = 'sim';
            }

            $qr->jaAproiei = $jaApoiei;
            $qr->jaReprovei = $jaReprovei;
            $arr[] = $qr;
        }

        $dados = array(
            'comentarios' => $arr,
            'userLogado' => $userLogado,
        );

        $this->load->view('user_cidadao/comentario/verComentario_view', $dados);
    }

    function salvarNovoComentarioProblema() {
        if (($this->session->userdata('idCidadao')) && ($this->session->userdata('nomeCidadao')) && ($this->session->userdata('emailCidadao')) && ($this->session->userdata('senhaCidadao'))) {

            $this->form_validation->set_rules('comentario', 'Comentário', 'required|min_length[9]');

            if ($this->form_validation->run() == FALSE) {
                echo "<script> alert('O comentário não foi realizado, texto muito curto.'); Tela.fecharModal(); </script>";
            } else {

                $comentario = strip_tags($this->input->post('comentario'));

                if (strlen($comentario) < 10) {
                    echo "<script> alert('O comentário não foi realizado, texto muito curto.'); Tela.fecharModal(); </script>";
                    exit();
                };

                $idProblema = $_POST['idProblema'];
               // $comentario = $_POST['comentario'];

                $this->session->userdata('idCidadao');

                $data = date('y-m-d');

                $dadosComentario = array(
                    'idComentario' => '',
                    'textoComentario' => $comentario,
                    'dataComentario' => $data,
                    'idProblema' => $idProblema,
                    'idCidadao' => $this->session->userdata('idCidadao')
                );

                $this->comentario_model->salvarNovoComentario($dadosComentario);
                echo '<script>   var colabocaoCidadao = 0;
                if ($("#minhasColaboracoes").is(\':checked\', true)) {
                    colabocaoCidadao = 1;
                } else {
                    colabocaoCidadao = 0;
                }
                var status = $("#status").val();
                var categoria = $("#categoria").val();
                var ordem = $("#ordem").val();

                Conteudo.generateRandomMarkers(status, categoria, ordem, colabocaoCidadao, 0);
                Problema.verTodosComentarios(\'' . $idProblema . '\'); </script>';
            }
        }
    }

    function apoiaComentario() {
        if (($this->session->userdata('idCidadao')) && ($this->session->userdata('nomeCidadao')) && ($this->session->userdata('emailCidadao')) && ($this->session->userdata('senhaCidadao'))) {

            $idComentario = $_POST['idComentario'];

            if ($this->comentario_model->verificarUserApoioComentario($idComentario, $this->session->userdata('idCidadao')) == 0) {


                $dados = array(
                    'idComentario' => $idComentario,
                    'idCidadao' => $this->session->userdata('idCidadao'),
                    'statusApoio' => '1',
                );
                $this->comentario_model->apoiarComentario($dados);

                // echo "<script> Problema.verTodosComentario('" . $idProblema . "') </script>";
            }
        }
    }

    function reprovarComentario() {

        if (($this->session->userdata('idCidadao')) && ($this->session->userdata('nomeCidadao')) && ($this->session->userdata('emailCidadao')) && ($this->session->userdata('senhaCidadao'))) {

            $idComentario = $_POST['idComentario'];



            if ($this->comentario_model->verificarUserReprovadoComentario($idComentario, $this->session->userdata('idCidadao')) == 0) {

                $dados = array(
                    'idComentario' => $idComentario,
                    'idCidadao' => $this->session->userdata('idCidadao'),
                    'statusReprova' => '1',
                );

                $this->comentario_model->reprovaComentario($dados);
            }
        }
    }

}

