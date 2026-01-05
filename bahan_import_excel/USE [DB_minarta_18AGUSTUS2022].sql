USE [DB_minarta_18AGUSTUS2022]
GO
/****** Object:  StoredProcedure [dbo].[Posting_Payroll]    Script Date: 26/08/2022 14:45:44 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:      <Author,,Name>
-- Create date: <Create Date,,>
-- Description: <Description,,>
-- =============================================

ALTER PROCEDURE [dbo].[Posting_Payroll_percobaan] 
    --Posting_Payroll 'administrator','bank','ALL','2021-12-01'
    --Posting_Payroll 'administrator','bank','None','2021-12-01'
    --Posting_Payroll 'administrator','bank','BNI','2021-12-01'
    --Posting_Payroll 'administrator','company','ALL','2021-12-01'
    --Posting_Payroll 'administrator','company','KGS','2021-12-01'

    @userID varchar(50),
    @type varchar(50),
    @param varchar(50),
    @Periode datetime
AS
BEGIN
    SET NOCOUNT ON;

    if OBJECT_ID('tempdb..#tempAtt') IS NOT NULL DROP TABLE #tempAtt
    if OBJECT_ID('tempdb..#tempPost') IS NOT NULL DROP TABLE #tempPost

    create table #tempAtt(
        nik varchar(50), statusEmp varchar(50), tglparamStart datetime, tglparamClose datetime, tglmasuk datetime, masuk_kantor datetime, konversi_masukkantor numeric(10,0), jammasuk datetime, konversi_masuk numeric(10,0), jamkeluar datetime, konversi_keluar numeric(10,0), status varchar(50), keterangan text, durasi_terlambat numeric(10,0), harikerja numeric(10,0)
    )

    create table #tempPost(
        nik varchar(50), nama varchar(50), jabatan varchar(50), divisi varchar(50), lokasikerja varchar(50), statusEmp varchar(50), tglparamStart datetime, tglparamClose datetime, gajipokok numeric(10,0), tunjangankesehatan numeric(10,0), tunjanganjabatan numeric(10,0), tunjanganoperasional numeric(10,0), fasilitastiket numeric(10,0), fasilitasrumah numeric(10,0), fasilitastransportasi numeric(10,0), pph21 numeric(10,0), jhtemployee numeric(10,0), jpemployee numeric(10,0), potongan_kedisiplinan_telat_lebih5kali numeric(10,0), potongan_kedisiplinan_telat_kurang5kali numeric(10,0), potongan_kedisiplinan_noabsen numeric(10,0), potongan_kedisiplinan_alpha numeric(10,0), potongan_kedisiplinan_alpa_lebih3kali numeric(10,0), potongan_kedisiplinan numeric(10,0), potongan_thp numeric(10,0), thp_awal numeric(10,0), thp numeric(10,0), telat_1menit numeric(10,0), alpa numeric(10,0), no_absen numeric(10,0), hadir numeric(10,0), cuti numeric(10,0), ijin numeric(10,0), dinas_luar numeric(10,0), wfh numeric(10,0), statuskaryawan varchar(50), active varchar(50), tglnonactive datetime, bank varchar(50), norek varchar(30), company varchar(50), harikerja numeric(10,0)
    )

    --drop table TabrekapPayroll
    --create table TabrekapPayroll(
    --  nik varchar(50), nama varchar(50), jabatan varchar(50), divisi varchar(50), lokasikerja varchar(50), gajipokok numeric(10,0), tunjangankesehatan numeric(10,0), tunjanganjabatan numeric(10,0), tunjanganoperasional numeric(10,0), fasilitastiket numeric(10,0), fasilitasrumah numeric(10,0), fasilitastransportasi numeric(10,0), pph21 numeric(10,0), jhtemployee numeric(10,0), jpemployee numeric(10,0), potongan_kedisiplinan_telat_lebih5kali numeric(10,0), potongan_kedisiplinan_telat_kurang5kali numeric(10,0), potongan_kedisiplinan_noabsen numeric(10,0), potongan_kedisiplinan_alpha numeric(10,0), potongan_kedisiplinan_alpa_lebih3kali numeric(10,0), potongan_kedisiplinan numeric(10,0), potongan_thp numeric(10,0), thp_awal numeric(10,0), thp numeric(10,0), bank varchar(50), norek varchar(30), company varchar(50), userid varchar(50)
    --)

    declare @tglStart datetime, @tglClose datetime, @jamMasuk datetime, @waktuMasuk int, @jam int, @menit int

    set @tglClose = format(@Periode, 'yyyy') + '-' + format(@Periode, 'MM') + '-20'
    set @tglStart = dateadd(month,-1,convert(varchar, @tglClose, 101))--mundur 1 bulan
    set @tglStart = format(@tglStart, 'yyyy') + '-' + format(@tglStart, 'MM') + '-21'--setup date 21

    --select @tglStart, @tglClose
    set @jamMasuk = '1900-01-01 08:29:00.000'

    set @jam = format(@jamMasuk,'HH') * 3600
    set @menit = format(@jamMasuk,'mm') * 60
    set @waktuMasuk = @jam + @menit

    declare @harikerja int
    --set @harikerja = 21
    set @harikerja = DATEDIFF(day, @tglStart, @tglClose) + 2
    --select @harikerja

    --potongan berdasarkan hitung hari libur
    select @harikerja = @harikerja - isnull(COUNT(*),0) from HariLibur where TglLibur between @tglStart and @tglClose
    --select isnull(COUNT(*),0) from HariLibur where TglLibur between @tglStart and @tglClose
    --select @harikerja

    --cek data potongan berdasarkan hitung hari libur
    --select @harikerja
    --select * from HariLibur where TglLibur between @tglStart and @tglClose

    --get weekend
    declare @tglValue datetime
    declare @tglValidate datetime
    set @tglValue = dateadd(day,-1,convert(varchar, @tglStart, 101))--mundur 1 hari
    --select @tglValue
    declare @Counter int
    declare @dayWeekend int
    set @dayWeekend = 0
    set @Counter = 1
    WHILE(@Counter IS NOT NULL
            AND @Counter <= @harikerja)
    BEGIN
        set @tglValue = dateadd(day,1,convert(varchar, @tglValue, 101))--tambah 1 hari
        --cek tgl = hari libur nasional
        select @tglValidate = max(TglLibur) from HariLibur where TglLibur = @tglValue
        if @tglValidate is null --tidak ada
        begin
            --get day
            if format(@tglValue,'dddd') = 'saturday' or format(@tglValue,'dddd') = 'sunday'
            begin 
                --select format(@tglValue,'ddd dd-MMM-yyy')
                set @dayWeekend = @dayWeekend + 1
            end
        end
            
        SET @Counter  = @Counter  + 1
    END

    --select @harikerja
    --select @dayWeekend
    
    --setup reduced weekend
    set @harikerja = @harikerja - @dayWeekend
    --select @harikerja
    --select @dayWeekend

    insert into #tempAtt
        select b.NIK, b.statusEmp, b.tglparamStart, b.tglparamClose,
        b.TglMasuk, b.masuk_kantor, b.konversi_masukkantor, b.JamMasuk, b.konversi_masuk, b.JamKeluar, b.konversi_keluar, b.Status, b.Keterangan, b.durasi_terlambat, 
        DATEDIFF(day, b.tglparamStart, b.tglparamClose) as harikerja
    from
    (select a.NIK, 
        case
            when not (a.TglNonActive) is null then 'resign'
            when not (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose) is null then 'baru'
            else 'aktif'
        end as statusEmp,
        case
            when not (a.TglNonActive) is null then @tglStart
            when not (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose) is null then (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose)
            else @tglStart
        end as tglparamStart,
        case
            when not (a.TglNonActive) is null then a.TglNonActive
            when not (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose) is null then @tglClose
            else @tglClose
        end as tglparamClose,
        a.TglMasuk, a.masuk_kantor, a.konversi_masukkantor, a.JamMasuk, a.konversi_masuk, a.JamKeluar, a.konversi_keluar, a.Status,
        a.Keterangan, 
        case
            when a.konversi_masuk - a.konversi_masukkantor < 0 then 0
            else a.konversi_masuk - a.konversi_masukkantor
        end as durasi_terlambat, 0 as harikerja
    from
    (select 
        a.NIK, b.PrdAwal, b.TglNonActive, a.TglMasuk, @jamMasuk as masuk_kantor, @waktuMasuk as konversi_masukkantor, a.JamMasuk,
        (left((substring(CONVERT(VARCHAR, a.JamMasuk, 108),0,9)),2) * 3600) + (substring(substring(CONVERT(VARCHAR, a.JamMasuk, 108),0,9),4,2) * 60) as konversi_masuk
        , a.JamKeluar,
        (left((substring(CONVERT(VARCHAR, a.JamKeluar, 108),0,9)),2) * 3600) + (substring(substring(CONVERT(VARCHAR, a.JamKeluar, 108),0,9),4,2) * 60) as konversi_keluar,
        Status, a.Keterangan
    from Absensi as a
    inner join 
    Karyawan as b
    on a.NIK = b.NIK
     where a.TglMasuk between @tglStart and @tglClose
    ) as a
    ) as b

    --rekap absensi
    insert into #tempPost
    select 
        a.nik, a.nama, a.jabatan, c.divisi, 
        case
            when c.LokasiKerja = 'Pusat' then 'Pusat'
            when c.LokasiKerja is null then 'Pusat'
            else (select max(JobNm) from Job where JobNo = c.LokasiKerja)
        end as lokasikerja,
        case
            when not (a.TglNonActive) is null then 'resign'
            when not (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose) is null then 'baru'
            else 'aktif'
        end as statusEmp,
        case
            when not (a.TglNonActive) is null then @tglStart
            when not (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose) is null then (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose)
            else @tglStart
        end as tglparamStart,
        case
            when not (a.TglNonActive) is null then a.TglNonActive
            when not (select PrdAwal from karyawan where nik = a.NIK and PrdAwal between @tglStart and @tglClose) is null then @tglClose
            else @tglClose
        end as tglparamClose        
        , c.gajipokok, c.tunjangankesehatan, c.tunjanganjabatan, c.tunjanganoperasional, isnull(c.fasilitastiket, 0), isnull(c.fasilitasrumah, 0), isnull(c.fasilitastransportasi, 0), c.pph21, c.jhtemployee, c.jpemployee, 0 as potongan_kedisiplinan_telat_lebih5kali, 0 as potongan_kedisiplinan_telat_kurang5kali, 0 as potongan_kedisiplinan_noabsen, 0 as potongan_kedisiplinan_alpha,0 as potongan_kedisiplinan_alpa_lebih3kali, 0 as potongan_kedisiplinan, 0 as potongan_thp, 0 as thp_awal, 0 as pay,
        --formula potongan kedisiplinan karyawan
        --hitung jumlah telat 1 menit
        --isnull((select count(*) from #tempAtt where nik = a.NIK and durasi_terlambat > 60),0) as telat_1menit,
        isnull((select count(*) from #tempAtt where nik = a.NIK and durasi_terlambat > 60 and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK)),0) as telat_1menit,             
        --hitung jumlah alpa
        --isnull((select count(*) from #tempAtt where nik = a.NIK and status = 'Alpa'),0) as alpa,
        isnull((select count(*) from #tempAtt where nik = a.NIK and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK) and status = 'Alpa'),0) as alpa,               
        --hitung jumlah tidak absen
        --isnull((select count(*) from #tempAtt where nik = a.NIK and (jammasuk is null or jamkeluar is null)),0) as no_absen,
        isnull((select count(*) from #tempAtt where nik = a.NIK and (jammasuk is null or jamkeluar is null) and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK)),0) as no_absen,               
        --hitung jumlah hadir
        isnull((select count(*) from #tempAtt where nik = a.NIK and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK) and status in ('Masuk','Hadir')),0) as hadir,              
        --hitung jumlah Cuti
        --isnull((select count(*) from #tempAtt where nik = a.NIK and status in ('Cuti','Cuti Menikah','Cuti Melahirkan')),0) as cuti,
        isnull((select count(*) from #tempAtt where nik = a.NIK and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK) and status in ('Cuti','Cuti Menikah','Cuti Melahirkan')),0) as cuti,               
        --hitung jumlah Ijin
        --isnull((select count(*) from #tempAtt where nik = a.NIK and status in ('Ijin','Ijin 1/2 Hari','Medical RS','Sakit')),0) as Ijin,
        isnull((select count(*) from #tempAtt where nik = a.NIK and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK) and status in ('Ijin','Ijin 1/2 Hari','Medical RS','Sakit')),0) as Ijin,               
        --hitung jumlah dinas luar
        --isnull((select count(*) from #tempAtt where nik = a.NIK and status = 'Dinas Proyek'),0) as dinas_luar,
        isnull((select count(*) from #tempAtt where nik = a.NIK and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK) and status = 'Dinas Proyek'),0) as dinas_luar,             
        --hitung jumlah WFH
        --isnull((select count(*) from #tempAtt where nik = a.NIK and status = 'WFH'),0) as WFH,
        isnull((select count(*) from #tempAtt where nik = a.NIK and tglmasuk between (select max(tglparamStart) from #tempAtt where nik = a.NIK) and (select max(tglparamClose) from #tempAtt where nik = a.NIK) and status = 'WFH'),0) as WFH,             
        a.statuskaryawan, a.active, a.tglnonactive, '' as bank, a.norek, c.company, 0 as harikerja
    from 
    --I. Start from all employee
    (select * from
        (select * from karyawan where Active = '1'
        union all
        select * from karyawan where Active = '0' and TglNonActive >= @tglStart
        ) as b
    ) as a
    inner join 
    (select * from EmpPayroll WHERE (Company='MDH' or Company='KIP' or Company='KGS' or Company='KSC')) as c
    on c.NIK = a.NIK 

    --get total hari kerja
    update a set a.harikerja = DATEDIFF(day, b.tglparamStart, b.tglparamClose)
    from #tempPost as a
    inner join #tempPost as b
    on b.nik = a.nik

    --potongan kedisiplinan karyawan
    --a.  Telat (1 Menit) > 5 kali , akan di potong seluruh Tunjangan Operasional
    update a set a.potongan_kedisiplinan_telat_lebih5kali = a.tunjanganoperasional from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.telat_1menit > 5

    update a set a.potongan_kedisiplinan = a.tunjanganoperasional from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.telat_1menit > 5

    --b.  Telat (1 Menit) > 1 dan < 5 kali, akan di potong ( Tunjangan Operasional/21 * Jumlah Telat)
    update a set a.potongan_kedisiplinan_telat_kurang5kali = (a.telat_1menit/21 * a.tunjanganoperasional) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.telat_1menit >= 1 and a.telat_1menit <= 5

    update a set a.potongan_kedisiplinan = (a.potongan_kedisiplinan) + (a.telat_1menit/21 * a.tunjanganoperasional) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.telat_1menit >= 1 and a.telat_1menit <= 5

    --d.  Tidak Absen Masuk/Pulang, akan di potong (Tunjangan Operasional/30 * Jumlah Tidak Absen)
    --update a set a.potongan_kedisiplinan_noabsen = (a.tunjanganoperasional/@harikerja * a.no_absen) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.no_absen > 0

    --update a set a.potongan_kedisiplinan = (a.potongan_kedisiplinan) + (a.tunjanganoperasional/@harikerja * a.no_absen) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.no_absen > 0

    --pusat
    update a set a.potongan_kedisiplinan_noabsen = (a.tunjanganoperasional/21 * a.no_absen) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.no_absen > 0 and a.lokasikerja = 'Pusat'

    update a set a.potongan_kedisiplinan = (a.potongan_kedisiplinan) + (a.tunjanganoperasional/21 * a.no_absen) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.no_absen > 0 and a.lokasikerja = 'Pusat'

    --non pusat
    update a set a.potongan_kedisiplinan_noabsen = (a.tunjanganoperasional/30 * a.no_absen) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.no_absen > 0 and not a.lokasikerja = 'Pusat'

    update a set a.potongan_kedisiplinan = (a.potongan_kedisiplinan) + (a.tunjanganoperasional/30 * a.no_absen) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.no_absen > 0 and not a.lokasikerja = 'Pusat'

    --potongan_thp
    update a set a.potongan_thp = b.AmountPotonganTHP from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik

    --c.  Alpha , akan di potong  (THP/21 * Jumlah Alpha)
    update a set a.potongan_kedisiplinan_alpha = (a.alpa/21 * a.tunjanganoperasional) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.alpa >= 1 and a.alpa <= 3

    update a set a.potongan_kedisiplinan = (a.potongan_kedisiplinan) + (a.alpa/21 * a.tunjanganoperasional) from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.alpa >= 1 and a.alpa <= 3

    --a.  Alpa > 3 kali , akan di potong seluruh Tunjangan Operasional
    update a set a.potongan_kedisiplinan_alpa_lebih3kali = a.tunjanganoperasional from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.alpa > 3

    update a set a.potongan_kedisiplinan = (a.potongan_kedisiplinan) + a.tunjanganoperasional from #tempPost as a inner join EmpPayroll as b on b.NIK = a.nik where b.PotonganKedisplinan = '1' and a.alpa > 3

    --hitung thp awal
    update a set a.thp_awal = 
        (a.gajipokok + a.tunjangankesehatan + a.tunjanganjabatan + a.tunjanganoperasional) 
        - (a.pph21 + a.jhtemployee + a.jpemployee) + (a.fasilitastiket + a.fasilitasrumah + a.fasilitastransportasi) 
        from #tempPost as a

    --hitung thp total
    update a set a.thp = 
        (a.gajipokok + a.tunjangankesehatan + a.tunjanganjabatan + a.tunjanganoperasional) 
        - (a.pph21 + a.jhtemployee + a.jpemployee + a.potongan_kedisiplinan + a.potongan_thp) + (a.fasilitastiket + a.fasilitasrumah + a.fasilitastransportasi) 
        from #tempPost as a

    --POTONGAN KEDISIPLINAN DI WAL
    --hitung thp after resign not pusat
    --update a set a.thp = ((a.hadir / 30) * a.thp) from #tempPost as a inner join #tempAtt as b on b.nik = a.nik where b.statusEmp = 'resign' and not a.lokasikerja = 'Pusat'

    ----hitung thp after resign pusat
    --update a set a.thp = ((a.hadir / 21) * a.thp) from #tempPost as a inner join #tempAtt as b on b.nik = a.nik where b.statusEmp = 'resign' and a.lokasikerja = 'Pusat'

    ----hitung thp after karyawan baru not pusat
    --update a set a.thp = ((a.hadir / 30) * a.thp) from #tempPost as a inner join #tempAtt as b on b.nik = a.nik where b.statusEmp = 'baru' and not a.lokasikerja = 'Pusat'

    ----hitung thp after karyawan baru pusat
    --update a set a.thp = ((a.hadir / 21) * a.thp) from #tempPost as a inner join #tempAtt as b on b.nik = a.nik where b.statusEmp = 'baru' and a.lokasikerja = 'Pusat'
    
    --POTONGAN KEDISIPLINAN DI AKHIR
    --hitung thp after resign not pusat
    update a set a.thp = ((a.harikerja / 30) * a.thp_awal) - (a.potongan_kedisiplinan + a.potongan_thp) from #tempPost as a where a.statusEmp = 'resign' and not a.lokasikerja = 'Pusat'

    --hitung thp after resign pusat
    update a set a.thp = ((a.hadir / 21) * a.thp_awal) - (a.potongan_kedisiplinan + a.potongan_thp) from #tempPost as a inner join #tempAtt as b on b.nik = a.nik where b.statusEmp = 'resign' and a.lokasikerja = 'Pusat'

    --hitung thp after karyawan baru not pusat
    update a set a.thp = ((a.harikerja / 30) * a.thp_awal) - (a.potongan_kedisiplinan + a.potongan_thp) from #tempPost as a where a.statusEmp = 'baru' and not a.lokasikerja = 'Pusat'

    --hitung thp after karyawan baru pusat
    update a set a.thp = ((a.hadir / 21) * a.thp_awal) - (a.potongan_kedisiplinan + a.potongan_thp) from #tempPost as a inner join #tempAtt as b on b.nik = a.nik where b.statusEmp = 'baru' and a.lokasikerja = 'Pusat'

    update a set a.bank = b.Bank from #tempPost as a
    inner join Karyawan as b
    on a.nik = b.NIK

    delete from TabrekapPayroll where userid = @userID

    insert into TabrekapPayroll
    select nik, nama, jabatan, divisi, lokasikerja, gajipokok, tunjangankesehatan, tunjanganjabatan, tunjanganoperasional, fasilitastiket, fasilitasrumah, fasilitastransportasi, pph21, jhtemployee, jpemployee, potongan_kedisiplinan_telat_lebih5kali, potongan_kedisiplinan_telat_kurang5kali, potongan_kedisiplinan_noabsen, potongan_kedisiplinan_alpha,potongan_kedisiplinan_alpa_lebih3kali, potongan_kedisiplinan, potongan_thp, thp_awal, thp,  bank, norek, company, @userID from #tempPost order by company, bank

    --select * from TabrekapPayroll where nama like '%lia sri%'
    --select * from #tempPost where nama like '%tomy%' or nama like '%lia sri%' or nama like '%oscar%'
    --  
    --select * from #tempAtt where nik = '210042'
    --select PrdAwal from karyawan where nik = '210111' and PrdAwal between @tglStart and @tglClose

    select * from TabrekapPayroll

    --select * from TabrekapPayroll where nik='210111' or nik='130018'
    --select * from #tempAtt where nik = '130018'
    --select * from #tempPost where nik = '130018'
    ----update Karyawan set active='0', TglNonActive = '2022-02-09' where nik = '210111'
    --select * from Karyawan where nik = '210111'
END

--select * from TabrekapPayroll where nama='mulyo saputra'
--delete from TabrekapPayroll

--Posting_Payroll 'administrator','bank','ALL','2022-02-01'
--Posting_Payroll 'administrator','bank','None','2022-02-01'
--Posting_Payroll 'administrator','bank','BNI','2022-02-01'
--Posting_Payroll 'administrator','company','ALL','2022-03-01'
--Posting_Payroll 'administrator','company','KGS','2022-02-01'
--Posting_Payroll 'administrator','company','ALL','2022-01-01'