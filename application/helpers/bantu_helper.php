<?php


function no_ko_buatan($JobNo = '', $Alokasi = '')
{
    $CI = &get_instance();
    $query = $CI->db->query(" SELECT JobNo,Alokasi,CounterKO FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY CounterKO DESC ")->row_array();

    $CounterKO = $query['CounterKO'];


    $nomor_urut = (int) $CounterKO + 1;
    $gabung = 'KO' . $JobNo.$nomor_urut;
    // $gabung = 'KO' . $JobNo . '0' . $nomor_urut;

    return $gabung;
}

function dapat_data_job($JobNo)
{
    $CI = &get_instance();

    $query =  $CI->db->query(" SELECT * ,CONCAT_WS(' - ',JobNo,JobNm) as JobDetail  FROM Job WHERE JobNo='$JobNo' ")->row_array();
    return $query;
}

function list_job_alokasi($Alokasi = '')
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('MIS_LOGGED_ID');

    $isi_where = '';

    if ($Alokasi !== '') {
        $isi_where = "WHERE ax.Alokasi='$Alokasi'";
    }

    $query = "select ax.Alokasi, bx.Keterangan, CONCAT_WS(' - ',ax.Alokasi,bx.Keterangan) as AlokasiGabung from
    (select item as Alokasi 
    from
    dbo.SplitString (
    (select top 1 a.AksesAlokasi from (select * from Login) as a
    left outer join
    (select * from Alokasi) as b
    on b.Alokasi = a.AksesAlokasi
    Where a.UserID= '$user_id')
    , ',')
    ) as ax
    left outer join 
    Alokasi as bx
    on bx.Alokasi = ax.Alokasi
    " . $isi_where . "
    group by ax.Alokasi, bx.Keterangan";

    if ($Alokasi !== '') {
        $eksekusi = $CI->db->query($query)->row_array();
        return $eksekusi;
    } else {
        $eksekusi = $CI->db->query($query)->result_array();
        return $eksekusi;
    }
}

function list_tipe_form($Alokasi)
{
    $CI = &get_instance();
    $query = $CI->db->query("SELECT CONCAT_WS(' - ',TipeForm,Keterangan) as tipe_form_gabung, TipeForm FROM TipeForm  WHERE Alokasi='$Alokasi'")->result_array();
    return $query;
}

function list_data_bank()
{
    $CI = &get_instance();

    $query = $CI->db->get('Bank');

    return $query->result_array();
}

function list_kode_rap($JobNo, $Alokasi)
{
    $CI = &get_instance();
    $query = $CI->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe != 'Header'")->result_array();
    return $query;
}

function list_kode_rap_bukan_perulangan($JobNo, $Alokasi)
{
    $CI = &get_instance();
    $query = $CI->db->query("SELECT CONCAT_WS(' - ',KdRAP,Uraian) as kdrap_detail FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe != 'Header'")->row_array();
    return $query['kdrap_detail'];
}


function Dapatkan_KSO($JobNo)
{

    $CI = &get_instance();
    $myquery = $CI->db->query("SELECT KSO FROM Job WHERE JobNo='$JobNo' ");
    $KSO = '';
    if ($myquery->num_rows() > 0) {
        $mydata = $myquery->row_array();
        $KSO = $mydata['KSO'];
    }
    return $KSO;
}

function cek_pddtl_sementara($NoPD)
{

    $CI = &get_instance();
    $Company = $CI->config->item('Company');
    $query = $CI->db->query(" SELECT * FROM table_pd_detail_sementara WHERE NoPD='$NoPD' AND Company='$Company' ");
    return $query->num_rows();
}

function cek_pddtl_sementara_mdl_edit($NoPD)
{

    $CI = &get_instance();
    $query = $CI->db->query(" SELECT * FROM PdDtl WHERE NoPD='$NoPD' ");
    return $query->num_rows();
}

function CekNoPD($NoPD)
{
    $CI = &get_instance();

    $query = $CI->db->select('NoPD')->from('PdHdr')->where('NoPD', $NoPD)->get();
    if ($query->num_rows() > 0) {
        return 'ada';
    } else {
        return 'tidak';
    }
}

function pembuatan_sesi_sementara($JobNo, $Alokasi)
{
    $CI = &get_instance();
    $CI->session->set_flashdata('sesi', 'mysesi');
    $CI->session->set_flashdata('JobNo', $JobNo);
    $CI->session->set_flashdata('Alokasi', $Alokasi);
}

function pesan_alert($class = '', $message = '')
{
    $CI = &get_instance();
    if ($class != '' && $message != '') {
        $CI->session->set_flashdata('message', '<div class="col-12">
            <div class="alert alert-' . $class . ' alert-dismissible bg-' . $class . ' text-white border-0 fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>' . $message . '</div></div>');
    }
}


function cek_approve_AK($JobNo = '', $Alokasi = '', $NoPD = '')
{

    $CI = &get_instance();
    $status = FALSE;
    $query_ak = $CI->db->query(" SELECT TOP 1 NoPD,ApprovedByAK FROM PdHdr WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Alokasi='$Alokasi' AND ApprovedByAK IS NOT NULL");
    if ($query_ak->num_rows() > 0) {
        $status = TRUE;
    }
    return $status;
}


function cek_approve_KK_atau_KT($JobNo = '', $Alokasi = '', $NoPD = '', $bagian = '')
{

    $CI = &get_instance();
    $status = '';
    $ubah = '';
    if ($bagian == 'keuangan') {
        $ubah = 'ApprovedByKK';
    }

    if ($bagian == 'lapangan') {
        $ubah = 'ApprovedByKT';
    }




    $query_kk_atau_kt = $CI->db->query(" SELECT TOP 1 NoPD," . $ubah . " FROM PdHdr WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Alokasi='$Alokasi' AND ApprovedByAK IS NOT NULL AND " . $ubah . " IS NOT NULL ");
    if ($query_kk_atau_kt->num_rows() > 0) {

        if ($ubah == 'ApprovedByKK') {
            $status = 'Approved KK';
        }

        if ($ubah == 'ApprovedByKT') {
            $status = 'Approved KT';
        }
    }

    return $status;
}

function cek_sudah_ada_pembayaran_atau_belum($JobNo = '', $Alokasi = '', $NoPD = '')
{

    $CI = &get_instance();
    $status = FALSE;
    $query_ble = $CI->db->query("SELECT TOP 1 * FROM BLE WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC");
    if ($query_ble->num_rows() > 0) {
        $status = TRUE;
    }
    return $status;
}

function KodeVendor()
{
    $CI = &get_instance();
    $CI->db->select('MAX(RIGHT(Vendor.VendorID, 4)) as VendorId', FALSE);
    $CI->db->order_by('VendorID', 'DESC');
    $CI->db->limit(1);
    $query = $CI->db->get('Vendor');
    if ($query->num_rows() <> 0) {
        $data = $query->row();
        $kode = intval($data->VendorId) + 1;
    } else {
        $kode = 1;
    }
    $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
    $kodetampil = "VEN" . $batas;
    return $kodetampil;
}


function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return $hasil;
}

function KhususDecimal($angka)
{
    return floatval(preg_replace('/[^\d.]/', '', $angka));
}

function UbahUangKhususDecimal($angka)
{
    return number_format($angka, 2, '.', ',');
}
function decimal_coba($angka)
{
    return number_format((float)$angka, 3, '.', '');
}


function dapat_list_job($pengurutan='')
{

    $CI = &get_instance();
    $user_id = $CI->session->userdata('MIS_LOGGED_ID');
    $Company = $CI->config->item('Company');

    $var_pengurutan = '';
    if($pengurutan !=''){
        $var_pengurutan = 'ORDER BY ax.JobNo DESC';
    }

    $query = $CI->db->query("

        select ax.JobNo, bx.JobNm from
        (select item as JobNo
        from
        dbo.SplitString ((select top 1 a.AksesJob from (select * from Login) as a
        left outer join
        (select * from Job) as b
        on b.JobNo = a.AksesJob
        Where a.UserID= '$user_id'), ',')) as ax
        left outer join 
        Job as bx
        on bx.JobNo = ax.JobNo WHERE bx.Company='$Company' group by ax.JobNo, bx.JobNm ".$var_pengurutan."

        ");

    return $query->result_array();
}


function dapat_list_vendor()
{
    $CI = &get_instance();
    $query = $CI->db->query("SELECT VendorId,VendorNm FROM Vendor  ORDER BY TimeEntry DESC ")->result_array();
    return $query;
}

function randomString($length)
{
    $str        = "";
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $max        = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

function ubah_counter_ko($JobNo, $Alokasi)
{
    $CI = &get_instance();

    $cek_counter = $CI->db->query("
        SELECT TOP 1 JobNo, Alokasi, CounterKO FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY CounterKO DESC  ");

    if ($cek_counter->num_rows() == 0) {
        $UserEntry = $CI->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,
            'CounterKO' => 1,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        $CI->db->insert('Counter', $data);
    } else {

        $ubah_counter = $CI->db->query(" SELECT TOP 1 JobNo, Alokasi, CounterKO FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY CounterKO DESC  ")->row_array();

        $CounterKO = $ubah_counter['CounterKO'];
        $jumlah = (int) $CounterKO + 1;
        $data = array(
            'CounterKO' => $jumlah
        );

        $where = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi
        );

        $CI->db->set($data)->where($where)->update('Counter');
    }
}



function cek_approve_kohdr($NoKO)
{
    $CI = &get_instance();
    $query = $CI->db->query(" SELECT ApprovedBy FROM KoHdr WHERE NoKO='$NoKO' ")->row_array();

    if ($query['ApprovedBy'] == '' || $query['ApprovedBy'] == NULL) {
        return 0;
    } else {
        return 1;
    }
}


function ppn_baru_decimal()
{
    //contoh cara hitung = (20000/1.11) * 100   
    $CI = &get_instance();
    $PPN = 1.11;
    return $PPN;
}


function ppn_baru_angka()
{
    //contoh cara hitung = (20000/1.11) * 100   
    $CI = &get_instance();
    $PPN = 0.11;
    return $PPN;
}
function HilangkanDecimal($angka)
{
    return floatval(preg_replace('/[^\d.]/', '', $angka));
}


function pembuatan_no_ref_pada_pd_untuk_lapangan($JobNo, $Alokasi)
{
    $CI = &get_instance();
    $kunci_lapangan = 'L';

    $nomor_buatan = '';

    $query = $CI->db->query(" SELECT TOP 1 NoRef FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC ");
    $row = $query->row_array();

    $NoRef = $row['NoRef'];
    $potong = substr($NoRef, -5);
    $gabung_lagi = (int) $potong + 1;
    $nomor_buatan = '00' . $gabung_lagi;


    $penggabungan = $kunci_lapangan . $JobNo . $Alokasi . $nomor_buatan;

    return $penggabungan;
}


function dapat_nomor_urut_pd_detail($nama_table, $NoPD)
{
    $CI = &get_instance();
    $query = $CI->db->query(" SELECT TOP 1 * FROM " . $nama_table . " WHERE NoPD='$NoPD'  ");

    $nomor = 0;
    if ($query->num_rows() == 0) {
        $nomor = 1;
    } else {
        $row = $query->row_array();
        $NoUrut = $row['NoUrut'];
        $nomor = (int) $NoUrut + 1;
    }
    return $nomor;
}

function Cek_Detail_PD($NoPD, $nama_table)
{

    $CI = &get_instance();
    $query = $CI->db->query(" SELECT * FROM " . $nama_table . " WHERE NoPD='$NoPD'  ");
    return $query->num_rows();
}


function dapat_list_job_untuk_select()
{
    $CI = &get_instance();
    $UserID = $CI->session->userdata('MIS_LOGGED_ID');
    $query = $CI->db->query("

        SELECT ax.JobNo, bx.JobNm FROM
        (SELECT item as JobNo
            FROM
            dbo.SplitString ((SELECT TOP 1 a.AksesJob FROM (SELECT * FROM Login) as a
                LEFT OUTER join
                (SELECT * FROM Job) as b
                ON b.JobNo = a.AksesJob
                WHERE a.UserID= '$UserID'), ',')) as ax
        LEFT OUTER JOIN 
        Job as bx
        on bx.JobNo = ax.JobNo GROUP BY ax.JobNo, bx.JobNm

        ")->result_array();
    return $query;
}

function dapat_list_job_mdh()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('MIS_LOGGED_ID');

    $akses = $CI->db->query("SELECT AksesJob FROM Login WHERE UserID='$user_id' ")->row_array();

    $explode = explode(",", $akses['AksesJob']);
    $implode = "'" . implode("','", $explode) . "'";
    $Company = $CI->config->item('Company');
    return $CI->db->query("SELECT * From Job Where TipeJob='Project' and Company='$Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result_array();
}

function dapat_isi_dari_aksesjob()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('MIS_LOGGED_ID');
    $akses = $CI->db->query("SELECT AksesJob FROM Login WHERE UserID='$user_id' ")->row_array();

    $explode = explode(",", $akses['AksesJob']);
    $implode = "'" . implode("','", $explode) . "'";
    return $implode;
}


function dapat_rekening($RekId)
{
    $CI = &get_instance();
    $query = $CI->db->query("SELECT * FROM Rekening WHERE RekId='$RekId' ");
    $hasil = '';
    if ($query->num_rows() > 0) {
        $row = $query->row_array();
        $Bank = $row['Bank'];
        $AtasNama = $row['AtasNama'];
        $NoRek = $row['NoRek'];
        $hasil = $Bank . ' / ' . $AtasNama . ' / ' . $NoRek;
        return $hasil;
    } else {
        return $hasil;
    }
}

function get_rek($myrek)
{
    $CI = &get_instance();
    $myquery = $CI->db->query("SELECT * FROM Rekening WHERE RekId='$myrek' ");
    if ($myrek == 0) {
        return array('Bank' => '', 'NoRek' => '');
    } else {
        if ($myquery->num_rows() > 0) {

            return $myquery->row_array();
        } else {
            return array('Bank' => '', 'NoRek' => '');
        }
    }
}

function dapat_total_untuk_rpt_penyerapan_rap($JobNo, $Alokasi, $Header)
{
    $CI = &get_instance();
    $query = $CI->db->query("
        SELECT SUM(HrgSatuan * Vol) as Total FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Detail' AND Header='$Header' GROUP BY Header
        ");
    $data = 0;

    if ($query->num_rows() > 0) {
        $row = $query->row_array();
        $data = $row['Total'];
    } else {
        $data = 0;
    }

    return $data;
}

function dapat_detail_penyerapan_rap($JobNo, $Alokasi, $KdRAP, $dari_tgl, $sampai_tgl)
{
    $CI = &get_instance();
    $query = $CI->db->query("
        SELECT A.* FROM PdDtl A 
        JOIN PdHdr B ON A.NoPD=B.NoPD
        WHERE B.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND 
        B.RejectBy IS NULL AND A.KdRAP='$KdRAP' AND 
        B.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl'")->result_array();
    $TotalTerserap = 0;
    foreach ($query as $data) {

        if ($data['NoPJ'] != '' || $data['NoPJ'] != NULL) {
            $TotalTerserap += $data['PjVol'] * $data['PjHrgSatuan'];
        }
    }

    return $TotalTerserap;
}

function dapat_total_serap_rap($JobNo, $Alokasi, $KdRAP, $dari_tgl, $sampai_tgl, $Tipe)
{
    $CI = &get_instance();

    $TotalTerserap = 0;

    if ($Tipe == 'Header') {

        $sintak = "SELECT A.* FROM PdDtl A JOIN PdHdr B ON A.NoPD=B.NoPD WHERE
        B.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.RejectBy IS NULL AND A.KdRAP='$KdRAP' AND
        B.TglPD>='$dari_tgl' AND B.TglPD<='$sampai_tgl' ";
        $query = $CI->db->query($sintak)->result_array();
        foreach ($query as $data) {

            if ($data['NoPJ'] != NULL) {
                $TotalTerserap += $data['PjVol'] * $data['PjHrgSatuan'];
            } else {
                $TotalTerserap = 79797;
            }
        }
    } else {
        $sintak = "SELECT A.* FROM PdDtl A JOIN PdHdr B ON A.NoPD=B.NoPD WHERE
        B.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.RejectBy IS NULL AND A.KdRAP='$KdRAP' AND
        B.TglPD>='$dari_tgl' AND B.TglPD<='$sampai_tgl' ";
        $query = $CI->db->query($sintak)->result_array();
        foreach ($query as $data) {
            if ($data['NoPJ'] != NULL) {
                $TotalTerserap += $data['PjVol'] * $data['PjHrgSatuan'];
            } else {
                $TotalTerserap = 999999;
            }

            // $TotalTerserap = $data['PjVol'] * $data['PjHrgSatuan'];
        }
    }

    return $TotalTerserap;
}

function coba_coba($JobNo, $Alokasi, $KdRAP, $dari_tgl, $sampai_tgl, $Tipe)
{
    $CI = &get_instance();
    $TotalTerserap = 0;

    if ($Tipe == 'Header') {

        $query = $CI->db->query("SELECT a.PjVol,a.PjHrgSatuan,a.NoPJ,
            (CASE
                WHEN a.NoPJ IS NOT NULL THEN (a.PjVol * a.PjHrgSatuan)
                ELSE 0
                END) as 'apapa'
            from PdDtl a 
            left join PdHdr b ON a.NoPD=B.NoPD
            WHERE b.JobNo='$JobNo' AND b.Alokasi='$Alokasi' AND b.RejectBy IS NULL AND a.KdRAP='$KdRAP' AND b.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl'")->result_array();

        foreach ($query as $data_saya) {
            if ($data_saya['NoPJ'] <> NULL) {
                $TotalTerserap = $data_saya['NoPJ'];
            } else {
                $TotalTerserap = 8888888888;
            }
        }
        return $TotalTerserap;
    } else {
        $query = $CI->db->query("SELECT a.PjVol,a.PjHrgSatuan,a.NoPJ,
            (CASE
                WHEN a.NoPJ IS NOT NULL THEN (a.PjVol * a.PjHrgSatuan)
                ELSE 0
                END) as 'apapa'
            from PdDtl a 
            left join PdHdr b ON a.NoPD=B.NoPD
            WHERE b.JobNo='$JobNo' AND b.Alokasi='$Alokasi' AND b.RejectBy IS NULL AND a.KdRAP='$KdRAP' AND b.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl'")->result_array();

        foreach ($query as $data_saya) {

            if ($data_saya['NoPJ'] <> NULL) {
                $TotalTerserap = $data_saya['NoPJ'];
            } else {
                $TotalTerserap = 777777;
            }
        }
        return $TotalTerserap;
    }
}

function percobaan_penyerapan_rap($Tipe, $JobNo, $Alokasi, $KdRAP, $dari_tgl, $sampai_tgl)
{
    $CI = &get_instance();
    // $JobNo = '21570';
    // $Alokasi='B';
    // $KdRAP = 'I.2';
    // $dari_tgl =  date('Y-m-d',strtotime('2022-01-01'));
    // $sampai_tgl =  date('Y-m-d',strtotime('2022-01-31'));

    if ($Tipe == 'Header') {
        // $q_saya = $this->db->query("SELECT FROM PdDtl a
        // INNER JOIN PdHdr b ON a.NoPD=b.NoPD
        // WHERE a.KdRAP='$KdRAP' AND b.JobNo='$JobNo' AND
        // b.Alokasi='$Alokasi' AND b.RejectBy IS NULL AND 
        // b.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl' AND a.NoPJ IS NOT NULL");
    }

    if ($Tipe == 'Detail') {
        $query = $CI->db->query("
            SELECT SUM(a.PjVol * a.PjHrgSatuan) as TotalPJ
            FROM PdDtl a
            INNER JOIN PdHdr b ON a.NoPD=b.NoPD
            WHERE a.KdRAP='$KdRAP' AND b.JobNo='$JobNo' AND
            b.Alokasi='$Alokasi' AND b.RejectBy IS NULL AND 
            b.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl' AND a.NoPJ IS NOT NULL")->row();



        return $query->TotalPJ;
    }

    // $nilai = 0;
    // if($Tipe == 'Header'){
    //     $nilai = 0;
    // }else{
    //     $query = $CI->db->query("
    //     SELECT SUM(a.PjVol * a.PjHrgSatuan) as TotalPJ
    //     FROM PdDtl a
    //     INNER JOIN PdHdr b ON a.NoPD=b.NoPD
    //     WHERE a.KdRAP='$KdRAP' AND b.JobNo='$JobNo' AND
    //     b.Alokasi='$Alokasi' AND b.RejectBy IS NULL AND 
    //     b.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl' AND a.NoPJ IS NOT NULL")->row();



    //     $nilai = $query->TotalPJ;
    // }

    // return $nilai;
}
function tes_lagi($KdRAP, $Tipe)
{
    $CI = &get_instance();



    $ok = 0;
    $Total = 0;
    $terserap = 0;
    if ($Tipe == 'Header') {

        $query = $CI->db->query("
            SELECT a.*,(a.PjVol * a.PjHrgSatuan) as total FROM PdDtl A JOIN PdHdr B ON A.NoPD=B.NoPD WHERE
            B.JobNo='21570' AND B.Alokasi='B' AND B.RejectBy IS NULL AND A.KdRAP='$KdRAP' AND 
            B.TglPD>='2022-01-01' AND B.TglPD<='2022-01-31' AND a.NoPJ IS NOT NULL 
            ")->result_array();


        foreach ($query as $data) {
            // if(!is_null($data['NoPJ'])){
            // $ok = $data['total'];
            // }

        }
    } else {
        $query = $CI->db->query("
            SELECT a.* FROM PdDtl A JOIN PdHdr B ON A.NoPD=B.NoPD WHERE
            B.JobNo='21570' AND B.Alokasi='B' AND B.RejectBy IS NULL AND A.KdRAP='$KdRAP' AND 
            B.TglPD>='2022-01-01' AND B.TglPD<='2022-01-31' AND a.NoPJ IS NOT NULL
            ")->result_array();

        foreach ($query as $data) {
            // if(!is_null($data['NoPJ'])){
            $ok += $data['PjVol'] * $data['PjHrgSatuan'];
            // }

        }
    }


    return $ok;
}

function new_decimal($nilai)
{
    return number_format($nilai, 2, '.', ',');
}

function new_decimal_persen($nilai)
{
    return number_format($nilai, 1, '.', ',');
}




function tgl_baru_tahun_pendek($tgl)

{
    if (is_null($tgl)) {
        return '';
    } else {
        return date('d-M-y', strtotime($tgl));
    }
    return date('d-M-y', strtotime($tgl));
}

function tgl_baru_tahun_pendek_dengan_waktu($mytgl)
{
    if ($mytgl == '') {
        return '';
    } else {
        $UbahTgl = date('d-M-y H:i', strtotime($mytgl));
        return $UbahTgl;
    }
}


function BuatDecimal($number){
    return number_format((float)$number, 2, '.', '');
}

function HapusKomaPHP($value){
    return str_replace( ',', '', $value );
}

function TanggalSaya($mytgl)
{
    if ($mytgl == '') {
        return '';
    } else {
        $UbahTgl = date('d-M-Y', strtotime($mytgl));
        return $UbahTgl;
    }
}

function lastOfMonth($year, $month) {
    return date("Y-m-d", strtotime('-1 second', strtotime('+1 month',strtotime($month . '/01/' . $year. ' 00:00:00'))));
}

function  getBulan($bln)
{
	switch ($bln) {
		case  1:
			return  "Januari";
			break;
		case  2:
			return  "Februari";
			break;
		case  3:
			return  "Maret";
			break;
		case  4:
			return  "April";
			break;
		case  5:
			return  "Mei";
			break;
		case  6:
			return  "Juni";
			break;
		case  7:
			return  "Juli";
			break;
		case  8:
			return  "Agustus";
			break;
		case  9:
			return  "September";
			break;
		case  10:
			return  "Oktober";
			break;
		case  11:
			return  "November";
			break;
		case  12:
			return  "Desember";
			break;
	}
}

function BulanIndonesia($tanggal){


    $tanggal = ($tanggal == '' || $tanggal == NULL || $tanggal == 0) ? '' : date('Y-'.$tanggal.'-d');
    if ($tanggal == '') {
        return '';
        exit;
    }
    $bulan = array (
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $bulan[ (int)$pecahkan[1] ];
}

function getTermin($JobNo){
    $CI =& get_instance();

    $query = $CI->db->query("

        SELECT SUM(TerminInduk) as Amount From TerminInduk Where JobNo='$JobNo' 
        ")->result();
    $Total = 0;
    foreach($query as $data){
        $Total = $data->Amount;
    }
    
    return $Total;

}



if (!function_exists('number_to_str')) {

    function  number_to_str($number){

        $convertion = [1=>'A', 2 =>'B', 3=>'C', 4=>'D', 5=>'e', 6=>'f', 7=>'g', 8=>'h', 9=>'i', 0=>'j'];
        $array_data = str_split($number);
        $new_data   = '';
        foreach ($array_data as  $value) {
            $new_data .= $convertion[$value]."";
        }
        return $new_data;

    }
}

function Data_job_untuk_report(){
    $CI =& get_instance();
    $Company = $CI->config->item('Company');
    $query = $CI->db->query("SELECT JobNo,JobNm, CONCAT_WS(' - ',JobNo,JobNm) as Jobgabung FROM Job WHERE Company='$Company'")->result();
    return $query;

}

function SemuaVendor($VendorId=''){
    $CI =& get_instance();

    $query_vendor_id = '';
    if ($VendorId !='') {
        $query_vendor_id = "WHERE VendorId='$VendorId' ";
    }

    $query = $CI->db->query("SELECT VendorId,VendorNm, CONCAT_WS(' - ',VendorId,VendorNm) as vendorgabung FROM Vendor ".$query_vendor_id." ORDER BY VendorNm")->result();
    return $query;

}

function BuatNoRef($JobNo='',$Alokasi=''){

    if ($JobNo == '') {
        return 'No. Job tidak tersedia';
        exit;
    }

    if ($Alokasi=='') {
        return 'Alokasi tidak tersedia';
        exit;
    }

    $CI =& get_instance();

    $nomor = '';

    $query = $CI->db->query(" SELECT TOP 1 NoRef FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoRef DESC ");
    if ($query->num_rows() == 0) {
        $nomor = '00001';
    }else{
        $row = $query->row();
        $NoRef = $row->NoRef;
        $nomor = substr($NoRef, -5,5);
    }
    ;

    $gabung = 'L'.$JobNo.$Alokasi;

    $coba = null;
    $nilai = abs($nomor);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 10) {

        $nilai++;
        $coba = '0000'.$nilai;
        if ($nilai == 10) {
            $coba = '000'.$nilai;
        }

    }  else if ($nilai < 100) {
        $nilai++;
        $coba = '000'.$nilai;
        if ($nilai == 100) {
            $coba = '00'.$nilai;
        }

    } else if ($nilai < 1000) {
        $nilai++;
        $coba = '00'.$nilai;
        if ($nilai == 1000) {
            $coba = '0'.$nilai;
        }

    } else if ($nilai < 10000) {
        $nilai++;
        $coba = '0'.$nilai;
        if ($nilai == 10000) {
            $coba = $nilai;
        }

    }else{
        $coba = $nilai;
    }

    if ($coba > 10000) {
        return 'terjadi kesalahan';
    }

    return $gabung.$coba;

}

function getAllJabatan()
	{
		$CI =& get_instance();
		return $query = $CI->db->query('SELECT PositionName FROM JobPosition')->result();
	}



    function tbl_logo(){
        $CI =& get_instance();
        if (!$CI->db->table_exists('tbl_logo') )
        {

            $CI->db->query("CREATE TABLE tbl_logo(
                [LogoID] [bigint] identity NOT NULL,
                [LogoFoto] [ntext] NULL,
            )");

        }
    }

    function TglIndonesia($tanggal, $show_day = false, $show_time = false)
{
	// Nama-nama bulan dan hari dalam Bahasa Indonesia
	$nama_hari = [
		'Sunday'    => 'Minggu',
		'Monday'    => 'Senin',
		'Tuesday'   => 'Selasa',
		'Wednesday' => 'Rabu',
		'Thursday'  => 'Kamis',
		'Friday'    => 'Jumat',
		'Saturday'  => 'Sabtu'
	];

	$nama_bulan = [
		1  => 'Januari',
		2  => 'Februari',
		3  => 'Maret',
		4  => 'April',
		5  => 'Mei',
		6  => 'Juni',
		7  => 'Juli',
		8  => 'Agustus',
		9  => 'September',
		10 => 'Oktober',
		11 => 'November',
		12 => 'Desember'
	];

	try {
		// Jika kosong atau null, return "-"
		if (empty($tanggal)) {
			return '-';
		}

		// --- Deteksi dan Buat DateTime Object ---
		$date = null;
		if ($tanggal instanceof DateTime) {
			$date = $tanggal;
		} elseif (is_numeric($tanggal)) {
			// Asumsi timestamp ini UTC, lalu set timezone
			$date = (new DateTime())->setTimestamp((int)$tanggal);
		} elseif (is_string($tanggal)) {
			// Normalisasi string (hilangkan spasi)
			$tanggal = trim($tanggal);

			// Cek format yang valid
			if (!strtotime($tanggal)) {
				return '-';
			}
			$date = new DateTime($tanggal);
		} else {
			return '-';
		}

		// --- Set Timezone ke WIB ---
		$timezone = new DateTimeZone('Asia/Jakarta'); // Jakarta adalah representasi WIB
		$date->setTimezone($timezone);

		// --- Ambil Bagian Tanggal ---
		$day = (int) $date->format('d');
		$month = (int) $date->format('m');
		$year = $date->format('Y');

		// --- Format Tanggal Dasar ---
		$tanggal_id = $day . ' ' . $nama_bulan[$month] . ' ' . $year;

		// --- Tambahkan Hari (Opsional) ---
		if ($show_day) {
			$hari_en = $date->format('l');
			$hari_id = isset($nama_hari[$hari_en]) ? $nama_hari[$hari_en] : $hari_en;
			$tanggal_id = $hari_id . ', ' . $tanggal_id;
		}

		// --- Tambahkan Waktu (Opsional) ---
		if ($show_time) {
			$time = $date->format('H:i'); // Format jam, menit, detik (24-jam)
			$tanggal_id .= ' ' . $time;
		}

		return $tanggal_id;
	} catch (Exception $e) {
		// Log the error for debugging, but return '-' for user
		error_log("Error in TglIndonesia: " . $e->getMessage());
		return '-';
	}
}
