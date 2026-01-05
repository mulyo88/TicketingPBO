<?php 
function po_print($LedgerNo = '')
    {
        if ($LedgerNo == '') {
            die;
        }



        $data['data_po'] = $this->M_kontrak_po->dapat_data_ko($LedgerNo);
        // $data['QRCode'] = 'assets/' . $this->dapat_qrcode($data['data_po']['QRCode']);
        $NoKO = $data['data_po']['NoKO'];
        $JobNo = $data['data_po']['JobNo'];
        $data['NoKO'] = $NoKO;
        $data['JobNo'] = $JobNo;
        $data['detail']  = $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga  FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ")->result_array();


        $TxtSubTotal = 0;
        $query = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut ASC ");
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                $Vol = ($row->Vol==NULL || $row->Vol=='') ? 0 : (float) $row->Vol;
                $HrgSatuan = ($row->HrgSatuan==NULL || $row->HrgSatuan=='') ? 0: (float) $row->HrgSatuan;

                $JumlahHarga = $Vol * $HrgSatuan;
                $TxtSubTotal += $JumlahHarga;

            }
        }


        $query_data_KO = $this->db->query("SELECT ISNULL(DiscPercentage,0) AS DiscPercentage, ISNULL(DiscAmount,0) AS DiscAmount, ISNULL(PotongKO,NULL) AS PotongKO, ISNULL(PPN,0) AS PPN FROM KoHdr WHERE NoKO='$NoKO' ");

        $TxtDiscPersen = 0;
        $TxtDiscNominal = 0;
        $TxtPPN = 0;
        $TxtPotongKO = NULL;
        $Hitung_GrandTotal = 0;

        if ($query_data_KO->num_rows() > 0) {
            $row = $query_data_KO->row();

            $TxtDiscPersen = (float) $row->DiscPercentage;
            $TxtDiscNominal = (float) $row->DiscAmount;
            $TxtPPN = (float) $row->PPN;

            $Hitung_GrandTotal = ($TxtSubTotal - $TxtDiscNominal) + $TxtPPN;
            $TxtPotongKO = $row->PotongKO;

        }

        $data['TxtDiscPersen'] = $TxtDiscPersen;
        $data['TxtDiscNominal'] = $TxtDiscNominal;
        $data['TxtPPN'] = $TxtPPN;
        $data['Hitung_GrandTotal'] = $Hitung_GrandTotal;
        $data['TxtDiscPersen'] = $TxtDiscPersen;
        $data['TxtSubTotal'] = $TxtSubTotal;

        // print_rr($data);
        // exit;

        $this->load->view('procurement/kontrak_po/print/print_po/po_content', $data);
    }


     ?>
