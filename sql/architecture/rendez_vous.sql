-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "rendez_vous";
CREATE TABLE "public"."rendez_vous" (
    "date" date NOT NULL,
    "patient_id" character varying NOT NULL,
    "praticien_id" character varying NOT NULL,
    "specialite_id" character varying NOT NULL,
    "statut" character varying NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);


-- 2024-10-01 15:10:24.861923+00
