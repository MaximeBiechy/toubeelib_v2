-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "patient";
CREATE TABLE "public"."patient" (
    "nom" character varying NOT NULL,
    "prenom" character varying NOT NULL,
    "adresse" text NOT NULL,
    "tel" character varying NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);


-- 2024-10-01 15:11:05.445921+00
