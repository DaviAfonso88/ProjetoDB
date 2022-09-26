<?php

namespace App\Controllers;

use CodeIgniter\HTTP\request;
use CodeIgniter\controller;

class Main extends Controller
{
    public function index()
    {


        $data['dados'] = $this->projeto_getalldados();
        return view('home_projeto', $data);
    }


    public function new_dados()
    {

        return view('new_dados');
    }


    public function newdadossubmition()
    {

        if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
            return redirect()->to(site_url('public/main'));
        }



        $params = [
            'dados' => $this->request->getPost('data_name')
        ];

        $db = db_connect();
        $db->query(" INSERT INTO dados(job, datetime_created)
          VALUES(:dados:, NOW())
        ", $params);
        $db->close();

        return redirect()->to(site_url('public/main'));
    }




    private function projeto_getalldados()
    {
        $db = db_connect();
        $dados = $db->query("SELECT * FROM dados")->getResultObject();
        $db->close();

        return $dados;
    }


    public function dadosdone($id_job = 1)
    {

        $params = [
            'id_job' => $id_job
        ];

        $db = db_connect();
        $db->query("
        UPDATE dados
        SET datetime_finished = NOW(),
        datetime_updated = NOW()
        WHERE id_job = :id_job:
        ", $params);
        $db->close();

        return redirect()->to(site_url('public/main'));
    }


    public function editdados($id_job = 1)
    {

        $params = [
            'id_job' => $id_job

        ];
        $db = db_connect();
        $dados = $db->query("
        SELECT * FROM dados WHERE id_job = :id_job:
        ", $params)->getResultObject();
        $db->close();


        $data['job'] = $dados[0];
        return view('edit_dados', $data);
    }





    public function editdadossubmition()
    {
        $params = [
            'id_job' => $this->request->getPost('id_job'),
            'job' => $this->request->getPost('job_name'),

        ];

        $db = db_connect();
        $db->query("
        UPDATE dados
        SET job = :job:,
        datetime_updated = NOW()
        WHERE id_job = :id_job:
        ", $params);
        $db->close();

        return redirect()->to(site_url('public/main'));
    }




    public function deletedados($id_job = 1)
    {

        $params = [
            'id_job' => $id_job
        ];

        $db = db_connect();
        $data['job'] = $db->query("
           SELECT * FROM dados WHERE id_job = :id_job:
        
        ", $params)->getResultObject()[0];
        $db->close();


        return view('delete_dados', $data);
    }



    public function deletedadosconfirm($id_job)
    {

        // delete da tarefa na bd 

        $params = [
            'id_job' => $id_job
        ];

        $db = db_connect();
        $db->query("DELETE FROM dados WHERE id_job = :id_job:", $params);
        $db->close();



        // atualização da página incial

        return redirect()->to(site_url('public/main'));
    }
}
