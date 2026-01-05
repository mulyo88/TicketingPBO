</tr>
			
			<tr>
				<td style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
					<label><input type="checkbox" class="form-control input-sm" value="SJ" checked="'.(CekBuktiPendukung($data_ko['SyaratPembayaran'], 'SJ') == TRUE) ? 'checked' : ''.'" style="font-size: 9px;"> Surat Jalan/Tanda Terima Lapangan</label>
				</td>
				<td>
					<label><input type="checkbox" class="form-control input-sm" value="PO" checked="'.(CekBuktiPendukung($data_ko['SyaratPembayaran'], 'PO') == TRUE) ? 'checked' : ''.'" style="font-size: 9px;"> Copy PO</label>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
					<label><input type="checkbox" class="form-control input-sm" value="BAP" checked="'.(CekBuktiPendukung($data_ko['SyaratPembayaran'], 'BAP') == TRUE) ? 'checked' : ''.'" style="font-size: 9px;"> Berita Acara Pembayaran</label>
				</td>
				
			</tr>

			<tr>
				<td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:11px;" >
					<label><input type="checkbox" class="form-control input-sm" value="BAOP" checked="'.(CekBuktiPendukung($data_ko['SyaratPembayaran'], 'BAOP') == TRUE) ? 'checked' : ''.'" style="font-size: 9px;"> Berita Acara Opname Pekerjaan</label>
				</td>
			</tr>