USE `fftt`

set @genrid = (select id from ref_referential WHERE type = 'GENR' AND identifier = 'M');

INSERT INTO `per_person` (`licence_id`, `gender_id`, `country_id`, `address_id`, `photo_id`, `creator_id`, `editor_id`, `family_name`, `given_name`, `birth_date`, `birth_place`, `description`, `email`, `telephone`, `mobile`, `optin_fftt`, `optin_partners`, `date_created`, `date_modified`) VALUES
(NULL, @genrid, NULL, NULL, NULL, NULL, NULL, 'SYSTEM', 'Admin', '2020-03-27', NULL, NULL, 'nicolas.moral@acseo-conseil.fr', NULL, NULL, 0, 0, '2020-03-27 12:10:09', '2020-03-27 12:10:09');

set @person_id = (select id from per_person where family_name = 'SYSTEM' AND given_name = 'Admin');

INSERT INTO `sec_aut_account` (`id`, `person_id`, `creator_id`, `editor_id`, `username`, `salt`, `enabled`, `password`, `last_login`, `roles`, `confirmation_token`, `date_created`, `date_modified`) VALUES
(NULL, @person_id, NULL, NULL, 'system_admin', 'zts09jiAZmEUBROMmvGuRqGKHYbyX5HEe8niZJ.Dwpg', 1, '$2y$13$nkEimk.Rm/can4mRXEpooumnQe4SnrsY1Spz2uKwXDmJ1.cQ9pCXm', '2020-04-01 17:03:04', 'a:0:{}', 'hfNEvqJBVVyQ2VFJ_uheiwf7ax2WIM8M4iI5VLDv1Is', '2020-03-27 12:10:09', '2020-04-01 17:03:04');

set @accountId = (select id from sec_aut_account where username = 'system_admin');
set @matrixId = (select id from sec_mat_matrix where name = 'System admin');

INSERT INTO `sec_per_federation_permission` (`id`, `account_id`, `matrix_id`, `node_id`, `creator_id`, `editor_id`, `valid_from`, `valid_until`, `dates_editable`, `date_created`, `date_modified`) VALUES
(NULL, @accountId, @matrixId, 1, 1, 1, '2020-03-27 00:00:00', '2022-03-27 00:00:00', 1, '2020-03-27 12:10:10', '2020-03-27 12:10:10');
