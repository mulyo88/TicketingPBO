<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Model_rkp_pembayaran extends CI_Model
{

    var $Company;
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->Company = $this->config->item('Company');

    }


    function dapat_data_rkp_pembayaran($data)
    {
        $JobNo = $data['JobNo'];
        $PrdAwal = $data['dari_tgl'];
        $PrdAkhir = $data['sampai_tgl'];

        $query_tgl = '';

        $NamaTerima = '';
        $BankTerima = '';
        $NoRekTerima = '';
        $TtlBayar = 0;
        $NamaTerima = '';
        $RekId = '';

        if ($JobNo == 0) {
            $query_tgl .= "SELECT A.*, B.JobNm FROM BLE A
            JOIN Job B ON A.JobNo = B.JobNo
            WHERE TglBayar>='$PrdAwal' AND TglBayar<='$PrdAkhir' AND Company='$this->Company' ORDER BY TglBayar";
        } else {
            $query_tgl .= "SELECT A.*, B.JobNm FROM BLE A 
            JOIN Job B ON A.JobNo = B.JobNo 
            WHERE A.JobNo='$JobNo' AND TglBayar>='$PrdAwal' AND TglBayar<='$PrdAkhir' ORDER BY TglBayar";
        }

        $query = $this->db->query($query_tgl);

        $myarray = array();
        $norut = 1;

        $sum_TotalBayar = 0;



        foreach ($query->result() as $row ) :
            $RekId = $row->RekId;

            if ($row->NmPenerimaTunai !='') {
                $BankTerima = "-";
                $NoRekTerima = "-";
            }else{
                $NamaTerima = $row->AtasNama;
                $BankTerima = $row->Bank;
                $NoRekTerima = $row->NoRek;
            }

            $BankKirim = '';
            $NoRekKirim = '';

            $get_rekening = $this->db->where('RekId',$RekId)->get('Rekening');

            if ($get_rekening->num_rows() > 0) {
                $row_rekening = $get_rekening->row_array();

                $BankKirim = $row_rekening['Bank'];
                $NoRekKirim = $row_rekening['NoRek'];
            }

            $JenisTrf = '';

            if ($row->CaraBayar !='' ) :

                if ($row->CaraBayar == 'CG' || $row->CaraBayar == 'CEK BG' )
                {
                    $JenisTrf = $row->NoCG;
                }else{
                    $JenisTrf = $row->JenisTrf;
                }

            endif;



            $myarray[] = array(
                'norut' => $norut,
                'TglBayar' => $row->TglBayar,
                'TipeForm' => $row->TipeForm,
                'Alokasi' => $row->Alokasi,
                'NoPD' => $row->NoPD,
                'JobNm' => $row->JobNm,
                'NamaTerima' => $NamaTerima,
                'NoRekTerima' => $NoRekTerima,
                'BankTerima' => $BankTerima,
                'Amount' => $row->Amount,
                'CaraBayar' => $row->CaraBayar,
                'BankKirim' => $BankKirim,
                'JenisTrf' => $JenisTrf,
                'NoRekKirim' => $NoRekKirim,
                'NoKO' => $row->NoKO,
                'Keterangan' => $row->Keterangan,
            );

            if ($row->Keterangan <> 'PINDAH DANA') {
                $TtlBayar += $row->Amount;
            }

            $norut++;
        endforeach;

        return array('myarray' => $myarray, 'TtlBayar' => $TtlBayar );


        // return $query->result_array();
    }

    function dapat_data_rkp_pembayaran_PDF($data)
    {
        $JobNo = $data['JobNo'];
        $PrdAwal = $data['dari_tgl'];
        $PrdAkhir = $data['sampai_tgl'];

        $query_tgl = '';

        if ($JobNo == 0) {
            $query_tgl .= "SELECT A.*, B.JobNm FROM BLE A
            JOIN Job B ON A.JobNo = B.JobNo
            WHERE TglBayar>='$PrdAwal' AND TglBayar<='$PrdAkhir' AND Company='$this->Company' ORDER BY TglBayar";
        } else {
            $query_tgl .= "SELECT A.*, B.JobNm FROM BLE A 
            JOIN Job B ON A.JobNo = B.JobNo 
            WHERE A.JobNo='$JobNo' AND TglBayar>='$PrdAwal' AND TglBayar<='$PrdAkhir' ORDER BY TglBayar";
        }

        $query = $this->db->query($query_tgl);
        return $query->result_array();
    }
}

/* End of file Model_rkp_pembayaran.php */
