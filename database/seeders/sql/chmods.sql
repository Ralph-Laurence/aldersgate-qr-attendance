INSERT INTO `chmods` (`id`, `user_fk_id`, `access_advanced`, `access_attendance`, `access_students`, `access_users`, `created_at`, `updated_at`) VALUES
(1, 1, 'f', 'f', 'f', 'f', NULL, NULL),
(2, 2, 'f', 'f', 'f', 'f', NULL, NULL),
(3, 3, 'f', 'f', 'f', 'f', NULL, NULL),
(4, 4, 'f', 'f', 'f', 'f', NULL, NULL),
(5, 5, 'f', 'f', 'f', 'f', NULL, NULL),
(6, 6, 'm', 'm', 'm', 'm', NULL, NULL),
(7, 7, 'm', 'm', 'm', 'm', NULL, NULL),
(8, 8, 'x', 'w', 'w', 'x', NULL, NULL),
(9, 9, 'x', 'w', 'w', 'x', NULL, NULL),
(10, 10, 'x', 'w', 'w', 'x', NULL, NULL);

/*
INSERT INTO permissions (user_fk, permission_1, permission_2, permission_3)
SELECT id, 
    CASE role
        WHEN 2 THEN 'f'
        WHEN 1 THEN 'm'
        ELSE 'x'
    END,
    CASE role
        WHEN 2 THEN 'f'
        WHEN 1 THEN 'm'
        ELSE 'x'
    END,
    CASE role
        WHEN 2 THEN 'f'
        WHEN 1 THEN 'm'
        ELSE 'x'
    END
FROM users;
*/