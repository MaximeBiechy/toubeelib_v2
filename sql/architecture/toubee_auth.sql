-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "users";
CREATE TABLE "public"."users" (
    "id" uuid NOT NULL,
    "email" character varying(128) NOT NULL,
    "password" character varying(256) NOT NULL,
    "role" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "users_email" UNIQUE ("email"),
    CONSTRAINT "users_id" PRIMARY KEY ("id")
) WITH (oids = false);


-- 2024-10-20 15:04:00.979839+00
