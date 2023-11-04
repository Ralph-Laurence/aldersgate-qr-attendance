INSERT INTO  `junior_students`  (student_no, firstname, middlename, lastname, contact, email, birthday, grade_level) VALUES 
('02-00001',    'Kaitlyn',      'Padilla',      'Gonzalez',     '09123456789',      'kago@aldersgate.edu',  '1/11/2000',        10),
('02-00002',    'Jacob',        'Jaimes',       'Gomez',        '09123456788',      'jogo@aldersgate.edu',  '1/19/2000',        10),
('02-00003',    'Landon',       'Lara',         'Hidalgo',      '09123456787',      'lahi@aldersgate.edu',  '7/2/2001',         9),
('02-00004',    'Kaitlynne',    'Jimenez',      'Ibanez',       '09123456786',      'kaib@aldersgate.edu',  '4/1/2000',         10),
('02-00005',    'Jasmine',      'Galindo',      'Jiménez',      '09123456785',      'jaji@aldersgate.edu',  '2/15/2003',        7),
('02-00006',    'Kira',         'Ibarra',       'Japson',       '09123456784',      'kiji@aldersgate.edu',  '6/21/2004',        8),
('02-00007',    'Jaida',        'Hernandez',    'Leon',         '09123456783',      'jala@aldersgate.edu',  '1/11/2002',        9),
('02-00008',    'Kaitlin',      'Macias',       'Maldonado',    '09123456782',      'kamac@aldersgate.edu',  '8/13/2000',        10),
('02-00009',    'Jace',         'Nava',         'Manriquez',    '09123456781',      'jama@aldersgate.edu',  '6/25/2003',        7),
('02-00010',    'Kaden',        'Martinez',     'Monte',        '09123456780',      'kama@aldersgate.edu',  '4/14/2002',        8),
('02-00011',    'Kelsey',       'Marin',        'Navarrete',    '09123456779',      'kana@aldersgate.edu',  '4/26/2001',        9);

UPDATE `junior_students` SET created_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE created_at IS NULL AND updated_at IS NULL;