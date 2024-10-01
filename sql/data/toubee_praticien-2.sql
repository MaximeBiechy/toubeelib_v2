-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

INSERT INTO "praticien" ("nom", "prenom", "adresse", "telephone", "specialite_id", "id") VALUES
('Dupont',	'Jean',	'nancy',	'0123456789',	'1c883940-7914-48cf-8e7a-be360cad7ce3',	'8779424d-1939-491a-9abf-171d8f050aaa'),
('Durand',	'Pierre',	'vandeuve',	'0123456789',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb',	'84cad6e1-b71c-4517-a435-edc70f3e114d'),
('Martin',	'Marie',	'3lassou',	'0123456789',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32',	'20964b5c-2644-418d-a698-c58752e36948');

INSERT INTO "specialite" ("label", "description", "id") VALUES
('Dentiste',	'Spécialiste des dents',	'1c883940-7914-48cf-8e7a-be360cad7ce3'),
('Ophtalmologue',	'Spécialiste des yeux',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb'),
('Généraliste',	'Médecin généraliste',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32'),
('Pédiatre',	'Médecin pour enfants',	'fb97c73b-71c1-40e0-ae29-8d1c6b4b2851'),
('Médecin du sport',	'Maladies et traumatismes liés à la pratique sportive',	'0b20924b-013a-43eb-bcd8-547bcc0f3764');

-- 2024-10-01 15:11:00.602574+00
