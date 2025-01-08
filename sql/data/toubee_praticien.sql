-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

INSERT INTO "praticien" ("nom", "prenom", "adresse", "telephone", "specialite_id", "id") VALUES
('Dupont',	'Jean',	'nancy',	'0123456789',	'1c883940-7914-48cf-8e7a-be360cad7ce3',	'8779424d-1939-491a-9abf-171d8f050aaa'),
('Durand',	'Pierre',	'vandeuve',	'0123456789',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb',	'84cad6e1-b71c-4517-a435-edc70f3e114d'),
('Martin',	'Marie',	'3lassou',	'0123456789',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32',	'20964b5c-2644-418d-a698-c58752e36948'),
('Ibrahimovic',	'Zlatan',	'Chez lui',	'000000000',	'0b20924b-013a-43eb-bcd8-547bcc0f3764',	'5b4a407d-fdb9-4fe4-977a-d29fcffcd175'),
('Dupont',	'Jean',	'nancy',	'0123456789',	'1c883940-7914-48cf-8e7a-be360cad7ce3',	'8779424d-1939-491a-9abf-171d8f050aaa'),
('Durand',	'Pierre',	'vandeuve',	'0123456789',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb',	'84cad6e1-b71c-4517-a435-edc70f3e114d'),
('Martin',	'Marie',	'3lassou',	'0123456789',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32',	'20964b5c-2644-418d-a698-c58752e36948'),
('Vaillant',	'Theo',	'45 Rue Didier',	'0425376437',	'1c883940-7914-48cf-8e7a-be360cad7ce3',	'c401c65c-8d47-3fab-bab3-c3713a09ce06'),
('Gonzalez',	'Patricia',	'67 Rue d''Alsace',	'0770088167',	'1c883940-7914-48cf-8e7a-be360cad7ce3',	'40708f53-a81b-3f1f-aeed-886ce1e3be60'),
('Rey',	'Alexandre',	'56 Rue des gens cools',	'0585968798',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb',	'd7b34ecf-f3c0-3f2d-84c9-be32f27f1a78'),
('Dumas',	'Madeleine',	'4 Rue Des potiers',	'0589689568',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb',	'28b72906-3cbf-3662-8806-b471d873343e'),
('Alexandre',	'Frederic',	'56 Rue du Pâté',	'0145785869',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32',	'cf11bb88-f700-3b8e-8c17-745902612058'),
('Georges',	'Emilie',	'45 Rue du Chantier',	'0256895698',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32',	'ada2fe33-aa08-3d48-b09d-d924c4a8f709'),
('Pichon',	'Audrey',	'45 Rue de la Musique',	'0258695896',	'fb97c73b-71c1-40e0-ae29-8d1c6b4b2851',	'3740ce08-d7ed-3f30-89dc-37c75705a5c0'),
('Breton',	'Emilie',	'45 Rue de l''Aube',	'0258695869',	'0b20924b-013a-43eb-bcd8-547bcc0f3764',	'229f36ae-ff42-3b2b-a8bf-e0d90ea46448'),
('Alexandre',	'Pascal',	'56 Rue du Soleil',	'0789689585',	'0b20924b-013a-43eb-bcd8-547bcc0f3764',	'387a2731-2dd9-3c14-931f-2b024fa46b27');

INSERT INTO "specialite" ("label", "description", "id") VALUES
('Dentiste',	'Spécialiste des dents',	'1c883940-7914-48cf-8e7a-be360cad7ce3'),
('Ophtalmologue',	'Spécialiste des yeux',	'1e01171e-3d79-4ee2-9fb4-f00d58b8c2eb'),
('Généraliste',	'Médecin généraliste',	'fd8b6ca2-663c-4932-bb5c-e0752d3d5d32'),
('Pédiatre',	'Médecin pour enfants',	'fb97c73b-71c1-40e0-ae29-8d1c6b4b2851'),
('Médecin du sport',	'Maladies et traumatismes liés à la pratique sportive',	'0b20924b-013a-43eb-bcd8-547bcc0f3764');

-- 2024-10-20 15:06:25.681733+00
