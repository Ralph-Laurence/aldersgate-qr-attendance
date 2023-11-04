INSERT INTO  `elementary_students`  (student_no, firstname, middlename, lastname, contact, email, birthday, grade_level) VALUES 
('02-00012',    'Jayden',       'Ochoa',        'Nava',         '09123456778',      'jana@aldersgate.edu',  '2/23/2000',        1),
('02-00013',    'Jaiden',       'Navarrete',    'Oliva',        '09123456777',      'jaoc@aldersgate.edu',  '1/28/2003',        5),
('02-00014',    'Kaitlynne',    'Oliva',        'Ochoa',        '09123456776',      'kaol@aldersgate.edu',  '8/14/2002',        1),
('02-00015',    'Jagger',       'Pacheco',      'Padilla',      '09123456775',      'japa@aldersgate.edu',  '2/11/2001',        2),
('02-00016',    'Kaitlynn',     'Garcia',       'Pacheco',      '09123456774',      'kapa@aldersgate.edu',  '6/10/2000',        6),
('02-00017',    'Jaida',        'Palacios',     'Quezada',      '09123456773',      'japaq@aldersgate.edu',  '1/8/2003',        3),
('02-00018',    'Kaleb',        'Quezada',      'Palacios',     '09123456772',      'kaqu@aldersgate.edu',  '1/13/2002',        2),
('02-00019',    "Jaxon",        "Ramirez",      "Rangel",       '09123456771',      "jarax@aldersgate.edu",  '5/22/2000',       6),
('02-00020',    "Kelsey",       "Rangel",       "Ramirez",      '09123456770',      "kara@aldersgate.edu",  '8/18/2000',        6),
('02-00021',    "Jace",         "Raya",         "Raya",         '09123456769',      "jara@aldersgate.edu",  '2/12/2000',        6),
('02-00022',    "Kaden",        "Roda",         "Salazar",      '09123456768',      "kasa@aldersgate.edu",  '9/16/2000',        6),
('02-00023',    "Kaley",        "Del Rio",      "Salinas",      '09123456767',      "kasax@aldersgate.edu",  '10/21/2000',      6),
('02-00024',    "Jayden",       'Dela Cruz',    "Sabiano",      "09123456766",      'jasax@aldersgate.edu',  '11/23/2002',      4),
('02-00025',    "Jaiden",       'Dela Vega',    "Santarosa",    "09123456765",      'jasa@aldersgate.edu',  '10/8/2003',        4);

UPDATE `elementary_students` SET created_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE created_at IS NULL AND updated_at IS NULL;