USE [Priebslogistik];

-- EXPORTFLAG: NULL als Default + Spalte NULLABLE
IF EXISTS (SELECT 1 FROM sys.columns WHERE object_id=OBJECT_ID('dbo.BEWEGUNG') AND name='EXPORTFLAG' AND is_nullable=0)
    ALTER TABLE dbo.BEWEGUNG ALTER COLUMN EXPORTFLAG nchar(4) NULL;

-- vorhandene Default-Constraint ggf. droppen
DECLARE @df sysname;
SELECT @df = dc.name
FROM sys.default_constraints dc
JOIN sys.columns c ON c.default_object_id = dc.object_id
WHERE dc.parent_object_id = OBJECT_ID('dbo.BEWEGUNG') AND c.name='EXPORTFLAG';
IF @df IS NOT NULL EXEC('ALTER TABLE dbo.BEWEGUNG DROP CONSTRAINT ' + @df);

-- Default auf NULL (eigentlich redundant, aber explizit):
ALTER TABLE dbo.BEWEGUNG ADD CONSTRAINT DF_BEWEGUNG_EXPORTFLAG DEFAULT (NULL) FOR EXPORTFLAG;

-- EXPORTSTAPEL: Default 0
SELECT @df = dc.name
FROM sys.default_constraints dc
JOIN sys.columns c ON c.default_object_id = dc.object_id
WHERE dc.parent_object_id = OBJECT_ID('dbo.BEWEGUNG') AND c.name='EXPORTSTAPEL';
IF @df IS NOT NULL EXEC('ALTER TABLE dbo.BEWEGUNG DROP CONSTRAINT ' + @df);

ALTER TABLE dbo.BEWEGUNG ADD CONSTRAINT DF_BEWEGUNG_EXPORTSTAPEL DEFAULT (0) FOR EXPORTSTAPEL;

-- vorhandene NULLs auf 0 ziehen (einmalig)
UPDATE dbo.BEWEGUNG SET EXPORTSTAPEL = 0 WHERE EXPORTSTAPEL IS NULL;
