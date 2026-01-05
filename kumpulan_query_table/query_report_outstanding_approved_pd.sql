DROP TABLE IF EXISTS Tbl_report_outstan_approved_pd
CREATE TABLE Tbl_report_outstan_approved_pd(
	LedgerNo bigint NOT NULL IDENTITY (1,1),
	JobNo nvarchar(15),
	JobNm nvarchar(100),
	NoPD nvarchar(20),
	Alokasi nvarchar(1),
	TipeForm nvarchar(10),
	AtasNama nvarchar(100),
	NoRek nvarchar(30),
	NoKO nvarchar(15),
	TotalPD money,
	TglPD date,
	DueDate date,
	Deskripsi nvarchar(255),
	CompanyId nvarchar(100),
	ApprovedBy nvarchar(30)
            
);